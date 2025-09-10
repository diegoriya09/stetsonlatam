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
    <link href="css/index.css?v=<?php echo time(); ?>" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .save-option {
            margin-top: 15px;
            display: flex;
            align-items: center;
        }

        .save-option input {
            margin-right: 10px;
        }
    </style>
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

                        <div id="save-address-option" class="save-option px-4 py-3">
                            <input type="checkbox" id="save-address" name="save_address" value="true">
                            <label for="save-address">Guardar esta dirección para futuras compras</label>
                        </div>

                        <hr class="my-6">

                        <h2 class="text-xl font-bold px-4 py-3">Método de Pago</h2>

                        <?php if (!empty($saved_payment_methods)): ?>
                            <div class="flex max-w-[480px] gap-4 px-4 py-3">
                                <label class="flex flex-col flex-1">
                                    <p>Elige un método de pago guardado</p>
                                    <select name="payment_id" id="payment-select" class="form-input h-14">
                                        <option value="new">-- Añadir un nuevo método de pago --</option>
                                        <?php foreach ($saved_payment_methods as $pay): ?>
                                            <option value='<?php echo json_encode($pay); ?>'>
                                                <?php echo htmlspecialchars(ucfirst($pay['card_type']) . ' terminada en ' . $pay['last_four_digits'] . ' (Expira: ' . $pay['expiry_date'] . ')'); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </label>
                            </div>
                        <?php endif; ?>

                        <div id="new-payment-fields">
                            <div class="flex max-w-[480px] gap-4 px-4 py-3">
                                <label class="flex flex-col flex-1">
                                    <p>Método de Pago</p>
                                    <select name="metodo" id="metodo-select" class="form-input h-14" required>
                                        <option value="">Selecciona un método de pago</option>
                                        <option value="tarjeta">Añadir Tarjeta de Crédito/Débito</option>
                                        <option value="pse">Añadir PSE</option>
                                    </select>
                                </label>
                            </div>
                            <div id="tarjeta-fields" class="hidden">
                                <div class="flex max-w-[480px] gap-4 px-4 py-3"><label class="flex flex-col flex-1">
                                        <p>Número de Tarjeta</p><input name="numero_tarjeta" placeholder="1234 5678 9012 3456" class="form-input h-14" />
                                    </label></div>
                                <div class="flex max-w-[480px] gap-4 px-4 py-3"><label class="flex flex-col flex-1">
                                        <p>Nombre en la Tarjeta</p><input name="nombre_tarjeta" placeholder="John Doe" class="form-input h-14" />
                                    </label></div>
                                <div class="flex max-w-[480px] gap-4 px-4 py-3"><label class="flex flex-col flex-1">
                                        <p>Fecha de Expiración</p><input name="expiracion" placeholder="MM/YY" class="form-input h-14" />
                                    </label><label class="flex flex-col flex-1">
                                        <p>CVV</p><input name="cvv" placeholder="123" class="form-input h-14" />
                                    </label></div>
                            </div>
                            <div id="pse-fields" class="hidden">
                                <div class="flex max-w-[480px] gap-4 px-4 py-3"><label class="flex flex-col flex-1">
                                        <p>Banco</p><input name="banco_pse" placeholder="Ingresa el nombre del banco" class="form-input h-14" />
                                    </label></div>
                                <div class="flex max-w-[480px] gap-4 px-4 py-3"><label class="flex flex-col flex-1">
                                        <p>Tipo de Cuenta</p><input name="tipo_cuenta_pse" placeholder="Ahorros/Corriente" class="form-input h-14" />
                                    </label></div>
                                <div class="flex max-w-[480px] gap-4 px-4 py-3"><label class="flex flex-col flex-1">
                                        <p>Documento</p><input name="documento_pse" placeholder="Ingresa tu documento" class="form-input h-14" />
                                    </label></div>
                            </div>
                        </div>

                        <div id="save-payment-option" class="save-option px-4 py-3">
                            <input type="checkbox" id="save-payment" name="save_payment" value="true">
                            <label for="save-payment">Guardar este método de pago para futuras compras</label>
                        </div>

                        <div class="flex px-4 py-3">
                            <button type="submit" class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center rounded-lg h-12 px-5 flex-1 bg-[#e68019] text-[#181411] font-bold">Continuar al Pago</button>
                        </div>
                    </form>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const addressSelect = document.getElementById('address-select');
                            const paymentSelect = document.getElementById('payment-select');
                            const form = document.getElementById('checkout-form');
                            const saveAddressCheckboxContainer = document.getElementById('save-address')?.parentElement;
                            const savePaymentCheckboxContainer = document.getElementById('save-payment')?.parentElement;

                            // Lógica para auto-rellenar dirección
                            if (addressSelect) {
                                addressSelect.addEventListener('change', function() {
                                    if (this.value === 'new') {
                                        form.direccion.value = '';
                                        form.ciudad.value = '';
                                        form.estado.value = '';
                                        form.zip.value = '';
                                        form.pais.value = '';
                                        saveAddressCheckboxContainer.style.display = 'flex';
                                    } else {
                                        const selectedAddr = JSON.parse(this.value);
                                        form.direccion.value = selectedAddr.street_address || '';
                                        form.ciudad.value = selectedAddr.city || '';
                                        form.estado.value = selectedAddr.state || '';
                                        form.zip.value = selectedAddr.postal_code || '';
                                        form.pais.value = selectedAddr.country || '';
                                        saveAddressCheckboxContainer.style.display = 'none';
                                    }
                                });
                            }

                            // Lógica para auto-rellenar método de pago
                            if (paymentSelect) {
                                paymentSelect.addEventListener('change', function() {
                                    if (this.value === 'new') {
                                        document.getElementById('new-payment-fields').style.display = 'block';
                                        document.getElementById('metodo-select').required = true;
                                        savePaymentCheckboxContainer.style.display = 'flex';
                                    } else {
                                        const selectedPay = JSON.parse(this.value);
                                        // Ocultar los campos de pago manual
                                        document.getElementById('new-payment-fields').style.display = 'none';
                                        document.getElementById('metodo-select').required = false;
                                        // Aquí no rellenamos nada, el ID del método se enviará
                                        // con el select payment_id
                                        savePaymentCheckboxContainer.style.display = 'none';
                                    }
                                });
                            }

                            // Lógica para mostrar campos de tarjeta/pse
                            document.querySelector('select[name="metodo"]').addEventListener('change', function() {
                                document.getElementById('tarjeta-fields').classList.add('hidden');
                                document.getElementById('pse-fields').classList.add('hidden');
                                if (this.value === 'tarjeta') {
                                    document.getElementById('tarjeta-fields').classList.remove('hidden');
                                } else if (this.value === 'pse') {
                                    document.getElementById('pse-fields').classList.remove('hidden');
                                }
                            });

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
                                    .then(res => {
                                        if (!res.ok) {
                                            return res.json().then(errorData => {
                                                throw new Error(errorData.message || 'Error del servidor');
                                            });
                                        }
                                        return res.json();
                                    })
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
                                            text: err.message || 'Algo salió mal. Intenta de nuevo.'
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
    <script src="js/auth.js?v=<?php echo time(); ?>"></script>
    <script src="js/index.js?v=<?php echo time(); ?>"></script>
</body>

</html>