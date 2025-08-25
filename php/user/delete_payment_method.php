<?php
// php/user/delete_payment_method.php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../conexion.php';
require_once '../../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header('Content-Type: application/json');

// --- INICIO: Bloque de Autenticación JWT ---
function getAuthorizationHeader(){
    if (isset($_SERVER['HTTP_AUTHORIZATION'])) { return trim($_SERVER["HTTP_AUTHORIZATION"]); }
    if (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) { return trim($_SERVER["REDIRECT_HTTP_AUTHORIZATION"]); }
    return null;
}

$authHeader = getAuthorizationHeader();
if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Token no proporcionado.']);
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


// Obtenemos el ID del método de pago enviado por JavaScript
$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'No se proporcionó el ID del método de pago.']);
    exit;
}

$payment_id = $data['id'];

try {
    // Preparamos la consulta para borrar
    // IMPORTANTE: El "AND user_id = ?" es una medida de seguridad crucial.
    // Asegura que un usuario solo pueda borrar SUS PROPIOS métodos de pago.
    $stmt = $conn->prepare("DELETE FROM user_payment_methods WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $payment_id, $user_id);

    if ($stmt->execute()) {
        // Verificamos si realmente se borró una fila
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Método de pago eliminado con éxito.']);
        } else {
            // Esto ocurre si el ID del método de pago no pertenece al usuario
            http_response_code(403); // Forbidden
            echo json_encode(['success' => false, 'message' => 'No tienes permiso para eliminar este método de pago.']);
        }
    } else {
        throw new Exception("Error al ejecutar la consulta.");
    }
    $stmt->close();
} catch (Exception $e) {
    http_response_code(500); // Internal Server Error
    error_log("Error al eliminar método de pago: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Hubo un error en el servidor.']);
}

$conn->close();
?>