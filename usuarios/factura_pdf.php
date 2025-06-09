<?php

session_start();

// Verificar si el usuario está logueado y es admin
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../index.php");//lo redirige al login
    exit;
}

require_once '../includes/db.php';

require_once '../includes/auth.php';
require __DIR__ . '/../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

session_start();

if (!isset($_SESSION['ultima_venta'])) {
    echo "No hay datos para generar la factura.";
    exit;
}

$venta = $_SESSION['ultima_venta'];
$productos = $venta['productos'];
$total = $venta['total'];
$fecha = $venta['fecha'];
$vendedor = $venta['vendedor'];
$pago = $venta['pago'];
$devuelta = $venta['devuelta'];
$numero_factura = $venta['numero_factura'];

// HTML bonito
$html = '
<style>
    body { font-family: Helvetica, sans-serif; font-size: 12px; }
    h1, h2 { text-align: center; margin-bottom: 5px; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th, td { border: 1px solid #000; padding: 5px; text-align: center; }
    .resumen { margin-top: 20px; }
</style>

<h1>Cafetería Alianza</h1>
<h2>Factura N° ' . $numero_factura . '</h2>
<p><strong>Fecha:</strong> ' . $fecha . '<br>
<strong>Vendedor:</strong> ' . htmlspecialchars($vendedor) . '</p>

<table>
    <thead>
        <tr>
            <th>Producto</th>
            <th>Cant.</th>
            <th>Precio</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>';

foreach ($productos as $item) {
    $subtotal = $item['precio'] * $item['cantidad'];
    $html .= '<tr>
        <td>' . htmlspecialchars($item['nombre']) . '</td>
        <td>' . $item['cantidad'] . '</td>
        <td>$' . number_format($item['precio'], 0) . '</td>
        <td>$' . number_format($subtotal, 0) . '</td>
    </tr>';
}

$html .= '</tbody></table>
<div class="resumen">
    <p><strong>Total:</strong> $' . number_format($total, 0) . '</p>
    <p><strong>Pagado:</strong> $' . number_format($pago, 0) . '</p>
    <p><strong>Devuelta:</strong> $' . number_format($devuelta, 0) . '</p>
</div>
<p style="text-align: center; margin-top: 30px;">¡Gracias por tu compra! 💖</p>
';




// PDF con Dompdf
$options = new Options();
$options->set('defaultFont', 'Helvetica');
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("factura_" . $numero_factura . ".pdf", ["Attachment" => true]);

unset($_SESSION['ultima_venta']);
?>



