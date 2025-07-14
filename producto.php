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

// Obtener tallas del producto
$tallas = [];
$stmt = $conn->prepare("SELECT s.id, s.name FROM sizes s JOIN product_sizes ps ON s.id = ps.size_id WHERE ps.product_id = ?");
$stmt->bind_param("i", $producto['id']);
$stmt->execute();
$result_tallas = $stmt->get_result();
while ($row = $result_tallas->fetch_assoc()) {
  $tallas[] = $row;
}
$stmt->close();

// Obtener colores del producto
$colores = [];
$stmt = $conn->prepare("SELECT c.id, c.name, c.hex FROM colors c JOIN product_colors pc ON c.id = pc.color_id WHERE pc.product_id = ?");
$stmt->bind_param("i", $producto['id']);
$stmt->execute();
$result_colores = $stmt->get_result();
while ($row = $result_colores->fetch_assoc()) {
  $colores[] = $row;
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
<html lang="en" prefix="schema: http://schema.org/">

<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($producto['name']) ?></title>
  <meta name="description" content="Stetson LATAM - Legendary Hats for Latin America and Central America">
  <link rel="icon" href="img/logo.webp" type="image/x-icon" loading="lazy">
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

      <?php if (!empty($colores)): ?>
        <div class="colores">
          <strong>Color:</strong>
          <?php foreach ($colores as $color): ?>
            <button type="button" class="color-btn" data-color="<?= $color['name'] ?>" style="--color: <?= $color['hex'] ?>;" title="<?= $color['name'] ?>"></button>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($tallas)): ?>
        <div class="tallas">
          <strong>Size:</strong>
          <?php foreach ($tallas as $talla): ?>
            <button type="button" class="size-btn" data-size="<?= $talla['name'] ?>"><?= htmlspecialchars($talla['name']) ?></button>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <div class="cantidad">
        <strong>Quantity:</strong>
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

      <script>
        // Selección de color y talla
        document.addEventListener('DOMContentLoaded', function() {
          let selectedColor = null;
          let selectedSize = null;
          const addToCartBtn = document.querySelector('.add-to-cart-btn');

          // Color
          document.querySelectorAll('.color-btn').forEach(btn => {
            btn.addEventListener('click', function() {
              document.querySelectorAll('.color-btn').forEach(b => b.classList.remove('selected'));
              this.classList.add('selected');
              selectedColor = this.getAttribute('data-color-id'); // Usar el ID del color
              if (addToCartBtn) addToCartBtn.dataset.color = selectedColor || '';
            });
          });

          // Talla
          document.querySelectorAll('.size-btn').forEach(btn => {
            btn.addEventListener('click', function() {
              document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('selected'));
              this.classList.add('selected');
              selectedSize = this.getAttribute('data-size-id'); // Usar el ID de la talla
              if (addToCartBtn) addToCartBtn.dataset.size = selectedSize || '';
            });
          });

          // Interceptar el add-to-cart
          if (addToCartBtn) {
            addToCartBtn.addEventListener('click', function(e) {
              if (document.querySelectorAll('.color-btn').length && !selectedColor) {
                Swal.fire({
                  icon: 'warning',
                  text: 'Please select a color.'
                });
                e.preventDefault();
                return;
              }
              if (document.querySelectorAll('.size-btn').length && !selectedSize) {
                Swal.fire({
                  icon: 'warning',
                  text: 'Please select a size.'
                });
                e.preventDefault();
                return;
              }
            });
          }
        });
      </script>
      <div class="descripcion">
        <p><?= nl2br(htmlspecialchars($producto['description'])) ?></p>
      </div>
    </div>
  </section>

  <?php if (!empty($relacionados)): ?>
    <section class="relacionados">
      <h2>Related Products</h2>
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