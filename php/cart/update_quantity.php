<?php
require_once '../conexion.php';
require_once '../../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header('Content-Type: application/json');

$headers = getallheaders();
if (!isset($headers['Authorization'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Token not provided']);
    exit;
}

list($jwt) = sscanf($headers['Authorization'], 'Bearer %s');
if (!$jwt) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Invalid token']);
    exit;
}

$secret_key = "StetsonLatam1977";
try {
    $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
    $user_id = $decoded->data->id;
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Invalid token']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$producto_id = $data['producto_id'] ?? null;
$color_id = $data['color_id'] ?? null;
$size_id = $data['size_id'] ?? null;
$quantity = $data['quantity'] ?? null;

if (!$producto_id || !$color_id || !$size_id || $quantity < 1) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
    exit;
}

$stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND users_id = ?");
$stmt->bind_param("iii", $quantity, $cart_item_id, $user_id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Updated quantity']);
    } else {
         echo json_encode(['success' => false, 'message' => 'The item was not found or does not belong to you.']);
    }
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error updating quantity']);
}

$stmt->close();
$conn->close();
