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



header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename=ventas.csv');

$output = fopen('php://output', 'w');

// 📌 Encabezados con columnas seguidas
fputcsv($output, [
    'Fecha', 'Producto', 'Cantidad Vendida', 'Total Ingresos',
    'Producto Más Vendido', 'Total Vendido', 'Ingresos Totales por Producto'
]);

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

// 📝 Escribir los datos en columnas
foreach ($ventas as $venta) {
    fputcsv($output, [
        $venta['fecha_venta'],
        $venta['producto'],
        $venta['cantidad_total'],
        '$' . number_format($venta['ingresos'], 2),
        $producto_mas_vendido['producto'],
        $producto_mas_vendido['total_vendido'] . ' unidades',
        '$' . number_format($producto_mas_vendido['total_vendido'] * $venta['ingresos'], 2)
    ]);
}

fclose($output);
?>