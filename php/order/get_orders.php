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
    SELECT p.id, p.total, p.fecha, 
           GROUP_CONCAT(d.estado SEPARATOR ', ') as estado
    FROM pedidos p
    LEFT JOIN pedido_detalle d ON p.id = d.pedido_id
    WHERE p.user_id = ?
    GROUP BY p.id
");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $orders = [];
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }

    echo json_encode(['success' => true, 'orders' => $orders]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Token inválido', 'error' => $e->getMessage()]);
}
