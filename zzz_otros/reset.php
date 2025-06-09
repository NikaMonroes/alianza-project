<?php
session_start();
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $nuevaClave = password_hash($_POST['clave'], PASSWORD_DEFAULT);

    // Buscar usuario con ese token
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE token_recuperacion = ?");
    $stmt->execute([$token]);
    $usuario = $stmt->fetch();

    if ($usuario) {
        // Actualizar contraseña y borrar token
        $stmt = $pdo->prepare("UPDATE usuarios SET password = ?, token_recuperacion = NULL WHERE id = ?");
        $stmt->execute([$nuevaClave, $usuario['id']]);

        echo "<script>
            Swal.fire({
                title: '¡Éxito!',
                text: 'Tu contraseña ha sido cambiada correctamente.',
                icon: 'success',
                confirmButtonText: 'Ir al login'
            }).then(() => {
                window.location.href = 'login.php';
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                title: 'Error',
                text: 'Token inválido o expirado.',
                icon: 'error',
                confirmButtonText: 'Intentar nuevamente'
            });
        </script>";
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
</body>
</html>



============
FUNCIONANDO


<?php
session_start();
require_once '../includes/db.php';

$mensaje = ''; // Variable para mostrar alerta

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $nuevaClave = password_hash($_POST['clave'], PASSWORD_DEFAULT);

    // Buscar usuario con ese token
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE token_recuperacion = ?");
    $stmt->execute([$token]);
    $usuario = $stmt->fetch();

    if ($usuario) {
        // Actualizar contraseña y borrar token
        $stmt = $pdo->prepare("UPDATE usuarios SET password = ?, token_recuperacion = NULL WHERE id = ?");
        $stmt->execute([$nuevaClave, $usuario['id']]);

        // Guardar mensaje de éxito para mostrar en SweetAlert2
        $mensaje = "Tu contraseña ha sido cambiada correctamente.";
    } else {
        // Mensaje de error si el token es inválido
        $mensaje = "Token inválido o expirado.";
    }
}
?>