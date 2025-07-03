<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: /');
    exit;
}

require '../php/conexion.php';

// Validar y sanitizar entradas
$nombre = trim(strip_tags($_POST['nombre'] ?? ''));
$precio = floatval($_POST['precio'] ?? 0);
$categoria = trim(strip_tags($_POST['categoria'] ?? ''));

// Validar campos obligatorios
if (!$nombre || !$precio || !$categoria) {
    die("Todos los campos son obligatorios.");
}

// Procesar imagen si se subió
$imagen = null;
if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
    $nombreArchivo = uniqid('prod_') . '.' . $ext;
    $ruta = "../img/" . $nombreArchivo;
    if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta)) {
        $imagen = "img/" . $nombreArchivo; // Ruta para la base de datos
    }
}

// Insertar producto
$stmt = $conn->prepare("INSERT INTO productos (name, price, category, image) VALUES (?, ?, ?, ?)");
$stmt->bind_param("sdss", $nombre, $precio, $categoria, $imagen);
if ($stmt->execute()) {
    header("Location: admin.php?msg=Producto+agregado");
    exit;
} else {
    die("Error al agregar producto: " . $stmt->error);
}
?>