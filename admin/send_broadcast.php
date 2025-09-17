<?php
// admin/send_broadcast.php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    die("Acceso denegado.");
}
require_once '../php/conexion.php';

$message = $_POST['message'] ?? '';
$link = $_POST['link'] ?? '';

if (empty($message)) {
    header("Location: admin?section=notifications&error=Mensaje vacío");
    exit;
}

try {
    // 1. Obtener todos los IDs de los usuarios
    $users_result = $conn->query("SELECT id FROM users");
    $user_ids = $users_result->fetch_all(MYSQLI_NUM);
    
    // 2. Insertar una notificación para cada usuario
    $stmt = $conn->prepare("INSERT INTO notifications (user_id, message, link) VALUES (?, ?, ?)");
    foreach ($user_ids as $id_array) {
        $user_id = $id_array[0];
        $stmt->bind_param("iss", $user_id, $message, $link);
        $stmt->execute();
    }
    header("Location: admin?section=notifications&success=Notificación enviada a " . count($user_ids) . " usuarios.");
} catch (Exception $e) {
    header("Location: admin?section=notifications&error=" . urlencode($e->getMessage()));
}
?>