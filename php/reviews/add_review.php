<?php
// SCRIPT DE DIAGNÓSTICO PARA add_review.php

// Forzar la visualización de TODOS los errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Punto de control 1: Ver si los archivos se incluyen correctamente
require_once '../conexion.php';
require_once '../../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use mysqli_sql_exception;

header('Content-Type: application/json');

// Punto de control 2: Verificar la conexión a la DB
if (!$conn || $conn->connect_error) {
   echo json_encode(['error' => 'Fallo en el Punto 2: La conexión a la base de datos no es válida.', 'conn_status' => $conn ? $conn->connect_error : 'Variable $conn no existe.']);
   exit;
}

// Punto de control 3: Decodificar el Token JWT
try {
   function getAuthorizationHeader()
   {
      if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
         return trim($_SERVER["HTTP_AUTHORIZATION"]);
      }
      if (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
         return trim($_SERVER["REDIRECT_HTTP_AUTHORIZATION"]);
      }
      if (function_exists('apache_request_headers')) {
         $requestHeaders = apache_request_headers();
         if (isset($requestHeaders['Authorization'])) {
            return trim($requestHeaders['Authorization']);
         }
      }
      return null;
   }
   $authHeader = getAuthorizationHeader();
   if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
      throw new Exception('Fallo en el Punto 3: Token no proporcionado.');
   }
   $jwt = trim(str_replace('Bearer', '', $authHeader));
   $secret_key = "StetsonLatam1977";
   $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
   $user_id = $decoded->data->id;
} catch (Exception $e) {
   echo json_encode(['error' => 'Fallo en el Punto 3: Error decodificando el JWT.', 'message' => $e->getMessage()]);
   exit;
}

// Punto de control 4: Leer los datos POST
$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$rating = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;
$comment = trim(strip_tags($_POST['comment'] ?? ''));

if ($product_id <= 0 || $rating <= 0 || empty($comment)) {
   echo json_encode(['error' => 'Fallo en el Punto 4: Datos POST no recibidos o incompletos.', 'post_data' => $_POST]);
   exit;
}

// Punto de control 5: Verificar compra
try {
   $stmt_check = $conn->prepare("SELECT COUNT(*) as purchase_count FROM pedidos p JOIN pedido_detalle pd ON p.id = pd.pedido_id WHERE p.user_id = ? AND pd.product_id = ? AND (p.estado = 'Enviado' OR p.estado = 'Completado')");
   if ($stmt_check === false) {
      throw new Exception("Error al preparar la consulta de verificación de compra: " . $conn->error);
   }
   $stmt_check->bind_param("ii", $user_id, $product_id);
   $stmt_check->execute();
   $result_check = $stmt_check->get_result()->fetch_assoc();
   $stmt_check->close();
} catch (Exception $e) {
   echo json_encode(['error' => 'Fallo en el Punto 5: Error en la consulta de verificación de compra.', 'message' => $e->getMessage()]);
   exit;
}

// Punto de control 6: Insertar la reseña
try {
   $stmt_insert = $conn->prepare("INSERT INTO product_reviews (product_id, user_id, rating, comment) VALUES (?, ?, ?, ?)");
   if ($stmt_insert === false) {
      throw new Exception("Error al preparar la consulta de inserción: " . $conn->error);
   }
   $stmt_insert->bind_param("iiis", $product_id, $user_id, $rating, $comment);
   $stmt_insert->execute();
   $stmt_insert->close();
} catch (Exception $e) {
   echo json_encode(['error' => 'Fallo en el Punto 6: Error en la consulta de inserción.', 'message' => $e->getMessage()]);
   exit;
}

// Si llega hasta aquí, todo está bien
echo json_encode(['success' => 'Todos los puntos de control pasaron exitosamente.']);
exit;
