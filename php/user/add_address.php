<?php
// php/user/add_address.php
require_once '../conexion.php';
require_once '../../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header('Content-Type: application/json');

// (Función getAuthorizationHeader)
function getAuthorizationHeader(){
    if (isset($_SERVER['HTTP_AUTHORIZATION'])) { return trim($_SERVER["HTTP_AUTHORIZATION"]); }
    if (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) { return trim($_SERVER["REDIRECT_HTTP_AUTHORIZATION"]); }
    return null;
}

// Obtenemos los datos enviados por JavaScript
$data = json_decode(file_get_contents('php://input'), true);

// Validación simple (puedes añadir más validaciones)
if (empty($data['street_address']) || empty($data['city']) || empty($data['country'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Por favor, completa todos los campos requeridos.']);
    exit;
}

try {
    $stmt = $conn->prepare("INSERT INTO user_addresses (user_id, street_address, city, state, postal_code, country) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", 
        $user_id, 
        $data['street_address'], 
        $data['city'], 
        $data['state'], 
        $data['postal_code'], 
        $data['country']
    );

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Dirección añadida con éxito.']);
    } else {
        throw new Exception("Error al guardar la dirección.");
    }
    $stmt->close();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
}
?>