<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: /');
    exit;
}

require '../php/conexion.php';

// Validar y sanitizar entradas
$nombre = trim(strip_tags($_POST['nombre'] ?? ''));
$descripcion = trim(strip_tags($_POST['descripcion'] ?? ''));
$precio = floatval($_POST['precio'] ?? 0);
$categoria = trim(strip_tags($_POST['categoria'] ?? ''));

// Validar campos obligatorios
if (!$nombre || !$descripcion || !$precio || !$categoria) {
    die("All fields are required. Please fill in all fields.");
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
$stmt = $conn->prepare("INSERT INTO productos (name, description, price, category, image) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("ssdss", $nombre, $descripcion, $precio, $categoria, $imagen);
if ($stmt->execute()) {
    header("Location: admin.php?msg=added+product");
    exit;
} else {
    die("Error adding product: " . $stmt->error);
}
?>