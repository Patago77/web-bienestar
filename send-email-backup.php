<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    // Configurar SMTP
    $mail->isSMTP();
    $mail->Host       = 'smtp.hostinger.com'; // Servidor SMTP
    $mail->SMTPAuth   = true;
    $mail->Username   = 'pilates@bienestaresmovimiento.com'; // Tu correo de Hostinger
    $mail->Password   = 'TuContraseñaSegura'; // Contraseña SMTP
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Cambia a ENCRYPTION_SMTPS si usas SSL
    $mail->Port       = 587; // Cambia a 465 si usas SSL

    // Configurar destinatario
    $mail->setFrom('pilates@bienestaresmovimiento.com', 'Bienestar es Movimiento');
    $mail->addAddress('bienestaresmovimiento@gmail.com', 'Destinatario');

    // Contenido del correo
    $mail->isHTML(true);
    $mail->Subject = 'Prueba de SMTP en Hostinger';
    $mail->Body    = '<h3>Si recibes este correo, el servidor SMTP funciona correctamente.</h3>';

    $mail->send();
    echo '✅ Correo enviado con éxito.';
} catch (Exception $e) {
    echo "❌ Error al enviar el correo: {$mail->ErrorInfo}";
}
?>
