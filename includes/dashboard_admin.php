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

<div class="container mt-5">
    <h2 class="text-center mb-4">Panel de Administración</h2>
    <div class="row g-4">

        <div class="col-md-3">
            <a href="../admin/registrar_usuario.php" class="text-decoration-none">
                <div class="card text-white bg-primary h-100">
                    <div class="card-body text-center">
                        <h5 class="card-title">Usuarios</h5>
                        <p class="card-text">Crear y administrar usuarios</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="../productos/listar.php" class="text-decoration-none">
                <div class="card text-white bg-success h-100">
                    <div class="card-body text-center">
                        <h5 class="card-title">Productos</h5>
                        <p class="card-text">Ver, editar o eliminar productos</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="../usuarios/dashboard_ventas.php" class="text-decoration-none">
                <div class="card text-white bg-warning h-100">
                    <div class="card-body text-center">
                        <h5 class="card-title">Ventas</h5>
                        <p class="card-text">Panel y Gestion de  Facturas</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="../consultas/reportes.php" class="text-decoration-none">
                <div class="card text-white bg-dark h-100">
                    <div class="card-body text-center">
                        <h5 class="card-title">Reportes</h5>
                        <p class="card-text">Consulta de productos</p>
                    </div>
                </div>
            </a>
        </div>

   

    </div>
</div>

<?php require_once '../includes/footer.php'; ?>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
