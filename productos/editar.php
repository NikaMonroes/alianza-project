<?php

session_start();

// Verificar si el usuario está logueado y es admin
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../index.php");//lo redirige al login
    exit;
}

require_once '../includes/db.php';
require_once '../includes/auth.php';
require_once '../includes/header.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: listar.php');
    exit;
}

// Obtener producto actual
$stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ?");
$stmt->execute([$id]);
$producto = $stmt->fetch();

if (!$producto) {
    echo "<div class='container mt-5 text-danger'>Producto no encontrado.</div>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $referencia = $_POST['referencia'];
    $precio = $_POST['precio'];
    $peso = $_POST['peso'];
    $categoria = $_POST['categoria'];
    $stock = $_POST['stock'];

    $stmt = $pdo->prepare("UPDATE productos SET nombre = ?, referencia = ?, precio = ?, peso = ?, categoria = ?, stock = ? WHERE id = ?");
    $stmt->execute([$nombre, $referencia, $precio, $peso, $categoria, $stock, $id]);

    header('Location: listar.php');
    exit;
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


<div class="container mt-5">
    <h2 class="mb-4 text-center">Editar Producto</h2>

    <form method="POST" class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($producto['nombre']) ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Referencia</label>
            <input type="text" name="referencia" class="form-control" value="<?= htmlspecialchars($producto['referencia']) ?>" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Precio ($)</label>
            <input type="number" name="precio" class="form-control" value="<?= $producto['precio'] ?>" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Peso (g)</label>
            <input type="number" name="peso" class="form-control" step="0.01" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Categoría</label>
            <input type="text" name="categoria" class="form-control" value="<?= htmlspecialchars($producto['categoria']) ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Stock</label>
            <input type="number" name="stock" class="form-control" value="<?= $producto['stock'] ?>" required>
        </div>

        <div class="col-12 text-center">
            <button type="submit" class="btn btn-primary">Actualizar Producto</button>
            <a href="listar.php" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
