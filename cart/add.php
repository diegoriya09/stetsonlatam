<?php
include '/php/conexion.php';
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['users_id'])) {
  echo json_encode(['success' => false, 'message' => 'Usuario no logueado']);
  exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$user_id = $_SESSION['users_id'];
$producto_id = intval($data['id']);

// Verificar si ya está en el carrito
$check = $conn->prepare("SELECT quantity FROM cart WHERE users_id = ? AND producto_id = ?");
$check->bind_param("ii", $user_id, $producto_id);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
  // Ya existe, actualizar cantidad
  $update = $conn->prepare("UPDATE cart SET quantity = quantity + 1 WHERE users_id = ? AND producto_id = ?");
  $update->bind_param("ii", $user_id, $producto_id);
  $update->execute();
} else {
  // No existe, insertar
  $insert = $conn->prepare("INSERT INTO cart (users_id, producto_id, quantity) VALUES (?, ?, 1)");
  $insert->bind_param("ii", $user_id, $producto_id);
  $insert->execute();
}

echo json_encode(['success' => true]);
?>
