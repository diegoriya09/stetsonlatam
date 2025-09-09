<?php
// php/cart/checkout.php (COMPLETO Y ADAPTADO A TU BASE DE DATOS)

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../conexion.php';
require_once '../../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header('Content-Type: application/json');

function getAuthorizationHeader()
{
    if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
        return trim($_SERVER["HTTP_AUTHORIZATION"]);
    }
    if (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
        return trim($_SERVER["REDIRECT_HTTP_AUTHORIZATION"]);
    }
    if (function_exists('apache_request_headers')) {
        $requestHeaders = apache_request_headers();
        if (isset($requestHeaders['Authorization'])) {
            return trim($requestHeaders['Authorization']);
        }
    }
    return null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $transaction_started = false;
    try {
        $authHeader = getAuthorizationHeader();
        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            throw new Exception('Token no proporcionado o formato inválido.');
        }
        $jwt = trim(str_replace('Bearer', '', $authHeader));
        $secret_key = "StetsonLatam1977";
        $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
        $user_id = $decoded->data->id;

        // --- Lógica para guardar dirección y/o método de pago (ADAPTADA A TU SCHEMA) ---

        // Si se marcó "guardar dirección" y se está usando una dirección nueva
        if (isset($_POST['save_address']) && $_POST['save_address'] === 'true' && (!isset($_POST['address_id']) || $_POST['address_id'] === 'new')) {
            $street_address = $_POST['direccion'] ?? '';
            $city = $_POST['ciudad'] ?? '';
            $state = $_POST['estado'] ?? '';
            $postal_code = $_POST['zip'] ?? '';
            $country = $_POST['pais'] ?? '';

            $stmt_save_addr = $conn->prepare("INSERT INTO user_addresses (user_id, street_address, city, state, postal_code, country) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt_save_addr->bind_param("isssss", $user_id, $street_address, $city, $state, $postal_code, $country);
            $stmt_save_addr->execute();
            $stmt_save_addr->close();
        }

        // Si se marcó "guardar método de pago" y es una tarjeta nueva
        if (isset($_POST['save_payment']) && $_POST['save_payment'] === 'true' && (!isset($_POST['payment_id']) || $_POST['payment_id'] === 'new') && isset($_POST['metodo']) && $_POST['metodo'] === 'tarjeta') {
            $numero_tarjeta = preg_replace('/\D/', '', $_POST['numero_tarjeta'] ?? '');
            $last_four_digits = substr($numero_tarjeta, -4);
            $expiry_date = $_POST['expiracion'] ?? '';
            $card_type = 'Tarjeta'; // Puedes mejorarlo para detectar Visa, Mastercard, etc.

            $stmt_save_pay = $conn->prepare("INSERT INTO user_payment_methods (user_id, card_type, last_four_digits, expiry_date) VALUES (?, ?, ?, ?)");
            $stmt_save_pay->bind_param("isss", $user_id, $card_type, $last_four_digits, $expiry_date);
            $stmt_save_pay->execute();
            $stmt_save_pay->close();
        }

        $conn->begin_transaction();
        $transaction_started = true;

        // --- El resto de tu lógica de checkout (verificar stock, crear pedido, etc.) ---
        // (Esta parte no necesita cambios)

        $stmt_cart = $conn->prepare("SELECT c.*, p.name AS nombre, p.price AS precio, col.name AS color_nombre, s.name AS size_nombre FROM cart c JOIN productos p ON c.producto_id = p.id LEFT JOIN colors col ON c.color_id = col.id LEFT JOIN sizes s ON c.size_id = s.id WHERE c.users_id = ? FOR UPDATE");
        $stmt_cart->bind_param("i", $user_id);
        $stmt_cart->execute();
        $items = $stmt_cart->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt_cart->close();

        if (count($items) === 0) {
            throw new Exception("Tu carrito está vacío.");
        }

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
            throw new Exception("Lo sentimos, no hay suficiente stock para los siguientes productos: {$nombres}.");
        }

        $stmt_decrement = $conn->prepare("UPDATE product_variants SET stock = stock - ? WHERE product_id = ? AND color_id = ? AND size_id = ?");
        foreach ($items as $item) {
            $stmt_decrement->bind_param("iiii", $item['quantity'], $item['producto_id'], $item['color_id'], $item['size_id']);
            $stmt_decrement->execute();
        }
        $stmt_decrement->close();

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

        $stmt_detail = $conn->prepare("INSERT INTO pedido_detalle (pedido_id, producto_id, nombre_producto, precio, cantidad, color_id, color_nombre, size_id, size_nombre) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($items as $item) {
            $stmt_detail->bind_param("iisdiisis", $pedido_id, $item['producto_id'], $item['nombre'], $item['precio'], $item['quantity'], $item['color_id'], $item['color_nombre'], $item['size_id'], $item['size_nombre']);
            $stmt_detail->execute();
        }
        $stmt_detail->close();

        $stmt_clear = $conn->prepare("DELETE FROM cart WHERE users_id = ?");
        $stmt_clear->bind_param("i", $user_id);
        $stmt_clear->execute();
        $stmt_clear->close();

        $conn->commit();
        $transaction_started = false;

        echo json_encode(["success" => true, "message" => "¡Pedido creado correctamente!", "pedido_id" => $pedido_id]);
    } catch (Exception $e) {
        if ($transaction_started) {
            $conn->rollback();
        }
        http_response_code(400);
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    } finally {
        if (isset($conn) && $conn->ping()) {
            $conn->close();
        }
    }
    exit;
}
