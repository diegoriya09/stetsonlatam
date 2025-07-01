<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../conexion.php';

header('Content-Type: application/json');

// Obtener y validar los IDs
if (!isset($_GET['ids']) || empty($_GET['ids'])) {
    echo json_encode(['productos' => []]);
    exit;
}

$ids = explode(',', $_GET['ids']);
// Filtrar solo números enteros positivos
$ids = array_filter($ids, function($id) {
    return ctype_digit($id) && $id > 0;
});

if (empty($ids)) {
    echo json_encode(['productos' => []]);
    exit;
}

// Preparar placeholders y consulta
$placeholders = implode(',', array_fill(0, count($ids), '?'));
$sql = "SELECT id, name, price, image FROM productos WHERE id IN ($placeholders)";
$stmt = $conn->prepare($sql);

// Bind dinámico
$types = str_repeat('i', count($ids));
$stmt->bind_param($types, ...$ids);

$stmt->execute();
$result = $stmt->get_result();

$productos = [];
while ($row = $result->fetch_assoc()) {
    $productos[] = $row;
}

echo json_encode(['productos' => $productos]);

$stmt->close();
$conn->close();
?>