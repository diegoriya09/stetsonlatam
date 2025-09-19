<?php
require_once 'conexion.php';
require_once '../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Google\Client as Google_Client;

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$id_token = $data['credential'] ?? null;

if (!$id_token) {
    echo json_encode(['success' => false, 'message' => 'No se recibió el token de credencial.']);
    exit;
}

// TU ID DE CLIENTE (el mismo que usaste en el frontend)
$CLIENT_ID = 'TU_ID_DE_CLIENTE.apps.googleusercontent.com';

$client = new Google_Client(['client_id' => $CLIENT_ID]);
$payload = $client->verifyIdToken($id_token);

if ($payload) {
    $google_id = $payload['sub'];
    $email = $payload['email'];
    $name = $payload['name'];

    // Verificar si el usuario ya existe en nuestra base de datos
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($user) {
        // El usuario existe, lo logueamos
        $user_id = $user['id'];
        // Si no tiene el google_id guardado, lo actualizamos
        if (empty($user['google_id'])) {
            $stmt_update = $conn->prepare("UPDATE users SET google_id = ? WHERE id = ?");
            $stmt_update->bind_param("si", $google_id, $user_id);
            $stmt_update->execute();
            $stmt_update->close();
        }
    } else {
        // El usuario no existe, lo registramos
        $random_password = password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT);
        $stmt_insert = $conn->prepare("INSERT INTO users (name, email, password, google_id) VALUES (?, ?, ?, ?)");
        $stmt_insert->bind_param("ssss", $name, $email, $random_password, $google_id);
        $stmt_insert->execute();
        $user_id = $conn->insert_id;
        $stmt_insert->close();
    }
    
    // --- GENERAR NUESTRO PROPIO TOKEN JWT PARA EL USUARIO ---
    $secret_key = "StetsonLatam1977";
    $issuer_claim = "stetsonlatam.com";
    $audience_claim = "stetsonlatam.com";
    $issuedat_claim = time();
    $expire_claim = $issuedat_claim + 3600; // expira en 1 hora

    // Obtener rol del usuario (asumimos 'user' por defecto)
    $stmt_role = $conn->prepare("SELECT role FROM users WHERE id = ?");
    $stmt_role->bind_param("i", $user_id);
    $stmt_role->execute();
    $role = $stmt_role->get_result()->fetch_assoc()['role'] ?? 'user';
    $stmt_role->close();

    $token_payload = array(
        "iss" => $issuer_claim,
        "aud" => $audience_claim,
        "iat" => $issuedat_claim,
        "exp" => $expire_claim,
        "data" => array(
            "id" => $user_id,
            "name" => $name,
            "email" => $email,
            "role" => $role
        )
    );

    $jwt = JWT::encode($token_payload, $secret_key, 'HS256');
    echo json_encode(['success' => true, 'token' => $jwt]);

} else {
    // Token de Google inválido
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Token de Google inválido.']);
}

$conn->close();
?>