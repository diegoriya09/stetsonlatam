<?php
// php/admin/export_report.php

// Iniciar sesión para verificar el rol de administrador
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    http_response_code(403);
    die("Acceso denegado.");
}

require '../php/conexion.php';

// Obtener los filtros de la URL
$start_date = $_GET['start_date'] ?? date('Y-m-01');
$end_date = $_GET['end_date'] ?? date('Y-m-d');
$format = $_GET['format'] ?? 'csv';

try {
    // Obtener los datos de la base de datos (misma consulta que en los reportes)
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

    // Generar el archivo según el formato solicitado
    if ($format === 'csv') {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="reporte_ventas_' . date('Y-m-d') . '.csv"');
        $output = fopen('php://output', 'w');
        fputcsv($output, ['Fecha', 'Unidades Vendidas', 'Ingresos Totales']); // Escribir cabeceras
        foreach ($data as $row) {
            fputcsv($output, $row); // Escribir cada fila de datos
        }
        fclose($output);
        exit;

    } elseif ($format === 'pdf') {
        // Asegúrate de haber descargado FPDF y colocado en una carpeta como 'php/lib/fpdf/'
        require '../php/lib/fpdf186/fpdf.php';
        
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',12);
        
        // Cabeceras de la tabla
        $pdf->Cell(40, 10, 'Fecha', 1);
        $pdf->Cell(60, 10, 'Unidades Vendidas', 1);
        $pdf->Cell(60, 10, 'Ingresos Totales', 1);
        $pdf->Ln();
        
        // Datos de la tabla
        $pdf->SetFont('Arial','',12);
        foreach($data as $row) {
            $pdf->Cell(40, 10, $row['sale_date'], 1);
            $pdf->Cell(60, 10, $row['units_sold'], 1);
            $pdf->Cell(60, 10, '$' . number_format($row['total_revenue'], 2), 1);
            $pdf->Ln();
        }
        $pdf->Output('D', 'reporte_ventas_' . date('Y-m-d') . '.pdf');
        exit;
    }

} catch (Exception $e) {
    http_response_code(500);
    die("Error al generar el reporte: " . $e->getMessage());
}
?>