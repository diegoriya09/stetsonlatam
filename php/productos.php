<?php
$conexion = new mysqli("localhost", "stetsonlatamdb");

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$sql = "SELECT id, name, price, image FROM productos";
$resultado = $conexion->query($sql);

if ($resultado->num_rows > 0) {
    while($producto = $resultado->fetch_assoc()) {
        echo '
        <article class="card-item" typeof="schema:Product">
            <img src="' . $producto["image"] . '" alt="' . $producto["name"] . '">
            <h3 property="schema:name">' . $producto["name"] . '</h3>
            <button class="add-to-cart-btn" 
                data-id="' . $producto["id"] . '" 
                data-nombre="' . $producto["name"] . '" 
                data-precio="' . $producto["price"] . '" 
                data-imagen="' . $producto["image"] . '">
                🛒 Agregar al carrito
            </button>
        </article>';
    }
} else {
    echo "<p>No hay productos disponibles.</p>";
}

$conexion->close();
?>
