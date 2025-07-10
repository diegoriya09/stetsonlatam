<?php
require_once 'php/conexion.php';

// Obtener colores disponibles para sombreros
$sql_colores = "SELECT DISTINCT c.id, c.name, c.hex 
                FROM colors c 
                INNER JOIN product_colors pc ON c.id = pc.color_id 
                INNER JOIN productos p ON pc.product_id = p.id 
                WHERE p.category = 'hats'";
$result_colores = $conn->query($sql_colores);

$colores = [];
if ($result_colores && $result_colores->num_rows > 0) {
  while ($row = $result_colores->fetch_assoc()) {
    $colores[] = $row;
  }
}

$color_id = isset($_GET['color']) ? intval($_GET['color']) : 0;

if ($color_id > 0) {
  $sql = "SELECT p.* FROM productos p
          INNER JOIN product_colors pc ON p.id = pc.product_id
          WHERE p.category = 'hats' AND pc.color_id = $color_id";
} else {
  $sql = "SELECT * FROM productos WHERE category = 'hats'";
}
$result = $conn->query($sql);

$productos = [];
if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $productos[] = $row;
  }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Hats | Stetson Latam</title>
  <meta name="description" content="Stetson LATAM - Legendary Hats for Latin America and Central America">
  <link rel="icon" href="img/logo.webp" type="image/x-icon" loading="lazy">
  <link rel="stylesheet" href="css/index.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Lora:wght@700&display=swap" rel="stylesheet">
</head>

<body>
  <?php include 'navbar.php'; ?>
  <?php include 'modal.php'; ?>
  <section class="section hats">
    <h2>Stetson Hats</h2>
    <div class="sort-container">
      <label for="sort-select" class="sort-label">Sort</label>
      <select id="sort-select" class="sort-select">
        <option value="name-asc">Name, A–Z</option>
        <option value="name-desc">Name, Z–A</option>
        <option value="price-asc">Price Low to High</option>
        <option value="price-desc">Price High to Low</option>
      </select>
    </div>
    <div class="color-filters" style="margin: 1rem 0;">
      <form method="GET" style="display: inline;">
        <div class="dropdown">
          <button type="button" class="dropdown-toggle">
            <?= $color_id > 0 ? 'Filter: ' . htmlspecialchars(array_filter($colores, fn($c) => $c['id'] == $color_id)[0]['name']) : 'Filter by color' ?>
            <i class="fas fa-chevron-down"></i>
          </button>
          <div class="dropdown-menu">
            <button type="submit" name="color" value="0" class="dropdown-item">All colors</button>
            <?php foreach ($colores as $color): ?>
              <button type="submit" name="color" value="<?= $color['id'] ?>" class="dropdown-item">
                <span class="color-circle" style="background: <?= $color['hex'] ?>;"></span>
                <?= htmlspecialchars($color['name']) ?>
              </button>
            <?php endforeach; ?>
          </div>
        </div>
      </form>
    </div>
    <div class="card-grid">
      <?php if (count($productos) > 0): ?>
        <?php foreach ($productos as $producto): ?>
          <article class="card-item"
            data-name="<?= htmlspecialchars($producto['name']) ?>"
            data-price="<?= $producto['price'] ?>">
            <a href="producto.php?id=<?= $producto['id'] ?>" class="card-link">
              <img src="<?= htmlspecialchars($producto['image']) ?>" alt="<?= htmlspecialchars($producto['name']) ?>" loading="lazy">
              <h3><?= htmlspecialchars($producto['name']) ?></h3>
              <p>$<?= number_format($producto['price'], 2, ',', '.') ?></p>
            </a>
            <!-- Botón de wishlist -->
            <button class="wishlist-btn"
              data-id="<?= $producto['id'] ?>"
              data-name="<?= htmlspecialchars($producto['name']) ?>"
              data-price="<?= $producto['price'] ?>"
              data-image="<?= htmlspecialchars($producto['image']) ?>">
              <i class="fas fa-heart"></i>
            </button>
          </article>
        <?php endforeach; ?>
      <?php else: ?>
        <p style="color:#3c3737;">There are no hats available at this time.</p>
      <?php endif; ?>
    </div>
  </section>
  <!-- Sidebar del carrito -->
  <?php include 'cart.php'; ?>
  <!-- Modal de checkout -->
  <?php include 'checkout.php'; ?>

  <?php include 'footer.php'; ?>
  <script src="js/auth.js?v=<?php echo time(); ?>"></script>
  <script src="js/index.js?v=<?php echo time(); ?>"></script>
  <script src="js/cart.js?v=<?php echo time(); ?>"></script>
  <script src="js/wishlist.js?v=<?php echo time(); ?>"></script>
  <script src="js/hats.js?v=<?php echo time(); ?>"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const toggle = document.querySelector('.dropdown-toggle');
      const menu = document.querySelector('.dropdown-menu');

      toggle.addEventListener('click', () => {
        menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
      });

      // Cierra el menú si haces clic fuera
      document.addEventListener('click', (e) => {
        if (!toggle.contains(e.target) && !menu.contains(e.target)) {
          menu.style.display = 'none';
        }
      });
    });
  </script>
</body>

</html>