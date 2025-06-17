<?php
$host = 'localhost';
$user = 'root';
$pass = 'miusuario_loading';
$dbname = 'stetsonlatamdb';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
