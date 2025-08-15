<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Procesar el formulario si es POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Token CSRF inválido.");
    }

    // Sanitizar y validar campos
    $nombre = trim(strip_tags($_POST['nombre']));
    $email = trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
    $pais = $_POST['pais'];
    $ciudad = trim(strip_tags($_POST['ciudad']));
    $direccion = trim(strip_tags($_POST['direccion']));
    $telefono = trim(strip_tags($_POST['telefono']));
    $metodo = $_POST['metodo'];

    // Validaciones básicas
    if (!preg_match('/^[a-zA-Z\s]{3,40}$/', $nombre)) {
        $error = "Nombre inválido.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email inválido.";
    } elseif (empty($pais) || empty($ciudad) || empty($direccion) || empty($telefono)) {
        $error = "Todos los campos son obligatorios.";
    } elseif ($metodo === 'tarjeta') {
        // Validar campos de tarjeta
        $numero_tarjeta = preg_replace('/\D/', '', $_POST['numero_tarjeta']);
        $nombre_tarjeta = trim($_POST['nombre_tarjeta']);
        $expiracion = trim($_POST['expiracion']);
        $cvv = trim($_POST['cvv']);
        if (strlen($numero_tarjeta) < 13 || strlen($numero_tarjeta) > 19) {
            $error = "Número de tarjeta inválido.";
        } elseif (empty($nombre_tarjeta) || !preg_match('/^[a-zA-Z\s]+$/', $nombre_tarjeta)) {
            $error = "Nombre en la tarjeta inválido.";
        } elseif (!preg_match('/^(0[1-9]|1[0-2])\/\d{2}$/', $expiracion)) {
            $error = "Fecha de expiración inválida.";
        } elseif (!preg_match('/^\d{3,4}$/', $cvv)) {
            $error = "CVV inválido.";
        }
    } elseif ($metodo === 'pse') {
        // Validar campos de PSE
        $banco = $_POST['banco_pse'];
        $tipo_cuenta = $_POST['tipo_cuenta_pse'];
        $documento = trim($_POST['documento_pse']);
        if (empty($banco) || empty($tipo_cuenta) || empty($documento)) {
            $error = "Completa todos los datos de PSE.";
        }
    }

    // Si hay error, puedes mostrarlo en el HTML
    if (isset($error)) {
        echo "<div style='color:red; text-align:center;'>$error</div>";
    } else {
        // Aquí procesas el pago simulado, guardas la orden, etc.
        echo "<div style='color:green; text-align:center;'>¡Pago procesado correctamente!</div>";
        // Limpia el formulario, redirige, etc.
    }
}
?>

<html>

