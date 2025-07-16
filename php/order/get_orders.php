<?php
require_once 'php/conexion.php';
require_once '../vendor/autoload.php';


header('Content-Type: application/json');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$headers = apache_request_headers();
$authHeader = $headers['Authorization'] ?? '';
if (!preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
  http_response_code(401);
  echo json_encode(['error' => 'Missing or invalid token']);
  exit;
}

$jwt = $matches[1];
$secretKey = "StetsonLatam1977";
try {
  $decoded = JWT::decode($jwt, new Key($secretKey, 'HS256'));
  $user_id = $decoded->data->id;
} catch (Exception $e) {
  http_response_code(401);
  echo json_encode(['error' => 'Token expired or invalid']);
  exit;
}

$user_id = $decoded->user_id; // obtenido del JWT

$sql = "SELECT p.*, 
               (SELECT COUNT(*) FROM pedido_detalle d WHERE d.pedido_id = p.id) AS total_items
        FROM pedidos p 
        WHERE p.user_id = ? 
        ORDER BY p.fecha DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$pedidos = [];
while ($row = $result->fetch_assoc()) {
  $pedidos[] = $row;
}

echo json_encode($pedidos);
?>
