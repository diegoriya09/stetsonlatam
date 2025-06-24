<?php
require_once 'php/conexion.php';

$sql = "SELECT * FROM productos WHERE category = 'hats'";
$result = $conn->query($sql);

$productos = [];
if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $productos[] = $row;
  }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Hats | Stetson Latam</title>
  <meta name="description" content="Stetson LATAM - Legendary Hats for Latin America and Central America">
  <link rel="icon" href="img/logo.png" type="image/x-icon">
  <link rel="stylesheet" href="css/index.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Lora:wght@700&display=swap" rel="stylesheet">
</head>
<body>
  <nav class="main-nav">
        <div class="nav-left">
            <a href="index.php">
                <h2 class="logo">STETSON LATAM</h2>
            </a>
        </div>
        <!-- Enlaces centrados -->
        <ul class="nav-center">
            <li><a href="hats.php">Hats</a></li>
            <li><a href="index.php#stories">Stories</a></li>
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

  <section class="section hats">
    <h2>Stetson Hats</h2>
    <div class="sort-container">
      <label for="sort-select" class="sort-label">Sort</label>
      <select id="sort-select" class="sort-select">
        <option value="name-asc">Name, A–Z</option>
        <option value="name-desc">Name, Z–A</option>
        <option value="price-asc">Price Low to High</option>
        <option value="price-desc">Price High to Low</option>
      </select>
    </div>
    <div class="card-grid">
      <?php if (count($productos) > 0): ?>
        <?php foreach ($productos as $producto): ?>
          <article class="card-item"
          data-name="<?= htmlspecialchars($producto['name']) ?>"
          data-price="<?= $producto['price'] ?>">
            <img src="<?= htmlspecialchars($producto['image']) ?>" alt="<?= htmlspecialchars($producto['name']) ?>">
            <h3><?= htmlspecialchars($producto['name']) ?></h3>
            <p>$<?= number_format($producto['price'], 0, ',', '.') ?>.00</p>
            <button class="add-to-cart-btn"
              data-id="<?= $producto['id'] ?>"
              data-name="<?= htmlspecialchars($producto['name']) ?>"
              data-price="<?= $producto['price'] ?>"
              data-image="<?= htmlspecialchars($producto['image']) ?>">
              <i class="fas fa-cart-plus"></i>
            </button>
          </article>
        <?php endforeach; ?>
      <?php else: ?>
        <p style="color:#3c3737;">There are no hats available at this time.</p>
      <?php endif; ?>
    </div>
  </section>
  <!-- Sidebar del carrito -->
    <div id="carrito-sidebar" class="sidebar">
        <div class="sidebar-header">
            <h3>My cart</h3>
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
    <script src="js/index.js?v=<?php echo time(); ?>"></script>
    <script src="js/auth.js?v=<?php echo time(); ?>"></script>
    <script src="js/cart.js?v=<?php echo time(); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
