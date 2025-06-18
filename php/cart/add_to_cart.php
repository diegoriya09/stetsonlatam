<?php
session_start();
require_once '../conexion.php';

if (!isset($_SESSION['users_id'])) {
    echo json_encode(['success' => false, 'message' => 'No logueado']);
    exit;
}

$user_id = $_SESSION['user_id'];
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
