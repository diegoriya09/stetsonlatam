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

// Obtener datos del body
$data = json_decode(file_get_contents('php://input'), true);
$producto_id = $data['producto_id'] ?? null;
$quantity = $data['quantity'] ?? 1;

if (!$producto_id || $quantity < 1) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    exit;
}

// Verificar si el producto ya está en el carrito
$sql_check = "SELECT quantity FROM cart WHERE users_id = ? AND producto_id = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("ii", $user_id, $producto_id);
$stmt_check->execute();
$result = $stmt_check->get_result();

if ($result->num_rows > 0) {
    // Ya existe, actualizar cantidad
    $row = $result->fetch_assoc();
    $new_quantity = $row['quantity'] + $quantity;

    $sql_update = "UPDATE cart SET quantity = ? WHERE users_id = ? AND producto_id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("iii", $new_quantity, $user_id, $producto_id);
    $stmt_update->execute();
    $stmt_update->close();

    echo json_encode(['success' => true, 'message' => 'Cantidad actualizada']);
} else {
    // No existe, insertar
    $sql_insert = "INSERT INTO cart (users_id, producto_id, quantity) VALUES (?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("iii", $user_id, $producto_id, $quantity);
    $stmt_insert->execute();
    $stmt_insert->close();

    echo json_encode(['success' => true, 'message' => 'Producto añadido']);
}

$stmt_check->close();
$conn->close();
