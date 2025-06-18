<?php
// Conexión a la base de datos
require_once 'conexion.php';

// Verificar conexión
if ($conexion->connect_error) {
    die("<p style='color: red;'>✘ Conexión fallida: " . $conexion->connect_error . "</p>");
}

// Ejecutar consulta SQL
$sql = "SELECT * FROM productos";
$resultado = $conexion->query($sql);

// Mostrar mensajes de prueba
if (!$resultado) {
    echo "<p style='color: red;'>✘ Error en la consulta: " . $conexion->error . "</p>";
} elseif ($resultado->num_rows === 0) {
    echo "<p style='color: orange;'>⚠ No se encontraron productos en la base de datos.</p>";
} else {
    echo "<p style='color: green;'>✔ Se encontraron productos: " . $resultado->num_rows . "</p>";

    // Mostrar productos
    while ($producto = $resultado->fetch_assoc()) {
        echo '<article class="card-item" typeof="schema:Product">';
        echo '<img src="' . htmlspecialchars($producto['image']) . '" alt="' . htmlspecialchars($producto['name']) . '">';
        echo '<h3 property="schema:name">' . htmlspecialchars($producto['name']) . '</h3>';
        echo '<p>$' . number_format($producto['price'], 0, ',', '.') . '</p>';
        echo '<button class="add-to-cart-btn" 
                    data-id="' . $producto['id'] . '" 
                    data-name="' . htmlspecialchars($producto['name']) . '" 
                    data-price="' . $producto['price'] . '" 
                    data-image="' . htmlspecialchars($producto['image']) . '">
                🛒 Agregar al carrito
              </button>';
        echo '</article>';
    }
}

$conexion->close();
?>
