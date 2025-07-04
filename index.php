<!DOCTYPE html>
<html lang="en" prefix="schema: http://schema.org/">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stetson LATAM - Original Stetson Hats for All Latin America</title>
    <meta name="description" content="In Stetson LATAM, in alliance with DINALSOM, find legendary hats with shipments to all Latin America. Shop online with confidence.">
    <link rel="icon" href="img/logo.webp" type="image/x-icon" loading="lazy">
    <link rel="stylesheet" href="css/index.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@700&display=swap" rel="stylesheet">
</head>
<body typeof="schema:WebPage">
    <section class="hero-slider">
        <!-- Columna izquierda: Texto -->
        <div class="hero-left">
            <div class="hero-content">
            <h1 id="hero-title">WESTERN CLASSICS</h1>
            <p id="hero-text">
                Discover the best straw hats in the west, from icons to 
                all-new styles, each crafted in the U.S.A. for legendary 
                quality and resilience.
            </p>
            <a id="hero-btn" href="#featured" class="cta-btn">SHOP NOW</a>
            </div>
        </div>
        <!-- Columna derecha: Slides de imagen -->
        <div class="hero-right">
            <div class="slide active"
            data-title="WESTERN CLASSICS"
            data-text="Discover the best straw hats in the west, from icons to all-new styles, each crafted in the U.S.A. for legendary quality and resilience."
            data-link="#featured"
            style="background-image: url('img/slide1.webp') loading='lazy';"></div>
            <div class="slide"
            data-title="OUTDOOR STRAW HATS"
            data-text="Designed for adventure, our handwoven outdoor straw hats bring breathable protection and style to the wild."
            data-link="#featured"
            style="background-image: url('img/slide2.webp') loading='lazy';"></div>
            <div class="slide"
            data-title="MEN'S APPAREL"
            data-text="Timeless, western-inspired outerwear, range-ready denim and new short-sleeve snap-fronts for spring and beyond."
            data-link="#featured"
            style="background-image: url('img/slide3.webp') loading='lazy';"></div>
            <!-- Indicadores -->
            <div class="dots">
            <span class="dot active" data-index="0"></span>
            <span class="dot" data-index="1"></span>
            <span class="dot" data-index="2"></span>
            </div>
        </div>
    </section>
    <?php include 'navbar.php'; ?>
    <!-- Sidebar del carrito -->
    <?php include 'cart.php'; ?>
    <?php include 'checkout.php'; ?>
    <!-- Modal para login/registro -->
    <?php include 'modal.php'; ?>
    <header class="hero-split">
        <div class="hero-left">
            <img src="img/hero.webp" alt="Hombre con sombrero" loading="lazy"/>
            <div class="hero-right">
            <div class="hero-text">
            <h1>THE OPEN ROAD</h1>
            <p>
                Since 1937, it’s been the hat of choice for artists, outlaws,<br />
                Presidents, and trailblazers of all kinds.
            </p>
            <a href="#featured" class="shop-now-btn">SHOP NOW</a>
            </div>
        </div>
        </div> 
    </header>
    <?php include 'featuredHats.php'; ?>
    
    <!-- Historias de la marca -->
    <?php include 'stories.php'; ?>

    <?php include 'footer.php'; ?>

    <script src="js/index.js?v=<?php echo time(); ?>"></script>
    <script src="js/auth.js?v=<?php echo time(); ?>"></script>
    <script src="js/cart.js?v=<?php echo time(); ?>"></script>
    <script src="js/wishlist.js?v=<?php echo time(); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>