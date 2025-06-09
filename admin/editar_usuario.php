<?php

session_start();

// Verificar si el usuario está logueado y es admin
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../index.php");//lo redirige al login
    exit;
}

require_once '../includes/db.php';
require_once '../includes/auth.php';

// Verificar si el usuario está logueado y es admin
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Obtener el ID del usuario
if (!isset($_GET['id'])) {
    header("Location: registrar_usuario.php?error=No se especificó un usuario");
    exit;
}

$id = $_GET['id'];

// Obtener datos actuales del usuario
try {
    $stmt = $pdo->prepare("SELECT nombre, email, rol FROM usuarios WHERE id = ?");
    $stmt->execute([$id]);
    $usuario = $stmt->fetch();

    if (!$usuario) {
        header("Location: registrar_usuario.php?error=Usuario no encontrado");
        exit;
    }
} catch (PDOException $e) {
    header("Location: registrar_usuario.php?error=" . urlencode($e->getMessage()));
    exit;
}

// Procesar la actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $rol = $_POST['rol'];

    try {
        $stmt = $pdo->prepare("UPDATE usuarios SET nombre = ?, email = ?, rol = ? WHERE id = ?");
        $stmt->execute([$nombre, $email, $rol, $id]);

        header("Location: registrar_usuario.php?mensaje=Usuario actualizado correctamente");
        exit;
    } catch (PDOException $e) {
        $error = "Error al actualizar usuario: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="container mt-5">
    <h2 class="text-center mb-4">Editar Usuario</h2>

    <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

    <div class="card p-4">
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Nombre:</label>
                <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email:</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($usuario['email']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Rol:</label>
                <select name="rol" class="form-select" required>
                    <option value="vendedor" <?= $usuario['rol'] === 'vendedor' ? 'selected' : '' ?>>Vendedor</option>
                    <option value="admin" <?= $usuario['rol'] === 'admin' ? 'selected' : '' ?>>Administrador</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary w-100">Guardar Cambios</button>
        </form>
    </div>

    <p class="mt-3 text-center"><a href="registrar_usuario.php" class="btn btn-secondary">← Volver al registro</a></p>

    <script>
if (window.location.search.includes("mensaje=Usuario actualizado correctamente")) {
    Swal.fire({
        title: "¡Éxito!",
        text: "Usuario actualizado correctamente",
        icon: "success",
        confirmButtonText: "OK"
    });
}
</script>
</body>
</html>