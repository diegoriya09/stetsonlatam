<?php
// php/reviews/add_review.php (VERSIÓN FINAL Y CORREGIDA)

require_once '../conexion.php';
require_once '../../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use mysqli_sql_exception;

header('Content-Type: application/json');

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

try {
   if (!$conn || $conn->connect_error) {
      throw new Exception("Error de conexión a la base de datos.");
   }

   // 1. Autenticación con JWT
   $authHeader = getAuthorizationHeader();
   if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
      throw new Exception('Token no proporcionado.');
   }
   $jwt = trim(str_replace('Bearer', '', $authHeader));
   $secret_key = "StetsonLatam1977";
   $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
   $user_id = $decoded->data->id;

   // 2. Verificación de existencia del usuario
   $user_check_stmt = $conn->prepare("SELECT id FROM users WHERE id = ?");
   $user_check_stmt->bind_param("i", $user_id);
   $user_check_stmt->execute();
   if ($user_check_stmt->get_result()->num_rows === 0) {
      http_response_code(404);
      throw new Exception("El usuario especificado en el token no existe.");
   }
   $user_check_stmt->close();

   // 3. Obtener datos de la reseña
   $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
   $rating = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;
   $comment = trim(strip_tags($_POST['comment'] ?? ''));

   if ($product_id <= 0 || $rating < 1 || $rating > 5 || empty($comment)) {
      throw new Exception('Por favor, completa todos los campos.');
   }

   // 4. Verificación de compra
   $has_purchased = false;
   $stmt_check = $conn->prepare("
        SELECT COUNT(*) as purchase_count
        FROM pedidos p
        JOIN pedido_detalle pd ON p.id = pd.pedido_id
        WHERE p.user_id = ? AND pd.product_id = ? AND (p.estado = 'Enviado' OR p.estado = 'Completado')
    ");
   $stmt_check->bind_param("ii", $user_id, $product_id);
   $stmt_check->execute();
   $result_check = $stmt_check->get_result()->fetch_assoc();
   if ($result_check && $result_check['purchase_count'] > 0) {
      $has_purchased = true;
   }
   $stmt_check->close();

   if (!$has_purchased) {
      http_response_code(403);
      throw new Exception('Solo los clientes que han comprado este producto pueden dejar una reseña.');
   }

   // 5. Insertar la reseña
   $stmt_insert = $conn->prepare("INSERT INTO product_reviews (product_id, user_id, rating, comment) VALUES (?, ?, ?, ?)");
   $stmt_insert->bind_param("iiis", $product_id, $user_id, $rating, $comment);
   $stmt_insert->execute();

   if ($stmt_insert->affected_rows > 0) {
      echo json_encode(['success' => true, 'message' => '¡Gracias por tu reseña!']);
   } else {
      throw new Exception('No se pudo guardar la reseña.');
   }
   $stmt_insert->close();
} catch (mysqli_sql_exception $e) {
   if ($e->getCode() == 1062) {
      http_response_code(409);
      echo json_encode(['success' => false, 'message' => 'Ya has dejado una reseña para este producto.']);
   } else {
      http_response_code(500);
      error_log("Error de base de datos en add_review: " . $e->getMessage());
      echo json_encode(['success' => false, 'message' => 'Ocurrió un error al guardar tu reseña.']);
   }
} catch (Exception $e) {
   http_response_code(400);
   echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
   if (isset($conn) && $conn->ping()) {
      $conn->close();
   }
}
