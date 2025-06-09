<?php
require_once 'config.php';

// Si el usuario no ha iniciado sesión, lo redirige al login
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}
?>