<?php
session_start();
// El PHP solo obtiene los productos recomendados.
// El carrito se manejará 100% con JavaScript.
require_once 'php/conexion.php';

$user_id = $_SESSION['user_id'] ?? null;
$recomendados = [];

try {
    if ($user_id !== null) {
        $sql_rec = "SELECT p.* FROM productos p INNER JOIN user_visits uv ON p.id = uv.product_id WHERE uv.user_id = ? GROUP BY p.id ORDER BY COUNT(*) DESC LIMIT 5";
        $stmt_rec = $conn->prepare($sql_rec);
        $stmt_rec->bind_param("i", $user_id);
    } else {
        $sql_rec = "SELECT p.* FROM productos p INNER JOIN user_visits uv ON p.id = uv.product_id WHERE uv.user_id IS NULL GROUP BY p.id ORDER BY COUNT(*) DESC LIMIT 5";
        $stmt_rec = $conn->prepare($sql_rec);
    }
    $stmt_rec->execute();
    $result_rec = $stmt_rec->get_result();
    while ($row = $result_rec->fetch_assoc()) {
        $recomendados[] = $row;
    }
} catch (Exception $e) {
    error_log("Error al obtener recomendados en cart.php: " . $e->getMessage());
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Shopping Cart | Stetson LATAM</title>
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
                <h1>Shopping Cart</h1>
                <div id="cart-items-container">
                    <p class="loading-cart">Loading your cart...</p>
                </div>
            </div>
            <aside class="order-summary-column">
                <h2>Order Summary</h2>
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span id="summary-subtotal">$0.00</span>
                </div>
                <div class="summary-row">
                    <span>Shipping</span>
                    <span>Calculated at next step</span>
                </div>
                <div class="summary-total">
                    <span>Total</span>
                    <span id="summary-total">$0.00</span>
                </div>
                <a href="checkout.php" id="checkout-btn" class="checkout-button">Proceed to Checkout</a>
            </aside>
        </div>
        
        <section class="recommendations-section">
            <h2>You may also like</h2>
            <div class="product-grid">
                <?php foreach ($recomendados as $producto): ?>
                    <a href="producto.php?id=<?php echo $producto['id']; ?>" class="product-card">
                        <div class="product-card-image" style="background-image: url('<?php echo htmlspecialchars($producto['image']); ?>');"></div>
                        <div class="product-card-info">
                            <h3 class="product-name"><?php echo htmlspecialchars($producto['name']); ?></h3>
                            <p class="product-price">$<?php echo number_format($producto['price'], 2); ?></p>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>
    </main>
    <?php include 'footer.php'; ?>
    <?php include 'modal.php'; ?>
    <script src="js/index.js?v=<?php echo time(); ?>"></script>
    <script src="js/auth.js?v=<?php echo time(); ?>"></script>
    <script src="js/cart.js?v=<?php echo time(); ?>"></script>
</body>
</html>