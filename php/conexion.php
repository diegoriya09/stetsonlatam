<?php
$host = "localhost";
$usuario = "root";
$contrasena = "Dinalsom1977";
$basedatos = "stetsonlatamDB";

$conn = new mysqli($host, $usuario, $contrasena, $basedatos);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
