<?php
$to = "bienestaresmovimiento@gmail.com";  // Reemplázalo con tu email real
$subject = "Prueba de correo desde XAMPP";
$message = "Este es un correo de prueba enviado desde PHP en XAMPP.";
$headers = "From: prueba@tuweb.com";

if(mail($to, $subject, $message, $headers)) {
    echo "Correo enviado con éxito.";
} else {
    echo "Error al enviar el correo.";
}
?>
