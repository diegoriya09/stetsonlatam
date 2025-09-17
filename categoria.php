<?php
// Incluimos la conexión a la base de datos (que ya usa MySQLi)
require 'php/conexion.php';
require_once 'php/minifier.php';
session_start();

// 1. OBTENER Y VALIDAR EL ID DE LA CATEGORÍA
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    exit('Categoría no válida');
}
$categoria_id = $_GET['id'];

// 2. OBTENER LA INFORMACIÓN DE LA CATEGORÍA SELECCIONADA
try {
    $sql_cat = "SELECT nombre, descripcion, imagen_banner FROM categorias WHERE id = ?";
    $stmt_cat = $conn->prepare($sql_cat);
    $stmt_cat->bind_param("i", $categoria_id);
    $stmt_cat->execute();
    $result_cat = $stmt_cat->get_result();
    $categoria = $result_cat->fetch_assoc();

    if (!$categoria) {
        exit('Categoría no encontrada');
    }
} catch (Exception $e) {
    error_log("Error al obtener categoría: " . $e->getMessage());
    exit('Error al cargar la página.');
}

// 3. OBTENER LOS IDs DE LA CATEGORÍA Y SUS DESCENDIENTES
$relevant_ids = [];
try {
    $all_categories_sql = "SELECT id, categoria_padre_id FROM categorias";
    $all_categories_result = $conn->query($all_categories_sql);
    $all_categories = $all_categories_result->fetch_all(MYSQLI_ASSOC);

    function getCategoriaIdsRecursivamente($parentId, $categories)
    {
        $ids = [$parentId];
        foreach ($categories as $category) {
            if ($category['categoria_padre_id'] == $parentId) {
                $ids = array_merge($ids, getCategoriaIdsRecursivamente($category['id'], $categories));
            }
        }
        return $ids;
    }
    $relevant_ids = getCategoriaIdsRecursivamente($categoria_id, $all_categories);
} catch (Exception $e) {
    error_log("Error al obtener árbol de categorías: " . $e->getMessage());
}

// 4. OBTENER FILTROS DISPONIBLES (COLORES Y TALLAS) SÓLO PARA ESTA CATEGORÍA
$colores = [];
$tallas = [];
if (!empty($relevant_ids)) {
    $placeholders = implode(',', array_fill(0, count($relevant_ids), '?'));
    $types = str_repeat('i', count($relevant_ids));

    // Colores disponibles en esta categoría
    $sql_colores = "SELECT DISTINCT c.id, c.name, c.hex 
                    FROM colors c
                    INNER JOIN product_colors pc ON c.id = pc.color_id
                    INNER JOIN producto_categoria pcat ON pc.product_id = pcat.producto_id
                    WHERE pcat.categoria_id IN ($placeholders)";
    $stmt_colores = $conn->prepare($sql_colores);
    $stmt_colores->bind_param($types, ...$relevant_ids);
    $stmt_colores->execute();
    $result_colores = $stmt_colores->get_result();
    while ($row = $result_colores->fetch_assoc()) {
        $colores[] = $row;
    }

    // Tallas disponibles en esta categoría
    $sql_tallas = "SELECT DISTINCT s.id, s.name 
                   FROM sizes s
                   INNER JOIN product_sizes ps ON s.id = ps.size_id
                   INNER JOIN producto_categoria pcat ON ps.product_id = pcat.producto_id
                   WHERE pcat.categoria_id IN ($placeholders)";
    $stmt_tallas = $conn->prepare($sql_tallas);
    $stmt_tallas->bind_param($types, ...$relevant_ids);
    $stmt_tallas->execute();
    $result_tallas = $stmt_tallas->get_result();
    while ($row = $result_tallas->fetch_assoc()) {
        $tallas[] = $row;
    }
}


