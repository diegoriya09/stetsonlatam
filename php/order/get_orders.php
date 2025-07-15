<?php
require_once 'php/conexion.php';
require_once '../vendor/autoload.php';


header('Content-Type: application/json');

$user_id = $decoded->user_id; // obtenido del JWT

$sql = "SELECT p.*, 
               (SELECT COUNT(*) FROM pedido_detalle d WHERE d.pedido_id = p.id) AS total_items
        FROM pedidos p 
        WHERE p.user_id = ? 
        ORDER BY p.fecha DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$pedidos = [];
while ($row = $result->fetch_assoc()) {
  $pedidos[] = $row;
}

echo json_encode($pedidos);
?>
