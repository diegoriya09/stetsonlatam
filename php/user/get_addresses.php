<?php
// php/user/get_addresses.php
ini_set('display_errors', 1);
error_reporting(E_ALL);

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

$authHeader = getAuthorizationHeader();
if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Token no proporcionado']);
    exit;
}

$jwt = $matches[1];
$secret_key = "StetsonLatam1977";

try {
    $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
    $user_id = $decoded->data->id;

    $stmt = $conn->prepare("SELECT * FROM user_addresses WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $addresses = $result->fetch_all(MYSQLI_ASSOC);

    echo json_encode(['success' => true, 'addresses' => $addresses]);

} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Token inválido', 'error' => $e->getMessage()]);
}
?>