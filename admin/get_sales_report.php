<?php
require '../php/conexion.php';
header('Content-Type: application/json');
session_start();

// Aquí podrías añadir tu lógica de autenticación de admin

$start_date = $_GET['start_date'] ?? date('Y-m-01');
$end_date = $_GET['end_date'] ?? date('Y-m-d');

try {
    $stmt = $conn->prepare("
        SELECT 
            DATE(p.fecha) as sale_date, 
            SUM(pd.cantidad) as units_sold, 
            SUM(pd.precio * pd.cantidad) as total_revenue 
        FROM pedidos p
        JOIN pedido_detalle pd ON p.id = pd.pedido_id
        WHERE DATE(p.fecha) BETWEEN ? AND ?
        GROUP BY DATE(p.fecha)
        ORDER BY sale_date ASC
    ");
    $stmt->bind_param("ss", $start_date, $end_date);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    $conn->close();

    // Formatear datos para Chart.js
    $report = [
        'labels' => [],
        'revenue_data' => [],
        'units_data' => [],
        'table_data' => $data
    ];
    foreach($data as $row){
        $report['labels'][] = $row['sale_date'];
        $report['revenue_data'][] = $row['total_revenue'];
        $report['units_data'][] = $row['units_sold'];
    }

    echo json_encode(['success' => true, 'report' => $report]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>