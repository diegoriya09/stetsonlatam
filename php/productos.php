<?php
require 'conexion.php';

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Mensaje de prueba
echo "<p style='color: green;'>✔ Conexión exitosa</p>";

$sql = "SELECT * FROM productos";
$resultado = $conexion->query($sql);

if ($resultado && $resultado->num_rows > 0) {
    echo "<p>✔ Se encontraron productos: " . $resultado->num_rows . "</p>";
    while ($producto = $resultado->fetch_assoc()) {
        echo '<article class="card-item">';
        echo '<img src="' . $producto['image'] . '" alt="' . $producto['name'] . '">';
        echo '<h3>' . $producto['name'] . '</h3>';
        echo '<p>$' . number_format($producto['price'], 0, ',', '.') . '</p>';
        echo '</article>';
    }
} else {
    echo "<p style='color: red;'>✘ No se encontraron productos.</p>";
}
$conexion->close();
?>
