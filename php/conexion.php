<?php
$host = 'localhost:3306';
$user = 'stetsonlatamdb';
$pass = 'Dinalsom1977';
$dbname = 'stetsonlatamdb';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
