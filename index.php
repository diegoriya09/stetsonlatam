<!DOCTYPE html>
<html lang="en" prefix="schema: http://schema.org/">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stetson Latam</title>
    <meta name="description" content="Stetson LATAM - Legendary Hats for Latin America and Central America">
    <link rel="icon" href="img/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="css/index.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@700&display=swap" rel="stylesheet">
</head>
<body typeof="schema:WebPage">
    <section class="hero-slider">
        <div class="slide active" data-title="THE OPEN ROAD"
            data-text="Since 1937, it’s been the hat of choice for artists,
            outlaws, Presidents, and trailblazers."
            data-link="#featured"
            style="background-image: url('img/slide1.png');"></div>
        <div class="slide" data-title="WESTERN STRAW"
            data-text="Fresh and breezy for summer adventures."
            data-link="#featured"
            style="background-image: url('img/slide2.png');"></div>
        <div class="slide" data-title="LATTE MILAN"
            data-text="Sophisticated city charm meets ranch style."
            data-link="#featured"
            style="background-image: url('img/slide3.png');"></div>

        <!-- Indicadores -->
        <div class="dots">
            <span class="dot active" data-index="0"></span>
            <span class="dot" data-index="1"></span>
            <span class="dot" data-index="2"></span>
        </div>

        <!-- Contenido dinámico (texto + botón) -->
        <div class="hero-content">
            <h1 id="hero-title">THE OPEN ROAD</h1>
            <p id="hero-text">Since 1937, it’s been the hat of choice for artists, <br>
                outlaws, Presidents, and trailblazers.</p>
            <a id="hero-btn" href="#featured" class="cta-btn">SHOP NOW</a>
        </div>
    </section>
    <?php include 'navbar.php'; ?>
    <!-- Sidebar del carrito -->
    <?php include 'cart.php'; ?>
    <!-- Modal para login/registro -->
    <div id="user-modal" class="modal">
        <div class="modal-content form-wrapper">
            <span class="close">&times;</span>
            <!-- Login Form -->
            <div id="login-form" class="form-section">
                <h2>Login</h2>
                <form action="php/login.php" method="POST">
                    <input type="email" name="email" placeholder="Email" required />
                    <input type="password" name="password" placeholder="Password" required />
                    <button type="submit">Enter</button>
                </form>
                <p>Don't have an account? <a href="#" id="switch-to-register">Create account</a></p>
            </div>
            <!-- Register Form (oculto al inicio) -->
            <div id="register-form" class="form-section" style="display: none;">
                <h2>Create Account</h2>
                <form id="registerForm" action="/php/register.php" method="POST">
                    <input type="text" name="name" placeholder="Full name" required />
                    <input type="email" name="email" placeholder="Email" required />
                    <input type="password" name="password" placeholder="Password" required />
                    <button type="submit">Create</button>
                </form>
                <p>Already have an account? <a href="#" id="switch-to-login">Login</a></p>
            </div>
        </div>
    </div>
    <header class="hero-split">
        <div class="hero-left">
            <img src="img/hero.png" alt="Hombre con sombrero" />
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>