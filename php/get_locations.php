<?php
// php/get_locations.php
require_once 'conexion.php';
header('Content-Type: application/json');

try {
    $sql = "SELECT DISTINCT country, city FROM stores ORDER BY country, city";
    $result = $conn->query($sql);
    $locations_flat = $result->fetch_all(MYSQLI_ASSOC);
    
    // Estructuramos los datos en un formato País -> [Ciudades]
    $locations_structured = [];
    foreach ($locations_flat as $loc) {
        $country = $loc['country'];
        $city = $loc['city'];
        if (!isset($locations_structured[$country])) {
            $locations_structured[$country] = [];
        }
        $locations_structured[$country][] = $city;
    }

    echo json_encode(['success' => true, 'locations' => $locations_structured]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error fetching locations.']);
}
$conn->close();
?>