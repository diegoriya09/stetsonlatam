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

$color_ids = $_GET['colors'] ?? [];

if (!empty($color_ids)) {
  // Crear placeholders para query (?,?,?)
  $placeholders = implode(',', array_fill(0, count($color_ids), '?'));

  $sql = "SELECT DISTINCT p.* FROM productos p
          INNER JOIN product_colors pc ON p.id = pc.product_id
          WHERE p.category = 'hats' AND pc.color_id IN ($placeholders)";

  $stmt = $conn->prepare($sql);
  $stmt->bind_param(str_repeat('i', count($color_ids)), ...$color_ids);
  $stmt->execute();
  $result = $stmt->get_result();
} else {
  $sql = "SELECT * FROM productos WHERE category = 'hats'";
  $result = $conn->query($sql);
}

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
      <form method="GET" id="color-filter-form">
        <strong>Filter by colors:</strong><br><br>
        <?php foreach ($colores as $color): ?>
          <?php
          $isSelected = in_array($color['id'], $_GET['colors'] ?? []);
          ?>
          <button
            type="button"
            class="color-filter-btn <?= $isSelected ? 'active' : '' ?>"
            data-color-id="<?= $color['id'] ?>"
            title="<?= htmlspecialchars($color['name']) ?>"
            style="
          background: <?= $color['hex'] ?>;
          border: 2px solid #ccc;
          border-radius: 50%;
          width: 32px; 
          height: 32px; 
          margin-right: 8px;
          cursor: pointer;
          outline: none;
          <?= $isSelected ? 'box-shadow: 0 0 0 3px #bfa76a;' : '' ?>
        ">
          </button>
        <?php endforeach; ?>

        <!-- Campos ocultos para enviar los colores -->
        <div id="hidden-colors"></div>

        <button type="submit" style="margin-left: 12px;">Apply Filter</button>
        <?php if (!empty($_GET['colors'])): ?>
          <a href="hats.php" style="margin-left: 12px; font-size: 0.9rem;">Clear filter</a>
        <?php endif; ?>
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
      const buttons = document.querySelectorAll('.color-filter-btn');
      const form = document.getElementById('color-filter-form');
      const hiddenContainer = document.getElementById('hidden-colors');

      let selectedColors = new Set(<?= json_encode($_GET['colors'] ?? []) ?>);

      buttons.forEach(button => {
        button.addEventListener('click', () => {
          const colorId = button.dataset.colorId;

          if (selectedColors.has(colorId)) {
            selectedColors.delete(colorId);
            button.classList.remove('active');
            button.style.boxShadow = '';
          } else {
            selectedColors.add(colorId);
            button.classList.add('active');
            button.style.boxShadow = '0 0 0 3px #bfa76a';
          }

          // Actualizar campos ocultos
          hiddenContainer.innerHTML = '';
          selectedColors.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'colors[]';
            input.value = id;
            hiddenContainer.appendChild(input);
          });
        });
      });

      // Al cargar, rellenar hidden inputs
      selectedColors.forEach(id => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'colors[]';
        input.value = id;
        hiddenContainer.appendChild(input);
      });
    });
  </script>
</body>

</html>