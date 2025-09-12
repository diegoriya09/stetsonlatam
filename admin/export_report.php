<?php
require_once '../conexion.php';
// Lógica para obtener los datos igual que en get_sales_report.php
// ...
$start_date = $_GET['start_date'] ?? date('Y-m-01');
$end_date = $_GET['end_date'] ?? date('Y-m-d');
$format = $_GET['format'] ?? 'csv'; // csv o pdf

$stmt = $conn->prepare("SELECT DATE(p.fecha) as sale_date, SUM(pd.cantidad) as units_sold, SUM(pd.precio * pd.cantidad) as total_revenue FROM pedidos p JOIN pedido_detalle pd ON p.id = pd.pedido_id WHERE DATE(p.fecha) BETWEEN ? AND ? GROUP BY DATE(p.fecha) ORDER BY sale_date ASC");
$stmt->bind_param("ss", $start_date, $end_date);
$stmt->execute();
$data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

if ($format === 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="reporte_ventas.csv"');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Fecha', 'Unidades Vendidas', 'Ingresos Totales']); // Cabeceras
    foreach ($data as $row) {
        fputcsv($output, $row);
    }
    fclose($output);
} elseif ($format === 'pdf') {
    require_once '../lib/fpdf.php';
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',12);
    // Cabeceras
    $pdf->Cell(40,10,'Fecha',1);
    $pdf->Cell(60,10,'Unidades Vendidas',1);
    $pdf->Cell(60,10,'Ingresos Totales',1);
    $pdf->Ln();
    // Datos
    $pdf->SetFont('Arial','',12);
    foreach($data as $row) {
        $pdf->Cell(40,10,$row['sale_date'],1);
        $pdf->Cell(60,10,$row['units_sold'],1);
        $pdf->Cell(60,10,'$' . number_format($row['total_revenue'], 2),1);
        $pdf->Ln();
    }
    $pdf->Output('D', 'reporte_ventas.pdf');
}
$conn->close();
?>