<head>
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="" />
    <link
        rel="stylesheet"
        as="style"
        onload="this.rel='stylesheet'"
        href="https://fonts.googleapis.com/css2?display=swap&amp;family=Noto+Serif%3Awght%40400%3B500%3B700%3B900&amp;family=Noto+Sans%3Awght%40400%3B500%3B700%3B900" />

    <title>Checkout</title>
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
                        <h2 class="text-[#181411] text-lg font-bold leading-tight tracking-[-0.015em]">Stetson Latam</h2>
                    </div>
                    <div class="flex items-center gap-9">
                        <a class="text-[#181411] text-sm font-medium leading-normal" href="#">Help</a>
                        <a class="text-[#181411] text-sm font-medium leading-normal" href="myorders.php">Orders</a>
                    </div>
                </div>
                <div class="flex flex-1 justify-end gap-8">
                    <label class="flex flex-col min-w-40 !h-10 max-w-64">
                        <div class="flex w-full flex-1 items-stretch rounded-lg h-full">
                            <div
                                class="text-[#887563] flex border-none bg-[#f4f2f0] items-center justify-center pl-4 rounded-l-lg border-r-0"
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
                                class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-lg text-[#181411] focus:outline-0 focus:ring-0 border-none bg-[#f4f2f0] focus:border-none h-full placeholder:text-[#887563] px-4 rounded-l-none border-l-0 pl-2 text-base font-normal leading-normal"
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
                    <form id="checkout-form" method="POST" action="php/checkout.php" class="flex flex-col">

                        <!-- Token CSRF (lo generas en PHP antes) -->
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                        <div class="flex flex-wrap gap-2 p-4">
                            <a class="text-[#887563] text-base font-medium" href="cart.php">Cart</a>
                            <span class="text-[#887563] text-base font-medium">/</span>
                            <span class="text-[#181411] text-base font-medium">Checkout</span>
                        </div>

                        <div class="flex max-w-[480px] gap-4 px-4 py-3">
                            <label class="flex flex-col flex-1">
                                <p>Full Name</p>
                                <input name="nombre" placeholder="Enter your full name" class="form-input h-14" required />
                            </label>
                        </div>

                        <div class="flex max-w-[480px] gap-4 px-4 py-3">
                            <label class="flex flex-col flex-1">
                                <p>Email</p>
                                <input type="email" name="email" placeholder="Enter your email" class="form-input h-14" required />
                            </label>
                        </div>

                        <div class="flex max-w-[480px] gap-4 px-4 py-3">
                            <label class="flex flex-col flex-1">
                                <p>Address</p>
                                <input name="direccion" placeholder="Enter your address" class="form-input h-14" required />
                            </label>
                        </div>

                        <div class="flex max-w-[480px] gap-4 px-4 py-3">
                            <label class="flex flex-col flex-1">
                                <p>City</p>
                                <input name="ciudad" placeholder="Enter your city" class="form-input h-14" required />
                            </label>
                        </div>

                        <div class="flex max-w-[480px] gap-4 px-4 py-3">
                            <label class="flex flex-col flex-1">
                                <p>State</p>
                                <input name="estado" placeholder="Enter your state" class="form-input h-14" />
                            </label>
                        </div>

                        <div class="flex max-w-[480px] gap-4 px-4 py-3">
                            <label class="flex flex-col flex-1">
                                <p>Zip Code</p>
                                <input name="zip" placeholder="Enter your zip code" class="form-input h-14" />
                            </label>
                        </div>

                        <div class="flex max-w-[480px] gap-4 px-4 py-3">
                            <label class="flex flex-col flex-1">
                                <p>Country</p>
                                <input name="pais" placeholder="Enter your country" class="form-input h-14" required />
                            </label>
                        </div>

                        <div class="flex max-w-[480px] gap-4 px-4 py-3">
                            <label class="flex flex-col flex-1">
                                <p>Phone Number</p>
                                <input name="telefono" placeholder="Enter your phone number" class="form-input h-14" required />
                            </label>
                        </div>

                        <div class="flex px-4 py-3">
                            <button type="submit"
                                class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center rounded-lg h-12 px-5 flex-1 bg-[#e68019] text-[#181411] font-bold">
                                Continue to Payment
                            </button>
                        </div>
                    </form>
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
                                <div class="text-[#887563]" data-icon="InstagramLogo" data-size="24px" data-weight="regular">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" fill="currentColor" viewBox="0 0 256 256">
                                        <path
                                            d="M128,80a48,48,0,1,0,48,48A48.05,48.05,0,0,0,128,80Zm0,80a32,32,0,1,1,32-32A32,32,0,0,1,128,160ZM176,24H80A56.06,56.06,0,0,0,24,80v96a56.06,56.06,0,0,0,56,56h96a56.06,56.06,0,0,0,56-56V80A56.06,56.06,0,0,0,176,24Zm40,152a40,40,0,0,1-40,40H80a40,40,0,0,1-40-40V80A40,40,0,0,1,80,40h96a40,40,0,0,1,40,40ZM192,76a12,12,0,1,1-12-12A12,12,0,0,1,192,76Z"></path>
                                    </svg>
                                </div>
                            </a>
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
                        </div>
                        <p class="text-[#887563] text-base font-normal leading-normal">© 2024 Stetson Latin America. All rights reserved.</p>
                    </footer>
                </div>
            </footer>
        </div>
    </div>
</body>

</html>