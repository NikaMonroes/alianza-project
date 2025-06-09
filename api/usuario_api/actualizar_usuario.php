<?php
// api/usuario_api/actualizar_usuario.php actiualiza usuario por id
require_once '../../includes/db.php';

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
$id = $_GET['id'] ?? null;

if ($id && isset($data['nombre'], $data['email'])) {
    $stmt = $pdo->prepare("UPDATE usuarios SET nombre = ?, email = ? WHERE id = ?");
    if ($stmt->execute([$data['nombre'], $data['email'], $id])) {
        echo json_encode(["mensaje" => "Usuario actualizado"]);
    } else {
        echo json_encode(["error" => "Error al actualizar usuario"]);
    }
} else {
    echo json_encode(["error" => "Datos incompletos o ID no proporcionado"]);
}
?>