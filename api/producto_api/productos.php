<?php
require_once '../../includes/db.php';

header("Content-Type: application/json");

$stmt = $pdo->query("SELECT id, nombre, precio, stock, categoria FROM productos");
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($productos);
?>