// 5. CONSTRUIR CONSULTA DE PRODUCTOS CON FILTROS
$productos = [];
try {
    $color_ids = $_GET['colors'] ?? [];
    $talla_ids = $_GET['sizes'] ?? [];

    $sql = "SELECT DISTINCT p.* FROM productos p";
    $joins = ["INNER JOIN producto_categoria pc ON p.id = pc.producto_id"];
    $where = [];
    $params = [];
    $types = '';

    // Filtro principal por categoría (¡muy importante!)
    if (!empty($relevant_ids)) {
        $placeholders = implode(',', array_fill(0, count($relevant_ids), '?'));
        $where[] = "pc.categoria_id IN ($placeholders)";
        $types .= str_repeat('i', count($relevant_ids));
        $params = array_merge($params, $relevant_ids);
    }

    // Filtros adicionales de color
    if (!empty($color_ids)) {
        $joins[] = "INNER JOIN product_colors pcol ON p.id = pcol.product_id";
        $placeholders = implode(',', array_fill(0, count($color_ids), '?'));
        $where[] = "pcol.color_id IN ($placeholders)";
        $types .= str_repeat('i', count($color_ids));
        $params = array_merge($params, $color_ids);
    }

    // Filtros adicionales de talla
    if (!empty($talla_ids)) {
        $joins[] = "INNER JOIN product_sizes ps ON p.id = ps.product_id";
        $placeholders = implode(',', array_fill(0, count($talla_ids), '?'));
        $where[] = "ps.size_id IN ($placeholders)";
        $types .= str_repeat('i', count($talla_ids));
        $params = array_merge($params, $talla_ids);
    }

    $sql .= ' ' . implode(' ', array_unique($joins));
    if (!empty($where)) {
        $sql .= ' WHERE ' . implode(' AND ', $where);
    }
    $sql .= ' ORDER BY p.name';

    $stmt = $conn->prepare($sql);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $productos[] = $row;
    }
} catch (Exception $e) {
    error_log("Error al obtener productos con filtros: " . $e->getMessage());
    $productos = [];
}

