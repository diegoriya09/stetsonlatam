<?php
require_once 'php/conexion.php';

$productos = [];

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$sql = "SELECT * FROM productos WHERE category = 'hats' AND is_featured = 1";
$resultado = $conn->query($sql);

if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $productos[] = $fila;
    }
}

$conn->close();
?>

<section id="featured" class="section featured" typeof="schema:ItemList">
        <h2 property="schema:name">Featured Hats</h2>
        <div class="card-grid">
            <?php 
            include 'featuredHats.php'; 
            if (count($productos) > 0):
                foreach ($productos as $producto): ?>
                    <article class="card-item">
                        <img src="<?= htmlspecialchars($producto['image']) ?>" alt="<?= htmlspecialchars($producto['name']) ?>">
                        <h3><?= htmlspecialchars($producto['name']) ?></h3>
                        <p>$<?= number_format($producto['price'], 0, ',', '.') ?></p>
                        <button class="add-to-cart-btn"
                            data-id="<?= $producto['id'] ?>"
                            data-name="<?= htmlspecialchars($producto['name']) ?>"
                            data-price="<?= $producto['price'] ?>"
                            data-image="<?= htmlspecialchars($producto['image']) ?>">
                            <i class="fas fa-cart-plus"></i>
                        </button>
                    </article>
            <?php 
                endforeach;
            else:
                echo "<p style='color:red;'>No hay productos disponibles.</p>";
            endif;
            ?>
        </div>
    </section>
