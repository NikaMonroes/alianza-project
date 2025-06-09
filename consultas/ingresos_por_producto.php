<?php

session_start();

// Verificar si el usuario está logueado y es admin
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../index.php");//lo redirige al login
    exit;
}

require_once '../includes/db.php';
require_once '../includes/auth.php';

$consulta = $pdo->query("
    SELECT p.nombre, SUM(df.cantidad * df.precio_unitario) AS ingresos 
    FROM detalle_factura df 
    JOIN productos p ON df.producto_id = p.id 
    GROUP BY p.nombre 
    ORDER BY ingresos DESC
");
$productos = $consulta->fetchAll();
?>

<table class="table table-striped mt-3">
    <thead>
        <tr>
            <th>Producto</th>
            <th>Ingresos Generados</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($productos as $producto) : ?>
            <tr>
                <td><?= $producto['nombre'] ?></td>
                <td>$<?= number_format($producto['ingresos'], 2) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<p><a href="../consultas/ingresos_por_producto.php" class="btn btn-success">Exportar a Excel</a><p>