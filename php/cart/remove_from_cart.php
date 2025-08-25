<?php
require_once '../conexion.php';
require_once '../../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header('Content-Type: application/json');

// Obtener JWT
$headers = getallheaders();
if (!isset($headers['Authorization'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}
list($jwt) = sscanf($headers['Authorization'], 'Bearer %s');
if (!$jwt) {
    echo json_encode(['success' => false, 'message' => 'Invalid token']);
    exit;
}

$secret_key = "StetsonLatam1977";
try {
    $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
    $user_id = $decoded->data->id;
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Invalid token']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$producto_id = isset($data['producto_id']) ? (int)$data['producto_id'] : null;
$color_id = isset($data['color_id']) ? (int)$data['color_id'] : null;
$size_id = isset($data['size_id']) ? (int)$data['size_id'] : null;

if (!$producto_id || !$color_id || !$size_id) {
    echo json_encode(['success' => false, 'message' => 'Missing data']);
    exit;
}

// Usamos el ID único del item del carrito y el ID del usuario por seguridad.
$sql = "DELETE FROM cart WHERE id = ? AND users_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $cart_item_id, $user_id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Item deleted']);
    } else {
        echo json_encode(['success' => false, 'message' => 'The item was not found or does not belong to you.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Error deleting']);
}

$stmt->close();
$conn->close();
