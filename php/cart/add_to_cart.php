<?php
require_once '../conexion.php';
require_once '../vendor/autoload.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header('Content-Type: application/json');

// ✅ Leer token
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

// ✅ Leer datos del cuerpo JSON
$data = json_decode(file_get_contents('php://input'), true);
$producto_id = $data['producto_id'] ?? null;
$quantity = $data['quantity'] ?? 1;

if (!$producto_id || !$quantity) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    exit;
}

// ✅ Verifica si ya está en el carrito
$sql_check = "SELECT id FROM cart WHERE users_id = ? AND producto_id = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("ii", $user_id, $producto_id);
$stmt_check->execute();
$result = $stmt_check->get_result();

if ($result->num_rows > 0) {
    // Ya existe, actualiza
    $sql_update = "UPDATE cart SET quantity = quantity + ? WHERE users_id = ? AND producto_id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("iii", $quantity, $user_id, $producto_id);
    $stmt_update->execute();
    $stmt_update->close();
} else {
    // No existe, inserta
    $sql_insert = "INSERT INTO cart (users_id, producto_id, quantity) VALUES (?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("iii", $user_id, $producto_id, $quantity);
    $stmt_insert->execute();
    $stmt_insert->close();
}

echo json_encode(['success' => true]);
$conn->close();
