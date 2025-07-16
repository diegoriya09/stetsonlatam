<?php
require_once '../conexion.php';
require_once '../../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header('Content-Type: application/json');

function getBearerToken() {
    $headers = null;

    if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
        $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
    } elseif (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
        $headers = trim($_SERVER["REDIRECT_HTTP_AUTHORIZATION"]);
    } elseif (function_exists('apache_request_headers')) {
        $requestHeaders = apache_request_headers();
        foreach ($requestHeaders as $key => $value) {
            if (strtolower($key) === 'authorization') {
                return trim(str_replace('Bearer', '', $value));
            }
        }
    }

    return null;
}

$jwt = getBearerToken();
$secretKey = "StetsonLatam1977";

if (!$jwt) {
    http_response_code(401);
    echo json_encode(['error' => 'Token not provided']);
    exit;
}

try {
    $decoded = JWT::decode($jwt, new Key($secretKey, 'HS256'));
    $user_id = $decoded->data->id;

    $stmt = $conn->prepare("SELECT id, fecha, total, estado,
        (SELECT COUNT(*) FROM pedido_detalles WHERE pedido_id = pedidos.id) AS total_items
        FROM pedidos WHERE user_id = ? ORDER BY fecha DESC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $pedidos = $result->fetch_all(MYSQLI_ASSOC);

    echo json_encode($pedidos);
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(['error' => 'Invalid token', 'message' => $e->getMessage()]);
}
