<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: /');
    exit;
}

require '../php/conexion.php';

$id = intval($_POST['id'] ?? 0);
$nombre = trim(strip_tags($_POST['nombre'] ?? ''));
$precio = floatval($_POST['precio'] ?? 0);
$categoria = trim(strip_tags($_POST['categoria'] ?? ''));

if (!$id || !$nombre || !$precio || !$categoria) {
    die("Todos los campos son obligatorios.");
}

// Procesar imagen si se subió
$imagen = null;
if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
    $nombreArchivo = uniqid('prod_') . '.' . $ext;
    $ruta = "../uploads/" . $nombreArchivo;
    if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta)) {
        $imagen = $nombreArchivo;
    }
}

if ($imagen) {
    $stmt = $conn->prepare("UPDATE productos SET name=?, price=?, category=?, image=? WHERE id=?");
    $stmt->bind_param("sdssi", $nombre, $precio, $categoria, $image, $id);
} else {
    $stmt = $conn->prepare("UPDATE productos SET name=?, price=?, category=? WHERE id=?");
    $stmt->bind_param("sdsi", $nombre, $precio, $categoria, $id);
}

if ($stmt->execute()) {
    header("Location: admin.php?msg=Producto+editado");
    exit;
} else {
    die("Error al editar producto: " . $stmt->error);
}
?>