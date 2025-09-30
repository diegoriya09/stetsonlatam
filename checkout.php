<?php
// checkout.php (CÓDIGO COMPLETO Y ADAPTADO A TU BASE DE DATOS)

if (session_status() !== PHP_SESSION_ACTIVE) session_start();
require_once 'php/conexion.php';

// --- OBTENER DATOS DEL USUARIO AL CARGAR LA PÁGINA ---
$user_id = $_SESSION['user_id'] ?? null;
$saved_addresses = [];
$saved_payment_methods = [];
$user_email = '';
$user_name = '';

if ($user_id) {
    // Obtener datos básicos del usuario (nombre, email)
    $stmt_user = $conn->prepare("SELECT name, email FROM users WHERE id = ?");
    $stmt_user->bind_param("i", $user_id);
    if ($stmt_user->execute()) {
        $result_user = $stmt_user->get_result()->fetch_assoc();
        if ($result_user) {
            $user_name = $result_user['name'];
            $user_email = $result_user['email'];
        }
    }
    $stmt_user->close();

    // ADAPTADO: Leer las columnas correctas de tu tabla user_addresses
    $stmt_addr = $conn->prepare("SELECT id, street_address, city, state, postal_code, country, is_default FROM user_addresses WHERE user_id = ?");
    $stmt_addr->bind_param("i", $user_id);
    $stmt_addr->execute();
    $result_addr = $stmt_addr->get_result();
    while ($row = $result_addr->fetch_assoc()) {
        $saved_addresses[] = $row;
    }
    $stmt_addr->close();

    // ADAPTADO: Leer las columnas correctas de tu tabla user_payment_methods
    $stmt_pay = $conn->prepare("SELECT id, card_type, last_four_digits, expiry_date, is_default FROM user_payment_methods WHERE user_id = ?");
    $stmt_pay->bind_param("i", $user_id);
    $stmt_pay->execute();
    $result_pay = $stmt_pay->get_result();
    while ($row = $result_pay->fetch_assoc()) {
        $saved_payment_methods[] = $row;
    }
    $stmt_pay->close();
}

$conn->close();
?>
<html>

