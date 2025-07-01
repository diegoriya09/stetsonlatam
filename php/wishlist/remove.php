<?php
require_once '../conexion.php';
require_once '../../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header('Content-Type: application/json');

// Obtener JWT
$headers = getallheaders();
if (!isset($headers['Authorization'])) {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}
list($jwt) = sscanf($headers['Authorization'], 'Bearer %s');
if (!$jwt) {
    echo json_encode(['success' => false, 'message' => 'Token inválido']);
    exit;
}

$secret_key = "StetsonLatam1977";
try {
    $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
    $user_id = $decoded->data->id;
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Token inválido']);
    exit;
}

// Obtener producto_id del body
$data = json_decode(file_get_contents('php://input'), true);
$producto_id = intval($data['producto_id'] ?? 0);

if ($producto_id > 0) {
    $stmt = $conn->prepare("DELETE FROM wishlist WHERE user_id = ? AND producto_id = ?");
    $stmt->bind_param("ii", $user_id, $producto_id);
    $stmt->execute();
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'ID inválido']);
}

$stmt->close();
$conn->close();
