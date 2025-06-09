<?php

session_start();

// Verificar si el usuario está logueado y es admin
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../index.php");//lo redirige al login
    exit;
}

require_once '../includes/db.php';
require_once '../includes/auth.php';
require_once '../vendor/autoload.php';


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

// Crear archivo Excel
$spreadsheet = new Spreadsheet();
$hoja = $spreadsheet->getActiveSheet();
$hoja->setTitle("Reporte de Ventas");

// **Estilo de encabezado**
$hoja->getStyle("A1:G1")->applyFromArray([
    'font' => ['bold' => true],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFDDDDDD']],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
]);

// Ajustar ancho automático
foreach (range('A', 'G') as $col) {
    $hoja->getColumnDimension($col)->setAutoSize(true);
}

// 📌 Encabezados
$hoja->setCellValue('A1', 'Fecha');
$hoja->setCellValue('B1', 'Producto');
$hoja->setCellValue('C1', 'Cantidad Vendida');
$hoja->setCellValue('D1', 'Total Ingresos');
$hoja->setCellValue('E1', 'Producto Más Vendido');
$hoja->setCellValue('F1', 'Total Vendido');
$hoja->setCellValue('G1', 'Ingresos Totales por Producto');

// 🔍 Obtener ventas por día
$stmt = $pdo->query("
    SELECT v.fecha_venta, p.nombre AS producto, SUM(v.cantidad) AS cantidad_total, 
           SUM(v.cantidad * p.precio) AS ingresos
    FROM ventas v
    JOIN productos p ON v.producto_id = p.id
    GROUP BY v.fecha_venta, p.nombre
    ORDER BY v.fecha_venta DESC
");
$ventas = $stmt->fetchAll();

// 🔥 Obtener producto más vendido
$stmt = $pdo->query("
    SELECT p.nombre AS producto, SUM(v.cantidad) AS total_vendido
    FROM ventas v
    JOIN productos p ON v.producto_id = p.id
    GROUP BY p.nombre
    ORDER BY total_vendido DESC
    LIMIT 1
");
$producto_mas_vendido = $stmt->fetch();

// 💰 Obtener ingresos por producto
$stmt = $pdo->query("
    SELECT p.nombre AS producto, SUM(v.cantidad * p.precio) AS ingresos
    FROM ventas v
    JOIN productos p ON v.producto_id = p.id
    GROUP BY p.nombre
    ORDER BY ingresos DESC
");
$ingresos_por_producto = $stmt->fetchAll();

// 📝 Escribir los datos en el archivo Excel
$fila = 2;
foreach ($ventas as $venta) {
    $hoja->setCellValue("A$fila", $venta['fecha_venta']);
    $hoja->setCellValue("B$fila", $venta['producto']);
    $hoja->setCellValue("C$fila", $venta['cantidad_total']);
    $hoja->setCellValue("D$fila", number_format($venta['ingresos'], 2, '.', ''));
    $hoja->setCellValue("E$fila", $producto_mas_vendido['producto']);
    $hoja->setCellValue("F$fila", $producto_mas_vendido['total_vendido'] . ' unidades');
    $hoja->setCellValue("G$fila", number_format($producto_mas_vendido['total_vendido'] * $venta['ingresos'], 2, '.', ''));          
        $fila++;
}

// Configurar cabeceras para descarga
header("Content-Disposition: attachment; filename=ReporteDeVentas_" . date("Y-m-d") . ".xlsx");
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Cache-Control: max-age=0");

$writer = new Xlsx($spreadsheet);
$writer->save("php://output");
exit;
?>