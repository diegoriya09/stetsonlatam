<?php
require_once 'conexion.php';

$productos = [];

if ($conn->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$sql = "SELECT * FROM productos";
$resultado = $conexion->query($sql);

if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $productos[] = $fila;
    }
}

$conn->close();
?>
