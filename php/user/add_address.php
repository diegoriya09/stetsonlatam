<?php
// php/user/add_address.php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../conexion.php';
require_once '../../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header('Content-Type: application/json');

// --- INICIO: Bloque de Autenticación JWT (puedes moverlo a un archivo común) ---
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


// Obtenemos los datos enviados por JavaScript desde el cuerpo de la petición
$data = json_decode(file_get_contents('php://input'), true);

// Validación simple de los datos recibidos
if (empty($data['street_address']) || empty($data['city']) || empty($data['country']) || empty($data['postal_code'])) {
    http_response_code(400); // Bad Request
    echo json_encode(['success' => false, 'message' => 'Por favor, completa todos los campos requeridos.']);
    exit;
}

// Guardar en la base de datos
try {
    $stmt = $conn->prepare(
        "INSERT INTO user_addresses (user_id, street_address, city, state, postal_code, country) VALUES (?, ?, ?, ?, ?, ?)"
    );
    // 'isssss' significa: integer, string, string, string, string, string
    $stmt->bind_param(
        "isssss",
        $user_id,
        $data['street_address'],
        $data['city'],
        $data['state'],
        $data['postal_code'],
        $data['country']
    );

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Dirección añadida con éxito.']);
    } else {
        throw new Exception("Error al ejecutar la consulta.");
    }
    $stmt->close();
} catch (Exception $e) {
    http_response_code(500); // Internal Server Error
    error_log("Error al guardar dirección: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Hubo un error al guardar la dirección.']);
}

$conn->close();
