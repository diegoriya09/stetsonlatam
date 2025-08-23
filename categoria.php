<?php
// Incluimos la conexión a la base de datos (que ya usa MySQLi)
require 'php/conexion.php';

// 1. OBTENER Y VALIDAR EL ID DE LA CATEGORÍA
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    exit('Invalid category');
}
$categoria_id = $_GET['id'];

// 2. OBTENER LA INFORMACIÓN DE LA CATEGORÍA SELECCIONADA
try {
    $sql_cat = "SELECT nombre, descripcion FROM categorias WHERE id = ?";
    $stmt_cat = $conn->prepare($sql_cat);
    // En MySQLi, los parámetros se "atan" con bind_param
    $stmt_cat->bind_param("i", $categoria_id);
    $stmt_cat->execute();
    $result_cat = $stmt_cat->get_result();
    $categoria = $result_cat->fetch_assoc();

    if (!$categoria) {
        exit('Category not found');
    }

} catch (Exception $e) {
    error_log("Error obtaining category: " . $e->getMessage());
    exit('Error loading page.');
}

// 3. OBTENER LOS PRODUCTOS DE LA CATEGORÍA Y SUS SUBCATEGORÍAS
// Como MySQLi no maneja consultas recursivas tan fácilmente, lo haremos con PHP.
$productos = [];
try {
    // Primero, traemos TODAS las categorías para construir el árbol en PHP
    $all_categories_sql = "SELECT id, categoria_padre_id FROM categorias";
    $all_categories_result = $conn->query($all_categories_sql);
    $all_categories = $all_categories_result->fetch_all(MYSQLI_ASSOC);

    // Función recursiva para encontrar todos los IDs descendientes
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

    // Obtenemos el array con el ID principal y todos sus hijos
    $relevant_ids = getCategoriaIdsRecursivamente($categoria_id, $all_categories);

    if (!empty($relevant_ids)) {
        // Creamos los placeholders (?) para la consulta IN (...)
        $placeholders = implode(',', array_fill(0, count($relevant_ids), '?'));
        // Creamos la cadena de tipos ("i" por cada ID)
        $types = str_repeat('i', count($relevant_ids));

        $sql_prod = "
            SELECT p.id, p.name, p.description, p.image, p.price 
            FROM productos p
            JOIN producto_categoria pc ON p.id = pc.producto_id
            WHERE pc.categoria_id IN ($placeholders)
            GROUP BY p.id
            ORDER BY p.nombre;
        ";

        $stmt_prod = $conn->prepare($sql_prod);
        // Usamos el "splat operator" (...) para pasar los IDs como argumentos
        $stmt_prod->bind_param($types, ...$relevant_ids);
        $stmt_prod->execute();
        $result_prod = $stmt_prod->get_result();
        $productos = $result_prod->fetch_all(MYSQLI_ASSOC);
    }

} catch (Exception $e) {
    error_log("Error retrieving products: " . $e->getMessage());
    $productos = [];
}
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
                        <img src="img/default-banner.jpg" alt="<?php echo htmlspecialchars($categoria['nombre']); ?>">
                    </div>
                </section>

                <section class="product-section">
                    <div class="product-grid">
                        <?php if (!empty($productos)): ?>
                            <?php foreach ($productos as $producto): ?>
                                <a href="producto.php?id=<?php echo $producto['id']; ?>" class="product-card">
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
                            <p class="no-products">No products were found in this collection..</p>
                        <?php endif; ?>
                    </div>
                </section>
            </main>

            <?php include 'footer.php'; ?>
        </div>
    </div>
    <?php include 'modal.php'; ?>
    <script src="js/index.js?v=<?php echo time(); ?>"></script>
    <script src="js/auth.js?v=<?php echo time(); ?>"></script>
</body>

</html>