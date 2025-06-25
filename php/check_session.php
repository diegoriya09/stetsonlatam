<?php
require_once 'conexion.php';
require_once '../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header('Content-Type: application/json');

// ✅ Esta función es compatible con todos los entornos (Apache, CGI, nginx, etc.)
function getAuthorizationHeader() {
    if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
        return trim($_SERVER["HTTP_AUTHORIZATION"]);
    }

    if (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
        return trim($_SERVER["REDIRECT_HTTP_AUTHORIZATION"]);
    }

    // Para servidores que no pasan el Authorization como HTTP_*
    if (function_exists('apache_request_headers')) {
        $requestHeaders = apache_request_headers();
        if (isset($requestHeaders['Authorization'])) {
            return trim($requestHeaders['Authorization']);
        }
    }

    return null;
}

// ✅ Leer el header
$authHeader = getAuthorizationHeader();

if (!$authHeader) {
    echo json_encode(['logged_in' => false, 'message' => 'No se envió el token']);
    exit;
}

// ✅ Extraer token
list($jwt) = sscanf($authHeader, 'Bearer %s');
if (!$jwt) {
    echo json_encode(['logged_in' => false, 'message' => 'Formato inválido de token']);
    exit;
}

$secret_key = "StetsonLatam1977";

try {
    $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));

    echo json_encode([
        'logged_in' => true,
        'user_id' => $decoded->data->id
    ]);
} catch (Exception $e) {
    echo json_encode([
        'logged_in' => false,
        'message' => 'Token inválido',
        'error' => $e->getMessage()
    ]);
}
