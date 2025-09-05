<?php
// update_cart.php (COMPLETO Y MODIFICADO)
require_once '../conexion.php';
require_once '../../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header('Content-Type: application/json');

// --- Bloque de Autenticación (sin cambios) ---
function getAuthorizationHeader()
{ /* ... */
} // Usa tu función
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
    echo json_encode(['success' => false, 'message' => 'Token inválido']);
    exit;
}
// --- Fin Bloque de Autenticación ---

$data = json_decode(file_get_contents('php://input'), true);
$cart_item_id = $data['cart_item_id'] ?? null;
$new_quantity = $data['cantidad'] ?? null; // JS envía 'cantidad' para update

if (!is_numeric($cart_item_id) || !is_numeric($new_quantity) || $new_quantity < 1) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Datos inválidos.']);
    exit;
}

try {
    // --- NUEVO: Bloque de Verificación de Stock ---
    // 1. Obtener los detalles del producto (producto_id, color_id, size_id) desde el carrito
    $stmt_item = $conn->prepare("SELECT producto_id, color_id, size_id FROM cart WHERE id = ? AND users_id = ?");
    $stmt_item->bind_param("ii", $cart_item_id, $user_id);
    $stmt_item->execute();
    $result_item = $stmt_item->get_result();
    if ($result_item->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'El artículo no se encontró en tu carrito.']);
        exit;
    }
    $item_details = $result_item->fetch_assoc();
    $stmt_item->close();

    // 2. Obtener el stock de esa variante
    $stmt_stock = $conn->prepare("SELECT stock FROM product_variants WHERE product_id = ? AND color_id = ? AND size_id = ?");
    $stmt_stock->bind_param("iii", $item_details['producto_id'], $item_details['color_id'], $item_details['size_id']);
    $stmt_stock->execute();
    $result_stock = $stmt_stock->get_result();
    $variant = $result_stock->fetch_assoc();
    $available_stock = $variant['stock'] ?? 0;
    $stmt_stock->close();

    // 3. Comparar la nueva cantidad con el stock
    if ($new_quantity > $available_stock) {
        echo json_encode(['success' => false, 'message' => "No hay suficiente stock. Solo quedan {$available_stock} unidades disponibles."]);
        exit;
    }
    // --- FIN: Bloque de Verificación de Stock ---

    // Si la validación es correcta, actualizamos
    $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND users_id = ?");
    $stmt->bind_param("iii", $new_quantity, $cart_item_id, $user_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Cantidad actualizada.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se realizaron cambios.']);
    }
    $stmt->close();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error del servidor.', 'error' => $e->getMessage()]);
}
$conn->close();
