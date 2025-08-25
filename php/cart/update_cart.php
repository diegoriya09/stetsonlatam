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

// Verificación más clara de los datos
$cart_item_id = $data['cart_item_id'] ?? null;
$cantidad = $data['quantity'] ?? null;

if (!is_numeric($cart_item_id) || !is_numeric($cantidad) || $cantidad < 1) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid data provided.']);
    exit;
}

try {
    $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND users_id = ?");
    $stmt->bind_param("iii", $cantidad, $cart_item_id, $user_id);
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Updated amount.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'The item was not found in your cart.']);
        }
    } else {
        throw new Exception("Error while running the update.");
    }
    $stmt->close();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error.']);
}
$conn->close();
