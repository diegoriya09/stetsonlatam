<?php
$host = 'localhost:3306';
$user = 'stetsonlatamdb';
$pass = '';
$dbname = 'stetsonlatamdb';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
