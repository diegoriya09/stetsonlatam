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

$cart_item_id = $data['cart_item_id'] ?? null;

if (!is_numeric($cart_item_id)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid item ID.']);
    exit;
}

try {
    $stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND users_id = ?");
    $stmt->bind_param("ii", $cart_item_id, $user_id);
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Item deleted.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'The item was not found in your cart.']);
        }
    } else {
        throw new Exception("Error while executing deletion.");
    }
    $stmt->close();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error.']);
}
$conn->close();
