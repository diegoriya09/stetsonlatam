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

$imagenes = [];
if (!empty($producto['images'])) {
    $imagenes = json_decode($producto['images'], true);
}

// Obtener productos relacionados (misma categoría, excluyendo el actual)
$relacionados = [];
if (!empty($producto['category'])) {
    $cat = $conn->real_escape_string($producto['category']);
    $sql_rel = "SELECT id, name, price, image FROM productos WHERE category = '$cat' AND id != {$producto['id']} LIMIT 4";
    $result_rel = $conn->query($sql_rel);
    while ($row = $result_rel->fetch_assoc()) {
        $relacionados[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($producto['name']) ?></title>
  <meta name="description" content="Stetson LATAM - Legendary Hats for Latin America and Central America">
  <link rel="icon" href="img/logo.png" type="image/x-icon" loading="lazy">
  <link rel="stylesheet" href="css/index.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="css/product.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Lora:wght@700&display=swap" rel="stylesheet">
</head>
<body>

<?php include 'navbar.php'; ?>

<?php include 'modal.php'; ?>

<?php include 'cart.php'; ?>
<?php include 'checkout.php'; ?>
<!-- Breadcrumbs -->
<nav class="breadcrumb" aria-label="breadcrumb">
  <ol>
    <li><a href="index.php">Home</a></li>
    <li><a href="hats.php">Hats</a></li>
    <li aria-current="page"><?= htmlspecialchars($producto['name']) ?></li>
  </ol>
</nav>
<button onclick="window.history.back()" class="btn-volver" style="margin: 1rem 0 0 0;">
  &larr; Volver atrás
</button>

<!-- Vista de producto -->
<section class="producto-detalle">
  <div class="galeria">
    <img src="<?= htmlspecialchars($producto['image']) ?>" class="img-principal" alt="<?= htmlspecialchars($producto['name']) ?>" loading="lazy">
    <?php if (!empty($imagenes)): ?>
    <div class="miniaturas">
      <?php foreach ($imagenes as $img): ?>
        <img src="<?= htmlspecialchars($img) ?>" class="miniatura" alt="Miniatura" style="width:60px;cursor:pointer;" loading="lazy">
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
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
      <label>Quantity:</label>
      <button class="menos">-</button>
      <input type="number" id="cantidad" value="1" min="1">
      <button class="mas">+</button>
    </div>

    <button class="add-to-cart-btn"
      data-id="<?= $producto['id'] ?>"
      data-name="<?= htmlspecialchars($producto['name']) ?>"
      data-price="<?= $producto['price'] ?>"
      data-image="<?= htmlspecialchars($producto['image']) ?>">
      <i class="fas fa-cart-plus"></i> Add to Cart
    </button>

    <div class="descripcion">
      <p><?= nl2br(htmlspecialchars($producto['description'])) ?></p>
    </div>
  </div>
</section>

<?php if (!empty($relacionados)): ?>
<section class="relacionados">
  <h2>Productos relacionados</h2>
  <div class="card-grid">
    <?php foreach ($relacionados as $rel): ?>
      <a href="producto.php?id=<?= $rel['id'] ?>" class="card-item">
        <img src="<?= htmlspecialchars($rel['image']) ?>" alt="<?= htmlspecialchars($rel['name']) ?>" loading="lazy">
        <h3><?= htmlspecialchars($rel['name']) ?></h3>
        <p>$<?= number_format($rel['price'], 2) ?></p>
      </a>
    <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>
<?php include 'footer.php'; ?>


    <script src="js/auth.js?v=<?php echo time(); ?>"></script>
    <script src="js/index.js?v=<?php echo time(); ?>"></script>
    <script src="js/cart.js?v=<?php echo time(); ?>"></script>
    <script src="js/hats.js?v=<?php echo time(); ?>"></script>
    <script src="js/product.js?v=<?php echo time(); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
