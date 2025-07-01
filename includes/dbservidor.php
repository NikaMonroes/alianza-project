<?php
$host = 'sql201.infinityfree.com';
$dbname = 'if0_XXXX XXXX XXXX_cafeteria';
$user = 'if0_XXXXXXXX';
$pass = 'XXXXXXXXX';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    // Configura PDO para lanzar excepciones en caso de error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Puedes personalizar este mensaje para producción
    die("Conexión fallida: " . $e->getMessage());
}

?>
