<?php
// php/user/add_payment_method.php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../conexion.php';
require_once '../../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header('Content-Type: application/json');

// --- INICIO: Bloque de Autenticación JWT ---
function getAuthorizationHeader()
{
    if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
        return trim($_SERVER["HTTP_AUTHORIZATION"]);
    }
    if (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
        return trim($_SERVER["REDIRECT_HTTP_AUTHORIZATION"]);
    }
    if (function_exists('apache_request_headers')) {
        $requestHeaders = apache_request_headers();
        if (isset($requestHeaders['Authorization'])) {
            return trim($requestHeaders['Authorization']);
        }
    }
    return null;
}

$authHeader = getAuthorizationHeader();
if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Token no proporcionado o en formato incorrecto.']);
    exit;
}

$jwt = $matches[1];
$secret_key = "StetsonLatam1977";

try {
    $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
    $user_id = $decoded->data->id;
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Token inválido', 'error' => $e->getMessage()]);
    exit;
}
// --- FIN: Bloque de Autenticación JWT ---


$data = json_decode(file_get_contents('php://input'), true);

// Validación
if (empty($data['card_type']) || empty($data['last_four']) || empty($data['expiry_date'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Por favor, completa todos los campos.']);
    exit;
}

// Guardar en la base de datos
try {
    $stmt = $conn->prepare(
        "INSERT INTO user_payment_methods (user_id, card_type, last_four_digits, expiry_date) VALUES (?, ?, ?, ?)"
    );
    // 'isss' significa: integer, string, string, string
    $stmt->bind_param(
        "isss",
        $user_id,
        $data['card_type'],
        $data['last_four'],
        $data['expiry_date']
    );

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Método de pago añadido con éxito.']);
    } else {
        throw new Exception("Error al ejecutar la consulta.");
    }
    $stmt->close();
} catch (Exception $e) {
    http_response_code(500);
    error_log("Error al guardar método de pago: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Hubo un error al guardar el método de pago.']);
}

$conn->close();
