<?php
require_once 'php/conexion.php';

$sql = "SELECT * FROM productos WHERE category = 'caps'";
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
<html lang="en" prefix="schema: http://schema.org/">

<head>
  <meta charset="UTF-8">
  <title>Caps | Stetson Latam</title>
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
    <h2>Stetson Caps</h2>
    <div class="sort-container">
      <label for="sort-select" class="sort-label">Sort</label>
      <select id="sort-select" class="sort-select">
        <option value="name-asc">Name, A–Z</option>
        <option value="name-desc">Name, Z–A</option>
        <option value="price-asc">Price Low to High</option>
        <option value="price-desc">Price High to Low</option>
      </select>
    </div>
    <div class="multi-color-dropdown">
      <form method="GET" id="color-filter-form">
        <button type="button" class="dropdown-toggle">
          Filter by colors <i class="fas fa-chevron-down"></i>
        </button>
        <div class="dropdown-menu">
          <?php foreach ($colores as $color):
            $selected = in_array($color['id'], $color_ids);
          ?>
            <label class="dropdown-item<?= $selected ? ' selected' : '' ?>">
              <input type="checkbox"
                name="colors[]"
                value="<?= $color['id'] ?>"
                <?= $selected ? 'checked' : '' ?> >
              <span class="color-circle" style="background: <?= $color['hex'] ?>;"></span>
              <?= htmlspecialchars($color['name']) ?>
            </label>
          <?php endforeach; ?>
          <?php foreach ($talla_ids as $tid): ?>
            <input type="hidden" name="sizes[]" value="<?= htmlspecialchars($tid) ?>">
          <?php endforeach; ?>
          <div style="margin-top:10px;">
            <button type="submit" class="apply-btn">Apply Filter</button>
            <?php if (!empty($color_ids)): ?>
              <a href="hats.php" class="clear-filter">Clear</a>
            <?php endif; ?>
          </div>
        </div>
      </form>
    </div>
    <div class="multi-size-dropdown">
      <form method="GET" id="size-filter-form">
        <button type="button" class="dropdown-toggle">
          Filter by sizes <i class="fas fa-chevron-down"></i>
        </button>
        <div class="dropdown-menu">
          <?php foreach ($tallas as $talla):
            $selected = in_array($talla['id'], $talla_ids);
          ?>
            <label class="dropdown-item<?= $selected ? ' selected' : '' ?>">
              <input type="checkbox"
                name="sizes[]"
                value="<?= $talla['id'] ?>"
                <?= $selected ? 'checked' : '' ?> >
              <?= htmlspecialchars($talla['name']) ?>
            </label>
          <?php endforeach; ?>
          <?php foreach ($color_ids as $cid): ?>
            <input type="hidden" name="colors[]" value="<?= htmlspecialchars($cid) ?>">
          <?php endforeach; ?>
          <div style="margin-top:10px;">
            <button type="submit" class="apply-btn">Apply Filter</button>
            <?php if (!empty($talla_ids)): ?>
              <a href="hats.php" class="clear-filter">Clear</a>
            <?php endif; ?>
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
        <p style="color:#3c3737;">There are no caps available at this time.</p>
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
</body>

</html>