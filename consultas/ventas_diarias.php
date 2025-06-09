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
    SELECT DATE(fecha_venta) AS dia, SUM(cantidad) AS total 
    FROM ventas 
    GROUP BY dia 
    ORDER BY dia DESC 
    LIMIT 7
");
$ventas = $consulta->fetchAll();
?>

<table class="table table-striped mt-3">
    <thead>
        <tr>
            <th>Día</th>
            <th>Total de Ventas</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($ventas as $venta) : ?>
            <tr>
                <td><?= $venta['dia'] ?></td>
                <td><?= $venta['total'] ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<p><a href="../consultas/ventas_diarias.php" class="btn btn-success">Exportar a Excel</a><p>