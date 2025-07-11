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
while ($row = $result_colores->fetch_assoc()) {
  $colores[] = $row;
}

// Obtener tallas disponibles para sombreros
$sql_tallas = "SELECT DISTINCT s.id, s.name 
               FROM sizes s
               INNER JOIN product_sizes ps ON s.id = ps.size_id
               INNER JOIN productos p ON ps.product_id = p.id
               WHERE p.category = 'hats'";
$result_tallas = $conn->query($sql_tallas);
$tallas = [];
while ($row = $result_tallas->fetch_assoc()) {
  $tallas[] = $row;
}

// Obtener filtros del GET
$color_ids = $_GET['colors'] ?? [];
$talla_ids = $_GET['sizes'] ?? [];

// Construir consulta con filtros
$sql = "SELECT DISTINCT p.* FROM productos p";
$joins = [];
$where = ["p.category = 'hats'"];
$params = [];
$types = '';

if (!empty($color_ids)) {
  $joins[] = "INNER JOIN product_colors pc ON p.id = pc.product_id";
  $placeholders = implode(',', array_fill(0, count($color_ids), '?'));
  $where[] = "pc.color_id IN ($placeholders)";
  $types .= str_repeat('i', count($color_ids));
  $params = array_merge($params, $color_ids);
}

if (!empty($talla_ids)) {
  $joins[] = "INNER JOIN product_sizes ps ON p.id = ps.product_id";
  $placeholders = implode(',', array_fill(0, count($talla_ids), '?'));
  $where[] = "ps.size_id IN ($placeholders)";
  $types .= str_repeat('i', count($talla_ids));
  $params = array_merge($params, $talla_ids);
}

if (!empty($joins)) {
  $sql .= ' ' . implode(' ', $joins);
}
if (!empty($where)) {
  $sql .= ' WHERE ' . implode(' AND ', $where);
}

$stmt = $conn->prepare($sql);
if (!empty($params)) {
  $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$productos = [];
while ($row = $result->fetch_assoc()) {
  $productos[] = $row;
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en" prefix="schema: http://schema.org/">

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
    <div class="multi-color-dropdown">
      <form method="GET" id="color-filter-form">
        <button type="button" class="dropdown-toggle">
          Filter by colors <i class="fas fa-chevron-down"></i>
        </button>
        <div class="dropdown-menu">
          <?php foreach ($colores as $color):
            $selected = in_array($color['id'], $color_ids);
          ?>
            <label class="dropdown-item">
              <input type="checkbox"
                name="colors[]"
                value="<?= $color['id'] ?>"
                <?= $selected ? 'checked' : '' ?>>
              <span class="color-circle" style="background: <?= $color['hex'] ?>;"></span>
              <?= htmlspecialchars($color['name']) ?>
            </label>
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
            <label class="dropdown-item">
              <input type="checkbox"
                name="sizes[]"
                value="<?= $talla['id'] ?>"
                <?= $selected ? 'checked' : '' ?>>
              <?= htmlspecialchars($talla['name']) ?>
            </label>
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
      const dropdowns = document.querySelectorAll('.multi-color-dropdown, .multi-size-dropdown');

      dropdowns.forEach(dropdown => {
        const toggle = dropdown.querySelector('.dropdown-toggle');
        if (!toggle) return;
        toggle.addEventListener('click', (e) => {
          e.stopPropagation();
          // Cierra otros dropdowns
          dropdowns.forEach(d => d !== dropdown && d.classList.remove('open'));
          dropdown.classList.toggle('open');
        });
      });

      // Cerrar todos los dropdowns si se hace clic fuera
      document.addEventListener('click', (e) => {
        dropdowns.forEach(dropdown => {
          if (!dropdown.contains(e.target)) {
            dropdown.classList.remove('open');
          }
        });
      });
    });
  </script>
</body>

</html>