<?php
require_once 'php/conexion.php';
require_once '../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Obtener JWT desde localStorage a través de una cookie (si lo pasas por JS)
$headers = apache_request_headers();
$authHeader = isset($headers['Authorization']) ? $headers['Authorization'] : (isset($headers['authorization']) ? $headers['authorization'] : '');

if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
   header("Location: index.php");
   exit;
}

$jwt = $matches[1];
$secretKey = "StetsonLatam1977"; // Usa la misma clave que en login.php

try {
   $decoded = JWT::decode($jwt, new Key($secretKey, 'HS256'));
   $user_id = $decoded->user_id;
} catch (Exception $e) {
   header("Location: index.php");
   exit;
}

// Ahora puedes ejecutar la consulta
$stmt = $conn->prepare("SELECT * FROM pedidos WHERE user_id = ? ORDER BY fecha DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$pedidos = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">

<head>
   <meta charset="UTF-8">
   <title>My Orders</title>
   <meta name="description" content="Stetson LATAM - Legendary Hats for Latin America and Central America">
   <link rel="icon" href="img/logo.webp" type="image/x-icon" loading="lazy">
   <link rel="stylesheet" href="css/index.css?v=<?php echo time(); ?>">
   <link rel="stylesheet" href="css/product.css?v=<?php echo time(); ?>">
   <link rel="stylesheet" href="css/myorders.css?v=<?php echo time(); ?>">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
   <link href="https://fonts.googleapis.com/css2?family=Lora:wght@700&display=swap" rel="stylesheet">
</head>

<body>
   <?php include 'navbar.php'; ?>
   <?php include 'modal.php'; ?>

   <?php include 'cart.php'; ?>
   <?php include 'checkout.php'; ?>

   <div class="container">
      <h2>My Orders</h2>
      <?php if (empty($pedidos)): ?>
         <p>No orders yet.</p>
      <?php else: ?>
         <?php foreach ($pedidos as $pedido): ?>
            <div class="pedido-card">
               <p><strong>Order #<?= $pedido['id'] ?></strong></p>
               <p>Date: <?= $pedido['fecha'] ?></p>
               <p>Total: $<?= number_format($pedido['total'], 2) ?></p>
               <p>Status: <?= $pedido['estado'] ?></p>
               <a href="detailorder.php?id=<?= $pedido['id'] ?>">View Details</a>
            </div>
         <?php endforeach; ?>
      <?php endif; ?>
   </div>

   <?php include 'footer.php'; ?>
   <script src="js/auth.js?v=<?php echo time(); ?>"></script>
   <script src="js/index.js?v=<?php echo time(); ?>"></script>
   <script src="js/cart.js?v=<?php echo time(); ?>"></script>
   <script src="js/hats.js?v=<?php echo time(); ?>"></script>
   <script src="js/product.js?v=<?php echo time(); ?>"></script>
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   <script src="js/myorders.js?v=<?php echo time() ?>"></script>
</body>

</html>