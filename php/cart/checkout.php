<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../conexion.php';
require_once '../../vendor/autoload.php';

if (session_status() !== PHP_SESSION_ACTIVE) session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    header('Content-Type: application/json');

    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        echo json_encode(["success" => false, "message" => "Token CSRF inválido"]);
        exit;
    }

    $nombre = $_POST['nombre'] ?? '';
    $email = $_POST['email'] ?? '';
    $pais = $_POST['pais'] ?? '';
    $ciudad = $_POST['ciudad'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $metodo = $_POST['metodo'] ?? '';
    $numero_tarjeta = $_POST['numero_tarjeta'] ?? '';
    $nombre_tarjeta = $_POST['nombre_tarjeta'] ?? '';
    $expiracion = $_POST['expiracion'] ?? '';
    $cvv = $_POST['cvv'] ?? '';
    $banco_pse = $_POST['banco_pse'] ?? '';
    $tipo_cuenta_pse = $_POST['tipo_cuenta_pse'] ?? '';
    $documento_pse = $_POST['documento_pse'] ?? '';
    $csrf_token = $_POST['csrf_token'] ?? '';

    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    if (!$user_id) {
        echo json_encode(["success" => false, "message" => "You must be logged in to checkout"]);
        exit;
    }


    $conn->begin_transaction();
    // Leer carrito desde DB si logueado (sino usar localStorage y enviarlo desde JS)
    try {
        // 1. Leer el carrito del usuario
        $stmt_cart = $conn->prepare("
            SELECT c.*, p.name AS nombre, p.price AS precio, col.name AS color_nombre, s.name AS size_nombre
            FROM cart c
            JOIN productos p ON c.producto_id = p.id
            LEFT JOIN colors col ON c.color_id = col.id
            LEFT JOIN sizes s ON c.size_id = s.id
            WHERE c.users_id = ? FOR UPDATE; -- Bloquear las filas para evitar concurrencia
        ");
        $stmt_cart->bind_param("i", $user_id);
        $stmt_cart->execute();
        $result = $stmt_cart->get_result();
        $items = $result->fetch_all(MYSQLI_ASSOC);
        $stmt_cart->close();

        if (count($items) === 0) {
            throw new Exception("No hay productos en el carrito");
        }

        // 2. NUEVO: Verificación final de stock y preparación para descontar
        $total = 0;
        $productos_sin_stock = [];
        foreach ($items as $item) {
            $stmt_stock = $conn->prepare("SELECT stock FROM product_variants WHERE product_id = ? AND color_id = ? AND size_id = ? FOR UPDATE");
            $stmt_stock->bind_param("iii", $item['producto_id'], $item['color_id'], $item['size_id']);
            $stmt_stock->execute();
            $variant_stock_result = $stmt_stock->get_result()->fetch_assoc();
            $stmt_stock->close();

            if (!$variant_stock_result || $item['quantity'] > $variant_stock_result['stock']) {
                $productos_sin_stock[] = $item['nombre'];
            }
            $total += $item['precio'] * $item['quantity'];
        }

        if (!empty($productos_sin_stock)) {
            $nombres = implode(', ', $productos_sin_stock);
            throw new Exception("Lo sentimos, no hay suficiente stock para los siguientes productos: {$nombres}. Por favor, ajusta tu carrito.");
        }

        // 3. NUEVO: Descontar el stock
        $stmt_decrement = $conn->prepare("UPDATE product_variants SET stock = stock - ? WHERE product_id = ? AND color_id = ? AND size_id = ?");
        foreach ($items as $item) {
            $stmt_decrement->bind_param("iiii", $item['quantity'], $item['producto_id'], $item['color_id'], $item['size_id']);
            $stmt_decrement->execute();
        }
        $stmt_decrement->close();

        // 4. Guardar pedido en la tabla 'pedidos' (tu código existente)
        $nombre = $_POST['nombre'] ?? '';
        $email = $_POST['email'] ?? '';
        $pais = $_POST['pais'] ?? '';
        $ciudad = $_POST['ciudad'] ?? '';
        $direccion = $_POST['direccion'] ?? '';
        $telefono = $_POST['telefono'] ?? '';
        $metodo = $_POST['metodo'] ?? '';
        $stmt_order = $conn->prepare("INSERT INTO pedidos (user_id, total, estado, nombre_cliente, email_cliente, pais, ciudad, direccion, telefono, metodo_pago) VALUES (?, ?, 'Pending', ?, ?, ?, ?, ?, ?, ?)");
        $stmt_order->bind_param("idssssssss", $user_id, $total, $nombre, $email, $pais, $ciudad, $direccion, $telefono, $metodo);
        $stmt_order->execute();
        $pedido_id = $conn->insert_id;
        $stmt_order->close();

        // 5. Guardar detalle de pedido (tu código existente)
        $stmt_detail = $conn->prepare("INSERT INTO pedido_detalle (pedido_id, producto_id, nombre_producto, precio, cantidad, color_id, color_nombre, size_id, size_nombre) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($items as $item) {
            $stmt_detail->bind_param("iisdiisis", $pedido_id, $item['producto_id'], $item['nombre'], $item['precio'], $item['quantity'], $item['color_id'], $item['color_nombre'], $item['size_id'], $item['size_nombre']);
            $stmt_detail->execute();
        }
        $stmt_detail->close();

        // 6. Vaciar carrito
        $stmt_clear = $conn->prepare("DELETE FROM cart WHERE users_id = ?");
        $stmt_clear->bind_param("i", $user_id);
        $stmt_clear->execute();
        $stmt_clear->close();

        // Si todo fue exitoso, confirmar la transacción
        $conn->commit();

        echo json_encode(["success" => true, "message" => "¡Pedido creado correctamente!", "pedido_id" => $pedido_id]);
    } catch (Exception $e) {
        // Si algo falla, revertir todos los cambios
        $conn->rollback();
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
    $conn->close();
    exit;
}
