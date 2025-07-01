<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Asegúrate de que la ruta es correcta

function enviarCorreo($destinatario, $asunto, $mensaje) {
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP (Usa Gmail, Outlook o tu propio servidor)
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Cambia según tu proveedor (ej. smtp.office365.com)
        $mail->SMTPAuth = true;
        $mail->Username = 'monicaromerofreelance@gmail.com'; // Tu correo
        $mail->Password = 'XXXX XXXX XXXX XXXX'; // Usa una contraseña de aplicación si usas Gmail
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Configuración del remitente
        $mail->setFrom('monicaromerofreelance@gmail.com', 'Cafeteria Alianza');
        $mail->addAddress($destinatario);

        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = $asunto;
        $mail->Body = $mensaje;

        // Enviar
        $mail->send();
        return true;
    } catch (Exception $e) {
        return "Error: " . $mail->ErrorInfo;
    }
}
?>
