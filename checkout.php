<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
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

    <title>Finalizar compra | Stetson LATAM</title>
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
                    <form id="checkout-form" method="POST" action="php/cart/checkout" class="flex flex-col">

                        <!-- Token CSRF (lo generas en PHP antes) -->
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                        <div class="flex flex-wrap gap-2 p-4">
                            <a class="text-[#887563] text-base font-medium" href="cart.php">Carrito</a>
                            <span class="text-[#3c3737] text-base font-medium">/</span>
                            <span class="text-[#3c3737] text-base font-medium">Finalizar compra</span>
                        </div>

                        <div class="flex max-w-[480px] gap-4 px-4 py-3">
                            <label class="flex flex-col flex-1">
                                <p>Nombre completo</p>
                                <input name="nombre" placeholder="Ingresa tu nombre completo" class="form-input h-14" required />
                            </label>
                        </div>

                        <div class="flex max-w-[480px] gap-4 px-4 py-3">
                            <label class="flex flex-col flex-1">
                                <p>Email</p>
                                <input type="email" name="email" placeholder="Ingresa tu email" class="form-input h-14" required />
                            </label>
                        </div>

                        <div class="flex max-w-[480px] gap-4 px-4 py-3">
                            <label class="flex flex-col flex-1">
                                <p>Dirección</p>
                                <input name="direccion" placeholder="Ingresa tu dirección" class="form-input h-14" required />
                            </label>
                        </div>

                        <div class="flex max-w-[480px] gap-4 px-4 py-3">
                            <label class="flex flex-col flex-1">
                                <p>Ciudad</p>
                                <input name="ciudad" placeholder="Ingresa tu ciudad" class="form-input h-14" required />
                            </label>
                        </div>

                        <div class="flex max-w-[480px] gap-4 px-4 py-3">
                            <label class="flex flex-col flex-1">
                                <p>Departamento</p>
                                <input name="estado" placeholder="Ingresa tu departamento" class="form-input h-14" />
                            </label>
                        </div>

                        <div class="flex max-w-[480px] gap-4 px-4 py-3">
                            <label class="flex flex-col flex-1">
                                <p>Código Postal</p>
                                <input name="zip" placeholder="Ingresa tu código postal" class="form-input h-14" />
                            </label>
                        </div>

                        <div class="flex max-w-[480px] gap-4 px-4 py-3">
                            <label class="flex flex-col flex-1">
                                <p>País</p>
                                <input name="pais" placeholder="Ingresa tu país" class="form-input h-14" required />
                            </label>
                        </div>

                        <div class="flex max-w-[480px] gap-4 px-4 py-3">
                            <label class="flex flex-col flex-1">
                                <p>Número de Teléfono</p>
                                <input name="telefono" placeholder="Ingresa tu número de teléfono" class="form-input h-14" required />
                            </label>
                        </div>
                        <div class="flex max-w-[480px] gap-4 px-4 py-3">
                            <label class="flex flex-col flex-1">
                                <p>Método de Pago</p>
                                <select name="metodo" class="form-input h-14" required>
                                    <option value="">Selecciona un método de pago</option>
                                    <option value="tarjeta">Tarjeta de Crédito/Débito</option>
                                    <option value="pse">PSE</option>
                                </select>
                            </label>
                        </div>
                        <!-- Campos para tarjeta -->
                        <div id="tarjeta-fields" class="hidden">
                            <div class="flex max-w-[480px] gap-4 px-4 py-3">
                                <label class="flex flex-col flex-1">
                                    <p>Número de Tarjeta</p>
                                    <input name="numero_tarjeta" placeholder="1234 5678 9012 3456" class="form-input h-14" />
                                </label>
                            </div>
                            <div class="flex max-w-[480px] gap-4 px-4 py-3">
                                <label class="flex flex-col flex-1">
                                    <p>Nombre en la Tarjeta</p>
                                    <input name="nombre_tarjeta" placeholder="John Doe" class="form-input h-14" />
                                </label>
                            </div>
                            <div class="flex max-w-[480px] gap-4 px-4 py-3">
                                <label class="flex flex-col flex-1">
                                    <p>Fecha de Expiración</p>
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
                                    <p>Banco</p>
                                    <input name="banco_pse" placeholder="Ingresa el nombre del banco" class="form-input h-14" />
                                </label>
                            </div>
                            <div class="flex max-w-[480px] gap-4 px-4 py-3">
                                <label class="flex flex-col flex-1">
                                    <p>Tipo de Cuenta</p>
                                    <input name="tipo_cuenta_pse" placeholder="Ahorros/Corriente" class="form-input h-14" />
                                </label>
                            </div>
                            <div class="flex max-w-[480px] gap-4 px-4 py-3">
                                <label class="flex flex-col flex-1">
                                    <p>Documento</p>
                                    <input name="documento_pse" placeholder="Ingresa tu documento" class="form-input h-14" />
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
                                Continuar al Pago
                            </button>
                        </div>
                    </form>
                    <script>
                        document.getElementById('checkout-form').addEventListener('submit', function(e) {
                            e.preventDefault();

                            const formData = new FormData(this);

                            const jwt = localStorage.getItem('jwt');

                            // Validación: Si no hay token, no se puede pagar
                            if (!jwt) {
                                Swal.fire('Error', 'Debes iniciar sesión para completar la compra.', 'error');
                                return;
                            }

                            fetch('php/cart/checkout', {
                                    method: 'POST',
                                    headers: {
                                        'Authorization': 'Bearer ' + jwt
                                    },
                                    body: formData
                                })
                                .then(res => res.json())
                                .then(data => {
                                    if (data.success) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Pedido Realizado',
                                            html: `<p>Tu número de pedido es <strong>#${data.pedido_id}</strong></p>`,
                                            confirmButtonText: 'OK'
                                        }).then(() => {
                                            window.location.href = 'https://stetsonlatam.com/';
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
                                        text: 'Algo salió mal.'
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