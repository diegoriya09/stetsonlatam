<?php
require_once '../conexion.php';
require_once '../../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header('Content-Type: application/json');

function getBearerToken() {
    if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
        return trim(str_replace('Bearer', '', $_SERVER["HTTP_AUTHORIZATION"]));
    } elseif (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
        return trim(str_replace('Bearer', '', $_SERVER["REDIRECT_HTTP_AUTHORIZATION"]));
    } elseif (function_exists('apache_request_headers')) {
        foreach (apache_request_headers() as $key => $value) {
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
    echo json_encode(['error' => 'Token missing']);
    exit;
}

try {
    $decoded = JWT::decode($jwt, new Key($secretKey, 'HS256'));
    $user_id = $decoded->data->id;

    $pedido_id = isset($_GET['pedido_id']) ? intval($_GET['pedido_id']) : 0;

    // Verifica que el pedido pertenezca al usuario
    $stmt = $conn->prepare("SELECT id FROM pedidos WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $pedido_id, $user_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows === 0) {
        http_response_code(403);
        echo json_encode(['error' => 'Access denied to this order']);
        exit;
    }

    // Obtener detalles
    $stmt = $conn->prepare("SELECT 
        p.nombre AS nombre_producto,
        pd.cantidad,
        pd.precio,
        c.nombre AS color_nombre,
        t.nombre AS size_nombre
        FROM pedido_detalles pd
        JOIN productos p ON p.id = pd.producto_id
        LEFT JOIN colores c ON c.id = pd.color_id
        LEFT JOIN tallas t ON t.id = pd.size_id
        WHERE pd.pedido_id = ?");
    $stmt->bind_param("i", $pedido_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $detalles = $result->fetch_all(MYSQLI_ASSOC);

    echo json_encode($detalles);
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(['error' => 'Invalid token', 'message' => $e->getMessage()]);
}
