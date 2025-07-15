<?php
require_once 'php/conexion.php';
require_once '../vendor/autoload.php';

header('Content-Type: application/json');

$pedido_id = isset($_GET['pedido_id']) ? intval($_GET['pedido_id']) : 0;

$sql = "SELECT * FROM pedido_detalle WHERE pedido_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $pedido_id);
$stmt->execute();
$result = $stmt->get_result();

$detalles = [];
while ($row = $result->fetch_assoc()) {
  $detalles[] = $row;
}

echo json_encode($detalles);
?>
