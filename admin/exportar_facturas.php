<?php

session_start();

// Verificar si el usuario está logueado y es admin
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../index.php");//lo redirige al login
    exit;
}

require_once '../includes/db.php';
require_once '../includes/auth.php';
require '../vendor/autoload.php'; // Asegúrate de que esta ruta es correcta

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Verificar acceso de administrador
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    die("Acceso no autorizado");
}

try {
    $consulta = $pdo->query("SELECT id, numero_factura, fecha, total, vendedor FROM facturas ORDER BY fecha DESC");
    $facturas = $consulta->fetchAll();
} catch (PDOException $e) {
    die("Error al obtener facturas: " . $e->getMessage());
}

// Crear archivo Excel
$spreadsheet = new Spreadsheet();
$hoja = $spreadsheet->getActiveSheet();
$hoja->setTitle("Facturas");

// Encabezados
$hoja->setCellValue('A1', 'ID');
$hoja->setCellValue('B1', 'Número Factura');
$hoja->setCellValue('C1', 'Fecha');
$hoja->setCellValue('D1', 'Total');
$hoja->setCellValue('E1', 'Vendedor');

// Llenar datos
$fila = 2;
foreach ($facturas as $f) {
    $hoja->setCellValue("A$fila", $f['id']);
    $hoja->setCellValue("B$fila", $f['numero_factura']);
    $hoja->setCellValue("C$fila", date("d/m/Y H:i", strtotime($f['fecha'])));
    $hoja->setCellValue("D$fila", $f['total']);
    $hoja->setCellValue("E$fila", $f['vendedor']);
    $fila++;
}

// Guardar y descargar
$writer = new Xlsx($spreadsheet);
$nombreArchivo = "Facturas_" . date("Y-m-d") . ".xlsx";
header("Content-Disposition: attachment; filename=$nombreArchivo");
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Cache-Control: max-age=0");

$writer->save("php://output");
exit;
?>