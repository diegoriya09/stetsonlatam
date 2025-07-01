<?php
require_once 'php/conexion.php';

// Leer los IDs desde localStorage usando JS (ver más abajo) o como fallback desde una cookie
$wishlist = isset($_GET['ids']) ? explode(',', $_GET['ids']) : [];
$productos = [];

if (!empty($wishlist)) {
  $placeholders = implode(',', array_fill(0, count($wishlist), '?'));
  $stmt = $conn->prepare("SELECT * FROM productos WHERE id IN ($placeholders)");
  $stmt->bind_param(str_repeat('i', count($wishlist)), ...$wishlist);
  $stmt->execute();
  $result = $stmt->get_result();

  while ($row = $result->fetch_assoc()) {
    $productos[] = $row;
  }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Wishlist - Stetson Latam</title>
  <link rel="stylesheet" href="css/index.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="css/wishlist.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
  <?php include 'navbar.php'; ?>

  <section class="section">
    <h2>Your Wishlist</h2>
    <div class="card-grid">
      <?php if (!empty($productos)): ?>
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
        <p style="color:#3c3737">Your wishlist is empty.</p>
      <?php endif; ?>
    </div>
  </section>

  <?php include 'footer.php'; ?>

  <script>
    // Redirige con los IDs almacenados en localStorage
    const wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
    if (wishlist.length > 0 && !window.location.search.includes('ids=')) {
      window.location.href = `wishlist.php?ids=${wishlist.join(',')}`;
    }
  </script>
</body>
</html>
