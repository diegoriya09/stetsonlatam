<?php
// admin/reply_to_review.php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Acceso denegado.']);
    exit;
}

require_once '../php/conexion.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$review_id = $data['review_id'] ?? 0;
$reply_text = trim(strip_tags($data['reply_text'] ?? ''));

if ($review_id <= 0 || empty($reply_text)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Faltan datos.']);
    exit;
}

$stmt = $conn->prepare("UPDATE product_reviews SET reply_text = ?, replied_at = NOW() WHERE id = ?");
$stmt->bind_param("si", $reply_text, $review_id);
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Respuesta guardada.']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al guardar la respuesta.']);
}
$stmt->close();
$conn->close();
?>