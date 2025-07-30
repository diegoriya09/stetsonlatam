<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../conexion.php';
require_once '../../vendor/autoload.php';

header('Content-Type: application/json');

$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$order_id) {
    echo json_encode(['success' => false, 'message' => 'ID de orden no válido']);
    exit;
}

$stmt = $conn->prepare("
    SELECT p.name, p.price, d.cantidad,
           d.color_nombre, d.size_nombre
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
