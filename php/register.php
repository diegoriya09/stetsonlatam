<?php
// register.php (VERSIÓN COMPLETA CON GENERACIÓN DE CUPÓN)

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
    exit;
}

require 'conexion.php';

// Sanitizar entradas
$name = trim(strip_tags($_POST['name'] ?? ''));
$email = trim(filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL));
$password = $_POST['password'] ?? '';

// Validaciones
if (!preg_match('/^[a-zA-Z\s]{3,40}$/', $name)) {
    echo json_encode(['status' => 'error', 'message' => 'Nombre inválido.']);
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'message' => 'Email inválido.']);
    exit;
}
if (strlen($password) < 6) {
    echo json_encode(['status' => 'error', 'message' => 'La contraseña debe tener al menos 6 caracteres.']);
    exit;
}

// Verificar si el email ya existe
$stmt_check = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt_check->bind_param("s", $email);
$stmt_check->execute();
$result = $stmt_check->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['status' => 'error', 'message' => 'Este correo electrónico ya está registrado.']);
    $stmt_check->close();
    $conn->close();
    exit;
}
$stmt_check->close();

// Hash de la contraseña
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

// Preparar la inserción del usuario
$sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $name, $email, $passwordHash);

if ($stmt->execute()) {
    // Obtener el ID del usuario recién creado
    $user_id = $conn->insert_id;

    echo json_encode(['status' => 'success', 'message' => '¡Registro exitoso!']);
} else {
    error_log("Error de registro: " . $stmt->error);
    echo json_encode(['status' => 'error', 'message' => 'Ocurrió un error durante el registro. Por favor, inténtalo de nuevo.']);
}

$stmt->close();
$conn->close();
