<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit;
}
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="#">Cafetería</a>
        <div class="collapse navbar-collapse justify-content-end">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <span class="nav-link text-white">Bienvenido, <?php echo $_SESSION['usuario']; ?>!</span>
                </li>
                <li class="nav-item">
                    <a href="../usuarios/logout.php" class="btn btn-danger">Cerrar sesión</a>
                </li>
            </ul>
        </div>
    </div>
</nav>