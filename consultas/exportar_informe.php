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

use Dompdf\Dompdf;
use Dompdf\Options;


// Configurar Dompdf
$options = new Options();
$options->set('defaultFont', 'Arial');
$dompdf = new Dompdf($options);

// 🔍 Obtener ventas de la base de datos
$stmt = $pdo->query("
    SELECT v.fecha_venta, p.nombre AS producto, SUM(v.cantidad) AS cantidad_total, 
           SUM(v.cantidad * p.precio) AS ingresos
    FROM ventas v
    JOIN productos p ON v.producto_id = p.id
    GROUP BY v.fecha_venta, p.nombre
    ORDER BY v.fecha_venta DESC
");
$ventas = $stmt->fetchAll();

// 💡 Generar HTML para el PDF
$html = '<h2 style="text-align: center;">Reporte de Ventas</h2>';
$html .= '<table border="1" style="width:100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Producto</th>
                    <th>Cantidad Vendida</th>
                    <th>Total Ingresos</th>
                </tr>
            </thead>
            <tbody>';

foreach ($ventas as $venta) {
    $html .= '<tr>
                <td>' . $venta['fecha_venta'] . '</td>
                <td>' . $venta['producto'] . '</td>
                <td>' . $venta['cantidad_total'] . '</td>
                <td>$' . number_format($venta['ingresos'], 2) . '</td>
              </tr>';
}

$html .= '</tbody></table>';

// 📌 Renderizar el PDF
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// 📥 Enviar el PDF al navegador
$dompdf->stream("reporte_ventas.pdf", ["Attachment" => true]);
?>