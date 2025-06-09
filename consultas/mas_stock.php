<?php

session_start();

// Verificar si el usuario está logueado y es admin
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../index.php");//lo redirige al login
    exit;
}

require_once '../includes/db.php';
require_once '../includes/auth.php';

try {
    // Consulta para obtener los productos con más stock
    $consulta = $pdo->query("SELECT nombre, stock FROM productos ORDER BY stock DESC LIMIT 10");
    $productos = $consulta->fetchAll();
} catch (PDOException $e) {
    $error = "Error al obtener los productos: " . $e->getMessage();
}
?>

<?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

<?php if (!empty($productos)) : ?>
    <table class="table table-striped mt-3">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Stock Disponible</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productos as $p) : ?>
                <tr>
                    <td><?= htmlspecialchars($p['nombre']) ?></td>
                    <td><?= $p['stock'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else : ?>
    <p class="text-center text-muted">No hay productos con stock disponible.</p>
<?php endif; ?>

<p><a href="../consultas/mas_stock.php" class="btn btn-success">Exportar a Excel</a><p>


        

