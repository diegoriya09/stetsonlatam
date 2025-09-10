<?php
// php/user/get_wishlist.php

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
   if (function_exists('apache_request_headers')) {
      $requestHeaders = apache_request_headers();
      if (isset($requestHeaders['Authorization'])) {
         return trim($requestHeaders['Authorization']);
      }
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

   $stmt = $conn->prepare("
        SELECT 
            p.id,
            p.name,
            p.price,
            p.image,
            w.id as wishlist_item_id
        FROM wishlist w
        JOIN productos p ON w.product_id = p.id
        WHERE w.user_id = ?
        ORDER BY w.added_at DESC
    ");
   $stmt->bind_param("i", $user_id);
   $stmt->execute();
   $result = $stmt->get_result();
   $wishlist_items = $result->fetch_all(MYSQLI_ASSOC);

   echo json_encode(['success' => true, 'wishlist' => $wishlist_items]);
} catch (Exception $e) {
   http_response_code(401);
   echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn->close();
