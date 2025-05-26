<?php 
require 'assets/phpmailer/src/Exception.php';
require 'assets/phpmailer/src/PHPMailer.php'; 
require 'assets/phpmailer/src/SMTP.php';

function enviarCodigoRecuperacion($correo, $codigo) {
    $mail = new PHPMailer\PHPMailer\PHPMailer();
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'help.lovemakeupca@gmail.com'; // Tu dirección de correo de Gmail
    $mail->Password = 'uoteptddjgljeukw'; // Tu contraseña de Gmail o contraseña de aplicación
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('help.lovemakeupca@gmail.com', 'Love Makeup');
    $mail->addAddress($correo);
    $mail->Subject = 'Codigo de Recuperacion de Clave';
    $mail->isHTML(true); // Habilitar HTML en el correo

    $mail->Body = "
    <html>
    <head>
        <style>
            .codigo {
                font-size: 15px;
                font-weight: bold;
                color: #df059d; /* Color personalizado */
            }

            .img{
                width:200px;
            }
        </style>
    </head>
    <body>

        <p>Estimado cliente</p>

        <p>Tu código de verificación es: <span class='codigo'>$codigo</span></p>
        <hr>
        <p>LoveMakeup C.A es tu mejor aliado en productos de belleza y maquillaje. Contamos con una amplia variedad de artículos diseñados para resaltar tu estilo y personalidad.</p>

        <p>Si necesitas más ayuda, no dudes en contactarnos:</p>
        <p> Teléfono: +58 424 5115414</p>
        <p> Correo: <a href='mailto:help.lovemakeupca@gmail.com'>help.lovemakeupca@gmail.com</a></p>
        
        <p>Gracias por confiar en <strong>LoveMakeup C.A.</strong></p>
        <p>¡Gracias por elegirnos!</p>

        <p>Atentamente,<br>El equipo de LoveMakeup C.A</p>
    </body>
    </html>
    ";
    if (!$mail->send()) {
        return false;
    } else {
        return true;
    }
}


?>