<?php
// php/get_stores.php
require_once 'conexion.php';
header('Content-Type: application/json');

try {
   $sql = "SELECT * FROM stores ORDER BY country, city";
   $result = $conn->query($sql);
   $stores = $result->fetch_all(MYSQLI_ASSOC);
   echo json_encode(['success' => true, 'stores' => $stores]);
} catch (Exception $e) {
   http_response_code(500);
   echo json_encode(['success' => false, 'message' => 'Error al obtener las tiendas.']);
}
$conn->close();
