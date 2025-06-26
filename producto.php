<?php
require_once 'php/conexion.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Validar ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Seguridad: sanitizar el ID
$id = $conn->real_escape_string($id);

// Ejecutar la consulta directamente
$sql = "SELECT * FROM productos WHERE id = $id";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
  $producto = $result->fetch_assoc();
} else {
  echo "Producto no encontrado.";
  exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($producto['name']) ?></title>
  <link rel="stylesheet" href="estilos.css">
</head>
<body>

<?php include 'navbar.php'; ?>

<!-- Breadcrumbs -->
<nav class="breadcrumb">
  <a href="index.php">Inicio</a> ›
  <a href="catalogo.php">Productos</a> ›
  <span><?= htmlspecialchars($producto['name']) ?></span>
</nav>

<!-- Vista de producto -->
<section class="producto-detalle">
  <div class="galeria">
    <img src="<?= htmlspecialchars($producto['image']) ?>" class="img-principal" alt="<?= htmlspecialchars($producto['name']) ?>">
    <!-- Aquí podrías agregar miniaturas si tienes más imágenes -->
  </div>

  <div class="info-producto">
    <h1><?= htmlspecialchars($producto['name']) ?></h1>
    <p class="precio">$<?= number_format($producto['price'], 2) ?></p>
    <p><strong>Color:</strong> Natural</p>

    <div class="tallas">
      <strong>Size:</strong>
      <button>6 3/4</button>
      <button>7</button>
      <button>7 1/4</button>
      <!-- Opcional: manejar tallas desde base de datos -->
    </div>

    <div class="cantidad">
      <label>Cantidad:</label>
      <button class="menos">-</button>
      <input type="number" id="cantidad" value="1" min="1">
      <button class="mas">+</button>
    </div>

    <button class="add-to-cart-btn"
      data-id="<?= $producto['id'] ?>"
      data-name="<?= htmlspecialchars($producto['name']) ?>"
      data-price="<?= $producto['price'] ?>"
      data-image="<?= htmlspecialchars($producto['image']) ?>">
      <i class="fas fa-cart-plus"></i> Añadir al carrito
    </button>

    <div class="descripcion">
      <p><?= nl2br(htmlspecialchars($producto['descripcion'])) ?></p>
    </div>
  </div>
</section>

<?php include 'footer.php'; ?>


<script src="js/cart.js?v=<?php echo time(); ?>"></script> <!-- Donde está handleAddToCart -->
</body>
</html>
