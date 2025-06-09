<?php

session_start();

// Verificar si el usuario está logueado y es admin
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../index.php");//lo redirige al login
    exit;
}

require_once '../includes/db.php';
require_once '../includes/header.php';
require_once '../includes/auth.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $pdo->beginTransaction();

        // Insertar la factura
        $stmtFactura = $pdo->prepare("INSERT INTO facturas (numero_factura, fecha, total, vendedor) VALUES (?, NOW(), ?, ?)");
        $stmtFactura->execute([$_POST['numero_factura'], $_POST['total'], $_SESSION['usuario']]);
        $factura_id = $pdo->lastInsertId(); 

        // Insertar productos en detalle_factura
        foreach ($_POST['productos'] as $producto) {
            $stmtDetalle = $pdo->prepare("INSERT INTO detalle_factura (factura_id, producto_id, cantidad, precio_unitario) VALUES (?, ?, ?, ?)");
            $stmtDetalle->execute([$factura_id, $producto['id'], $producto['cantidad'], $producto['precio']]);
        }

        $pdo->commit();
        header("Location: facturas.php?success=Factura registrada correctamente!");
        exit;
    } catch (PDOException $e) {
        $pdo->rollBack();
        $error = "Error al registrar factura: " . $e->getMessage();
    }
}
// Obtener todas las facturas
try {
    $consulta = $pdo->query("SELECT id, numero_factura, fecha, total, vendedor FROM facturas ORDER BY fecha DESC");
    $facturas = $consulta->fetchAll();
} catch (PDOException $e) {
    $error = "Error al obtener las facturas: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Facturación</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
     <div>
        <div class="bg-secondary text-white text-center py-3">
            <h2 class="fw-bold">💰🪙💵📖 🗒️  Listado de Facturas 🗒️ 💰🪙💵📖</h2>
        </div>
    </div>
<div class="container mt-5">
    

    <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

    <?php if (!empty($facturas)) : ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Número de Factura</th>
                    <th>Fecha</th>
                    <th>Total</th>
                    <th>Vendedor</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($facturas as $f) : ?>
                    <tr>
                        <td><?= $f['id'] ?></td>
                        <td><?= htmlspecialchars($f['numero_factura']) ?></td>
                        <td><?= date("d/m/Y H:i", strtotime($f['fecha'])) ?></td>
                        <td>$<?= number_format($f['total'], 2) ?></td>
                        <td><?= htmlspecialchars($f['vendedor']) ?></td>
                        <td>
                            <button class="btn btn-danger btn-sm" onclick="confirmarEliminacion(<?= $f['id'] ?>)">Eliminar</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p class="text-center text-muted">No hay facturas registradas aún.</p>
    <?php endif; ?>

    <p><a href="exportar_facturas.php" class="btn btn-success">Exportar a Excel</a><p>

    <p><a href="../usuarios/dashboard_ventas.php" class="btn btn-secondary">← Volver al panel de ventas</a></p>

    <script>
    function confirmarEliminacion(id) {
        Swal.fire({
            title: "¿Estás seguro?",
            text: "¡Esta acción no se puede deshacer!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "eliminar_factura.php?id=" + id;
            }
        });
    }
    </script>
</div>
</body>
</html>