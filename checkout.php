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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <title>Checkout</title>
    <link rel="icon" href="img/logo.webp" type="image/x-icon" loading="lazy">
    <link href="css/index.css?v=<?php echo time(); ?>" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="relative flex size-full min-h-screen flex-col bg-white group/design-root overflow-x-hidden">
        <div class="layout-container flex h-full grow flex-col">
            <?php include "header.php";?>
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
                        <div class="flex max-w-[480px] gap-4 px-4 py-3">
                            <label class="flex flex-col flex-1">
                                <p>Payment Method</p>
                                <select name="metodo" class="form-input h-14" required>
                                    <option value="">Select a payment method</option>
                                    <option value="tarjeta">Credit/Debit Card</option>
                                    <option value="pse">PSE</option>
                                </select>
                            </label>
                        </div>
                        <!-- Campos para tarjeta -->
                        <div id="tarjeta-fields" class="hidden">
                            <div class="flex max-w-[480px] gap-4 px-4 py-3">
                                <label class="flex flex-col flex-1">
                                    <p>Card Number</p>
                                    <input name="numero_tarjeta" placeholder="1234 5678 9012 3456" class="form-input h-14" />
                                </label>
                            </div>
                            <div class="flex max-w-[480px] gap-4 px-4 py-3">
                                <label class="flex flex-col flex-1">
                                    <p>Name on Card</p>
                                    <input name="nombre_tarjeta" placeholder="John Doe" class="form-input h-14" />
                                </label>
                            </div>
                            <div class="flex max-w-[480px] gap-4 px-4 py-3">
                                <label class="flex flex-col flex-1">
                                    <p>Expiration Date</p>
                                    <input name="expiracion" placeholder="MM/YY" class="form-input h-14" />
                                </label>
                                <label class="flex flex-col flex-1">
                                    <p>CVV</p>
                                    <input name="cvv" placeholder="123" class="form-input h-14" />
                                </label>
                            </div>
                        </div>

                        <!-- Campos para PSE -->
                        <div id="pse-fields" class="hidden">
                            <div class="flex max-w-[480px] gap-4 px-4 py-3">
                                <label class="flex flex-col flex-1">
                                    <p>Bank</p>
                                    <input name="banco_pse" placeholder="Enter bank name" class="form-input h-14" />
                                </label>
                            </div>
                            <div class="flex max-w-[480px] gap-4 px-4 py-3">
                                <label class="flex flex-col flex-1">
                                    <p>Account Type</p>
                                    <input name="tipo_cuenta_pse" placeholder="Savings/Checking" class="form-input h-14" />
                                </label>
                            </div>
                            <div class="flex max-w-[480px] gap-4 px-4 py-3">
                                <label class="flex flex-col flex-1">
                                    <p>Document</p>
                                    <input name="documento_pse" placeholder="Enter your document" class="form-input h-14" />
                                </label>
                            </div>
                        </div>

                        <script>
                            document.querySelector('select[name="metodo"]').addEventListener('change', function() {
                                document.getElementById('tarjeta-fields').classList.add('hidden');
                                document.getElementById('pse-fields').classList.add('hidden');

                                if (this.value === 'tarjeta') {
                                    document.getElementById('tarjeta-fields').classList.remove('hidden');
                                }
                                if (this.value === 'pse') {
                                    document.getElementById('pse-fields').classList.remove('hidden');
                                }
                            });
                        </script>

                        <div class="flex px-4 py-3">
                            <button type="submit"
                                class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center rounded-lg h-12 px-5 flex-1 bg-[#e68019] text-[#181411] font-bold">
                                Continue to Payment
                            </button>
                        </div>
                    </form>
                    <script>
                        document.getElementById('checkout-form').addEventListener('submit', function(e) {
                            e.preventDefault();

                            const formData = new FormData(this);

                            fetch('php/cart/checkout.php', {
                                    method: 'POST',
                                    body: formData
                                })
                                .then(res => res.json())
                                .then(data => {
                                    if (data.success) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Order Placed',
                                            html: `<p>Your order number is <strong>#${data.pedido_id}</strong></p>`,
                                            confirmButtonText: 'OK'
                                        }).then(() => {
                                            window.location.href = 'index.php';
                                        });
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: data.message
                                        });
                                    }
                                })
                                .catch(err => {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'Something went wrong.'
                                    });
                                });
                        });
                    </script>
                </div>
            </div>
            <?php include 'footer.php'; ?>
        </div>
    </div>
    <?php include 'modal.php'; ?>
    <script src="js/auth.js?v=<?php echo time(); ?>"></script>
    <script src="js/index.js?v=<?php echo time(); ?>"></script>
</body>

</html>