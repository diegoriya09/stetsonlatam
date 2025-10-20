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
        // 1. Obtener datos básicos del usuario (nombre, email)
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

        // 2. Leer las direcciones guardadas del usuario
        $stmt_addr = $conn->prepare("SELECT id, street_address, city, state, postal_code, country FROM user_addresses WHERE user_id = ?");
        $stmt_addr->bind_param("i", $user_id);
        $stmt_addr->execute();
        $result_addr = $stmt_addr->get_result();
        while ($row = $result_addr->fetch_assoc()) {
            $saved_addresses[] = $row; // Llenamos el array
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
                                            <option value="new">-- Usar una nueva dirección --</option>
                                            <?php foreach ($saved_addresses as $addr): ?>
                                                <option value='<?php echo json_encode($addr); ?>'>
                                                    <?php echo htmlspecialchars($addr['street_address'] . ', ' . $addr['city']); ?>
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
                                <div class="flex max-w-[480px] gap-4 px-4 py-3">
                                    <label class="flex flex-col w-1/3">
                                        <p>Tipo Doc.</p>
                                        <select name="docType" class="form-input h-14" required>
                                            <option value="CC">CC</option>
                                            <option value="CE">CE</option>
                                            <option value="NIT">NIT</option>
                                            <option value="PAS">PAS</option>
                                        </select>
                                    </label>
                                    <label class="flex flex-col flex-1">
                                        <p>Número de Documento</p>
                                        <input name="docNumber" placeholder="Tu número de documento" class="form-input h-14" required />
                                    </label>
                                </div>
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

                            <div class="flex max-w-[480px] items-center gap-4 px-4 py-3">
                                <input type="checkbox" id="save-address" name="save_address" value="true" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <label for="save-address" class="block text-sm text-gray-900">Guardar esta dirección para futuras compras</label>
                            </div>
                            <div id="shipping-options-container" class="px-4 py-3" style="display: none;">
                                <h3 class="text-lg font-bold mb-2">Método de Envío</h3>
                                <div id="shipping-options-list">
                                </div>
                            </div>
                            <div id="shipping-error" class="px-4 text-red-500 font-medium"></div>

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

                    </div>
                    <aside>
                        <h2>Resumen del pedido</h2>
                        <div>
                            <span>Subtotal</span>
                            <span id="summary-subtotal">$150,000.00</span>
                        </div>
                        <div>
                            <span>Envío</span>
                            <span id="summary-shipping">--</span>
                        </div>
                        <hr>
                        <div>
                            <span>Total</span>
                            <span id="summary-total">$150,000.00</span>
                        </div>
                    </aside>
                </div>
                <?php include 'footer.php'; ?>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        // --- SELECCIÓN DE ELEMENTOS ---
                        const form = document.getElementById('checkout-form');
                        const addressSelect = document.getElementById('address-select');
                        const cityInput = document.querySelector('input[name="ciudad"]');
                        const departmentInput = document.querySelector('input[name="estado"]');

                        const shippingCostEl = document.getElementById('summary-shipping');
                        const totalEl = document.getElementById('summary-total');
                        const subtotalEl = document.getElementById('summary-subtotal');

                        const shippingContainer = document.getElementById('shipping-options-container');
                        const shippingList = document.getElementById('shipping-options-list');
                        const shippingError = document.getElementById('shipping-error');

                        let currentShippingCost = 0;
                        // Simulación del subtotal del carrito. En una implementación real,
                        // este valor vendría de otra llamada a la API o se pasaría desde la página anterior.
                        let cartSubtotal = 150000;
                        subtotalEl.textContent = formatCurrency(cartSubtotal);
                        updateTotal();

                        // --- FUNCIONES AUXILIARES ---
                        function formatCurrency(value) {
                            return `$${parseFloat(value).toLocaleString('es-CO', { minimumFractionDigits: 0, maximumFractionDigits: 0 })}`;
                        }

                        function updateTotal() {
                            const subtotal = cartSubtotal;
                            const newTotal = subtotal + currentShippingCost;
                            totalEl.textContent = formatCurrency(newTotal);
                        }

                        // --- FUNCIÓN PRINCIPAL PARA OBTENER TARIFA DE ENVÍO ---
                        async function getShippingRate() {
                            const department = departmentInput.value;

                            if (department.trim().length < 3) {
                                shippingCostEl.textContent = '--';
                                shippingContainer.style.display = 'none';
                                shippingError.textContent = '';
                                currentShippingCost = 0;
                                updateTotal();
                                return;
                            }

                            shippingCostEl.textContent = 'Calculando...';
                            shippingError.textContent = '';

                            try {
                                // Usamos la ruta amigable del .htaccess
                                const res = await fetch(`/php/shipping-rate?departamento=${encodeURIComponent(department)}`);
                                const data = await res.json();

                                if (data.success) {
                                    if (data.requires_quote) {
                                        shippingError.textContent = data.message;
                                        shippingContainer.style.display = 'none';
                                        currentShippingCost = 0;
                                    } else {
                                        currentShippingCost = parseFloat(data.price);
                                        shippingCostEl.textContent = formatCurrency(currentShippingCost);
                                        shippingList.innerHTML = `
                            <div style="display: flex; justify-content: space-between; border: 1px solid #e5e5e5; padding: 12px; border-radius: 8px;">
                                <label for="shipping_rate_std">Envío Estándar</label>
                                <span>${formatCurrency(currentShippingCost)}</span>
                                <input type="radio" id="shipping_rate_std" name="shipping_option" value="${currentShippingCost}" checked style="display:none;">
                            </div>`;
                                        shippingContainer.style.display = 'block';
                                    }
                                } else {
                                    shippingError.textContent = data.message;
                                    shippingContainer.style.display = 'none';
                                    currentShippingCost = 0;
                                }
                            } catch (error) {
                                shippingError.textContent = 'Error al calcular el envío.';
                                shippingContainer.style.display = 'none';
                                currentShippingCost = 0;
                            }
                            updateTotal();
                        }

                        // --- ASIGNACIÓN DE EVENTOS ---

                        // 1. Calcular envío cuando se llene el campo "Departamento"
                        if (departmentInput) {
                            departmentInput.addEventListener('blur', getShippingRate);
                        }

                        // 2. Autocompletar formulario si se elige una dirección guardada
                        if (addressSelect) {
                            addressSelect.addEventListener('change', function() {
                                // Limpia todos los campos de dirección antes de llenarlos
                                ['direccion', 'ciudad', 'estado', 'zip', 'pais'].forEach(name => {
                                    const field = form.querySelector(`[name="${name}"]`);
                                    if (field) field.value = '';
                                });

                                if (this.value !== 'new') {
                                    const selectedAddr = JSON.parse(this.value);
                                    form.direccion.value = selectedAddr.street_address || '';
                                    form.ciudad.value = selectedAddr.city || '';
                                    form.estado.value = selectedAddr.state || '';
                                    form.zip.value = selectedAddr.postal_code || '';
                                    form.pais.value = selectedAddr.country || '';

                                    // Disparamos el evento 'blur' en el campo de departamento para que recalcule el envío
                                    if (departmentInput) {
                                        departmentInput.dispatchEvent(new Event('blur'));
                                    }
                                }
                            });
                        }

                        // 3. Lógica de envío del formulario
                        if (form) {
                            form.addEventListener('submit', function(e) {
                                e.preventDefault();
                                const formData = new FormData(this);
                                const jwt = localStorage.getItem('jwt');
                                if (!jwt) {
                                    Swal.fire('Error', 'Debes iniciar sesión para completar la compra.', 'error');
                                    return;
                                }

                                // Añadimos el costo de envío al FormData que se envía al backend
                                const selectedShipping = form.querySelector('input[name="shipping_option"]:checked');
                                if (selectedShipping) {
                                    formData.append('shipping_cost', selectedShipping.value);
                                } else {
                                    formData.append('shipping_cost', '0');
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
                                        if (data.redirect_url) {
                                            window.location.href = data.redirect_url;
                                        } else {
                                            Swal.fire('Error', data.message || 'Ocurrió un error inesperado.', 'error');
                                        }
                                    })
                                    .catch(err => {
                                        Swal.fire('Error', 'No se pudo procesar tu solicitud. Intenta de nuevo.', 'error');
                                    });
                            });
                        }
                    });
                </script>
            </div>
        </div>
        <?php include 'modal.php'; ?>
    </body>

    </html>