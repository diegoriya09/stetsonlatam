<?php
// search.php
require_once __DIR__ . '/php/conexion.php';
header('Content-Type: application/json; charset=utf-8');

$q = trim($_GET['q'] ?? '');
if ($q === '') {
    echo json_encode([]);
    exit;
}

$like = "%" . $q . "%";

// 1. Buscar en productos
$sqlProd = "SELECT id, name, price, image 
            FROM productos 
            WHERE name LIKE ? OR description LIKE ? 
            LIMIT 20";
$stmt = $conn->prepare($sqlProd);
$stmt->bind_param("ss", $like, $like);
$stmt->execute();
$res = $stmt->get_result();

$productos = [];
while ($row = $res->fetch_assoc()) {
    $productos[] = [
        "type" => "producto",
        "id" => $row['id'],
        "title" => $row['name'],
        "price" => $row['price'],
        "image" => $row['image'],
        "url" => "producto.php?id=" . $row['id']
    ];
}

// Unir todo en un JSON
echo json_encode([
    "productos" => $productos
]);
