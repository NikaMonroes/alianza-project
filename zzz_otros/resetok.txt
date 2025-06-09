<?php
session_start();
require_once '../includes/db.php';

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $nuevaClave = password_hash($_POST['clave'], PASSWORD_DEFAULT);

    // Buscar usuario con el token y la fecha de expiración
    $stmt = $pdo->prepare("SELECT id, token_expiracion FROM usuarios WHERE token_recuperacion = ?");
    $stmt->execute([$token]);
    $usuario = $stmt->fetch();

    // Validar si el token existe y aún es válido
    if ($usuario && isset($usuario['token_expiracion']) && strtotime($usuario['token_expiracion']) > time()) {
        // Token válido, actualizar contraseña y eliminar token
        $stmt = $pdo->prepare("UPDATE usuarios SET password = ?, token_recuperacion = NULL, token_expiracion = NULL WHERE id = ?");
        $stmt->execute([$nuevaClave, $usuario['id']]);

        $mensaje = "Tu contraseña ha sido cambiada correctamente.";
    } else {
        $mensaje = "Token inválido o expirado. Solicita otro enlace.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Restablecer Contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4 shadow-lg" style="width: 400px;">
            <h2 class="text-center mb-4">Restablecer Contraseña</h2>
            <p class="text-muted text-center">Introduce una nueva contraseña segura.</p>

            <form method="POST">
                <input type="hidden" name="token" value="<?= $_GET['token'] ?>">
                
                <div class="mb-3">
                    <label for="clave" class="form-label">Nueva contraseña</label>
                    <input type="password" name="clave" id="clave" class="form-control" placeholder="*********" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Cambiar contraseña</button>
            </form>

            <p class="mt-3 text-center"><a href="login.php">← Volver al inicio</a></p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <?php if (!empty($mensaje)): ?>
        <script>
        Swal.fire({
            title: "<?= strpos($mensaje, 'correctamente') !== false ? '¡Éxito!' : 'Error' ?>",
            text: "<?= $mensaje ?>",
            icon: "<?= strpos($mensaje, 'correctamente') !== false ? 'success' : 'error' ?>",
            confirmButtonText: "<?= strpos($mensaje, 'correctamente') !== false ? 'Ir al login' : 'Intentar nuevamente' ?>"
        }).then(() => {
            <?php if (strpos($mensaje, 'correctamente') !== false): ?>
                window.location.href = 'login.php';
            <?php endif; ?>
        });
        </script>
    <?php endif; ?>
</body>
</html>