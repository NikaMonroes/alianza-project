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

// Solo admin puede entrar
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: ../consultas/reportes.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reportes de Ventas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
</head>
<body>

<div>
        <div class="bg-secondary text-white text-center py-3">
            <h2 class="fw-bold">📊 Reportes de Ventas</h2>
        </div>
<div class="container mt-5">


    <div class="text-center">
        <button class="btn btn-primary mx-2" id="btnVentas">Ventas por Día</button>
        <button class="btn btn-success mx-2" id="btnProducto">Producto Más Vendido</button>
        <button class="btn btn-warning mx-2" id="btnIngresos">Ingresos por Producto</button>
        <button class="btn btn-danger mx-2" id="btnStock">+ Stock</button>
        <button class="btn btn-dark mx-2" id="btnCSV">📁 Exportar Informe pdf</button>
        <button class="btn btn-dark mx-2" id="btnCSV1">📁 Exportar Informe excel</button>
          
    </div>

    <div class="mt-4">
        <div id="tablaVentas" style="display: none;">
            <h3>📅 Ventas por Día</h3>
            <?php include '../consultas/ventas_diarias.php'; ?>
        </div>

        <div id="tablaProducto" style="display: none;">
            <h3>🔥 Producto Más Vendido</h3>
            <?php include __DIR__ . '/producto_mas_vendido.php'; ?>
        </div>

        <div id="tablaIngresos" style="display: none;">
            <h3>💰 Ingresos por Producto</h3>
            <?php include '../consultas/ingresos_por_producto.php'; ?>
        </div>
        <div id="tablaStock" style="display: none;">
            <h3>💰+ Stock</h3>
            <?php include 'mas_stock.php'; ?>
        </div>
    </div>
    <p><a href="../includes/dashboard_admin.php" class="btn btn-secondary">← Volver al panel</a></p>
    <script>
        $("#btnVentas").click(function() {
            $(".mt-4 div").hide();
            $("#tablaVentas").fadeIn();
        });

        $("#btnProducto").click(function() {
            $(".mt-4 div").hide();
            $("#tablaProducto").fadeIn();
        });

        $("#btnIngresos").click(function() {
            $(".mt-4 div").hide();
            $("#tablaIngresos").fadeIn();
        });

        $("#btnStock").click(function() {
            $(".mt-4 div").hide();
            $("#tablaStock").fadeIn();
        });
        $("#btnCSV").click(function() {
        window.location.href = 'exportar_informe.php';
        });
        $("#btnCSV1").click(function() {
        window.location.href = 'exportar_informe_excel.php';
        });
        
    </script>
    <script src="../assets/js/reportes.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</div>
</body>
</html>

