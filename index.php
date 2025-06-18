<!DOCTYPE html>
<html lang="en" prefix="schema: http://schema.org/">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stetson LATAM</title>
    <meta name="description" content="Stetson LATAM - Legendary Hats for Latin America and Central America">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/carrousel.css">
</head>

<body typeof="schema:WebPage">
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
                <div id="cart-count" class="cart-notification">0</div>
            </div>
        </div>
    </nav>
    <div class="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active" style="background-image: url('img/slide1.png');">
                <div class="carousel-caption">
                    <h2>Producto 1</h2>
                    <p>Descripción del producto 1</p>
                    <a href="enlace1.html" class="buy-btn">Comprar ahora</a>
                </div>
            </div>
            <div class="carousel-item" style="background-image: url('img/slide2.png');">
                <div class="carousel-caption">
                    <h2>Producto 2</h2>
                    <p>Descripción del producto 2</p>
                    <a href="enlace2.html" class="buy-btn">Comprar ahora</a>
                </div>
            </div>
            <div class="carousel-item" style="background-image: url('img/slide3.png');">
                <div class="carousel-caption">
                    <h2>Producto 3</h2>
                    <p>Descripción del producto 3</p>
                    <a href="enlace3.html" class="buy-btn">Comprar ahora</a>
                </div>
            </div>
        </div>
        <button class="carousel-btn prev">&#10094;</button>
        <button class="carousel-btn next">&#10095;</button>
        <div class="carousel-dots">
            <span class="dot active" data-slide="0"></span>
            <span class="dot" data-slide="1"></span>
            <span class="dot" data-slide="2"></span>
        </div>
    </div>

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
    <header class="hero" style="background-image: url('img/andes-background.jpg');">
        <div class="overlay">
            <h1 property="schema:headline">Legendary Hats</h1>
            <p property="schema:description">Crafted for Generations – Now in Latin America</p>
            <a href="#shop" class="cta">Shop Now</a>
        </div>
    </header>
    <section id="categories" class="section categories" typeof="schema:OfferCatalog">
        <h2 property="schema:name">Shop by Category</h2>
        <div class="card-grid">
            <article class="card-item" typeof="schema:Offer">
                <img src="img/hats.jpg" alt="Hats">
                <h3 property="schema:itemOffered">Hats</h3>
            </article>
            <article class="card-item" typeof="schema:Offer">
                <img src="img/caps.jpg" alt="Caps">
                <h3 property="schema:itemOffered">Caps</h3>
            </article>
            <article class="card-item" typeof="schema:Offer">
                <img src="img/accessories.jpg" alt="Accessories">
                <h3 property="schema:itemOffered">Accessories</h3>
            </article>
        </div>
    </section>
    <section id="featured" class="section featured" typeof="schema:ItemList">
        <h2 property="schema:name">Featured Hats</h2>
        <div class="card-grid">
            <?php
        include 'php/productos.php';
        if (count($productos) === 0) {
            echo "<p style='color:red;'>✘ No hay productos disponibles.</p>";
        } else {
            foreach ($productos as $producto) {
                echo '<article class="card-item" typeof="schema:Product">';
                echo '<img src="' . htmlspecialchars($producto['image']) . '" alt="' . htmlspecialchars($producto['name']) . '">';
                echo '<h3 property="schema:name">' . htmlspecialchars($producto['name']) . '</h3>';
                echo '<p>$' . number_format($producto['price'], 0, ',', '.') . '</p>';
                echo '<button class="add-to-cart-btn" 
                            data-id="' . $producto['id'] . '" 
                            data-name="' . htmlspecialchars($producto['name']) . '" 
                            data-price="' . $producto['price'] . '" 
                            data-image="' . htmlspecialchars($producto['image']) . '">
                        🛒 Agregar al carrito
                      </button>';
                echo '</article>';
            }
        }
        ?>
        </div>
    </section>

    <!-- Historias de la marca -->
    <section id="stories" class="section stories">
        <h2>Our Legacy</h2>
        <div class="card-grid">
            <article class="card-item">
                <img src="img/story1.jpg" alt="Origen Stetson">
                <h3>Herencia Vaquera</h3>
                <p>Desde el oeste americano hasta Latinoamérica, descubre la historia detrás del sombrero legendario.
                </p>
            </article>
            <article class="card-item">
                <img src="img/story2.jpg" alt="Fabricación artesanal">
                <h3>Artesanía Centenaria</h3>
                <p>Sombreros hechos a mano con técnicas que han perdurado por generaciones.</p>
            </article>
        </div>
    </section>
    <footer>
        <p>&copy; 2025 Stetson LATAM. All rights reserved.</p>
    </footer>
    <script src="js/index.js"></script>
    <script src="js/auth.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>