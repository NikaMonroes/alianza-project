<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Buscar el usuario
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($password, $usuario['password'])) {
        // Guardar datos de sesión
        $_SESSION['usuario'] = $usuario['nombre'];
        $_SESSION['rol'] = $usuario['rol'];

        // Redirigir según el rol
        if ($usuario['rol'] === 'admin') {
            header("Location: ../includes/dashboard_admin.php");
        } else {
            header("Location: ../usuarios/dashboard_ventas.php"); // Panel vendedor
        }
        exit;
    } else {
        $error = "Email o contraseña incorrectos.";
    }
}
?>


<!-- Formulario de Login Mejorado -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel - Cafetería</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div>
        <div class="bg-primary text-white text-center py-3 rounded shadow">
            <h2 class="fw-bold">🫖☕🍪🍩🍳🍰🍵🥛 ☕  Cafetería Alianza Project ☕ 🥛🍵🍰🍳🍩🍪☕🫖</h2>
        </div>
    </div>
    <div class="container mt-5 d-flex justify-content-center">
        <div class="card p-4 shadow" style="width: 400px;">
            <h2 class="text-center">Iniciar sesión</h2>
            <?php if (isset($error)) echo "<p class='text-danger text-center'>$error</p>"; ?>
            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label">Email:</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Contraseña:</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Ingresar</button>
                <div class="text-center mt-3">
                    <p><a href="recuperar.php">¿Olvidaste tu contraseña?</a></p>
                </div>
                
            </form>
        </div>
    </div>

    <?php require_once '../includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
