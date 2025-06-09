<?php
require_once '../../includes/db.php';

header("Content-Type: application/json");
$data = json_decode(file_get_contents("php://input"), true);
$id = $_GET['id'] ?? null;

if ($id && isset($data['nombre'], $data['precio'], $data['stock'])) {
    $stmt = $pdo->prepare("UPDATE productos SET nombre = ?, precio = ?, stock = ? WHERE id = ?");
    if ($stmt->execute([$data['nombre'], $data['precio'], $data['stock'], $id])) {
        echo json_encode(["mensaje" => "Producto actualizado"]);
    } else {
        echo json_encode(["error" => "Error al actualizar producto"]);
    }
} else {
    echo json_encode(["error" => "Datos incompletos o ID no proporcionado"]);
}
?>