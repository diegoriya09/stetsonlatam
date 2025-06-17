<?php
require 'conexion.php';

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT password FROM usuarios WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($hashed_password);
    $stmt->fetch();

    if (password_verify($password, $hashed_password)) {
        echo "Login exitoso";
        // Aquí puedes redirigir o crear sesión
    } else {
        echo "Contraseña incorrecta";
    }
} else {
    echo "Usuario no encontrado";
}
$conn->close();
?>
