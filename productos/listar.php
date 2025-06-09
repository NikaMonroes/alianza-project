<?php

session_start();

// Verificar si el usuario está logueado y es admin
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../index.php");//lo redirige al login
    exit;
}

require_once '../includes/db.php';
require_once '../includes/auth.php';
require_once '../includes/header.php';

// Consultar productos
$stmt = $pdo->query("SELECT * FROM productos ORDER BY fecha_creacion DESC");
$productos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel - Cafetería</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div>
        <div class="bg-secondary text-white text-center py-3">
            <h2 class="fw-bold">🫖☕🍪🍩🍳🍰🍵🥛 ☕  Listado de Productos ☕ 🥛🍵🍰🍳🍩🍪☕🫖</h2>
        </div>
    </div>
<div class="container mt-5">

    <a href="crear.php" class="btn btn-success mb-3">+ Añadir Producto</a>
    
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Nombre</th>
                <th>Referencia</th>
                <th>Precio</th>
                <th>Peso</th>
                <th>Categoría</th>
                <th>Stock</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productos as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['nombre']) ?></td>
                    <td><?= htmlspecialchars($p['referencia']) ?></td>
                    <td>$<?= number_format($p['precio']) ?></td>
                    <td><?= $p['peso'] ?> g</td>
                    <td><?= htmlspecialchars($p['categoria']) ?></td>
                    <td><?= $p['stock'] ?></td>
                    <td><?= $p['fecha_creacion'] ?></td>
                    <td>
                        <a href="editar.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-primary">Editar</a>
                        <button onclick="confirmarEliminar(<?= $p['id'] ?>)" class="btn btn-sm btn-danger">Eliminar</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div>
        <p><a href="../admin/exportar_inventario.php" class="btn btn-success">Exportar a Excel</a><p>
        <p><a href="../includes/dashboard_admin.php" class="btn btn-secondary">← Volver al panel</a></p>
    </div>
</div>

<script>
function confirmarEliminar(id) {
    Swal.fire({
        title: '¿Eliminar producto?',
        text: "No podrás revertir esto",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'eliminar.php?id=' + id;
        }
    });
}
</script>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
