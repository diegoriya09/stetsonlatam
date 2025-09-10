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
   $product_id = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;

   if ($product_id <= 0) {
      throw new Exception('ID de producto no válido.');
   }

   $stmt = $conn->prepare("SELECT id FROM wishlist WHERE user_id = ? AND product_id = ?");
   $stmt->bind_param("ii", $user_id, $product_id);
   $stmt->execute();
   $result = $stmt->get_result();

   echo json_encode(['success' => true, 'inWishlist' => $result->num_rows > 0]);
} catch (Exception $e) {
   echo json_encode(['success' => false, 'inWishlist' => false]);
}
