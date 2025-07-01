<?php
require_once '../conexion.php';
require_once '../../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


header('Content-Type: application/json');

// Obtener JWT desde el header
$headers = getallheaders();
if (!isset($headers['Authorization'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

list($jwt) = sscanf($headers['Authorization'], 'Bearer %s');
if (!$jwt) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Token inválido']);
    exit;
}

$secret_key = "StetsonLatam1977";
try {
    $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
    $user_id = $decoded->data->id;
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Token inválido', 'error' => $e->getMessage()]);
    exit;
}

// Obtener producto_id del body
$data = json_decode(file_get_contents('php://input'), true);
$producto_id = intval($data['producto_id'] ?? 0);

if ($producto_id > 0) {
    $stmt = $conn->prepare("INSERT IGNORE INTO wishlist (user_id, producto_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $producto_id);
    $stmt->execute();
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'ID inválido']);
}

$stmt_check->close();
$conn->close();
