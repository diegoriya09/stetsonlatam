<?php
require_once 'php/conexion.php';
require_once '../vendor/autoload.php';

if (!$user_id || !isset($_GET['id'])) {
   die("Acceso no autorizado.");
}

$pedido_id = intval($_GET['id']);

// Verificamos que el pedido pertenezca al usuario logueado
$stmt = $conn->prepare("SELECT * FROM pedidos WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $pedido_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$pedido = $result->fetch_assoc();

if (!$pedido) die("Pedido no encontrado.");

// Obtener detalles
$stmt = $conn->prepare("SELECT * FROM pedido_detalle WHERE pedido_id = ?");
$stmt->bind_param("i", $pedido_id);
$stmt->execute();
$detalles = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <title>Order Details #<?= $pedido_id ?></title>
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
      <h2>Order #<?= $pedido_id ?></h2>
      <p><strong>Date:</strong> <?= $pedido['fecha'] ?></p>
      <p><strong>Total:</strong> $<?= number_format($pedido['total'], 2) ?></p>
      <p><strong>Status:</strong> <?= $pedido['estado'] ?></p>

      <h3>Products:</h3>
      <ul>
         <?php foreach ($detalles as $item): ?>
            <li>
               <?= $item['nombre_producto'] ?> - $<?= number_format($item['precio'], 2) ?> x <?= $item['cantidad'] ?>
               <?php if ($item['color_nombre']) echo " | Color: {$item['color_nombre']}"; ?>
               <?php if ($item['size_nombre']) echo " | Talla: {$item['size_nombre']}"; ?>
            </li>
         <?php endforeach; ?>
      </ul>
   </div>

   <?php include 'footer.php'; ?>
</body>

</html>