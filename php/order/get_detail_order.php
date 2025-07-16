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

$pedido_id = isset($_GET['pedido_id']) ? intval($_GET['pedido_id']) : 0;

$sql = "SELECT * FROM pedido_detalle WHERE pedido_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $pedido_id);
$stmt->execute();
$result = $stmt->get_result();

$detalles = [];
while ($row = $result->fetch_assoc()) {
  $detalles[] = $row;
}

echo json_encode($detalles);
?>
