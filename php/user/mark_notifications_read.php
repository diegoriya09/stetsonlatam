<?php
// php/user/mark_notifications_read.php
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

   $stmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ? AND is_read = 0");
   $stmt->bind_param("i", $user_id);
   $stmt->execute();

   echo json_encode(['success' => true]);
} catch (Exception $e) {
   echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
