<?php
$host = 'sql201.infinityfree.com';
$dbname = 'if0_39105532_cafeteria';
$user = 'if0_39105532';
$pass = 'P2Px17KZlREOLK';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    // Configura PDO para lanzar excepciones en caso de error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Puedes personalizar este mensaje para producción
    die("Conexión fallida: " . $e->getMessage());
}

?>