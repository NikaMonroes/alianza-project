<?php

session_start();

// Verificar si el usuario está logueado y es admin
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../index.php");//lo redirige al login
    exit;
}

require_once '../includes/db.php';
require_once '../includes/header.php';
require_once '../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $rol = $_POST['rol'];

    // Encriptar la contraseña
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nombre, $email, $hashedPassword, $rol]);

        $mensaje = "Usuario registrado con éxito.";
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}

// Obtener todos los usuarios para mostrar en la tabla
try {
    $consulta = $pdo->query("SELECT id, nombre, email, rol FROM usuarios ORDER BY id DESC");
    $usuarios = $consulta->fetchAll();
} catch (PDOException $e) {
    $errorConsulta = "Error al obtener los usuarios: " . $e->getMessage();
}
?>



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
        <div class="bg-secondary text-white text-center py-3">
            <h2 class="fw-bold">🥷💂‍♀️🧑‍🚀👩‍✈️👩‍🎨🐧🐔🐱🐺 😊  Registrar nuevo usuario 😊 🥷💂‍♀️🧑‍🚀👩‍✈️👩‍🎨🐧🐔🐱🐺</h2>
        </div>
    </div>
<div class="container mt-5">
     
    <?php if (isset($mensaje)) echo "<div class='alert alert-success'>$mensaje</div>"; ?>
    <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    
    <div class="card p-4">
        <form method="POST">
            <div class="row">
            <div class="col-md-6">
                <label class="form-label">Nombre:</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Email:</label>
                <input type="email" name="email" class="form-control" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <label class="form-label">Contraseña:</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Rol:</label>
                <select name="rol" class="form-select" required>
                    <option value="vendedor">Vendedor</option>
                    <option value="admin">Administrador</option>
                </select>
            </div>
        </div>
            <div class="mt-3">
            <button type="submit" class="btn btn-primary w-30">Registrar Usuario</button>
            </div>
        </form>
    </div>

    <h3 class="mt-5">Usuarios registrados</h3>

    <?php if (!empty($usuarios)) : ?>
       <table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Rol</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($usuarios as $u) : ?>
            <tr>
                <td><?= $u['id'] ?></td>
                <td><?= htmlspecialchars($u['nombre']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td><?= $u['rol'] ?></td>
                <td>
                    <a href="editar_usuario.php?id=<?= $u['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
    <button class="btn btn-danger btn-sm" onclick="confirmarEliminacion(<?= $u['id'] ?>)">Eliminar</button>
</td>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
    <?php else : ?>
        <p class="text-center text-muted">No hay usuarios registrados aún.</p>
    <?php endif; ?>


    <p><a href="../includes/dashboard_admin.php" class="btn btn-secondary">← Volver al panel</a></p>
</div>

    <script>
function confirmarEliminacion(id) {
    Swal.fire({
        title: "¿Estás seguro?",
        text: "¡Esta acción no se puede deshacer!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "eliminar_usuario.php?id=" + id;
        }
    });
}

</script>
</body>
</html>