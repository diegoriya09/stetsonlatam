<?php
// php/search.php (VERSIÓN CORREGIDA SIN product_images)

error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'conexion.php';
header('Content-Type: application/json; charset=utf-8');

$query = trim($_GET['q'] ?? '');

if (strlen($query) < 2) {
    echo json_encode(['productos' => [], 'categorias' => []]);
    exit;
}

$searchTerm = "%" . $query . "%";
$productos = [];
$categorias = [];

try {
    // --- CORRECCIÓN: Consulta de Productos modificada para usar la columna 'image' ---
    $stmt_p = $conn->prepare("
        SELECT id, name, image 
        FROM productos 
        WHERE name LIKE ? OR description LIKE ?
        LIMIT 5
    ");
    $stmt_p->bind_param("ss", $searchTerm, $searchTerm);
    $stmt_p->execute();
    $result_p = $stmt_p->get_result();

    while ($row = $result_p->fetch_assoc()) {
        $productos[] = [
            'title' => htmlspecialchars($row['name']),
            'url'   => 'producto' . $row['id'],
            'image' => htmlspecialchars($row['image'] ?? 'img/default.jpg') // Usamos la columna 'image'
        ];
    }
    $stmt_p->close();

    // --- Búsqueda de Categorías (sin cambios) ---
    $stmt_c = $conn->prepare("SELECT id, nombre FROM categorias WHERE nombre LIKE ? LIMIT 3");
    $stmt_c->bind_param("s", $searchTerm);
    $stmt_c->execute();
    $result_c = $stmt_c->get_result();

    while ($row = $result_c->fetch_assoc()) {
        $categorias[] = [
            'title' => htmlspecialchars($row['nombre']),
            'url'   => 'categoria' . $row['id']
        ];
    }
    $stmt_c->close();
} catch (Exception $e) {
    http_response_code(500);
    error_log("Error en search.php: " . $e->getMessage());
    echo json_encode(['error' => 'Ocurrió un error en el servidor.']);
    exit;
}

// Devolver ambos resultados en un solo JSON (sin cambios)
echo json_encode(['productos' => $productos, 'categorias' => $categorias]);

$conn->close();
