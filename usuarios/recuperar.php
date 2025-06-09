<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/emailservice.php';




$mensaje = '';
$tipo_alerta = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Buscar usuario
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch();

    if ($usuario) {
        // Generar token aleatorio y su expiración
        $token = bin2hex(random_bytes(32));
        $expiracion = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // Guardar token en la BD
        $stmt = $pdo->prepare("UPDATE usuarios SET token_recuperacion = ?, token_expiracion = ? WHERE id = ?");
        $stmt->execute([$token, $expiracion, $usuario['id']]);

        // Enviar email con el enlace de recuperación
        $url = "http://localhost/cafeteria/usuarios/reset.php?token=$token";
        $asunto = "Recuperación de contraseña";
        $mensajeEmail = "<p>Hiciste una solicitud de recuperación de contraseña. Haz clic en el siguiente enlace:</p>
                         <a href='$url'>Restablecer contraseña</a>";

        enviarCorreo($email, $asunto, $mensajeEmail);

        // Guardar mensaje para alerta
        $mensaje = "Correo de recuperación enviado.";
        $tipo_alerta = "success";
    } else {
        $mensaje = "El correo no está registrado.";
        $tipo_alerta = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4 shadow-lg" style="width: 400px;">
            <h2 class="text-center mb-4">Recuperación de Contraseña</h2>
            <p class="text-muted text-center">Ingresa tu correo para recibir un enlace de recuperación.</p>

            <form method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">Correo electrónico</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="tuemail@example.com" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Enviar enlace</button>
            </form>

            <p class="mt-3 text-center"><a href="login.php">← Volver al inicio</a></p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <?php if (!empty($mensaje)): ?>
        <script>
        Swal.fire({
            title: "<?= $tipo_alerta == 'success' ? '¡Éxito!' : 'Error' ?>",
            text: "<?= $mensaje ?>",
            icon: "<?= $tipo_alerta ?>",
            confirmButtonText: "Aceptar"
        });
        </script>
    <?php endif; ?>
</body>
</html>