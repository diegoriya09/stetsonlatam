<?php
require_once '../conexion.php';
require_once '../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header('Content-Type: application/json');

// Obtener JWT del header
$headers = getallheaders();
if (!isset($headers['Authorization'])) {
    echo json_encode([]);
    exit;
}
list($jwt) = sscanf($headers['Authorization'], 'Bearer %s');
if (!$jwt) {
    echo json_encode([]);
    exit;
}

$secret_key = "StetsonLatam1977";
try {
    $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
    $user_id = $decoded->data->id;
} catch (Exception $e) {
    echo json_encode([]);
    exit;
}

$sql = "SELECT c.producto_id AS id, p.name, p.price, p.image, c.quantity 
        FROM cart c 
        JOIN productos p ON c.producto_id = p.id 
        WHERE c.users_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$carrito = [];
while ($row = $result->fetch_assoc()) {
    $carrito[] = $row;
}

echo json_encode($carrito);

$stmt->close();
$conn->close();
