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
        echo json_encode(["success" => false, "message" => "Usuario no autenticado"]);
        exit;
    }

    // Leer carrito desde DB si logueado (sino usar localStorage y enviarlo desde JS)
    $stmt = $conn->prepare("
    SELECT 
        c.*, 
        p.name AS nombre, 
        p.image AS imagen, 
        p.price AS precio,
        col.name AS color_nombre,
        s.name AS size_nombre
    FROM cart c
    JOIN productos p ON c.producto_id = p.id
    LEFT JOIN colors col ON c.color_id = col.id
    LEFT JOIN sizes s ON c.size_id = s.id
    WHERE c.users_id = ?
");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $items = [];
    $total = 0;

    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
        $total += $row['precio'] * $row['quantity'];
    }

    if (count($items) === 0) {
        echo json_encode(["success" => false, "message" => "No hay productos en el carrito"]);
        exit;
    }

    // Validar stock antes de proceder
    foreach ($items as $item) {
        $stmtStock = $conn->prepare("SELECT cantidad_disponible FROM productos WHERE id = ?");
        $stmtStock->bind_param("i", $item['producto_id']);
        $stmtStock->execute();
        $stockRes = $stmtStock->get_result();
        $stock = $stockRes->fetch_assoc();

        if (!$stock || $stock['cantidad_disponible'] < $item['quantity']) {
            echo json_encode([
                "success" => false,
                "message" => "Stock insuficiente para el producto: " . $item['nombre']
            ]);
            exit;
        }
    }

    // Guardar pedido en tabla 'pedidos'
    $stmt = $conn->prepare("
    INSERT INTO pedidos 
    (user_id, total, estado, nombre_cliente, email_cliente, pais, ciudad, direccion, telefono, metodo_pago)
    VALUES (?, ?, 'Pendiente', ?, ?, ?, ?, ?, ?, ?)
");
    $stmt->bind_param("idsssssss", $user_id, $total, $nombre, $email, $pais, $ciudad, $direccion, $telefono, $metodo);
    if (!$stmt->execute()) {
        echo json_encode(["success" => false, "message" => "Error al crear pedido"]);
        exit;
    }

    $pedido_id = $conn->insert_id;

    // Guardar detalle de pedido
    $stmt = $conn->prepare("INSERT INTO pedido_detalle (pedido_id, producto_id, nombre_producto, precio, cantidad, color_id, color_nombre, size_id, size_nombre) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

    foreach ($items as $item) {
        $stmt->bind_param(
            "iisdiisis",
            $pedido_id,
            $item['producto_id'],
            $item['nombre'],
            $item['precio'],
            $item['cantidad'],
            $item['color_id'],
            $item['color_nombre'],
            $item['size_id'],
            $item['size_nombre']
        );

        if (!$stmt->execute()) {
            die("Error insertando detalle del pedido: " . $stmt->error);
        }

        // Descontar del inventario
        $stmtStockUpdate = $conn->prepare("UPDATE productos SET cantidad_disponible = cantidad_disponible - ? WHERE id = ?");
        $stmtStockUpdate->bind_param("ii", $item['quantity'], $item['producto_id']);
        $stmtStockUpdate->execute();
    }

    // Vaciar carrito
    $stmt = $conn->prepare("DELETE FROM cart WHERE users_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    echo json_encode([
        "success" => true,
        "message" => "¡Pago procesado y pedido creado exitosamente!",
        "pedido_id" => $pedido_id
    ]);
    exit;
}
