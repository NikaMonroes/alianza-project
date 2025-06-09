<?php
session_start();
require_once '../includes/db.php';

// Verificar acceso de administrador
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Obtener productos con mayor stock
try {
    $consulta = $pdo->query("SELECT nombre, stock FROM productos ORDER BY stock DESC LIMIT 10");
    $productos = $consulta->fetchAll();
} catch (PDOException $e) {
    $error = "Error al obtener productos: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Productos con más stock</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2 class="text-center mb-4">Productos con más stock</h2>

    <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

    <?php if (!empty($productos)) : ?>
        <table class="table table-striped">
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
        
        <p><a href="../includes/dashboard_admin.php" class="btn btn-secondary">← Volver al panel</a></p>
</body>
</html>