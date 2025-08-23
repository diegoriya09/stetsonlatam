<?php
require_once 'php/conexion.php';

session_start();
$user_id = $_SESSION['user_id'] ?? null;

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
//productos más visitados por el usuario
$recomendados = [];

if ($user_id !== null) {
  // Usuario logueado
  $sql = "SELECT p.* 
            FROM productos p
            INNER JOIN user_visits uv ON p.id = uv.product_id
            WHERE uv.user_id = ?    
            GROUP BY p.id
            ORDER BY COUNT(*) DESC
            LIMIT 6";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $user_id);
} else {
  // Usuario no logueado (user_id es NULL)
  $sql = "SELECT p.* 
            FROM productos p
            INNER JOIN user_visits uv ON p.id = uv.product_id
            WHERE uv.user_id IS NULL
            GROUP BY p.id
            ORDER BY COUNT(*) DESC
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
  <link
    rel="stylesheet"
    as="style"
    onload="this.rel='stylesheet'"
    href="https://fonts.googleapis.com/css2?display=swap&amp;family=Noto+Sans%3Awght%40400%3B500%3B700%3B900&amp;family=Work+Sans%3Awght%40400%3B500%3B700%3B900" />

  <title>Hats | Stetson Latam</title>
  <link rel="icon" href="img/logo.webp" type="image/x-icon" loading="lazy">
  <link href="css/index.css?v=<?php echo time(); ?>" rel="stylesheet">

  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
  <div class="relative flex size-full min-h-screen flex-col bg-white group/design-root overflow-x-hidden">
    <div class="layout-container flex h-full grow flex-col">
      <?php include 'header.php'; ?>
      <div class="px-40 flex flex-1 justify-center py-5">
        <div class="layout-content-container flex flex-col max-w-[960px] flex-1">
          <div class="flex flex-wrap gap-2 p-4">
            <a class="text-[#7a7671] text-base font-medium leading-normal" href="index.php">Home</a>
            <span class="text-[#7a7671] text-base font-medium leading-normal">/</span>
            <span class="text-[#151514] text-base font-medium leading-normal">Hats</span>
          </div>
          <div class="flex flex-wrap justify-between gap-3 p-4">
            <p class="text-[#151514] tracking-light text-[32px] font-bold leading-tight min-w-72">Hats</p>
          </div>
          <div class="flex gap-3 p-3 flex-wrap pr-4">
            <!-- Filtro de tallas -->
            <div class="relative">
              <button id="size-filter-btn" type="button" class="flex h-8 items-center justify-center gap-x-2 rounded-lg bg-[#f3f2f2] pl-4 pr-2">
                <p class="text-[#151514] text-sm font-medium leading-normal">Size</p>
                <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                  <path d="M213.66,101.66l-80,80a8,8,0,0,1-11.32,0l-80-80A8,8,0,0,1,53.66,90.34L128,164.69l74.34-74.35a8,8,0,0,1,11.32,11.32Z"></path>
                </svg>
              </button>
              <div id="size-dropdown" class="absolute hidden mt-1 w-40 bg-white shadow rounded-lg p-2 z-10">
                <?php foreach ($tallas as $talla): ?>
                  <label class="flex items-center gap-2 text-sm cursor-pointer">
                    <input
                      type="checkbox"
                      class="size-check"
                      value="<?php echo $talla['id']; ?>"
                      <?php echo in_array($talla['id'], $_GET['sizes'] ?? []) ? 'checked' : ''; ?>>
                    <?php echo htmlspecialchars($talla['name']); ?>
                  </label>
                <?php endforeach; ?>
              </div>
            </div>

            <!-- Filtro de colores -->
            <div class="relative">
              <button id="color-filter-btn" type="button" class="flex h-8 items-center justify-center gap-x-2 rounded-lg bg-[#f3f2f2] pl-4 pr-2">
                <p class="text-[#151514] text-sm font-medium leading-normal">Color</p>
                <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                  <path d="M213.66,101.66l-80,80a8,8,0,0,1-11.32,0l-80-80A8,8,0,0,1,53.66,90.34L128,164.69l74.34-74.35a8,8,0,0,1,11.32,11.32Z"></path>
                </svg>
              </button>
              <div id="color-dropdown" class="absolute hidden mt-1 w-40 bg-white shadow rounded-lg p-2 z-10">
                <?php foreach ($colores as $color): ?>
                  <label class="flex items-center gap-2 text-sm cursor-pointer">
                    <input
                      type="checkbox"
                      class="color-check"
                      value="<?php echo $color['id']; ?>"
                      <?php echo in_array($color['id'], $_GET['colors'] ?? []) ? 'checked' : ''; ?>>
                    <span class="w-4 h-4 rounded-full border" style="background-color: <?php echo htmlspecialchars($color['hex']); ?>"></span>
                    <?php echo htmlspecialchars($color['name']); ?>
                  </label>
                <?php endforeach; ?>
              </div>
            </div>
            <script>
              document.addEventListener("DOMContentLoaded", () => {
                const sizeBtn = document.getElementById("size-filter-btn");
                const sizeDropdown = document.getElementById("size-dropdown");
                const colorBtn = document.getElementById("color-filter-btn");
                const colorDropdown = document.getElementById("color-dropdown");

                // Toggle dropdowns
                sizeBtn.addEventListener("click", () => {
                  sizeDropdown.classList.toggle("hidden");
                });
                colorBtn.addEventListener("click", () => {
                  colorDropdown.classList.toggle("hidden");
                });

                // Manejo de cambios en filtros
                document.querySelectorAll(".size-check, .color-check").forEach(input => {
                  input.addEventListener("change", () => {
                    const selectedSizes = Array.from(document.querySelectorAll(".size-check:checked")).map(cb => cb.value);
                    const selectedColors = Array.from(document.querySelectorAll(".color-check:checked")).map(cb => cb.value);

                    // Construir URL manteniendo filtros seleccionados
                    const params = new URLSearchParams();

                    selectedSizes.forEach(size => params.append("sizes[]", size));
                    selectedColors.forEach(color => params.append("colors[]", color));

                    // Recargar página con filtros
                    window.location.href = "?" + params.toString();
                  });
                });
              });
            </script>
          </div>
          <h2 class="text-[#151514] text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-5">Recommended for You</h2>
          <div class="flex overflow-y-auto [-ms-scrollbar-style:none] [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
            <div class="grid grid-cols-3 p-4 gap-3">
              <?php if (!empty($recomendados)) {
                foreach ($recomendados as $recomendado): ?>
                  <div class="flex h-full flex-1 flex-col gap-4 rounded-lg min-w-40">
                    <a href="producto.php?id=<?php echo $recomendado['id']; ?>" class="flex flex-col gap-3 pb-3 hover:scale-[1.03] transition-transform">
                      <div
                        class="w-full bg-center bg-no-repeat aspect-square bg-cover rounded-lg flex flex-col"
                        style='background-image: url("<?php echo htmlspecialchars($recomendado["image"]); ?>");'></div>
                      <div>
                        <p class="text-[#151514] text-base font-medium leading-normal"><?php echo htmlspecialchars($recomendado["name"]); ?></p>
                        <p class="text-[#7a7671] text-sm font-normal leading-normal">$<?php echo number_format($recomendado["price"], 2); ?></p>
                      </div>
                    </a>
                  </div>
              <?php endforeach;
              } else {
                echo "<p>No products recommended yet.</p>";
              } ?>
            </div>
          </div>
          <div class="flex justify-between gap-2 px-4 py-3">
            <div class="flex gap-2">
              <button id="sort-btn" class="p-2 text-[#151514]">
                <div class="text-[#151514]" data-icon="SortAscending" data-size="24px" data-weight="regular">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" viewBox="0 0 256 256">
                    <path
                      d="M128,128a8,8,0,0,1-8,8H48a8,8,0,0,1,0-16h72A8,8,0,0,1,128,128ZM48,72H184a8,8,0,0,0,0-16H48a8,8,0,0,0,0,16Zm56,112H48a8,8,0,0,0,0,16h56a8,8,0,0,0,0-16Zm125.66-21.66a8,8,0,0,0-11.32,0L192,188.69V112a8,8,0,0,0-16,0v76.69l-26.34-26.35a8,8,0,0,0-11.32,11.32l40,40a8,8,0,0,0,11.32,0l40-40A8,8,0,0,0,229.66,162.34Z"></path>
                  </svg>
                </div>
              </button>
            </div>
          </div>
          <div id="productos-container" class="grid grid-cols-3 gap-3 p-4">
            <?php foreach ($productos as $producto): ?>
              <div class="flex flex-col gap-3 pb-3 producto-item" data-price="<?php echo $producto['price']; ?>">
                <a href="producto.php?id=<?php echo $producto['id']; ?>&from=hats" class="flex flex-col gap-3 pb-3 hover:scale-[1.03] transition-transform">
                  <div
                    class="w-full bg-center bg-no-repeat aspect-square bg-cover rounded-lg flex flex-col"
                    style='background-image: url("<?php echo htmlspecialchars($producto["image"]); ?>");'></div>
                  <div>
                    <p class="text-[#151514] text-base font-medium leading-normal"><?php echo htmlspecialchars($producto["name"]); ?></p>
                    <p class="text-[#7a7671] text-sm font-normal leading-normal">$<?php echo number_format($producto["price"], 2); ?></p>
                  </div>
                </a>
              </div>
            <?php endforeach; ?>
          </div>
          <script>
            document.addEventListener("DOMContentLoaded", () => {
              const sortBtn = document.getElementById("sort-btn");
              const productosContainer = document.getElementById("productos-container");

              let ascending = true; // estado inicial

              sortBtn.addEventListener("click", () => {
                let productos = Array.from(document.querySelectorAll(".producto-item"));

                productos.sort((a, b) => {
                  let priceA = parseFloat(a.dataset.price);
                  let priceB = parseFloat(b.dataset.price);
                  return ascending ? priceA - priceB : priceB - priceA;
                });

                // limpiar contenedor y reinsertar productos ordenados
                productosContainer.innerHTML = "";
                productos.forEach(p => productosContainer.appendChild(p));

                // alternar orden
                ascending = !ascending;
              });
            });
          </script>
        </div>
      </div>
      <?php include 'footer.php'; ?>
    </div>
  </div>
  <?php include 'modal.php'; ?>
  <script src="js/auth.js?=v<? echo time(); ?>"></script>
  <script src="js/index.js?=v<? echo time(); ?>"></script>
</body>

</html>