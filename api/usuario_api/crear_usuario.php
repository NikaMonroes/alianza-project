<?php
require_once '../../includes/db.php';

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['nombre'], $data['email'], $data['password'], $data['rol'])) {
    $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$data['nombre'], $data['email'], $hashedPassword, $data['rol']])) {
        echo json_encode(["mensaje" => "Usuario creado con éxito"]);
    } else {
        echo json_encode(["error" => "Error al registrar usuario"]);
    }
} else {
    echo json_encode(["error" => "Datos incompletos"]);
}
?>