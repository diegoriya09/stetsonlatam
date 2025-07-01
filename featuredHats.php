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
        // Verificar si hay productos disponibles 
            if (count($productos) > 0):
                foreach ($productos as $producto): ?>
                    <article class="card-item">
                        <a href="producto.php?id=<?= $producto['id'] ?>" class="card-link">
                            <img src="<?= htmlspecialchars($producto['image']) ?>" alt="<?= htmlspecialchars($producto['name']) ?>">
                            <h3><?= htmlspecialchars($producto['name']) ?></h3>
                            <p>$<?= number_format($producto['price'], 0, ',', '.') ?></p>
                        </a>
                        <button class="wishlist-btn" 
                            data-id="<?= $producto['id'] ?>" 
                            data-name="<?= htmlspecialchars($producto['name']) ?>" 
                            data-price="<?= $producto['price'] ?>.00" 
                            data-image="<?= htmlspecialchars($producto['image']) ?>">
                            <i class="fas fa-heart"></i>
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
