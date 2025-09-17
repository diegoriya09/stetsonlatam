<?php
// php/user/get_notifications.php
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

   $stmt = $conn->prepare("SELECT id, message, link, is_read, created_at FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 10");
   $stmt->bind_param("i", $user_id);
   $stmt->execute();
   $notifications = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

   $unread_count_stmt = $conn->prepare("SELECT COUNT(*) as count FROM notifications WHERE user_id = ? AND is_read = 0");
   $unread_count_stmt->bind_param("i", $user_id);
   $unread_count_stmt->execute();
   $unread_count = $unread_count_stmt->get_result()->fetch_assoc()['count'];

   echo json_encode(['success' => true, 'notifications' => $notifications, 'unread_count' => $unread_count]);
} catch (Exception $e) {
   echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
