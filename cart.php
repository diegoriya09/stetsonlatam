<?php
session_start();
require_once 'php/conexion.php';

$user_id = $_SESSION['user_id'] ?? null;
$cart_items = [];

if ($user_id) {
    // Consulta el carrito desde la base de datos
    $stmt = $conn->prepare("
    SELECT c.*, p.name, p.image, p.price, s.name as size_name, co.name as color_name, co.hex, pc.color_id, ps.size_id
    FROM cart c
    JOIN productos p ON c.producto_id = p.id
    LEFT JOIN product_colors pc ON c.producto_id = pc.product_id
    LEFT JOIN product_sizes ps ON c.producto_id = ps.product_id
    LEFT JOIN colors co ON pc.color_id = co.id
    LEFT JOIN sizes s ON ps.size_id = s.id
    WHERE c.users_id = ?
  ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $cart_items[] = $row;
    }
    $stmt->close();
} else {
    // Si no está logueado, carga del localStorage usando JS
}

//productos más visitados por el usuario
$recomendados = [];

if ($user_id !== null) {
    // Usuario logueado
    $sql = "SELECT p.* 
            FROM productos p
            INNER JOIN user_visits uv ON p.id = uv.product_id
            WHERE uv.user_id = ?    
            GROUP BY p.id
            ORDER BY COUNT(*) DESC
            LIMIT 5";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
} else {
    // Usuario no logueado (user_id es NULL)
    $sql = "SELECT p.* 
            FROM productos p
            INNER JOIN user_visits uv ON p.id = uv.product_id
            WHERE uv.user_id IS NULL
            GROUP BY p.id
            ORDER BY COUNT(*) DESC
            LIMIT 5";
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $recomendados[] = $row;
}

$conn->close();
?>

<html>

