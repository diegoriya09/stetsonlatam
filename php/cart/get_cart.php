<?php
session_start();
require_once '../conexion.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT c.id, p.name, p.price, p.image, c.quantity 
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
