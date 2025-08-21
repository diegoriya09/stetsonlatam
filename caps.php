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
            LIMIT 5";
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
            LIMIT 5";
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

  <title>Caps | Stetson Latam</title>
  <link rel="icon" href="img/logo.webp" type="image/x-icon" loading="lazy">

  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
  <div class="relative flex size-full min-h-screen flex-col bg-white group/design-root overflow-x-hidden" style='font-family: "Work Sans", "Noto Sans", sans-serif;'>
    <div class="layout-container flex h-full grow flex-col">
      <header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-b-[#f3f2f2] px-10 py-3">
        <div class="flex items-center gap-8">
          <div class="flex items-center gap-4 text-[#151514]">
            <div class="size-4">
              <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M24 4H42V17.3333V30.6667H24V44H6V30.6667V17.3333H24V4Z" fill="currentColor"></path>
              </svg>
            </div>
            <h2 class="text-[#181411] text-lg font-bold leading-tight tracking-[-0.015em]"><a href="index.php">Stetson Latam</a></h2>
          </div>
          <div class="flex items-center gap-9">
            <a class="text-[#151514] text-sm font-medium leading-normal" href="hats.php">Hats</a>
            <a class="text-[#151514] text-sm font-medium leading-normal" href="caps.php">Caps</a>
          </div>
        </div>
        <div class="flex flex-1 justify-end gap-8">
          <label class="flex flex-col min-w-40 !h-10 max-w-64">
            <div class="flex w-full flex-1 items-stretch rounded-lg h-full">
              <div
                class="text-[#7a7671] flex border-none bg-[#f3f2f2] items-center justify-center pl-4 rounded-l-lg border-r-0"
                data-icon="MagnifyingGlass"
                data-size="24px"
                data-weight="regular">
                <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" viewBox="0 0 256 256">
                  <path
                    d="M229.66,218.34l-50.07-50.06a88.11,88.11,0,1,0-11.31,11.31l50.06,50.07a8,8,0,0,0,11.32-11.32ZM40,112a72,72,0,1,1,72,72A72.08,72.08,0,0,1,40,112Z"></path>
                </svg>
              </div>
              <input
                placeholder="Search"
                class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-[#151514] focus:outline-0 focus:ring-0 border-none bg-[#f3f2f2] focus:border-none h-full placeholder:text-[#7a7671] px-4 rounded-l-none border-l-0 pl-2 text-base font-normal leading-normal"
                value="" />
            </div>
          </label>
          <div class="flex gap-2">
            <button
              id="logout-btn"
              style="display:none;"
              class="flex max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 bg-[#f4f2f0] text-[#181411] gap-2 text-sm font-bold leading-normal tracking-[0.015em] min-w-0 px-2.5">
              <div class="text-[#181411]" data-icon="SignOut" data-size="20px" data-weight="regular">
                <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                  <path d="M216,128a8,8,0,0,1-8,8H104v16a8,8,0,0,1-13.66,5.66l-32-32a8,8,0,0,1,0-11.32l32-32A8,8,0,0,1,104,104v16h104A8,8,0,0,1,216,128ZM128,24A104,104,0,1,0,232,128,104.11,104.11,0,0,0,128,24Zm0,192a88,88,0,1,1,88-88A88.1,88.1,0,0,1,128,216Z"></path>
                </svg>
              </div>
            </button>
            <button
              id="user-btn"
              class="flex max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 bg-[#f3f2f2] text-[#151514] gap-2 text-sm font-bold leading-normal tracking-[0.015em] min-w-0 px-2.5">
              <div class="text-[#151514]" data-icon="User" data-size="20px" data-weight="regular">
                <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                  <path
                    d="M230.92,212c-15.23-26.33-38.7-45.21-66.09-54.16a72,72,0,1,0-73.66,0C63.78,166.78,40.31,185.66,25.08,212a8,8,0,1,0,13.85,8c18.84-32.56,52.14-52,89.07-52s70.23,19.44,89.07,52a8,8,0,1,0,13.85-8ZM72,96a56,56,0,1,1,56,56A56.06,56.06,0,0,1,72,96Z"></path>
                </svg>
              </div>
            </button>
            <button
              id="cart-btn"
              class="flex max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 bg-[#f3f2f2] text-[#151514] gap-2 text-sm font-bold leading-normal tracking-[0.015em] min-w-0 px-2.5">
              <div class="text-[#151514]" data-icon="ShoppingBag" data-size="20px" data-weight="regular">
                <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                  <path
                    d="M216,40H40A16,16,0,0,0,24,56V200a16,16,0,0,0,16,16H216a16,16,0,0,0,16-16V56A16,16,0,0,0,216,40Zm0,160H40V56H216V200ZM176,88a48,48,0,0,1-96,0,8,8,0,0,1,16,0,32,32,0,0,0,64,0,8,8,0,0,1,16,0Z"></path>
                </svg>
              </div>
            </button>
            <div
              class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10"
              style='background-image: url("img/logo.webp");'></div>
          </div>
        </div>
      </header>
      <div class="px-40 flex flex-1 justify-center py-5">
        <div class="layout-content-container flex flex-col max-w-[960px] flex-1">
          <div class="flex flex-wrap gap-2 p-4">
            <a class="text-[#7a7671] text-base font-medium leading-normal" href="index.php">Home</a>
            <span class="text-[#7a7671] text-base font-medium leading-normal">/</span>
            <span class="text-[#151514] text-base font-medium leading-normal">Caps</span>
          </div>
          <div class="flex flex-wrap justify-between gap-3 p-4">
            <p class="text-[#151514] tracking-light text-[32px] font-bold leading-tight min-w-72">Caps</p>
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
                echo "<p>No hay productos recomendados aún.</p>";
              } ?>
            </div>
          </div>

          <div class="flex justify-between gap-2 px-4 py-3">
            <div class="flex gap-2">
              <button class="p-2 text-[#151514]" id="sort-btn">
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
                <a href="producto.php?id=<?php echo $producto['id']; ?>&from=caps" class="flex flex-col gap-3 pb-3 hover:scale-[1.03] transition-transform">
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
      <?php include "footer.php"; ?>
    </div>
  </div>
  <?php include "modal.php" ?>
  <script src="js/auth.js?v=<? echo time(); ?>"></script>
  <script src="js/index.js?v=<? echo time(); ?>"></script>
</body>

</html>