<?php
require_once 'php/conexion.php';

session_start();
$user_id = $_SESSION['user_id'] ?? null;

// Obtener colores disponibles para sombreros
$sql_colores = "SELECT DISTINCT c.id, c.name, c.hex 
                FROM colors c 
                INNER JOIN product_colors pc ON c.id = pc.color_id 
                INNER JOIN productos p ON pc.product_id = p.id 
                WHERE p.category = 'caps'";
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
               WHERE p.category = 'caps'";
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
$where = ["p.category = 'caps'"];
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

//productos más visitados por el usuario
$user_id = $_SESSION['user_id'] ?? null;
$recomendados = [];

if ($user_id !== null) {
  // Usuario logueado
  $sql = "SELECT p.*, COUNT(*) AS visitas
            FROM productos p
            INNER JOIN user_visits uv ON p.id = uv.product_id
            WHERE uv.user_id = ?
            GROUP BY p.id
            ORDER BY visitas DESC
            LIMIT 6";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $user_id);
} else {
  // Usuario no logueado (user_id es NULL)
  $sql = "SELECT p.*, COUNT(*) AS visitas
            FROM productos p
            INNER JOIN user_visits uv ON p.id = uv.product_id
            WHERE uv.user_id IS NULL
            GROUP BY p.id
            ORDER BY visitas DESC
            LIMIT 6";
  $stmt = $conn->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
  $recomendados[] = $row;
}
$conn->close();
?>

<html>

