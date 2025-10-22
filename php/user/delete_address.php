<?php
// php/user/delete_address.php
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
$secret_key = "StetsonLatam1977"; // Asegúrate que esta clave sea la misma que usas al crear el token

try {
    $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
    $user_id = $decoded->data->id;
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Token inválido.']);
    exit;
}
// --- FIN: Bloque de Autenticación JWT ---


// Obtenemos el ID de la dirección enviado por JavaScript
$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'No se proporcionó el ID de la dirección.']);
    exit;
}

$address_id = $data['id'];

try {
    // Preparamos la consulta para borrar
    // IMPORTANTE: El "AND user_id = ?" es una medida de seguridad crucial.
    // Asegura que un usuario solo pueda borrar SUS PROPIAS direcciones.
    $stmt = $conn->prepare("DELETE FROM user_addresses WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $address_id, $user_id);

    if ($stmt->execute()) {
        // Verificamos si realmente se borró una fila
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Dirección eliminada con éxito.']);
        } else {
            // Esto ocurre si el ID de la dirección no pertenece al usuario
            http_response_code(403); // Forbidden
            echo json_encode(['success' => false, 'message' => 'No tienes permiso para eliminar esta dirección.']);
        }
    } else {
        throw new Exception("Error al ejecutar la consulta.");
    }
    $stmt->close();
} catch (Exception $e) {
    http_response_code(500); // Internal Server Error
    error_log("Error al eliminar dirección: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Hubo un error en el servidor.']);
}

$conn->close();
