<?php
// php/reviews/add_review.php
require_once '../conexion.php';
require_once '../../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
header('Content-Type: application/json');

// 1. Autenticación con JWT (igual que en tus otros scripts)
function getAuthorizationHeader() { /* ... tu función ... */ }
try {
    $authHeader = getAuthorizationHeader();
    if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) { throw new Exception('Token no proporcionado.'); }
    $jwt = trim(str_replace('Bearer', '', $authHeader));
    $secret_key = "StetsonLatam1977";
    $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
    $user_id = $decoded->data->id;
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Acceso no autorizado.']);
    exit;
}

// 2. Obtener datos de la reseña
$data = json_decode(file_get_contents("php://input"), true);
$product_id = $data['product_id'] ?? 0;
$rating = $data['rating'] ?? 0;
$comment = trim(strip_tags($data['comment'] ?? ''));

if ($product_id <= 0 || $rating < 1 || $rating > 5 || empty($comment)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Por favor, completa todos los campos.']);
    exit;
}

// 3. VERIFICACIÓN DE COMPRA
$has_purchased = false;
$stmt_check = $conn->prepare("
    SELECT COUNT(*) as purchase_count
    FROM pedidos p
    JOIN pedido_detalle pd ON p.id = pd.pedido_id
    WHERE p.user_id = ? AND pd.product_id = ? AND p.estado = 'Enviado' -- O 'Completado'
");
$stmt_check->bind_param("ii", $user_id, $product_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result()->fetch_assoc();
if ($result_check && $result_check['purchase_count'] > 0) {
    $has_purchased = true;
}
$stmt_check->close();

if (!$has_purchased) {
    http_response_code(403); // Forbidden
    echo json_encode(['success' => false, 'message' => 'Solo los clientes que han comprado este producto pueden dejar una reseña.']);
    exit;
}

// 4. Si la verificación es exitosa, insertar la reseña
try {
    $stmt_insert = $conn->prepare("INSERT INTO product_reviews (product_id, user_id, rating, comment) VALUES (?, ?, ?, ?)");
    $stmt_insert->bind_param("iiis", $product_id, $user_id, $rating, $comment);
    $stmt_insert->execute();
    $stmt_insert->close();
    echo json_encode(['success' => true, 'message' => '¡Gracias por tu reseña!']);
} catch (mysqli_sql_exception $e) {
    // Captura el error de clave única si el usuario ya ha dejado una reseña
    if ($e->getCode() == 1062) { // Código de error para 'Duplicate entry'
        http_response_code(409); // Conflict
        echo json_encode(['success' => false, 'message' => 'Ya has dejado una reseña para este producto.']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Ocurrió un error al guardar tu reseña.']);
    }
}
$conn->close();
?>