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
  <title>Stetson Hats</title>
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
            <li><a href="boots.php">Boots</a></li>
            <li><a href="mens.php">Mens</a></li>
            <li><a href="womens.php">Womens</a></li>
            <li><a href="accessories.php">Accessories</a></li>
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

  <section class="section hats">
    <h2>Stetson Hats</h2>
    <div class="card-grid">
      <?php if (count($productos) > 0): ?>
        <?php foreach ($productos as $producto): ?>
          <article class="card-item">
            <img src="<?= htmlspecialchars($producto['image']) ?>" alt="<?= htmlspecialchars($producto['name']) ?>">
            <h3><?= htmlspecialchars($producto['name']) ?></h3>
            <p>$<?= number_format($producto['price'], 0, ',', '.') ?></p>
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
        <p style="color:red;">There are no hats available at this time.</p>
      <?php endif; ?>
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
    <script src="js/index.js?v=<?php echo time(); ?>"></script>
    <script src="js/auth.js?v=<?php echo time(); ?>"></script>
    <script src="js/cart.js?v=<?php echo time(); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
