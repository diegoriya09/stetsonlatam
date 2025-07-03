<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: /');
    exit;
}

require '../php/conexion.php';

$id = intval($_GET['id'] ?? 0);
if (!$id) {
    die("ID inválido.");
}

$stmt = $conn->prepare("DELETE FROM productos WHERE id=?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: admin.php?msg=Producto+eliminado");
    exit;
} else {
    die("Error al eliminar producto: " . $stmt->error);
}
?>