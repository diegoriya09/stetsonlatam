<?php
// php/user/add_payment_method.php
require_once '../conexion.php';
require_once '../../vendor/autoload.php';

header('Content-Type: application/json');

// (Función getAuthorizationHeader)
function getAuthorizationHeader(){
    if (isset($_SERVER['HTTP_AUTHORIZATION'])) { return trim($_SERVER["HTTP_AUTHORIZATION"]); }
    if (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) { return trim($_SERVER["REDIRECT_HTTP_AUTHORIZATION"]); }
    return null;
}

$data = json_decode(file_get_contents('php://input'), true);

// ADVERTENCIA: ¡NUNCA guardes números de tarjeta completos!
// Esta es una simulación. Solo guardamos los últimos 4 dígitos.
if (empty($data['card_type']) || empty($data['last_four']) || empty($data['expiry_date'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Por favor, completa todos los campos.']);
    exit;
}

try {
    $stmt = $conn->prepare("INSERT INTO user_payment_methods (user_id, card_type, last_four_digits, expiry_date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $data['card_type'], $data['last_four'], $data['expiry_date']);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Método de pago añadido con éxito.']);
    } else {
        throw new Exception("Error al guardar el método de pago.");
    }
    $stmt->close();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
}
?>