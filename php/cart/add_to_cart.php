<?php
// add_to_cart.php (COMPLETO Y CORREGIDO)
require_once '../conexion.php';
require_once '../../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

// --- Bloque de Autenticación (sin cambios) ---
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
$authHeader = getAuthorizationHeader();
if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
    echo json_encode(['success' => false, 'message' => 'Token no proporcionado']);
    exit;
}
$jwt = trim(str_replace('Bearer', '', $authHeader));
$jwt = ltrim($jwt);
$secret_key = "StetsonLatam1977";
try {
    $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
    $user_id = $decoded->data->id;
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Token inválido', 'error' => $e->getMessage()]);
    exit;
}
// --- Fin Bloque de Autenticación ---

// Obtener y limpiar datos del body
$data = json_decode(file_get_contents('php://input'), true);
$producto_id = isset($data['producto_id']) ? (int)$data['producto_id'] : 0;
$quantity_to_add = isset($data['quantity']) ? (int)$data['quantity'] : 0;
$color_id = isset($data['color_id']) ? (int)$data['color_id'] : 0;
$size_id = isset($data['size_id']) ? (int)$data['size_id'] : 0;

if ($producto_id <= 0 || $quantity_to_add <= 0 || $color_id <= 0 || $size_id <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Todos los campos son requeridos.']);
    exit;
}

// --- LÓGICA DE CARRITO REFACTORIZADA ---
try {
    // 1. Obtener el stock disponible de la variante
    $stmt_stock = $conn->prepare("SELECT stock FROM product_variants WHERE product_id = ? AND color_id = ? AND size_id = ?");
    $stmt_stock->bind_param("iii", $producto_id, $color_id, $size_id);
    $stmt_stock->execute();
    $result_stock = $stmt_stock->get_result();

    if ($result_stock->num_rows === 0) {
        throw new Exception('Esta variante del producto no existe o está fuera de stock.');
    }
    $available_stock = $result_stock->fetch_assoc()['stock'];
    $stmt_stock->close();

    // 2. Buscar si el item YA EXISTE en el carrito
    $stmt_check = $conn->prepare("SELECT id, quantity FROM cart WHERE users_id = ? AND producto_id = ? AND color_id = ? AND size_id = ?");
    $stmt_check->bind_param("iiii", $user_id, $producto_id, $color_id, $size_id);
    $stmt_check->execute();
    $cart_item = $stmt_check->get_result()->fetch_assoc();
    $stmt_check->close();

    $quantity_in_cart = $cart_item ? $cart_item['quantity'] : 0;

    // 3. Validar stock
    $total_desired_quantity = $quantity_in_cart + $quantity_to_add;
    if ($total_desired_quantity > $available_stock) {
        $remaining_stock = $available_stock - $quantity_in_cart;
        $message = $available_stock > 0 ? "No hay suficiente stock. Solo puedes añadir " . ($remaining_stock > 0 ? $remaining_stock : 0) . " unidad(es) más." : "Producto agotado.";
        throw new Exception($message);
    }

    // 4. Decidir si ACTUALIZAR o INSERTAR
    if ($cart_item) {
        // El item ya existe, ACTUALIZAR
        $stmt_update = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
        $stmt_update->bind_param("ii", $total_desired_quantity, $cart_item['id']);
        $stmt_update->execute();
        $stmt_update->close();
        echo json_encode(['success' => true, 'message' => 'Cantidad actualizada en el carrito.']);
    } else {
        // El item es nuevo, INSERTAR
        $stmt_insert = $conn->prepare("INSERT INTO cart (users_id, producto_id, quantity, color_id, size_id) VALUES (?, ?, ?, ?, ?)");
        $stmt_insert->bind_param("iiiii", $user_id, $producto_id, $quantity_to_add, $color_id, $size_id);
        $stmt_insert->execute();
        $stmt_insert->close();
        echo json_encode(['success' => true, 'message' => 'Producto añadido al carrito.']);
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn->close();