<head>
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="" />
    <link
        rel="stylesheet"
        as="style"
        onload="this.rel='stylesheet'"
        href="https://fonts.googleapis.com/css2?display=swap&amp;family=Noto+Serif%3Awght%40400%3B500%3B700%3B900&amp;family=Noto+Sans%3Awght%40400%3B500%3B700%3B900" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <title>Shopping Cart</title>
    <link rel="icon" href="img/logo.webp" type="image/x-icon" loading="lazy">
    <link href="css/index.css?v=<?php echo time(); ?>" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="relative flex size-full min-h-screen flex-col bg-white group/design-root overflow-x-hidden">
        <div class="layout-container flex h-full grow flex-col">
            <?php include 'header.php'; ?>
            <div class="px-40 flex flex-1 justify-center py-5">
                <div class="layout-content-container flex flex-col max-w-[960px] flex-1">
                    <div class="flex flex-wrap gap-2 p-4">
                        <a class="text-[#887563] text-base font-medium leading-normal" href="index.php">Home</a>
                        <span class="text-[#887563] text-base font-medium leading-normal">/</span>
                        <span class="text-[#181411] text-base font-medium leading-normal">Shopping Cart</span>
                    </div>
                    <div class="flex flex-wrap justify-between gap-3 p-4">
                        <p class="text-[#181411] tracking-light text-[32px] font-bold leading-tight min-w-72">Shopping Cart</p>
                    </div>
                    <div class="px-4 py-3 @container">
                        <div class="flex overflow-hidden rounded-lg border border-[#e5e0dc] bg-white">
                            <table class="flex-1">
                                <thead>
                                    <tr class="bg-white">
                                        <th class="px-4 py-3 text-left text-[#181411] w-[150px] text-sm font-medium leading-normal">
                                            Image
                                        </th>
                                        <th class="px-4 py-3 text-left text-[#181411] w-[300px] text-sm font-medium leading-normal">
                                            Product
                                        </th>
                                        <th class="px-4 py-3 text-left text-[#181411] w-[100px] text-sm font-medium leading-normal">
                                            Price
                                        </th>
                                        <th class="px-4 py-3 text-left text-[#181411] w-[150px] text-sm font-medium leading-normal">
                                            Quantity
                                        </th>
                                        <th class="px-4 py-3 text-left text-[#181411] w-[100px] text-sm font-medium leading-normal">
                                            Size
                                        </th>
                                        <th class="px-4 py-3 text-left text-[#181411] w-[150px] text-sm font-medium leading-normal">
                                            Color
                                        </th>
                                        <th class="px-4 py-3 text-left text-[#181411] w-[100px] text-sm font-medium leading-normal">
                                            Total
                                        </th>
                                        <th class="px-4 py-3 text-center text-[#181411] w-[50px] text-sm font-medium leading-normal">
                                            Remove
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="cart-table-body">
                                    <!-- JavaScript will dynamically populate this section -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="flex justify-end px-4 py-3 border-t border-[#e5e0dc]">
                        <span class="text-[#181411] text-base font-bold leading-normal tracking-[0.015em]">
                            <span id="total-carrito">$0</span>
                        </span>
                    </div>
                    <div class="flex justify-stretch">
                        <div class="flex flex-1 gap-3 flex-wrap px-4 py-3 justify-end">
                            <button
                                class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-[#f4f2f0] text-[#181411] text-sm font-bold leading-normal tracking-[0.015em]">
                                <span class="truncate"><a href="index.php">Continue Shopping</a></span>
                            </button>
                            <button id="checkout-btn"
                                class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-[#e68019] text-[#181411] text-sm font-bold leading-normal tracking-[0.015em]">
                                <span class="truncate">Proceed to Checkout</span>
                            </button>
                            <script>
                                document.getElementById('checkout-btn').addEventListener('click', function(event) {
                                    event.preventDefault(); // evita comportamiento por defecto siempre

                                    const cartTable = document.getElementById('cart-table-body');
                                    const totalElement = document.getElementById('total-carrito');

                                    // Contar solo filas <tr>
                                    const rowCount = cartTable.querySelectorAll("tr").length;

                                    // Sacar el total como número
                                    let total = 0;
                                    if (totalElement) {
                                        const raw = totalElement.innerText.replace(/[^0-9.]/g, ''); // quita $ y otros símbolos
                                        total = parseFloat(raw) || 0;
                                    }

                                    // Validación
                                    if (rowCount === 0 || total === 0) {
                                        Swal.fire({
                                            icon: 'warning',
                                            title: 'Empty cart',
                                            text: 'You must add products to your cart before continuing.',
                                            confirmButtonColor: '#e68019'
                                        });
                                        return; // no redirige
                                    }

                                    // ✅ Solo si hay productos
                                    window.location.href = 'checkout.php';
                                });
                            </script>
                        </div>
                    </div>
                    <h2 class="text-[#181411] text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-5">You may also like</h2>
                    <div class="flex overflow-y-auto [-ms-scrollbar-style:none] [scrollbar-width:none] [&amp;::-webkit-scrollbar]:hidden">
                        <div class="flex items-stretch p-4 gap-3">
                            <?php if (!empty($recomendados)) {
                                foreach ($recomendados as $recomendado): ?>
                                    <div class="flex h-full flex-1 flex-col gap-4 rounded-lg min-w-40">
                                        <a href="producto.php?id=<?php echo $recomendado['id']; ?>" class="flex flex-col gap-3 pb-3 hover:scale-[1.03] transition-transform">
                                            <div
                                                class="w-full bg-center bg-no-repeat aspect-square bg-cover rounded-lg flex flex-col"
                                                style='background-image: url("<?php echo htmlspecialchars($recomendado["image"]); ?>");'></div>
                                            <div>
                                                <p class="text-[#151514] text-base font-medium leading-normal"><?php echo htmlspecialchars($recomendado["name"]); ?></p>
                                                <p class="text-[#7a7671] text-sm font-normal leading-normal">$<?php echo number_format($recomendado["price"], 2); ?></p>
                                            </div>
                                        </a>
                                    </div>
                            <?php endforeach;
                            } else {
                                echo "<p>No recommended products yet.</p>";
                            } ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php include 'footer.php'; ?>
        </div>
    </div>
    <?php include 'modal.php'; ?>
    <script src="js/auth.js?v=<?php echo time(); ?>"></script>
    <script src="js/index.js?v=<?php echo time(); ?>"></script>
    <script src="js/cart.js?v=<?php echo time(); ?>"></script>
</body>

</html>