<?php
// Mostrar errores (muy útil para depurar)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../conexion.php';
require_once '../../vendor/autoload.php';


use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header('Content-Type: application/json');

// Obtener JWT del header
$headers = getallheaders();
if (!isset($headers['Authorization'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'No se recibió el token']);
    exit;
}

list($jwt) = sscanf($headers['Authorization'], 'Bearer %s');
if (!$jwt) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Formato de token inválido']);
    exit;
}

// Decodificar JWT
$secret_key = "StetsonLatam1977";
try {
    $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
    $user_id = $decoded->data->id;
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Token inválido', 'error' => $e->getMessage()]);
    exit;
}

try {
    // Obtener productos de la wishlist
    $stmt = $conn->prepare("SELECT producto_id FROM wishlist WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $wishlist = [];
    while ($row = $result->fetch_assoc()) {
        $wishlist[] = (string)$row['producto_id'];
    }
    echo json_encode(['wishlist' => $wishlist]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error del servidor', 'error' => $e->getMessage()]);
}
