<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'php/conexion.php';

$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;
$color_id = isset($_GET['color_id']) ? intval($_GET['color_id']) : 0;
$size_id = isset($_GET['size_id']) ? intval($_GET['size_id']) : 0;

$stmt = $conn->prepare("SELECT stock FROM product_variants WHERE product_id = ? AND color_id = ? AND size_id = ?");
$stmt->bind_param("iii", $product_id, $color_id, $size_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
  echo json_encode(['stock' => (int)$row['stock']]);
} else {
  echo json_encode(['stock' => 0]);
}

$stmt->close();
$conn->close();
