<?php
require_once 'conexion.php';
require_once '../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header('Content-Type: application/json');

// ✅ Función compatible con todos los servidores (incluido CGI y FPM)
function getAuthorizationHeader() {
    $headers = null;

    if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
        $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
    } elseif (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
        $headers = trim($_SERVER["REDIRECT_HTTP_AUTHORIZATION"]);
    } elseif (function_exists('apache_request_headers')) {
        $requestHeaders = apache_request_headers();
        // Este header puede estar en minúsculas o con mayúsculas
        foreach ($requestHeaders as $key => $value) {
            if (strtolower($key) === 'authorization') {
                $headers = trim($value);
                break;
            }
        }
    }

    return $headers;
}

// ✅ Extraer el token del header
$authHeader = getAuthorizationHeader();
if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
    echo json_encode(['logged_in' => false, 'message' => 'No se envió el token']);
    exit;
}

$jwt = trim(str_replace('Bearer', '', $authHeader));
$jwt = ltrim($jwt);

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
