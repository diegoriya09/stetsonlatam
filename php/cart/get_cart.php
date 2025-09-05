<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../conexion.php';
require_once '../../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header('Content-Type: application/json');

// Verifica conexión
if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Error de conexión a la base de datos']);
    exit;
}

// Obtener token JWT desde el header
function getAuthorizationHeader()
{
    if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
        return trim($_SERVER["HTTP_AUTHORIZATION"]);
    } elseif (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
        return trim($_SERVER["REDIRECT_HTTP_AUTHORIZATION"]);
    } elseif (function_exists('apache_request_headers')) {
        $requestHeaders = apache_request_headers();
        foreach ($requestHeaders as $key => $value) {
            if (strtolower($key) === 'authorization') {
                return trim($value);
            }
        }
    }
    return null;
}

$authHeader = getAuthorizationHeader();
if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
    echo json_encode(['success' => false, 'message' => 'Token no proporcionado']);
    exit;
}

$jwt = trim(str_replace('Bearer', '', $authHeader));
$jwt = ltrim($jwt);
$secret_key = "StetsonLatam1977";

try {
    $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
    $user_id = $decoded->data->id;

    $stmt = $conn->prepare("
        SELECT  
            c.id as cart_item_id,
            c.producto_id, 
            p.name, 
            p.price, 
            p.image, 
            c.quantity,
            c.color_id, 
            co.name AS color_name, 
            co.hex, 
            c.size_id, 
            s.name AS size_name,
            pv.stock 
        FROM cart c 
        JOIN productos p ON c.producto_id = p.id 
        LEFT JOIN colors co ON co.id = c.color_id 
        LEFT JOIN sizes s ON s.id = c.size_id
        LEFT JOIN product_variants pv ON pv.product_id = c.producto_id AND pv.color_id = c.color_id AND pv.size_id = c.size_id 
        WHERE c.users_id = ?
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $carrito = [];
    while ($row = $result->fetch_assoc()) {
        $carrito[] = $row;
    }

    $stmt->close();
    $conn->close();
    echo json_encode(['success' => true, 'cart' => $carrito]);
    exit;
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Token inválido.', 'error' => $e->getMessage()]);
    exit;
}
