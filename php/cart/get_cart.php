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
    // Ajustar la consulta para usar color_id y size_id
    $sql = "SELECT  
                c.producto_id AS id, 
            p.name, 
            p.price, 
            p.image, 
            c.quantity, 
            c.color_id, 
            co.name AS color_name, 
            co.hex, 
            c.size_id, 
            s.name AS size_name 
        FROM cart c 
        JOIN productos p ON c.producto_id = p.id 
        LEFT JOIN colors co ON co.id = c.color_id 
        LEFT JOIN sizes s ON s.id = c.size_id 
        WHERE c.users_id = ?";


    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Error al preparar la consulta: " . $conn->error);
    }

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

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error del servidor', 'error' => $e->getMessage()]);
}
