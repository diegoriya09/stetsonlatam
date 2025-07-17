<?php
require_once 'conexion.php';

header('Content-Type: application/json');

$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$order_id) {
    echo json_encode(['success' => false, 'message' => 'ID de orden no válido']);
    exit;
}

$stmt = $conn->prepare("
    SELECT p.nombre, p.precio, d.cantidad
    FROM pedido_detalle d
    JOIN productos p ON d.producto_id = p.id
    WHERE d.pedido_id = ?
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();

$details = [];
while ($row = $result->fetch_assoc()) {
    $details[] = $row;
}

echo json_encode(['success' => true, 'details' => $details]);
