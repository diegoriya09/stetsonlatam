<?php
require_once 'conexion.php';
require_once '../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header('Content-Type: application/json');

// Obtener JWT del encabezado Authorization
$headers = getallheaders();
if (!isset($headers['Authorization'])) {
    echo json_encode(['logged_in' => false, 'message' => 'No se envió el token']);
    exit;
}

list($jwt) = sscanf($headers['Authorization'], 'Bearer %s');
if (!$jwt) {
    echo json_encode(['logged_in' => false, 'message' => 'JWT vacío o malformado']);
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
?>