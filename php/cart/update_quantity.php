<?php
require_once '../conexion.php';
require_once '../../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header('Content-Type: application/json');

$headers = getallheaders();
if (!isset($headers['Authorization'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Token no proporcionado']);
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
    echo json_encode(['success' => false, 'message' => 'Token inválido']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$producto_id = $data['producto_id'] ?? null;
$color_id = $data['color_id'] ?? null;
$size_id = $data['size_id'] ?? null;
$quantity = $data['quantity'] ?? null;

if (!$producto_id || !$color_id || !$size_id || $quantity < 1) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
    exit;
}

$stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND users_id = ?");
$stmt->bind_param("iii", $quantity, $cart_item_id, $user_id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Cantidad actualizada']);
    } else {
         echo json_encode(['success' => false, 'message' => 'El artículo no se encontró o no te pertenece']);
    }
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al actualizar la cantidad']);
}

$stmt->close();
$conn->close();
