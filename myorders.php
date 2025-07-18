<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once 'php/conexion.php';
// Ahora puedes ejecutar la consulta
$stmt = $conn->prepare("SELECT * FROM pedidos WHERE user_id = ? ORDER BY fecha DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$pedidos = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <title>My Orders</title>
   <meta name="description" content="Stetson LATAM - Legendary Hats for Latin America and Central America">
   <link rel="icon" href="img/logo.webp" type="image/x-icon" loading="lazy">
   <link rel="stylesheet" href="css/index.css?v=<?php echo time(); ?>">
   <link rel="stylesheet" href="css/product.css?v=<?php echo time(); ?>">
   <link rel="stylesheet" href="css/myorders.css?v=<?php echo time(); ?>">
   <link rel="stylesheet" href="css/wishlist.css?v=<?php echo time(); ?>">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
   <link href="https://fonts.googleapis.com/css2?family=Lora:wght@700&display=swap" rel="stylesheet">
</head>

<body>
   <?php include 'navbar.php'; ?>
   <?php include 'modal.php'; ?>

   <?php include 'cart.php'; ?>
   <?php include 'checkout.php'; ?>

   <div id="orders-section">
      <h1>My orders</h1>
   </div>

   <!-- Modal para detalle del pedido -->
   <div id="orderModal" class="modal hidden">
      <div class="modal-content-order">
         <span class="close-modal-order">&times;</span>
         <h2>Order Details</h2>
         <div id="orderDetails"></div>
      </div>
   </div>

   <?php include 'footer.php'; ?>
   <script src="js/auth.js?v=<?php echo time(); ?>"></script>
   <script src="js/index.js?v=<?php echo time(); ?>"></script>
   <script src="js/cart.js?v=<?php echo time(); ?>"></script>
   <script src="js/hats.js?v=<?php echo time(); ?>"></script>
   <script src="js/product.js?v=<?php echo time(); ?>"></script>
   <script src="js/myorders.js?v=<?php echo time(); ?>"></script>
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>