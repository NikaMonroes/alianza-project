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
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    die("Acceso no autorizado");
}

try {
    // Consultar productos
    $stmt = $pdo->query("SELECT nombre, referencia, precio, peso, categoria, stock, fecha_creacion FROM productos ORDER BY fecha_creacion DESC");
    $productos = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error al obtener productos: " . $e->getMessage());
}

// Crear archivo Excel
$spreadsheet = new Spreadsheet();
$hoja = $spreadsheet->getActiveSheet();
$hoja->setTitle("Inventario");

// Encabezados
$hoja->setCellValue('A1', 'Nombre');
$hoja->setCellValue('B1', 'Referencia');
$hoja->setCellValue('C1', 'Precio');
$hoja->setCellValue('D1', 'Peso (g)');
$hoja->setCellValue('E1', 'Categoría');
$hoja->setCellValue('F1', 'Stock');
$hoja->setCellValue('G1', 'Fecha de Creación');

// Llenar datos
$fila = 2;
foreach ($productos as $p) {
    $hoja->setCellValue("A$fila", $p['nombre']);
    $hoja->setCellValue("B$fila", $p['referencia']);
    $hoja->setCellValue("C$fila", $p['precio']);
    $hoja->setCellValue("D$fila", $p['peso']);
    $hoja->setCellValue("E$fila", $p['categoria']);
    $hoja->setCellValue("F$fila", $p['stock']);
    $hoja->setCellValue("G$fila", $p['fecha_creacion']);
    $fila++;
}

// Configurar cabeceras para descarga
header("Content-Disposition: attachment; filename=Inventario_" . date("Y-m-d") . ".xlsx");
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Cache-Control: max-age=0");

$writer = new Xlsx($spreadsheet);
$writer->save("php://output");
exit;
?>