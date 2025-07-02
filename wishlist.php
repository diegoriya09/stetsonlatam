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
  <link rel="icon" href="img/logo.png" type="image/x-icon">
  <link rel="stylesheet" href="css/index.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="css/wishlist.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
  <?php include 'navbar.php'; ?>

  <?php include 'modal.php'; ?>
  <?php include 'cart.php'; ?>
  <?php include 'checkout.php'; ?>

  <section class="section">
    <h2>Your Wishlist</h2>
    <div class="card-grid" id="wishlist-items">
      <!-- Aquí se renderizan los productos con JS -->
    </div>
    <p id="wishlist-empty" style="color:#3c3737">Your wishlist is empty.</p>
  </section>

  <?php include 'footer.php'; ?>

  <script>
    // Redirige con los IDs almacenados en localStorage
    const wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
    if (wishlist.length > 0 && !window.location.search.includes('ids=')) {
      window.location.href = `wishlist.php?ids=${wishlist.join(',')}`;
    }
  </script>
  <script src="js/auth.js?v=<?php echo time(); ?>"></script>
  <script src="js/hats.js?v=<?php echo time(); ?>"></script>
  <script src="js/cart.js?v=<?php echo time(); ?>"></script>
  <script src="js/wishlist.js?v=<?php echo time(); ?>"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
