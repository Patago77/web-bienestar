<?php
file_put_contents("debug_log.txt", print_r($_POST, true));

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// Cargar variables de entorno
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Inicializar PHPMailer
$mail = new PHPMailer(true);
$mail->CharSet = 'UTF-8'; // Asegurar que use UTF-8
$mail->Encoding = 'base64'; // Evitar problemas de codificación


// Verificar si el formulario fue enviado con POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario con sanitización
    $name = htmlspecialchars($_POST['name'] ?? '', ENT_QUOTES, 'UTF-8');
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars($_POST['message'] ?? '', ENT_QUOTES, 'UTF-8');

        // Evitar inyecciones de correo en el campo email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(["success" => false, "error" => "❌ Correo inválido"]);
            exit;
        }
    

    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host       = $_ENV['SMTP_HOST'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV['SMTP_USER'];
        $mail->Password   = $_ENV['SMTP_PASS'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Usa SSL
        $mail->Port       = 465; // Puerto para SSL

        // Configurar destinatario y remitente
        $mail->setFrom($_ENV['SMTP_USER'], "Bienestar es Movimiento"); // Evita usar el email del usuario aquí
        $mail->addReplyTo($email, $name); // Responder al usuario que envió el mensaje
        $mail->addAddress('bienestaresmovimiento@gmail.com', 'Bienestar es Movimiento');

        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = '=?UTF-8?B?' . base64_encode("📨 Nuevo mensaje desde el formulario de contacto") . '?=';
        $mail->Body    = "<h3>Nombre:</h3> $name <br>
                          <h3>Correo:</h3> $email <br>
                          <h3>Mensaje:</h3> $message";

        // Enviar correo y responder en JSON
        if ($mail->send()) {
            header("Location: exito.html"); // Redirige a la página de éxito
           
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
