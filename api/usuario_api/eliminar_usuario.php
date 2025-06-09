<?php
require_once '../../includes/db.php';

header("Content-Type: application/json");

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
    if ($stmt->execute([$id])) {
        echo json_encode(["mensaje" => "Usuario eliminado"]);
    } else {
        echo json_encode(["error" => "Error al eliminar usuario"]);
    }
} else {
    echo json_encode(["error" => "ID no proporcionado"]);
}
?>