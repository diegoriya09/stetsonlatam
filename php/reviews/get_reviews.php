<?php
// php/reviews/get_reviews.php
require_once '../conexion.php';
header('Content-Type: application/json');

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($product_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID de producto no válido.']);
    exit;
}

$stmt = $conn->prepare("
    SELECT r.rating, r.comment, r.created_at, r.reply_text, u.name as user_name
    FROM product_reviews r
    JOIN users u ON r.user_id = u.id
    WHERE r.product_id = ?
    ORDER BY r.created_at DESC
");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$reviews = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conn->close();

echo json_encode(['success' => true, 'reviews' => $reviews]);