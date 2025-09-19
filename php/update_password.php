<?php
// php/auth/update_password.php

require_once 'conexion.php';
header('Content-Type: application/json');

$token = $_POST['token'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($token) || strlen($password) < 6) {
    echo json_encode(['success' => false, 'message' => 'Token inválido o contraseña muy corta.']);
    exit;
}

// 1. Buscar usuario con el token válido y que no haya expirado
$stmt = $conn->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_token_expires_at > NOW()");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    echo json_encode(['success' => false, 'message' => 'El enlace de recuperación es inválido o ha expirado. Por favor, solicita uno nuevo.']);
    exit;
}

// 2. Si el token es válido, actualizar la contraseña y limpiar el token
$password_hash = password_hash($password, PASSWORD_DEFAULT);
$stmt_update = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expires_at = NULL WHERE id = ?");
$stmt_update->bind_param("si", $password_hash, $user['id']);
$stmt_update->execute();
$stmt_update->close();

echo json_encode(['success' => true, 'message' => '¡Contraseña actualizada con éxito! Ya puedes iniciar sesión.']);
$conn->close();
?>