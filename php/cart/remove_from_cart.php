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

$data = json_decode(file_get_contents('php://input'), true);
$producto_id = isset($data['producto_id']) ? (int)$data['producto_id'] : null;
$color_id = isset($data['color_id']) ? (int)$data['color_id'] : null;
$size_id = isset($data['size_id']) ? (int)$data['size_id'] : null;

if (!$producto_id || !$color_id || !$size_id) {
    echo json_encode(['success' => false, 'message' => 'Datos faltantes']);
    exit;
}

$sql = "DELETE FROM cart WHERE users_id = ? AND producto_id = ? AND color_id = ? AND size_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiii", $user_id, $producto_id, $color_id, $size_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al eliminar']);
}

$stmt->close();
$conn->close();
