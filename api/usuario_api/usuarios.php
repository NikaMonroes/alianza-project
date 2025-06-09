<?php
require_once '../../includes/db.php';

header("Content-Type: application/json");

$stmt = $pdo->query("SELECT id, nombre, email, rol FROM usuarios");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($usuarios);
?>