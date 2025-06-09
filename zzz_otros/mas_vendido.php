<?php
require_once '../includes/db.php';

$consulta = $pdo->query("
    SELECT p.nombre, SUM(v.cantidad) AS cantidad_vendida 
    FROM ventas v 
    JOIN productos p ON v.producto_id = p.id 
    GROUP BY p.nombre 
    ORDER BY cantidad_vendida DESC 
    LIMIT 1
");
$producto = $consulta->fetch();
?>

<table class="table table-striped mt-3">
    <thead>
        <tr>
            <th>Producto</th>
            <th>Cantidad Vendida</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><?= $producto['nombre'] ?></td>
            <td><?= $producto['cantidad_vendida'] ?></td>
        </tr>
    </tbody>
</table>