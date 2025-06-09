<?php
session_start();
require_once __DIR__ . '/includes/db.php';

// Verificar si el usuario está autenticado
if (isset($_SESSION['usuario'])) {
    // Si es admin, lo manda al dashboard de administración
    if ($_SESSION['rol'] === 'admin') {
        header("Location: includes/dashboard_admin.php");
    } else {
        // Si no es admin, va al dashboard de ventas
        header("Location: usuarios/dashboard_ventas.php");
    }
    exit;
}

// Si no ha iniciado sesión, lo manda al login
header("Location: usuarios/login.php");
exit;
?>

