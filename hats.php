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
  <link rel="stylesheet" href="css/index.css?v=<?php echo time(); ?>">
</head>
<body>
  <nav class="main-nav">
        <div class="nav-left">
            <a href="#">
                <h2 class="logo">STETSON LATAM</h2>
            </a>
        </div>
        <!-- Enlaces centrados -->
        <ul class="nav-center">
            <li><a href="hats.php">Hats</a></li>
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

</body>
</html>
