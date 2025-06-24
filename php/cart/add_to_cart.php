<?php
require_once 'php/conexion.php';
require_once '../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header('Content-Type: application/json');

// Obtener JWT del header
$headers = getallheaders();
if (!isset($headers['Authorization'])) {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}
list($jwt) = sscanf($headers['Authorization'], 'Bearer %s');
if (!$jwt) {
    echo json_encode(['success' => false, 'message' => 'No token']);
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
$producto_id = $data['producto_id'];
$cantidad = $data['quantity'];

// Verificar si ya está en el carrito
$sql = "SELECT * FROM cart WHERE users_id = ? AND producto_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $producto_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $sql = "UPDATE cart SET quantity = quantity + ? WHERE users_id = ? AND producto_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $cantidad, $user_id, $producto_id);
} else {
    $sql = "INSERT INTO cart (users_id, producto_id, quantity) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $user_id, $producto_id, $cantidad);
}

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al agregar']);
}

$stmt->close();
$conn->close();
