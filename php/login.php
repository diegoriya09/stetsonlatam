<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


session_start();

error_log("POST token: " . ($_POST['csrf_token'] ?? ''));
error_log("SESSION token: " . ($_SESSION['csrf_token'] ?? ''));

require 'conexion';
require '../vendor/autoload';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header('Content-Type: application/json');

// Sanitizar y validar entradas
$email = trim(filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL));
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    http_response_code(400);
    echo json_encode(["error" => "Email y contraseña son obligatorios"]);
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(["error" => "Email no válido"]);
    exit;
}

// (Opcional) Validar token CSRF
if (isset($_POST['csrf_token'])) {
    if (!isset($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        http_response_code(403);
        echo json_encode(["error" => "Token CSRF no válido"]);
        exit;
    }
}

$sql = "SELECT id, password, role FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($user_id, $hashed_password, $user_role);
    $stmt->fetch();

    if (password_verify($password, $hashed_password)) {
        $_SESSION['user_role'] = $user_role;
        $_SESSION['user_id'] = $user_id;
        
        $secret_key = "StetsonLatam1977";

        $payload = [
            "iss" => "stetsonlatam.com",
            "aud" => "stetsonlatam.com",
            "iat" => time(),
            "exp" => time() + 3600, //1 hour expiration
            "data" => [
                "id" => $user_id,
                "email" => $email,
                "role" => $user_role
            ]
        ];

        $jwt = JWT::encode($payload, $secret_key, 'HS256');
        echo json_encode([
        "token" => $jwt
    ]);
        exit;
    } else {
        http_response_code(401);
        echo json_encode(["error" => "Contraseña incorrecta"]);
        exit;
    }
} else {
    http_response_code(404);
    echo json_encode(["error" => "Usuario no encontrado"]);
}
?>
