<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // PHPMailer

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // 🔐 Validación de reCAPTCHA
    $secretKey = '6LeMWT8rAAAAAACnnQidX2DjXSvjHgFyGed_uEWP'; // Tu clave secreta real
    $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';

    $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secretKey}&response={$recaptchaResponse}&remoteip=" . $_SERVER['REMOTE_ADDR']);
    $captchaSuccess = json_decode($verify, true);

    if (!$captchaSuccess['success']) {
        echo json_encode(["success" => false, "error" => "❌ Verificá que no sos un robot."]);
        exit;
    }

    // 📥 Sanitización y validación
    $name = htmlspecialchars(trim($_POST['name'] ?? ''), ENT_QUOTES, 'UTF-8');
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars(trim($_POST['message'] ?? ''), ENT_QUOTES, 'UTF-8');

    if (!$name || !$email || !$message || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["success" => false, "error" => "❌ Datos inválidos en el formulario."]);
        exit;
    }

    try {
        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';

        // 📧 Configuración SMTP
        $mail->isSMTP();
        $mail->Host       = 'smtp.hostinger.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'pilates@bienestaresmovimiento.com';
        $mail->Password   = 'TU_CONTRASEÑA_REAL'; // ⚠️ Reemplazar por variable de entorno o .env
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        // 📤 Remitente y destinatario
        $mail->setFrom('pilates@bienestaresmovimiento.com', 'Bienestar es Movimiento');
        $mail->addReplyTo($email, $name);
        $mail->addAddress('bienestaresmovimiento@gmail.com', 'Bienestar es Movimiento');

        // 📨 Contenido del mensaje
        $mail->isHTML(true);
        $mail->Subject = '=?UTF-8?B?' . base64_encode("📩 Nuevo mensaje desde el formulario de contacto") . '?=';
        $mail->Body    = "
            <strong>Nombre:</strong> {$name}<br>
            <strong>Correo:</strong> {$email}<br>
            <strong>Mensaje:</strong><br><p>{$message}</p>
        ";

        // 📬 Enviar correo
        if ($mail->send()) {
            echo json_encode(["success" => true, "message" => "✅ Mensaje enviado con éxito."]);
        } else {
            echo json_encode(["success" => false, "error" => "❌ Error al enviar el mensaje."]);
        }
    } catch (Exception $e) {
        echo json_encode(["success" => false, "error" => "❌ Error SMTP: " . $mail->ErrorInfo]);
    }
} else {
    echo json_encode(["success" => false, "error" => "❌ No se recibieron datos del formulario."]);
}
?>
