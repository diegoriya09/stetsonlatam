<?php
// php/get_stores.php
require_once 'conexion.php';
header('Content-Type: application/json');

// Obtenemos los parámetros de la URL (si existen)
$country = $_GET['country'] ?? '';
$city = $_GET['city'] ?? '';

$sql = "SELECT * FROM stores";
$where = [];
$params = [];
$types = '';

if (!empty($country)) {
   $where[] = "country LIKE ?";
   $params[] = "%" . $country . "%";
   $types .= "s";
}
if (!empty($city)) {
   $where[] = "city LIKE ?";
   $params[] = "%" . $city . "%";
   $types .= "s";
}

if (!empty($where)) {
   $sql .= " WHERE " . implode(" AND ", $where);
}

$sql .= " ORDER BY country, city";

try {
   $stmt = $conn->prepare($sql);
   if (!empty($params)) {
      $stmt->bind_param($types, ...$params);
   }
   $stmt->execute();
   $result = $stmt->get_result();
   $stores = $result->fetch_all(MYSQLI_ASSOC);

   echo json_encode(['success' => true, 'stores' => $stores]);
} catch (Exception $e) {
   http_response_code(500);
   echo json_encode(['success' => false, 'message' => 'Error fetching stores.']);
}
$conn->close();
