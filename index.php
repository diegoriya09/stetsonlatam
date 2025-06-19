<!DOCTYPE html>
<html lang="en" prefix="schema: http://schema.org/">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stetson LATAM</title>
    <meta name="description" content="Stetson LATAM - Legendary Hats for Latin America and Central America">
    <link rel="stylesheet" href="css/index.css?v=8">
    <link rel="stylesheet" href="css/carrousel.css?v=23">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body typeof="schema:WebPage">
    <header class="hero-inverted">
        <div class="hero-text-side">
            <div class="hero-content">
            <h1>LEGENDARY HATS</h1>
            <p>Tradición, estilo y carácter. Ahora en América Latina.</p>
            <a href="#featured" class="shop-now-btn">EXPLORAR</a>
            </div>
        </div>
        <div class="hero-image-side">
            <img src="img/hero.png" alt="Stetson Modelo" />
        </div>
    </header>
    <nav class="main-nav">
        <!-- Puedes agregar el logo aquí si deseas -->
        <div class="nav-left">
            <a href="#">
                <img src="/img/logo.png" alt="Stetson Logo" class="logo" />
            </a>
        </div>
        <!-- Enlaces centrados -->
        <ul class="nav-center">
            <li><a href="#hats">Hats</a></li>
            <li><a href="#boots">Boots</a></li>
            <li><a href="#mens">Mens</a></li>
            <li><a href="#womens">Womens</a></li>
            <li><a href="#accessories">Accessories</a></li>
            <li><a href="#stories">Stories</a></li>
        </ul>
        <!-- Íconos a la derecha -->
        <div class="nav-right">
            <a href="#" id="open-user-modal"><img src="/img/user.png" alt="User" class="icon" /></a>
            <a id="logout-btn" style="display: none;"><img src="/img/logout.png" alt="Logout" class="icon"></a>
            <a href="#"><img src="/img/search.png" alt="Search" class="icon" /></a>
            <div class="cart-wrapper">
                <a id="btn-carrito"><img src="/img/cart.png" alt="Cart" class="icon" /></a>
            </div>
        </div>
    </nav>
    <!-- Sidebar del carrito -->
    <div id="carrito-sidebar" class="sidebar">
        <div class="sidebar-header">
            <h3>Tu Carrito</h3>
            <span class="close-sidebar" id="cerrar-carrito">&times;</span>
        </div>
        <div class="sidebar-content" id="carrito-items">
            <!-- Aquí se mostrarán los productos del carrito dinámicamente -->
        </div>
        <div class="sidebar-footer">
            <p id="total-carrito">Total: $0</p>
            <button class="pagar-btn">Pagar</button>
        </div>
    </div>
    <!-- Modal para login/registro -->
    <div id="user-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <!-- Login Form -->
            <div id="login-form" class="form-section">
                <form action="php/login.php" method="POST">
                    <input type="email" name="email" placeholder="Correo electrónico" required />
                    <input type="password" name="password" placeholder="Contraseña" required />
                    <button type="submit">Entrar</button>
                </form>
                <p>¿No tienes cuenta? <a href="#" id="switch-to-register">Regístrate</a></p>
            </div>
            <!-- Register Form (oculto al inicio) -->
            <div id="register-form" class="form-section" style="display: none;">
                <form id="registerForm" action="/php/register.php" method="POST">
                    <input type="text" name="name" placeholder="Nombre completo" required />
                    <input type="email" name="email" placeholder="Correo electrónico" required />
                    <input type="password" name="password" placeholder="Contraseña" required />
                    <button type="submit">Registrarse</button>
                </form>
                <p>¿Ya tienes cuenta? <a href="#" id="switch-to-login">Inicia sesión</a></p>
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
    <section id="featured" class="section featured" typeof="schema:ItemList">
        <h2 property="schema:name">Featured Hats</h2>
        <div class="card-grid">
            <?php 
            include 'productos.php'; 
            if (count($productos) > 0):
                foreach ($productos as $producto): ?>
                    <article class="card-item">
                        <img src="<?= htmlspecialchars($producto['image']) ?>" alt="<?= htmlspecialchars($producto['name']) ?>">
                        <h3><?= htmlspecialchars($producto['name']) ?></h3>
                        <p>$<?= number_format($producto['price'], 0, ',', '.') ?></p>
                        <a class="add-to-cart"><i class="fas fa-cart-plus"></i></a>
                    </article>
            <?php 
                endforeach;
            else:
                echo "<p style='color:red;'>No hay productos disponibles.</p>";
            endif;
            ?>
        </div>
    </section>
    <!-- Historias de la marca -->
    <section id="stories" class="section stories">
        <h2>Our Legacy</h2>
        <div class="card-grid">
            <article class="card-item">
                <img src="img/story1.png" alt="Origen Stetson">
                <h3>Herencia Vaquera</h3>
                <p>Desde el oeste americano hasta Latinoamérica, descubre la historia detrás del sombrero legendario.
                </p>
            </article>
            <article class="card-item">
                <img src="img/story2.png" alt="Fabricación artesanal">
                <h3>Artesanía Centenaria</h3>
                <p>Sombreros hechos a mano con técnicas que han perdurado por generaciones.</p>
            </article>
        </div>
    </section>
    <footer class="site-footer">
        <div class="footer-columns">
            <div class="footer-column">
            <h4>CUSTOMER SERVICE</h4>
            <ul>
                <li><a href="#">Contact Us</a></li>
                <li><a href="#">Shipping</a></li>
                <li><a href="#">Returns & Exchanges</a></li>
                <li><a href="#">Order Status</a></li>
                <li><a href="#">Fit Guide</a></li>
                <li><a href="#">Gift Cards</a></li>
                <li><a href="#">FAQ</a></li>
            </ul>
            </div>
            <div class="footer-column">
            <h4>OUR COMPANY</h4>
            <ul>
                <li><a href="#">About Us</a></li>
                <li><a href="#">Philanthropy</a></li>
                <li><a href="#">Wholesale</a></li>
            </ul>
            </div>
            <div class="footer-column">
            <h4>STORES</h4>
            <ul>
                <li><a href="#">Store Locator</a></li>
                <li><a href="#">Stetson Japan</a></li>
                <li><a href="#">Stetson Europe</a></li>
                <li><a href="#">Stetson Australia</a></li>
            </ul>
            </div>
            <div class="footer-column">
            <h4>CONNECT</h4>
            <ul>
                <li><a href="#">Instagram</a></li>
                <li><a href="#">Facebook</a></li>
                <li><a href="#">YouTube</a></li>
                <li><a href="#">Pinterest</a></li>
            </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; 2025 Stetson Latam. All Rights Reserved.</p>
            <div class="footer-links">
            <a href="#">Terms & Conditions</a> |
            <a href="#">Privacy Policy</a> |
            <a href="#">California Privacy Notice</a> |
            <a href="#">Accessibility Mode</a> |
            <a href="#">Do Not Sell Or Share My Personal Information</a>
            </div>
        </div>
    </footer>

    </footer>
    <script src="js/index.js"></script>
    <script src="js/auth.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>