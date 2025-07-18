<?php
require_once 'php/conexion.php';
require_once '../../vendor/autoload.php';

if (session_status() !== PHP_SESSION_ACTIVE) session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Token CSRF inválido.");
    }

    // Simulación de pago:
    $nombre     = trim(strip_tags($_POST['nombre']));
    $email      = trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
    $pais       = $_POST['pais'];
    $ciudad     = trim(strip_tags($_POST['ciudad']));
    $direccion  = trim(strip_tags($_POST['direccion']));
    $telefono   = trim(strip_tags($_POST['telefono']));
    $metodo     = $_POST['metodo'];

    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    if (!$user_id) die("Usuario no autenticado");

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
    WHERE c.user_id = ?
");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $items = [];
    $total = 0;

    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
        $total += $row['precio'] * $row['cantidad'];
    }

    if (count($items) === 0) {
        die("No hay productos en el carrito");
    }

    // Guardar pedido en tabla 'pedidos'
    $stmt = $conn->prepare("
    INSERT INTO pedidos 
    (user_id, total, estado, nombre_cliente, email_cliente, pais, ciudad, direccion, telefono, metodo_pago)
    VALUES (?, ?, 'Pendiente', ?, ?, ?, ?, ?, ?, ?)
");
    $stmt->bind_param("idsssssss", $user_id, $total, $nombre, $email, $pais, $ciudad, $direccion, $telefono, $metodo);
    if (!$stmt->execute()) die("Error al crear pedido");

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
    }

    // Vaciar carrito
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    echo json_encode([
        "success" => true,
        "message" => "¡Pago procesado y pedido creado exitosamente!",
        "pedido_id" => $pedido_id
    ]);
    exit;
}
