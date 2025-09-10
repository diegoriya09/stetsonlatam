<?php
require_once '../conexion.php';
require_once '../../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header('Content-Type: application/json');

function getAuthorizationHeader()
{
   if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
      return trim($_SERVER["HTTP_AUTHORIZATION"]);
   }
   if (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
      return trim($_SERVER["REDIRECT_HTTP_AUTHORIZATION"]);
   }
   return null;
}

try {
   $authHeader = getAuthorizationHeader();
   if (!$authHeader) {
      throw new Exception('No autenticado');
   }

   $jwt = trim(str_replace('Bearer', '', $authHeader));
   $secret_key = "StetsonLatam1977";
   $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
   $user_id = $decoded->data->id;

   $data = json_decode(file_get_contents("php://input"), true);
   $product_id = $data['product_id'] ?? 0;

   if ($product_id <= 0) {
      throw new Exception('ID de producto no válido.');
   }

   // Verificar si ya existe
   $stmt_check = $conn->prepare("SELECT id FROM wishlist WHERE user_id = ? AND product_id = ?");
   $stmt_check->bind_param("ii", $user_id, $product_id);
   $stmt_check->execute();
   $result = $stmt_check->get_result();

   if ($result->num_rows > 0) {
      // Ya existe, eliminarlo
      $stmt_delete = $conn->prepare("DELETE FROM wishlist WHERE user_id = ? AND product_id = ?");
      $stmt_delete->bind_param("ii", $user_id, $product_id);
      $stmt_delete->execute();
      echo json_encode(['success' => true, 'status' => 'removed']);
   } else {
      // No existe, añadirlo
      $stmt_insert = $conn->prepare("INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)");
      $stmt_insert->bind_param("ii", $user_id, $product_id);
      $stmt_insert->execute();
      echo json_encode(['success' => true, 'status' => 'added']);
   }
} catch (Exception $e) {
   http_response_code(400);
   echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
