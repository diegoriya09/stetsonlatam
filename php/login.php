<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'conexion.php';
require '../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header('Content-Type: application/json');

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

$sql = "SELECT id, password FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($user_id, $hashed_password);
    $stmt->fetch();

    if (password_verify($password, $hashed_password)) {
        $secret_key = "StetsonLatam1977";

        $payload = [
            "iss" => "stetsonlatam.com",
            "aud" => "stetsonlatam.com",
            "iat" => time(),
            "exp" => time() + 3600, //1 hour expiration
            "data" => [
                "id" => $user_id,
                "email" => $email
            ]
        ];

        $jwt = JWT::encode($payload, $secret_key, 'HS256');

    } else {
        http_response_code(401);
        echo json_encode(["error" => "Contraseña incorrecta"]);
    }
} else {
    http_response_code(404);
    echo json_encode(["error" => "Usuario no encontrado"]);
}
echo json_encode(["token" => $jwt]);
exit;
?>
