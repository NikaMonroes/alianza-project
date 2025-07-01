<?php
$host = 'sqlXXX.infinityfree.com';
$dbname = 'if0_XXXX_cafeteria'; //database
$user = 'if0_XXXXX'; //username
$pass = 'XXXXXX'; // password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    // Configura PDO para lanzar excepciones en caso de error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Puedes personalizar este mensaje para producción
    die("Conexión fallida: " . $e->getMessage());
}

?>
