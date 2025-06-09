<?php

session_start();

// Verificar si el usuario está logueado y es admin
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../index.php");//lo redirige al login
    exit;
}

require_once '../includes/db.php';
require_once '../includes/auth.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);

        header("Location: registrar_usuario.php?mensaje=Usuario eliminado correctamente");
        exit;
    } catch (PDOException $e) {
        header("Location: registrar_usuario.php?error=" . urlencode($e->getMessage()));
        exit;
    }
}
?>