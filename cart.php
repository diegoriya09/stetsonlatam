<?php
session_start();
require_once 'php/conexion.php';

$user_id = $_SESSION['user_id'] ?? null;
$cart_items = [];

if ($user_id) {
    // Consulta el carrito desde la base de datos
    $stmt = $conn->prepare("
    SELECT c.*, p.name, p.image, p.price, s.name as size_name, co.name as color_name, co.hex
    FROM cart c
    JOIN productos p ON c.producto_id = p.id
    LEFT JOIN sizes s ON c.size_id = s.id
    LEFT JOIN colors co ON c.color_id = co.id
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

    <title>Shopping Bag</title>
    <link rel="icon" href="img/logo.webp" type="image/x-icon" loading="lazy">

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="relative flex size-full min-h-screen flex-col bg-white group/design-root overflow-x-hidden" style='font-family: "Noto Serif", "Noto Sans", sans-serif;'>
        <div class="layout-container flex h-full grow flex-col">
            <header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-b-[#f4f2f0] px-10 py-3">
                <div class="flex items-center gap-8">
                    <div class="flex items-center gap-4 text-[#181411]">
                        <div class="size-4">
                            <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M24 4H42V17.3333V30.6667H24V44H6V30.6667V17.3333H24V4Z" fill="currentColor"></path>
                            </svg>
                        </div>
                        <h2 class="text-[#181411] text-lg font-bold leading-tight tracking-[-0.015em]"><a href="index.php">Stetson Latam</a></h2>
                    </div>
                    <div class="flex items-center gap-9">
                        <a class="text-[#181411] text-sm font-medium leading-normal" href="hats.php">Hats</a>
                        <a class="text-[#181411] text-sm font-medium leading-normal" href="caps.php">Caps</a>
                        <a class="text-[#181411] text-sm font-medium leading-normal" href="#">Contact</a>
                    </div>
                </div>
                <div class="flex flex-1 justify-end gap-8">
                    <label class="flex flex-col min-w-40 !h-10 max-w-64">
                        <div class="flex w-full flex-1 items-stretch rounded-lg h-full">
                            <div
                                class="text-[#7a7671] flex border-none bg-[#f3f2f2] items-center justify-center pl-4 rounded-l-lg border-r-0"
                                data-icon="MagnifyingGlass"
                                data-size="24px"
                                data-weight="regular">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" viewBox="0 0 256 256">
                                    <path
                                        d="M229.66,218.34l-50.07-50.06a88.11,88.11,0,1,0-11.31,11.31l50.06,50.07a8,8,0,0,0,11.32-11.32ZM40,112a72,72,0,1,1,72,72A72.08,72.08,0,0,1,40,112Z"></path>
                                </svg>
                            </div>
                            <input
                                placeholder="Search"
                                class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-[#151514] focus:outline-0 focus:ring-0 border-none bg-[#f3f2f2] focus:border-none h-full placeholder:text-[#7a7671] px-4 rounded-l-none border-l-0 pl-2 text-base font-normal leading-normal"
                                value="" />
                        </div>
                    </label>
                    <div class="flex gap-2">
                        <button
                            id="logout-btn"
                            style="display:none;"
                            class="flex max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 bg-[#f4f2f0] text-[#181411] gap-2 text-sm font-bold leading-normal tracking-[0.015em] min-w-0 px-2.5">
                            <div class="text-[#181411]" data-icon="SignOut" data-size="20px" data-weight="regular">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                                    <path d="M216,128a8,8,0,0,1-8,8H104v16a8,8,0,0,1-13.66,5.66l-32-32a8,8,0,0,1,0-11.32l32-32A8,8,0,0,1,104,104v16h104A8,8,0,0,1,216,128ZM128,24A104,104,0,1,0,232,128,104.11,104.11,0,0,0,128,24Zm0,192a88,88,0,1,1,88-88A88.1,88.1,0,0,1,128,216Z"></path>
                                </svg>
                            </div>
                        </button>
                        <button
                            id="user-btn"
                            class="flex max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 bg-[#f4f2f0] text-[#181411] gap-2 text-sm font-bold leading-normal tracking-[0.015em] min-w-0 px-2.5">
                            <div class="text-[#181411]" data-icon="User" data-size="20px" data-weight="regular">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                                    <path
                                        d="M230.92,212c-15.23-26.33-38.7-45.21-66.09-54.16a72,72,0,1,0-73.66,0C63.78,166.78,40.31,185.66,25.08,212a8,8,0,1,0,13.85,8c18.84-32.56,52.14-52,89.07-52s70.23,19.44,89.07,52a8,8,0,1,0,13.85-8ZM72,96a56,56,0,1,1,56,56A56.06,56.06,0,0,1,72,96Z"></path>
                                </svg>
                            </div>
                        </button>
                        <button
                            id="cart-btn"
                            class="flex max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 bg-[#f4f2f0] text-[#181411] gap-2 text-sm font-bold leading-normal tracking-[0.015em] min-w-0 px-2.5">
                            <div class="text-[#181411]" data-icon="ShoppingBag" data-size="20px" data-weight="regular">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" fill="currentColor" viewBox="0 0 256 256">
                                    <path
                                        d="M216,40H40A16,16,0,0,0,24,56V200a16,16,0,0,0,16,16H216a16,16,0,0,0,16-16V56A16,16,0,0,0,216,40Zm0,160H40V56H216V200ZM176,88a48,48,0,0,1-96,0,8,8,0,0,1,16,0,32,32,0,0,0,64,0,8,8,0,0,1,16,0Z"></path>
                                </svg>
                            </div>
                        </button>
                    </div>
                </div>
            </header>
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
                                        <th class="px-4 py-3 text-left text-[#181411] w-14 text-sm font-medium leading-normal">Product
                                        </th>
                                        <th class="px-4 py-3 text-left text-[#181411] w-[400px] text-sm font-medium leading-normal">Price
                                        </th>
                                        <th class="px-4 py-3 text-left text-[#181411] w-[400px] text-sm font-medium leading-normal">Quantity
                                        </th>
                                        <th class="px-4 py-3 text-left text-[#181411] w-[400px] text-sm font-medium leading-normal">Size
                                        </th>
                                        <th class="px-4 py-3 text-left text-[#181411] w-[400px] text-sm font-medium leading-normal">Color
                                        </th>
                                        <th class="px-4 py-3 text-left text-[#181411] w-[400px] text-sm font-medium leading-normal">Total
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- JavaScript will dynamically populate this section -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="flex justify-stretch">
                        <div class="flex flex-1 gap-3 flex-wrap px-4 py-3 justify-end">
                            <button
                                class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-[#f4f2f0] text-[#181411] text-sm font-bold leading-normal tracking-[0.015em]">
                                <span class="truncate">Continue Shopping</span>
                            </button>
                            <button
                                class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-[#e68019] text-[#181411] text-sm font-bold leading-normal tracking-[0.015em]">
                                <span class="truncate">Proceed to Checkout</span>
                            </button>
                        </div>
                    </div>
                    <h2 class="text-[#181411] text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-5">You may also like</h2>
                    <div class="flex overflow-y-auto [-ms-scrollbar-style:none] [scrollbar-width:none] [&amp;::-webkit-scrollbar]:hidden">
                        <div class="flex items-stretch p-4 gap-3">
                            <div class="flex h-full flex-1 flex-col gap-4 rounded-lg min-w-40">
                                <div
                                    class="w-full bg-center bg-no-repeat aspect-[3/4] bg-cover rounded-lg flex flex-col"
                                    style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBGJoTweVx-e3vNbYD7aZY8QpDeI4Cn9FP8jyzaYB61r08rmUubyayJxPo_V4gW-sRy2tR2uw4lAVj6wPm8GH2A9KdorVvfprer00yrL7dqujEr6ZshNYmUC2edvNQfid8LOIsbROm1CUFyNuFbT9sbHJTej88ho3tZ-9CRQg5UbXGOtxJb9cnWLk9SmJ79Sn1NeQZKEraSLB33KUon31IJdYeGjU4GPOp5t_Wu5WoQejL0oGF23LM0mUYW0iCP_zTZFhRS82VscxAg");'></div>
                                <div>
                                    <p class="text-[#181411] text-base font-medium leading-normal">Classic Cowboy Hat</p>
                                    <p class="text-[#887563] text-sm font-normal leading-normal">$120</p>
                                </div>
                            </div>
                            <div class="flex h-full flex-1 flex-col gap-4 rounded-lg min-w-40">
                                <div
                                    class="w-full bg-center bg-no-repeat aspect-[3/4] bg-cover rounded-lg flex flex-col"
                                    style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuC62EaHPGrX3IdLX9hWmiNOicoyotbYYs0kpxfy6CAFCQFBi7qe2G4a7QM8Nj-zfQLVO1KZgwOgu-DgqmXaDcWrZqQZV5AgDop4o_vxw9jt_9L6zXauFE49dzdlPv7I6yb9m85Yrdlu42IBy5L_-muIpePLabRk1uzG0upSjG7_6iM49ps0Fs0TJE9kh7Ahx_SSgfKv0YJjrBt8dzeZx63T-Bbkk1X0cVVBs-om4HxnIvI-OKnsPeH7k0UzbkS2iKcKqqzQrNG9jP2K");'></div>
                                <div>
                                    <p class="text-[#181411] text-base font-medium leading-normal">Stylish Fedora Hat</p>
                                    <p class="text-[#887563] text-sm font-normal leading-normal">$80</p>
                                </div>
                            </div>
                            <div class="flex h-full flex-1 flex-col gap-4 rounded-lg min-w-40">
                                <div
                                    class="w-full bg-center bg-no-repeat aspect-[3/4] bg-cover rounded-lg flex flex-col"
                                    style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuAo2DzWDFiJRdfL4YCos70uPdznHSa9t7hIoswXQhoEzrzaOfPxqqrkMPFAzlinVuNCZigwoMig-hKdPjQwHpHp_Vk7a1P_xzWMgYLtIsz9yHxOfRpFLtzAHZfSt_B-9uqIFPXDEhgxvnP0NNiTAQim5x3GQ1AAu41Vx8-VhAkgxCRE5nYAFIsgCC9SW_RN8nmYqDBiOPmxZ3JzbBExDyZAJxowNfyeBqFsd051n71S7gleczP6h_5zGMRcKjpaUfpMO_XLznZePDn-");'></div>
                                <div>
                                    <p class="text-[#181411] text-base font-medium leading-normal">Casual Baseball Cap</p>
                                    <p class="text-[#887563] text-sm font-normal leading-normal">$30</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="flex justify-center">
                <div class="flex max-w-[960px] flex-1 flex-col">
                    <footer class="flex flex-col gap-6 px-5 py-10 text-center @container">
                        <div class="flex flex-wrap items-center justify-center gap-6 @[480px]:flex-row @[480px]:justify-around">
                            <a class="text-[#887563] text-base font-normal leading-normal min-w-40" href="#">FAQs</a>
                            <a class="text-[#887563] text-base font-normal leading-normal min-w-40" href="#">Shipping &amp; Returns</a>
                            <a class="text-[#887563] text-base font-normal leading-normal min-w-40" href="#">Privacy Policy</a>
                        </div>
                        <div class="flex flex-wrap justify-center gap-4">
                            <a href="#">
                                <div class="text-[#887563]" data-icon="FacebookLogo" data-size="24px" data-weight="regular">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" viewBox="0 0 256 256">
                                        <path
                                            d="M128,24A104,104,0,1,0,232,128,104.11,104.11,0,0,0,128,24Zm8,191.63V152h24a8,8,0,0,0,0-16H136V112a16,16,0,0,1,16-16h16a8,8,0,0,0,0-16H152a32,32,0,0,0-32,32v24H96a8,8,0,0,0,0,16h24v63.63a88,88,0,1,1,16,0Z"></path>
                                    </svg>
                                </div>
                            </a>
                            <a href="#">
                                <div class="text-[#887563]" data-icon="TwitterLogo" data-size="24px" data-weight="regular">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" viewBox="0 0 256 256">
                                        <path
                                            d="M247.39,68.94A8,8,0,0,0,240,64H209.57A48.66,48.66,0,0,0,168.1,40a46.91,46.91,0,0,0-33.75,13.7A47.9,47.9,0,0,0,120,88v6.09C79.74,83.47,46.81,50.72,46.46,50.37a8,8,0,0,0-13.65,4.92c-4.31,47.79,9.57,79.77,22,98.18a110.93,110.93,0,0,0,21.88,24.2c-15.23,17.53-39.21,26.74-39.47,26.84a8,8,0,0,0-3.85,11.93c.75,1.12,3.75,5.05,11.08,8.72C53.51,229.7,65.48,232,80,232c70.67,0,129.72-54.42,135.75-124.44l29.91-29.9A8,8,0,0,0,247.39,68.94Zm-45,29.41a8,8,0,0,0-2.32,5.14C196,166.58,143.28,216,80,216c-10.56,0-18-1.4-23.22-3.08,11.51-6.25,27.56-17,37.88-32.48A8,8,0,0,0,92,169.08c-.47-.27-43.91-26.34-44-96,16,13,45.25,33.17,78.67,38.79A8,8,0,0,0,136,104V88a32,32,0,0,1,9.6-22.92A30.94,30.94,0,0,1,167.9,56c12.66.16,24.49,7.88,29.44,19.21A8,8,0,0,0,204.67,80h16Z"></path>
                                    </svg>
                                </div>
                            </a>
                            <a href="#">
                                <div class="text-[#887563]" data-icon="InstagramLogo" data-size="24px" data-weight="regular">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" viewBox="0 0 256 256">
                                        <path
                                            d="M128,80a48,48,0,1,0,48,48A48.05,48.05,0,0,0,128,80Zm0,80a32,32,0,1,1,32-32A32,32,0,0,1,128,160ZM176,24H80A56.06,56.06,0,0,0,24,80v96a56.06,56.06,0,0,0,56,56h96a56.06,56.06,0,0,0,56-56V80A56.06,56.06,0,0,0,176,24Zm40,152a40,40,0,0,1-40,40H80a40,40,0,0,1-40-40V80A40,40,0,0,1,80,40h96a40,40,0,0,1,40,40ZM192,76a12,12,0,1,1-12-12A12,12,0,0,1,192,76Z"></path>
                                    </svg>
                                </div>
                            </a>
                        </div>
                        <p class="text-[#887563] text-base font-normal leading-normal">© 2025 Stetson Latin America. All rights reserved.</p>
                    </footer>
                </div>
            </footer>
        </div>
    </div>
    <?php include 'modal.php'; ?>
    <script src="js/auth.js?v=<?php echo time(); ?>"></script>
    <script src="js/index.js?v=<?php echo time(); ?>"></script>
    <script src="js/cart.js?v=<?php echo time(); ?>"></script>
</body>

</html>