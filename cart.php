<?php
session_start();
// El PHP solo obtiene los productos recomendados.
// El carrito se manejará 100% con JavaScript.

$user_id = $_SESSION['user_id'] ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Carrito de compras | Stetson LATAM</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="img/logo.webp" type="image/x-icon">
    <link href="css/index.css?v=<?php echo time(); ?>" rel="stylesheet">
    <link href="css/cart.css?v=<?php echo time(); ?>" rel="stylesheet"> <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php include "header.php"; ?>
    <main class="cart-main">
        <div class="cart-container">
            <div class="cart-items-column">
                <h1>Carrito de compras</h1>
                <div id="cart-items-container">
                    <p class="loading-cart">Cargando tu carrito...</p>
                </div>
            </div>
            <aside class="order-summary-column">
                <h2>Resumen del pedido</h2>
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span id="summary-subtotal">$0.00</span>
                </div>
                <div class="summary-row">
                    <span>Envío</span>
                    <span>Calculado en el siguiente paso</span>
                </div>
                <div class="summary-total">
                    <span>Total</span>
                    <span id="summary-total">$0.00</span>
                </div>
                <a id="checkout-btn" class="checkout-button">Proceder al pago</a>
            </aside>
        </div>
    </main>
    <?php include 'footer.php'; ?>
    <?php include 'modal.php'; ?>
    <script src="js/index.js?v=<?php echo time(); ?>"></script>
    <script src="js/auth.js?v=<?php echo time(); ?>"></script>
    <script src="js/cart.js?v=<?php echo time(); ?>"></script>
</body>
</html>