<head>
  <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="" />
  <link rel="stylesheet" as="style" onload="this.rel='stylesheet'"
    href="https://fonts.googleapis.com/css2?display=swap&amp;family=Noto+Sans%3Awght%40400%3B500%3B700%3B900&amp;family=Work+Sans%3Awght%40400%3B500%3B700%3B900" />

  <title>Caps | Stetson Latam</title>
  <link rel="icon" href="img/logo.webp" type="image/x-icon" loading="lazy">
  <link href="css/index.css?v=<?php echo time(); ?>" rel="stylesheet">
  <link href="css/categoria.css?v=<?php echo time(); ?>" rel="stylesheet">

  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
  <div class="relative flex size-full min-h-screen flex-col bg-white">
    <div class="layout-container flex h-full grow flex-col">

      <?php include 'header.php'; ?>

      <main>
        <section class="category-banner">
          <div class="category-info">
            <h1>Caps</h1>
            <p>Discover our collection of modern and classic caps, perfect for any occasion and style.</p>
          </div>
          <div class="category-image">
            <img src="img/caps-banner.jpg" alt="Stetson Caps Collection">
          </div>
        </section>

        <section class="product-section">

          <div class="flex gap-3 p-4 flex-wrap items-center justify-between">
            <div class="flex gap-3 flex-wrap">
              <div class="relative">
                <button id="size-filter-btn" type="button"
                  class="flex h-10 items-center justify-center gap-x-2 rounded-lg bg-[#f3f2f2] px-4">
                  <p class="text-[#151514] text-sm font-medium">Size</p>
                  <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor"
                    viewBox="0 0 256 256">
                    <path
                      d="M213.66,101.66l-80,80a8,8,0,0,1-11.32,0l-80-80A8,8,0,0,1,53.66,90.34L128,164.69l74.34-74.35a8,8,0,0,1,11.32,11.32Z">
                    </path>
                  </svg>
                </button>
                <div id="size-dropdown" class="absolute hidden mt-1 w-40 bg-white shadow-lg rounded-lg p-2 z-10">
                  <?php foreach ($tallas as $talla): ?>
                    <label class="flex items-center gap-2 p-1 text-sm cursor-pointer hover:bg-gray-100 rounded">
                      <input type="checkbox" class="size-check" value="<?php echo $talla['id']; ?>" <?php echo in_array($talla['id'], $_GET['sizes'] ?? []) ? 'checked' : ''; ?>>
                      <?php echo htmlspecialchars($talla['name']); ?>
                    </label>
                  <?php endforeach; ?>
                </div>
              </div>

              <div class="relative">
                <button id="color-filter-btn" type="button"
                  class="flex h-10 items-center justify-center gap-x-2 rounded-lg bg-[#f3f2f2] px-4">
                  <p class="text-[#151514] text-sm font-medium">Color</p>
                  <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor"
                    viewBox="0 0 256 256">
                    <path
                      d="M213.66,101.66l-80,80a8,8,0,0,1-11.32,0l-80-80A8,8,0,0,1,53.66,90.34L128,164.69l74.34-74.35a8,8,0,0,1,11.32,11.32Z">
                    </path>
                  </svg>
                </button>
                <div id="color-dropdown" class="absolute hidden mt-1 w-48 bg-white shadow-lg rounded-lg p-2 z-10">
                  <?php foreach ($colores as $color): ?>
                    <label class="flex items-center gap-2 p-1 text-sm cursor-pointer hover:bg-gray-100 rounded">
                      <input type="checkbox" class="color-check" value="<?php echo $color['id']; ?>" <?php echo in_array($color['id'], $_GET['colors'] ?? []) ? 'checked' : ''; ?>>
                      <span class="w-4 h-4 rounded-full border"
                        style="background-color: <?php echo htmlspecialchars($color['hex']); ?>"></span>
                      <?php echo htmlspecialchars($color['name']); ?>
                    </label>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>

            <div class="flex items-center gap-2">
              <span class="text-sm font-medium text-gray-600">Sort by Price</span>
              <button id="sort-btn" class="p-2 rounded-lg bg-[#f3f2f2] text-[#151514]">
                <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor"
                  viewBox="0 0 256 256">
                  <path
                    d="M128,128a8,8,0,0,1-8,8H48a8,8,0,0,1,0-16h72A8,8,0,0,1,128,128ZM48,72H184a8,8,0,0,0,0-16H48a8,8,0,0,0,0,16Zm56,112H48a8,8,0,0,0,0,16h56a8,8,0,0,0,0-16Zm125.66-21.66a8,8,0,0,0-11.32,0L192,188.69V112a8,8,0,0,0-16,0v76.69l-26.34-26.35a8,8,0,0,0-11.32,11.32l40,40a8,8,0,0,0,11.32,0l40-40A8,8,0,0,0,229.66,162.34Z">
                  </path>
                </svg>
              </button>
            </div>
          </div>

          <div id="productos-container" class="product-grid">
            <?php foreach ($productos as $producto): ?>
              <a href="producto.php?id=<?php echo $producto['id']; ?>" class="product-card producto-item"
                data-price="<?php echo $producto['price']; ?>">
                <div class="product-card-image"
                  style="background-image: url('<?php echo htmlspecialchars($producto["image"]); ?>');"></div>
                <div class="product-card-info">
                  <h3 class="product-name"><?php echo htmlspecialchars($producto["name"]); ?></h3>
                  <p class="product-price">$<?php echo number_format($producto["price"], 2); ?></p>
                </div>
              </a>
            <?php endforeach; ?>
          </div>
        </section>

        <section class="px-4 py-8">
          <h2 class="text-[#151514] text-2xl font-bold mb-4">Recommended for You</h2>
          <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            <?php if (!empty($recomendados)): ?>
              <?php foreach ($recomendados as $recomendado): ?>
                <a href="producto.php?id=<?php echo $recomendado['id']; ?>" class="product-card">
                  <div class="product-card-image"
                    style="background-image: url('<?php echo htmlspecialchars($recomendado["image"]); ?>');"></div>
                  <div class="product-card-info">
                    <h3 class="product-name"><?php echo htmlspecialchars($recomendado["name"]); ?></h3>
                    <p class="product-price">$<?php echo number_format($recomendado["price"], 2); ?></p>
                  </div>
                </a>
              <?php endforeach; ?>
            <?php else: ?>
              <p class="text-gray-500">No products recommended yet.</p>
            <?php endif; ?>
          </div>
        </section>
      </main>

      <?php include 'footer.php'; ?>
    </div>
  </div>

  <?php include 'modal.php'; ?>

  <script>
    // Script para los filtros (tallas y colores)
    document.addEventListener("DOMContentLoaded", () => {
      const sizeBtn = document.getElementById("size-filter-btn");
      const sizeDropdown = document.getElementById("size-dropdown");
      const colorBtn = document.getElementById("color-filter-btn");
      const colorDropdown = document.getElementById("color-dropdown");

      sizeBtn.addEventListener("click", () => sizeDropdown.classList.toggle("hidden"));
      colorBtn.addEventListener("click", () => colorDropdown.classList.toggle("hidden"));

      document.querySelectorAll(".size-check, .color-check").forEach(input => {
        input.addEventListener("change", () => {
          const params = new URLSearchParams();
          document.querySelectorAll(".size-check:checked").forEach(cb => params.append("sizes[]", cb.value));
          document.querySelectorAll(".color-check:checked").forEach(cb => params.append("colors[]", cb.value));
          window.location.href = "?" + params.toString();
        });
      });
    });

    // Script para ordenar por precio
    document.addEventListener("DOMContentLoaded", () => {
      const sortBtn = document.getElementById("sort-btn");
      const productosContainer = document.getElementById("productos-container");
      let ascending = true;

      sortBtn.addEventListener("click", () => {
        let productos = Array.from(productosContainer.querySelectorAll(".producto-item"));
        productos.sort((a, b) => {
          let priceA = parseFloat(a.dataset.price);
          let priceB = parseFloat(b.dataset.price);
          return ascending ? priceA - priceB : priceB - priceA;
        });
        productosContainer.innerHTML = "";
        productos.forEach(p => productosContainer.appendChild(p));
        ascending = !ascending;
      });
    });
  </script>
  <script src="js/auth.js?v=<?php echo time(); ?>"></script>
  <script src="js/index.js?v=<?php echo time(); ?>"></script>
</body>

</html>