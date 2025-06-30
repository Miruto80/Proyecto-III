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
      body {
        font-family: 'Segoe UI', sans-serif;
        background-color: #f9f9f9;
        padding: 20px;
        color: #333333;
      }
      .container {
        max-width: 600px;
        margin: auto;
        background-color: #ffffff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        padding: 30px;
      }
      .header {
        text-align: center;
        color: #df059d;
      }
      .codigo {
        background-color: #fce4f4;
        color: #df059d;
        font-weight: bold;
        font-size: 20px;
        padding: 10px 20px;
        display: inline-block;
        border-radius: 5px;
        margin: 20px 0;
      }
      .footer {
        font-size: 12px;
        text-align: center;
        color: #888;
        margin-top: 30px;
      }
    </style>
  </head>
  <body>
    <div class='container'>
      <h2 class='header'>Código de Verificación</h2>

      <p>Hola,</p>

      <p>Gracias por confiar en <strong>LoveMakeup C.A</strong>. Usa el siguiente código para completar tu recuperación:</p>

      <div class='codigo'>$codigo</div>

      <p>Si no solicitaste este código, ignora este mensaje.</p>

      <hr>

      <p><strong>LoveMakeup C.A</strong> es tu mejor aliado en productos de belleza y maquillaje. ¡Descubre tu mejor versión con nosotros!</p>

      <p>Telf.: +58 424 5115414<br> Correo: <a href='mailto:help.lovemakeupca@gmail.com'>help.lovemakeupca@gmail.com</a></p>

<!-- Redes Sociales -->
<div style='text-align: center; margin-top: 30px;'>
  <a href='https://www.instagram.com/lovemakeupyk/' target='_blank' style='margin: 0 10px;'>
    <img src='https://cdn-icons-png.flaticon.com/24/1384/1384031.png' alt='Instagram' style='vertical-align: middle;'>
  </a>
  <a href='https://www.facebook.com/lovemakeupyk/' target='_blank' style='margin: 0 10px;'>
    <img src='https://cdn-icons-png.flaticon.com/24/1384/1384005.png' alt='Facebook' style='vertical-align: middle;'>
  </a>
  <a href='https://wa.me/584245115414' target='_blank' style='margin: 0 10px;'>
    <img src='https://cdn-icons-png.flaticon.com/24/733/733585.png' alt='WhatsApp' style='vertical-align: middle;'>
  </a>
</div>


      <div class='footer'>
        © 2025 LoveMakeup C.A. Todos los derechos reservados.
      </div>
    </div>
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