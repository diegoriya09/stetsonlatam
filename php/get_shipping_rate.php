<?php
// php/get_shipping_rate.php
require_once 'conexion.php';
header('Content-Type: application/json');

// Normalizamos la entrada para mejorar las coincidencias (ej. "Bogota D.C." -> "Bogota")
$department = trim($_GET['departamento'] ?? '');
if (empty($department)) {
   echo json_encode(['success' => false, 'message' => 'Departamento no proporcionado.']);
   exit;
}

// Lógica para el caso especial "Resto del mundo"
if (strtolower($department) === 'resto del mundo') {
   echo json_encode(['success' => true, 'requires_quote' => true, 'message' => 'Se requiere cotización de envío.']);
   exit;
}

$stmt = $conn->prepare("SELECT price FROM shipping_rates WHERE departamento = ?");
$stmt->bind_param("s", $department);
$stmt->execute();
$result = $stmt->get_result();
$rate = $result->fetch_assoc();

if ($rate) {
   echo json_encode(['success' => true, 'price' => $rate['price']]);
} else {
   echo json_encode(['success' => false, 'message' => 'No hay envíos disponibles para esta ubicación.']);
}

$stmt->close();
$conn->close();
