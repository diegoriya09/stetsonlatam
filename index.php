<?php
require_once 'php/conexion.php';
session_start();

try {
    // 1. OBTENER COLECCIONES DESTACADAS (las que no son "Best Sellers", etc.)
    $sql_collections = "SELECT id, nombre, imagen_banner FROM categorias 
                        WHERE categoria_padre_id IS NULL 
                        AND nombre NOT IN ('Best Sellers', 'New Arrivals', 'Accesorios')
                        LIMIT 4"; // Mostramos hasta 4 colecciones
    $result_collections = $conn->query($sql_collections);
    $collections = $result_collections->fetch_all(MYSQLI_ASSOC);

    // 2. OBTENER PRODUCTOS DESTACADOS (is_featured = 1)
    $sql_featured = "SELECT id, name, description, image, price FROM productos WHERE is_featured = 1 LIMIT 4";
    $result_featured = $conn->query($sql_featured);
    $featured_products = $result_featured->fetch_all(MYSQLI_ASSOC);

} catch (Exception $e) {
    error_log("Error en la página de inicio: " . $e->getMessage());
    $collections = [];
    $featured_products = [];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Stetson LATAM - Original Stetson Hats for All Latin America</title>
    <meta name="description" content="In Stetson LATAM, in alliance with DINALSOM, find legendary hats with shipments to all Latin America. Shop online with confidence." />
    <link rel="icon" href="img/logo.webp" type="image/x-icon">
    <link href="css/index.css?v=<?php echo time(); ?>" rel="stylesheet">
    <link href="css/categoria.css?v=<?php echo time(); ?>" rel="stylesheet"> <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="page-wrapper">
        <div class="content-container">
            <?php include "header.php"; ?>
            
            <main>
                <section class="hero-section">
                    <div class="hero-content">
                        <h1 class="hero-title">Authentic Style, Timeless Legacy</h1>
                        <h2 class="hero-subtitle">Explore our curated collection of premium hats, crafted with the spirit of the American West.</h2>
                        <a href="hats.php" class="hero-button">SHOP NOW</a>
                    </div>
                </section>

                <section class="home-section">
                    <h2 class="section-title">Shop by Category</h2>
                    <div class="collections-grid">
                        <?php foreach ($collections as $collection): ?>
                            <a href="categoria.php?id=<?php echo $collection['id']; ?>" class="collection-card">
                                <img src="<?php echo htmlspecialchars(!empty($collection['imagen_banner']) ? $collection['imagen_banner'] : 'img/default.jpg'); ?>" alt="<?php echo htmlspecialchars($collection['nombre']); ?>">
                                <div class="collection-card-overlay">
                                    <h3><?php echo htmlspecialchars($collection['nombre']); ?></h3>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </section>

                <section class="home-section">
                    <h2 class="section-title">Featured Products</h2>
                    <div class="product-grid">
                        <?php foreach ($featured_products as $producto): ?>
                            <a href="producto.php?id=<?php echo $producto['id']; ?>" class="product-card">
                                <div class="product-card-image" style="background-image: url('<?php echo htmlspecialchars($producto['image']); ?>');"></div>
                                <div class="product-card-info">
                                    <h3 class="product-name"><?php echo htmlspecialchars($producto['name']); ?></h3>
                                    <p class="product-price">$<?php echo number_format($producto['price'], 2); ?></p>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </section>

                <section class="home-section story-section">
                    <div class="story-image"></div>
                    <div class="story-content">
                        <h2 class="section-title">A Legacy of Craftsmanship</h2>
                        <p>For over 160 years, Stetson has been synonymous with quality and authenticity. Our products are crafted with meticulous attention to detail, using the finest materials to ensure lasting style and durability.</p>
                        <a href="aboutUs.php" class="story-button">OUR STORY</a>
                    </div>
                </section>

                <section id="signup-section" class="home-section signup-section">
                    <h2 class="section-title">Join the Stetson Community</h2>
                    <p>Stay up-to-date on the latest news, exclusive offers, and new product releases.</p>
                    <button id="open-user-modal" class="signup-button">SIGN UP</button>
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