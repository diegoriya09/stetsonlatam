<?php
require_once 'conexion.php';

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

echo "<p style='color: green;'>✔ Conexión exitosa</p>";

$sql = "SELECT * FROM productos";
$resultado = $conexion->query($sql);

if ($resultado) {
    echo "<p>Consulta ejecutada correctamente</p>";
    echo "<p>Total filas: " . $resultado->num_rows . "</p>";

    if ($resultado->num_rows > 0) {
        while ($producto = $resultado->fetch_assoc()) {
            echo "<pre>" . print_r($producto, true) . "</pre>"; // Muestra los datos crudos
        }
    } else {
        echo "<p style='color: red;'>✘ No se encontraron productos.</p>";
    }
} else {
    echo "<p style='color: red;'>Error en la consulta: " . $conexion->error . "</p>";
}

?>
