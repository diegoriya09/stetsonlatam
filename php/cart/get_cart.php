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
if (!$authHeader || !preg_match('/Bearer\s(\S+)/i', $authHeader, $matches)) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Token no proporcionado o en formato incorrecto.']);
    exit;
}

// Decodificar JWT
$jwt = trim(str_replace('Bearer', '', $authHeader));
$jwt = ltrim($jwt);
$secret_key = "StetsonLatam1977";

try {
    $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
    $user_id = $decoded->data->id;

    $stmt = $conn->prepare("
        SELECT  
            c.id as cart_item_id, -- Es buena práctica darle un alias claro
            c.producto_id, 
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

    // ¡ESTA ES LA LÍNEA CORREGIDA!
    echo json_encode(['success' => true, 'cart' => $carrito]);
    exit;
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error del servidor', 'error' => $e->getMessage()]);
    exit;
}
