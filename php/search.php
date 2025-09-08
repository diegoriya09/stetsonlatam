<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


require 'conexion.php';
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
        "url" => "producto" . $row['id']
    ];
}

// Unir todo en un JSON
echo json_encode([
    "productos" => $productos
]);
