<?php
require_once '../../includes/db.php';

header("Content-Type: application/json");
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['nombre'], $data['referencia'], $data['precio'], $data['peso'], $data['categoria'], $data['stock'])) {
    $stmt = $pdo->prepare("INSERT INTO productos (nombre, referencia, precio, peso, categoria, stock, fecha_creacion) VALUES (?, ?, ?, ?, ?, ?, CURDATE())");
    if ($stmt->execute([$data['nombre'], $data['referencia'], $data['precio'], $data['peso'], $data['categoria'], $data['stock']])) {
        echo json_encode(["mensaje" => "Producto creado con éxito"]);
    } else {
        echo json_encode(["error" => "Error al registrar producto"]);
    }
} else {
    echo json_encode(["error" => "Datos incompletos"]);
}
?>