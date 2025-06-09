<?php

session_start();

// Validar que el usuario tenga rol vendedor o admin
if (!isset($_SESSION['rol']) || !in_array($_SESSION['rol'], ['admin', 'vendedor'])) {
    header("Location: ../index.php");//lo redirige al login
    exit;
}

require_once '../includes/db.php';
require_once '../includes/auth.php';
require_once '../includes/header.php';

require '../vendor/autoload.php';



// Variables para mensajes
$alert = '';
$error = '';
$productos = [];
$mensaje = "";
$productos_agotados = 0;

// Obtener categorías
$stmt = $pdo->query("SELECT DISTINCT categoria FROM productos");
$categorias = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Si se selecciona una categoría
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['categoria'])) {
    $categoria = $_POST['categoria'];
    
    // Consulta para productos con stock
    $stmt = $pdo->prepare("SELECT * FROM productos WHERE categoria = ? AND stock > 0");
    $stmt->execute([$categoria]);
    $productos = $stmt->fetchAll();
    
    // Consulta para contar productos agotados
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM productos WHERE categoria = ? AND stock = 0");
    $stmt->execute([$categoria]);
    $productos_agotados = $stmt->fetchColumn();
}

// Agregar al carrito
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agregar'])) {
    $producto_id = $_POST['producto_id'];
    $cantidad = intval($_POST['cantidad']);
    $mensaje = "";

    // Verificar stock
    $stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ?");
    $stmt->execute([$producto_id]);
    $producto = $stmt->fetch();

    if ($producto && $producto['stock'] >= $cantidad) {
        // Guardar en sesión
        $_SESSION['carrito'][] = [
            'id' => $producto['id'],
            'nombre' => $producto['nombre'],
            'precio' => $producto['precio'],
            'cantidad' => $cantidad
        ];

        // Actualizar stock
        $nuevo_stock = $producto['stock'] - $cantidad;
        $stmt = $pdo->prepare("UPDATE productos SET stock = ? WHERE id = ?");
        $stmt->execute([$nuevo_stock, $producto_id]);

        // Redireccionar para limpiar POST
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        $mensaje = "Stock insuficiente o producto no encontrado.";
    }
}

