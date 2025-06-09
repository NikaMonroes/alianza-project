<?php
require_once 'includes/emailservice.php'; // ojo con la ruta que no lleva ../includes - sino es directamente la carpeta


// Datos de la cuenta de correo


$destinatario = "monroes74@gmail.com";
$asunto = "¡Gracias por tu compra!";
$mensaje = "<h1>Tu pedido ha sido procesado</h1><p>Gracias por confiar en nosotros.</p>";

$resultado = enviarCorreo($destinatario, $asunto, $mensaje);

echo $resultado === true ? "Correo enviado con éxito" : "Error al enviar: $resultado";
?>