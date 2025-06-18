<?php
$conexion = new mysqli("localhost", "root", "", "stetsonlatamdb");
$conexion->set_charset("utf8");

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$sql = "SELECT * FROM productos";
$resultado = $conexion->query($sql);

if ($resultado->num_rows > 0) {
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
} else {
    echo '<p>No hay productos disponibles.</p>';
}

$conexion->close();
?>
