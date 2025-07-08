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

<div id="checkout-modal" class="modalcheckout" style="display:none;">
    <div class="modal-content-checkout">
        <span class="close-modal" id="cerrar-checkout">&times;</span>
        <h2>Checkout</h2>
        <form id="checkout-form">
            <label>Full Name:<br>
                <input type="text" name="nombre" required>
            </label><br>
            <label>Email:<br>
                <input type="email" name="email" required>
            </label><br>
            <label>Country:<br>
                <select name="pais" id="pais-select" required>
                    <option value="">Select</option>
                    <option value="CO" data-code="+57">Colombia</option>
                    <option value="MX" data-code="+52">México</option>
                    <option value="US" data-code="+1">Estados Unidos</option>
                    <option value="AR" data-code="+54">Argentina</option>
                    <!-- Agrega más países según necesites -->
                </select>
            </label><br>
            <label>City:<br>
                <input type="text" name="ciudad" required>
            </label><br>
            <label>Address:<br>
                <input type="text" name="direccion" required>
            </label><br>
            <label>Phone:<br>
                <div style="display:flex;align-items:center;">
                    <span id="codigo-pais" style="margin-right:6px;">+57</span>
                    <input type="number" name="telefono" required style="flex:1;">
                </div>
            </label><br>
            <label>Payment method:<br>
                <select name="metodo" required>
                    <option value="">Select</option>
                    <option value="pse">PSE (Simulated)</option>
                    <option value="tarjeta">Card (Simulated)</option>
                </select>
            </label><br>
            <!-- Campos de tarjeta aquí -->
            <div id="tarjeta-fields" style="display:none; margin-top:1rem;">
                <label>Card number:<br>
                    <input type="text" name="numero_tarjeta" maxlength="19" placeholder="1234 5678 9012 3456">
                </label><br>
                <label>Name on card:<br>
                    <input type="text" name="nombre_tarjeta" placeholder="Como aparece en la tarjeta">
                </label><br>
                <label>Expiration date:<br>
                    <input type="text" name="expiracion" maxlength="5" placeholder="MM/AA">
                </label><br>
                <label>CVV:<br>
                    <input type="text" name="cvv" maxlength="4" placeholder="123">
                </label>
            </div>
            <div id="pse-fields" style="display:none; margin-top:1rem;">
                <label>Bank:<br>
                    <select name="banco_pse">
                        <option value="">Select your bank</option>
                        <option value="bancolombia">Bancolombia</option>
                        <option value="davivienda">Davivienda</option>
                        <option value="bbva">BBVA</option>
                        <!-- Agrega más bancos si lo deseas -->
                    </select>
                </label><br>
                <label>Account type:<br>
                    <select name="tipo_cuenta_pse">
                        <option value="">Select</option>
                        <option value="ahorro">Savings</option>
                        <option value="corriente">Checking</option>
                    </select>
                </label><br>
                <label>Document number:<br>
                    <input type="text" name="documento_pse" placeholder="ID or NIT">
                </label>
            </div>
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <button type="submit" class="pagar-btn">Pay</button>
        </form>
        <div id="checkout-confirm" style="display:none; margin-top:1rem;"></div>
    </div>
</div>