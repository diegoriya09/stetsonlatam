<?php
require_once '../conexion.php';
require_once '../../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

// --- INICIO: Bloque de Autenticación CORREGIDO ---
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
// --- FIN: Bloque de Autenticación ---

// Obtener datos del body
$data = json_decode(file_get_contents('php://input'), true);
error_log(print_r($data, true));
$producto_id = $data['producto_id'] ?? null;
$quantity = $data['quantity'] ?? 1;
$color_id = isset($data['color_id']) ? intval($data['color_id']) : null;
$size_id = isset($data['size_id']) ? intval($data['size_id']) : null;

if (!$producto_id || $quantity < 1 || !$color_id || !$size_id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    exit;
}

// 1. Obtener el stock actual de la variante específica
$stmt_stock = $conn->prepare("SELECT stock FROM product_variants WHERE product_id = ? AND color_id = ? AND size_id = ?");
$stmt_stock->bind_param("iii", $producto_id, $color_id, $size_id);
$stmt_stock->execute();
$result_stock = $stmt_stock->get_result();

if ($result_stock->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Esta variante del producto no existe o está fuera de stock.']);
    exit;
}
$variant = $result_stock->fetch_assoc();
$available_stock = $variant['stock'];
$stmt_stock->close();

// 2. Obtener la cantidad que el usuario ya tiene en el carrito (si la tiene)
$quantity_in_cart = 0;
$stmt_check = $conn->prepare("SELECT quantity FROM cart WHERE users_id = ? AND producto_id = ? AND color_id = ? AND size_id = ?");
$stmt_check->bind_param("iiii", $user_id, $producto_id, $color_id, $size_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();
if ($result_check->num_rows > 0) {
    $cart_item = $result_check->fetch_assoc();
    $quantity_in_cart = $cart_item['quantity'];
}
$stmt_check->close();

// 3. Validar si la cantidad total deseada supera el stock
$total_desired_quantity = $quantity_in_cart + $quantity;
if ($total_desired_quantity > $available_stock) {
    $remaining_stock = $available_stock - $quantity_in_cart;
    $message = $available_stock > 0 ? "No hay suficiente stock. Solo puedes añadir {$remaining_stock} unidad(es) más de este producto." : "Producto agotado.";
    echo json_encode(['success' => false, 'message' => $message]);
    exit;
}

// Si la validación de stock es exitosa, procedemos a insertar o actualizar
if ($quantity_in_cart > 0) {
    // Ya existe, actualizar cantidad
    $sql_update = "UPDATE cart SET quantity = ? WHERE users_id = ? AND producto_id = ? AND color_id = ? AND size_id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("iiiii", $total_desired_quantity, $user_id, $producto_id, $color_id, $size_id);
    $stmt_update->execute();
    $stmt_update->close();
    echo json_encode(['success' => true, 'message' => 'Cantidad actualizada']);
} else {
    // No existe, insertar
    $sql_insert = "INSERT INTO cart (users_id, producto_id, quantity, color_id, size_id) VALUES (?, ?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("iiiii", $user_id, $producto_id, $quantity, $color_id, $size_id);
    $stmt_insert->execute();
    $stmt_insert->close();
    echo json_encode(['success' => true, 'message' => 'Producto añadido']);
}

$conn->close();
