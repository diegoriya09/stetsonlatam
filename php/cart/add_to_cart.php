<?php
require_once '../conexion.php';
require_once '../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

// 1️⃣ Leer JWT desde headers
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

// 2️⃣ Validar JWT
$secret_key = "StetsonLatam1977";
try {
    $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
    $user_id = $decoded->data->id;
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Token inválido']);
    exit;
}

// 3️⃣ Leer JSON del body
$data = json_decode(file_get_contents('php://input'), true);
$producto_id = isset($data['producto_id']) ? intval($data['producto_id']) : null;
$quantity = isset($data['quantity']) ? intval($data['quantity']) : 1;

if (!$producto_id || $quantity < 1) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
    exit;
}

// 4️⃣ Verificar si el producto ya está en el carrito
$sql_check = "SELECT id FROM cart WHERE users_id = ? AND producto_id = ?";
$stmt = $conn->prepare($sql_check);
$stmt->bind_param("ii", $user_id, $producto_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    // Ya está, actualizamos
    $sql_update = "UPDATE cart SET quantity = quantity + ? WHERE users_id = ? AND producto_id = ?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("iii", $quantity, $user_id, $producto_id);
    $stmt->execute();
} else {
    // No está, insertamos
    $sql_insert = "INSERT INTO cart (users_id, producto_id, quantity) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql_insert);
    $stmt->bind_param("iii", $user_id, $producto_id, $quantity);
    $stmt->execute();
}

$stmt->close();
$conn->close();

echo json_encode(['success' => true]);