<head>
    <title>Finalizar compra | Stetson LATAM</title>
    <link rel="icon" href="img/logo.webp" type="image/x-icon">
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

                        <div class="flex flex-wrap gap-2 p-4">
                            <a class="text-[#887563] text-base font-medium" href="cart">Carrito</a>
                            <span class="text-[#3c3737] text-base font-medium">/</span>
                            <span class="text-[#3c3737] text-base font-medium">Finalizar compra</span>
                        </div>

                        <h2 class="text-xl font-bold px-4 py-3">Información de Envío</h2>

                        <?php if (!empty($saved_addresses)): ?>
                            <div class="flex max-w-[480px] gap-4 px-4 py-3">
                                <label class="flex flex-col flex-1">
                                    <p>Elige una dirección guardada</p>
                                    <select name="address_id" id="address-select" class="form-input h-14">
                                        <option value="new">-- Añadir una nueva dirección --</option>
                                        <?php foreach ($saved_addresses as $addr): ?>
                                            <option value='<?php echo json_encode($addr); ?>'>
                                                <?php echo htmlspecialchars($addr['street_address'] . ', ' . $addr['city'] . ', ' . $addr['country']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </label>
                            </div>
                        <?php endif; ?>

                        <div id="address-fields">
                            <div class="flex max-w-[480px] gap-4 px-4 py-3"><label class="flex flex-col flex-1">
                                    <p>Nombre completo</p><input name="nombre" placeholder="Ingresa tu nombre completo" class="form-input h-14" value="<?php echo htmlspecialchars($user_name); ?>" required />
                                </label></div>
                            <div class="flex max-w-[480px] gap-4 px-4 py-3"><label class="flex flex-col flex-1">
                                    <p>Email</p><input type="email" name="email" placeholder="Ingresa tu email" class="form-input h-14" value="<?php echo htmlspecialchars($user_email); ?>" required />
                                </label></div>
                            <div class="flex max-w-[480px] gap-4 px-4 py-3"><label class="flex flex-col flex-1">
                                    <p>Dirección</p><input name="direccion" placeholder="Ingresa tu dirección" class="form-input h-14" required />
                                </label></div>
                            <div class="flex max-w-[480px] gap-4 px-4 py-3"><label class="flex flex-col flex-1">
                                    <p>Ciudad</p><input name="ciudad" placeholder="Ingresa tu ciudad" class="form-input h-14" required />
                                </label></div>
                            <div class="flex max-w-[480px] gap-4 px-4 py-3"><label class="flex flex-col flex-1">
                                    <p>Departamento</p><input name="estado" placeholder="Ingresa tu departamento" class="form-input h-14" />
                                </label></div>
                            <div class="flex max-w-[480px] gap-4 px-4 py-3"><label class="flex flex-col flex-1">
                                    <p>Código Postal</p><input name="zip" placeholder="Ingresa tu código postal" class="form-input h-14" />
                                </label></div>
                            <div class="flex max-w-[480px] gap-4 px-4 py-3"><label class="flex flex-col flex-1">
                                    <p>País</p><input name="pais" placeholder="Ingresa tu país" class="form-input h-14" required />
                                </label></div>
                            <div class="flex max-w-[480px] gap-4 px-4 py-3"><label class="flex flex-col flex-1">
                                    <p>Número de Teléfono</p><input name="telefono" placeholder="Ingresa tu número de teléfono" class="form-input h-14" required />
                                </label></div>
                        </div>

                        <hr class="my-6">

                        <h2 class="text-xl font-bold px-4 py-3">Método de Pago</h2>

                        <div id="new-payment-fields">
                            <div class="flex max-w-[480px] gap-4 px-4 py-3">
                                <label class="flex flex-col flex-1">
                                    <p>Método de Pago</p>
                                    <select name="metodo" id="metodo-select" class="form-input h-14" required>
                                        <option value="">Selecciona un método de pago</option>
                                        <option value="transferencia">🏦 Transferencia Bancaria Directa</option>
                                        <option value="mercadopago">💳 Mercado Pago (Tarjetas, Saldo)</option>
                                        <option value="pse">🏛️ PSE (Pagos Seguros en Línea)</option>
                                        <option value="addi">✨ A cuotas con Addi</option>
                                    </select>
                                </label>
                            </div>
                        </div>

                        <div class="flex px-4 py-3">
                            <button type="submit" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center rounded-lg h-12 px-5 flex-1 bg-[#e68019] text-[#181411] font-bold">Continuar al Pago</button>
                        </div>
                    </form>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const addressSelect = document.getElementById('address-select');
                            const form = document.getElementById('checkout-form');

                            // Lógica para auto-rellenar dirección
                            if (addressSelect) {
                                addressSelect.addEventListener('change', function() {
                                    if (this.value === 'new') {
                                        form.direccion.value = '';
                                        form.ciudad.value = '';
                                        form.estado.value = '';
                                        form.zip.value = '';
                                        form.pais.value = '';
                                    } else {
                                        const selectedAddr = JSON.parse(this.value);
                                        form.direccion.value = selectedAddr.street_address || '';
                                        form.ciudad.value = selectedAddr.city || '';
                                        form.estado.value = selectedAddr.state || '';
                                        form.zip.value = selectedAddr.postal_code || '';
                                        form.pais.value = selectedAddr.country || '';
                                    }
                                });
                            }

                            // Lógica de envío del formulario
                            form.addEventListener('submit', function(e) {
                                e.preventDefault();
                                const formData = new FormData(this);
                                const jwt = localStorage.getItem('jwt');

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
                                        // CASO 1: Redirección a pasarela de pago
                                        if (data.redirect_url) {
                                            window.location.href = data.redirect_url;
                                            return;
                                        }

                                        // CASO 2: Éxito (ej. Transferencia bancaria)
                                        if (data.success) {
                                            let successMessage = `<p>Tu número de pedido es <strong>#${data.pedido_id}</strong></p>`;

                                            // Mensaje especial para transferencia
                                            if (data.payment_details && data.payment_details.type === 'transferencia') {
                                                successMessage += '<hr style="margin: 15px 0;"><h4>Datos para la transferencia:</h4>' +
                                                    `<p><strong>Banco:</strong> ${data.payment_details.banco}</p>` +
                                                    `<p><strong>Cuenta:</strong> ${data.payment_details.cuenta}</p>` +
                                                    `<p><strong>Titular:</strong> ${data.payment_details.titular}</p>` +
                                                    '<p style="margin-top:10px;">Por favor, envía el comprobante a nuestro email para confirmar tu pedido.</p>';
                                            }

                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Pedido Creado',
                                                html: successMessage,
                                                confirmButtonText: 'OK'
                                            }).then(() => {
                                                window.location.href = 'https://stetsonlatam.com/'; // O a una página de "gracias"
                                            });
                                        } else {
                                            // CASO 3: Error devuelto por el backend
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Error',
                                                text: data.message || 'Ocurrió un error inesperado.'
                                            });
                                        }
                                    })
                                    .catch(err => {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error de Conexión',
                                            text: 'No se pudo procesar tu solicitud. Intenta de nuevo.'
                                        });
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
</body>

</html>