// Eliminar un producto del carrito
if (isset($_POST['eliminar_producto'])) {
    $index = $_POST['producto_index'];
    unset($_SESSION['carrito'][$index]);
    $_SESSION['carrito'] = array_values($_SESSION['carrito']); // Reindexar
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Finalizar venta (generar PDF)
if (isset($_POST['finalizar_venta'])) {
    $total = array_sum(array_map(fn($item) => $item['precio'] * $item['cantidad'], $_SESSION['carrito']));
    $productos = $_SESSION['carrito'];
    $fecha = date("Y-m-d H:i:s");
    $vendedor = $_SESSION['usuario'] ?? 'admin';
    $pago = $_POST['pago'] ?? 0;
    $devuelta = $pago - $total;

    // Generar número de factura secuencial
    $stmt = $pdo->query("SELECT COUNT(*) + 1 AS numero FROM facturas");
    $numero = $stmt->fetch()['numero'];
    $numero_factura = 'F' . str_pad($numero, 5, '0', STR_PAD_LEFT);

  // Guardar la factura en DB
$stmt = $pdo->prepare("INSERT INTO facturas (numero_factura, fecha, total, vendedor) VALUES (?, ?, ?, ?)");
$stmt->execute([$numero_factura, $fecha, $total, $vendedor]);

// Obtener el ID de la factura recién creada
$factura_id = $pdo->lastInsertId();  

// Guardar los productos en detalle_factura
foreach ($productos as $item) {
    $stmtDetalle = $pdo->prepare("INSERT INTO detalle_factura (factura_id, producto_id, cantidad, precio_unitario) VALUES (?, ?, ?, ?)");
    $stmtDetalle->execute([$factura_id, $item['id'], $item['cantidad'], $item['precio']]);
}
    // Guardar en sesión para generar factura PDF
    $_SESSION['ultima_venta'] = [
        'numero_factura' => $numero_factura,
        'productos' => $productos,
        'total' => $total,
        'fecha' => $fecha,
        'vendedor' => $vendedor,
        'pago' => $pago,
        'devuelta' => $devuelta
    ];

    unset($_SESSION['carrito']);
    header("Location: factura_pdf.php");
    exit;
}


// Finalizar (guardar en BD y limpiar carrito)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['finalizar']) && !empty($_SESSION['carrito'])) {
    foreach ($_SESSION['carrito'] as $item) {
        $stmt = $pdo->prepare("UPDATE productos SET stock = stock - ? WHERE id = ?");
        $stmt->execute([$item['cantidad'], $item['id']]);

        $stmt = $pdo->prepare("INSERT INTO ventas (producto_id, cantidad, fecha_venta) VALUES (?, ?, CURDATE())");
        $stmt->execute([$item['id'], $item['cantidad']]);
    }

    $_SESSION['carrito'] = []; // Vaciar carrito
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Carrito de productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .producto-agotado {
            color: #dc3545;
            font-weight: bold;
        }
    </style>
</head>
<body>
    
<div class="container mt-5">
    <h1 class="mb-4">Agregar productos al carrito</h1>

    <div class="d-flex gap-2" >
      <!-- Boton de gestion de facturas solo administradores -->
<?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
    
              
        <p><a href="../admin/facturas.php" class="btn btn-success">Gestionar Facturas</a></p>    
        <p><a href="../includes/dashboard_admin.php" class="btn btn-secondary">← Volver al panel Administrador</a></p>
<?php endif; ?>
    </div>

    
    <?php if ($mensaje): ?>
        <div class="alert alert-danger"><?= $mensaje ?></div>
    <?php endif; ?>

    <!-- Formulario para elegir categoría -->
    <form method="POST" class="mb-3">
        <div class="input-group">
            <label class="input-group-text" for="categoria">Filtrar por categoría:</label>
            <select name="categoria" id="categoria" class="form-select" required>
                <option value="" disabled selected>Selecciona una categoría</option>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= htmlspecialchars($cat) ?>" <?= isset($_POST['categoria']) && $_POST['categoria'] == $cat ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-primary">Buscar</button>
        </div>
    </form>

    <!-- Mostrar alerta si hay productos agotados -->
    <?php if ($productos_agotados > 0): ?>
        <div class="alert alert-warning">
            Hay <?= $productos_agotados ?> producto(s) agotado(s) en esta categoría.
        </div>
    <?php endif; ?>

    <!-- Mostrar productos filtrados si hay -->
    <?php if (!empty($productos)): ?>
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Agregar</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $producto): ?>
                    <tr>
                        <td><?= htmlspecialchars($producto['nombre']) ?></td>
                        <td>$<?= number_format($producto['precio']) ?></td>
                        <td><?= $producto['stock'] ?></td>
                        <td>
                            <form method="POST" class="d-flex align-items-center">
                                <input type="hidden" name="producto_id" value="<?= $producto['id'] ?>">
                                <input type="number" name="cantidad" min="1" max="<?= $producto['stock'] ?>" value="1" class="form-control form-control-sm me-2" style="width: 80px;" required>
                                <button type="submit" name="agregar" class="btn btn-success btn-sm">Agregar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

                      



    <?php elseif (isset($_POST['categoria'])): ?>
        <div class="alert alert-info">
            No hay productos disponibles en esta categoría.
        </div>
    <?php endif; ?>
    
    <?php if (!empty($_SESSION['carrito'])): ?>
    <h3 class="mt-5">🛒 Carrito actual</h3>
    <form method="POST">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                foreach ($_SESSION['carrito'] as $i => $item):
                    $subtotal = $item['precio'] * $item['cantidad'];
                    $total += $subtotal;
                ?>
                    <tr>
                        <td><?= htmlspecialchars($item['nombre']) ?></td>
                        <td><?= $item['cantidad'] ?></td>
                        <td>$<?= number_format($subtotal) ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="producto_index" value="<?= $i ?>">
                                <button type="submit" name="eliminar_producto" class="btn btn-sm btn-danger">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr class="table-light">
                    <th colspan="2">Total a pagar</th>
                    <th colspan="2">$<?= number_format($total) ?></th>
                </tr>
            </tfoot>
        </table>
    </form>

        <!-- Formulario de pago -->
        <form method="POST" class="row g-3 align-items-center">
            <div class="col-auto">
                <label for="pago" class="col-form-label">💰 Cliente paga con:</label>
            </div>
            <div class="col-auto">
                <input type="number" name="pago" id="pago" class="form-control" min="<?= $total ?>" required oninput="calcularDevuelta()" placeholder="Ej. 5000">
            </div>
            <div class="col-auto">
                <span id="devuelta" class="form-text fw-bold text-success"></span>
            </div>
            <div class="col-auto">
                <button type="submit" name="finalizar_venta" class="btn btn-primary">Finalizar venta 🧾</button>
            </div>
        </form>

        <!-- Formulario separado para limpiar -->
        <form method="POST" class="mt-2">
            <button type="submit" name="finalizar" class="btn btn-success">Limpiar</button>
        </form>


    <script>
        function calcularDevuelta() {
            const pago = parseFloat(document.getElementById('pago').value);
            const total = <?= $total ?>;
            const devuelta = pago - total;

            if (!isNaN(devuelta)) {
                document.getElementById('devuelta').textContent = "Devuelta: $" + devuelta.toLocaleString();
            } else {
                document.getElementById('devuelta').textContent = "";
            }
        }
    </script>
    <?php endif; ?>
</div>
</body>
</html>