$num_productos = count($productos);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo htmlspecialchars($categoria['nombre']); ?> | Stetson LATAM</title>

    <link rel="icon" href="img/logo.webp" type="image/x-icon">
    <link href="css/index.css?v=<?php echo time(); ?>" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400..700;1,400..700&display=swap"
        rel="stylesheet">
    <link href="css/categoria.css?v=<?php echo time(); ?>" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

    <div class="relative flex size-full min-h-screen flex-col bg-white">
        <div class="layout-container flex h-full grow flex-col">

            <?php include 'header.php'; ?>

            <main>
                <section class="category-banner">
                    <div class="category-info">
                        <h1><?php echo htmlspecialchars($categoria['nombre']); ?></h1>
                        <p><?php echo htmlspecialchars($categoria['descripcion']); ?></p>
                    </div>
                    <div class="category-image">
                        <?php
                        // Determinamos qué imagen mostrar: la de la categoría o una por defecto
                        $imagen_a_mostrar = !empty($categoria['imagen_banner']) ? $categoria['imagen_banner'] : 'img/default.jpg';
                        ?>
                        <img src="<?php echo htmlspecialchars($imagen_a_mostrar); ?>"
                            alt="<?php echo htmlspecialchars($categoria['nombre']); ?>">
                    </div>
                </section>

                <section class="product-section">

                    <div class="flex justify-between items-center p-4">
                        <div>
                            <span class="font-bold text-sm text-[#3c3737] uppercase">ITEMS (<?php echo count($productos); ?>)</span>
                        </div>

                        <div class="flex gap-4">
                            <div class="relative">
                                <button id="size-filter-btn" type="button" class="flex items-center gap-x-1 text-sm font-semibold text-[#3c3737]">
                                    <span>TALLA</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16px" height="16px" fill="currentColor" viewBox="0 0 256 256">
                                        <path d="M213.66,101.66l-80,80a8,8,0,0,1-11.32,0l-80-80A8,8,0,0,1,53.66,90.34L128,164.69l74.34-74.35a8,8,0,0,1,11.32,11.32Z"></path>
                                    </svg>
                                </button>
                                <div id="size-dropdown" class="absolute hidden mt-2 w-40 bg-white shadow-lg rounded-lg p-2 z-10 border border-gray-200">

                                    <?php foreach ($tallas as $talla): ?>
                                        <label class="flex items-center gap-2 p-1 text-sm cursor-pointer hover:bg-gray-100 rounded">
                                            <input type="checkbox" class="size-check" value="<?php echo $talla['id']; ?>" <?php echo in_array($talla['id'], $_GET['sizes'] ?? []) ? 'checked' : ''; ?>>
                                            <?php echo htmlspecialchars($talla['name']); ?>
                                        </label>
                                    <?php endforeach; ?>

                                </div>
                            </div>

                            <div class="relative">
                                <button id="color-filter-btn" type="button" class="flex items-center gap-x-1 text-sm font-semibold text-[#3c3737]">
                                    <span>COLOR</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16px" height="16px" fill="currentColor" viewBox="0 0 256 256">
                                        <path d="M213.66,101.66l-80,80a8,8,0,0,1-11.32,0l-80-80A8,8,0,0,1,53.66,90.34L128,164.69l74.34-74.35a8,8,0,0,1,11.32,11.32Z"></path>
                                    </svg>
                                </button>
                                <div id="color-dropdown" class="absolute hidden mt-2 w-48 bg-white shadow-lg rounded-lg p-2 z-10 border border-gray-200">

                                    <?php foreach ($colores as $color): ?>
                                        <label class="flex items-center gap-2 p-1 text-sm cursor-pointer hover:bg-gray-100 rounded">
                                            <input type="checkbox" class="color-check" value="<?php echo $color['id']; ?>" <?php echo in_array($color['id'], $_GET['colors'] ?? []) ? 'checked' : ''; ?>>
                                            <span class="w-4 h-4 rounded-full border" style="background-color: <?php echo htmlspecialchars($color['hex']); ?>"></span>
                                            <?php echo htmlspecialchars($color['name']); ?>
                                        </label>
                                    <?php endforeach; ?>

                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <button id="sort-btn" class="flex items-center gap-x-1 text-sm font-semibold text-[#3c3737]">
                                <span>ORDENAR POR PRECIO</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16px" height="16px" fill="currentColor" viewBox="0 0 256 256">
                                    <path d="M213.66,101.66l-80,80a8,8,0,0,1-11.32,0l-80-80A8,8,0,0,1,53.66,90.34L128,164.69l74.34-74.35a8,8,0,0,1,11.32,11.32Z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div id="productos-container" class="product-grid">
                        <?php if (!empty($productos)): ?>
                            <?php foreach ($productos as $producto): ?>
                                <a href="producto<?php echo $producto['id']; ?>" class="product-card producto-item"
                                    data-price="<?php echo $producto['price']; ?>">
                                    <div class="product-card-image"
                                        style="background-image: url('<?php echo htmlspecialchars($producto['image']); ?>');">
                                    </div>
                                    <div class="product-card-info">
                                        <h3 class="product-name"><?php echo htmlspecialchars($producto['name']); ?></h3>
                                        <p class="product-price">$<?php echo number_format($producto['price'], 2); ?></p>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="no-products">No se encontraron productos en esta colección que coincidan con tus criterios.</p>
                        <?php endif; ?>
                    </div>
                </section>
            </main>

            <?php include 'footer.php'; ?>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const sizeBtn = document.getElementById("size-filter-btn");
            const sizeDropdown = document.getElementById("size-dropdown");
            const colorBtn = document.getElementById("color-filter-btn");
            const colorDropdown = document.getElementById("color-dropdown");

            if (sizeBtn) sizeBtn.addEventListener("click", () => sizeDropdown.classList.toggle("hidden"));
            if (colorBtn) colorBtn.addEventListener("click", () => colorDropdown.classList.toggle("hidden"));

            document.querySelectorAll(".size-check, .color-check").forEach(input => {
                input.addEventListener("change", () => {
                    const params = new URLSearchParams();
                    document.querySelectorAll(".size-check:checked").forEach(cb => params.append("sizes[]", cb.value));
                    document.querySelectorAll(".color-check:checked").forEach(cb => params.append("colors[]", cb.value));
                    window.location.href = "?" + params.toString();
                });
            });

            const sortBtn = document.getElementById("sort-btn");
            const productosContainer = document.getElementById("productos-container");
            let ascending = true;

            if (sortBtn) {
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
            }
        });
    </script>
    <?php include 'modal.php'; ?>

</body>

</html>