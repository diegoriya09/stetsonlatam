<?php
include 'conexion.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['users_id'])) {
  http_response_code(401);
  echo json_encode(["message" => "No autenticado"]);
  exit;
}

$users_id = intval($_SESSION['users_id']);

$sql = "SELECT p.id AS producto_id, p.name, p.price, p.image, c.quantity
        FROM cart c
        JOIN productos p ON c.producto_id = p.id
        WHERE c.users_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$carrito = [];
while ($row = $result->fetch_assoc()) {
  $carrito[] = $row;
}

echo json_encode($carrito);
?>
