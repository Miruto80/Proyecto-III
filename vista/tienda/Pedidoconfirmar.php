<!DOCTYPE html>
<html lang="en">

<head>
<!-- php CSS, Meta y titulo--> 
<?php include 'vista/complementos/head_catalogo.php' ?>

</head>
<style>
  .text-color1{
    color: #ff009a;
  }

  .oculto {
  display: none !important;
}

    .pasos-container {
      display: flex;
      justify-content: space-between;
      align-items: center;
      max-width: 600px;
      margin: 50px auto;
    }

    .paso {
      text-align: center;
      position: relative;
      flex: 1;
    }

    .paso:not(:last-child)::after {
      content: '';
      position: absolute;
      top: 15px;
      right: -50%;
      width: 100%;
      height: 2px;
      background-color: #ccc;
      z-index: 0;
    }

    .circulo {
      width: 30px;
      height: 30px;
      border-radius: 50%;
      margin: 0 auto 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      color: white;
      position: relative;
      z-index: 1;
    }

    .completado .circulo {
      background-color: #f679d4; /* amarillo */
    }

    .actual .circulo {
      background-color: #4fa7fa; /* naranja */
    }

    .pendiente .circulo {
      background-color: #adb5bd; /* gris */
    }

    .paso span {
      font-size: 14px;
    }

    .sombra-suave {
      box-shadow: 0 4px 12px rgba(255, 105, 180, 0.3); 
      }


    .confirmacion-box {
      max-width: 700px;
      margin: 10px auto;
      padding: 40px;
      border-radius: 20px;
      text-align: center;
      box-shadow: 0 8px 20px rgba(255, 105, 180, 0.3);
      background-color: #e4ffeb;
    }

    .confirmacion-box i {
      font-size: 64px;
      color: #10fe4d; 
      margin-bottom: 20px;
    }

    .confirmacion-box h2 {
      font-weight: 600;
      color: #212529;
    }

    .confirmacion-box p {
      font-size: 16px;
      color: #6c757d;
    }

    .footer-text {
      font-size: 13px;
      margin-top: 20px;
      color: #adb5bd;
    }


  </style>
<!-- |||||||||||||||| LOADER ||||||||||||||||||||-->
  <div class="preloader-wrapper">
    <div class="preloader">
    </div>
  </div>
<!-- |||||||||||||||| LOADER ||||||||||||||||||||-->

<!-- php CARRITO--> 
<?php include 'vista/complementos/carrito.php' ?>

<!-- php ENCABEZADO LOGO, ICONO CARRITO Y LOGIN--> 
<?php include 'vista/complementos/nav_catalogo.php' ?>

<section id="latest-blog" class="section-padding pt-0">
    <div class="container-lg">
        <div class="pasos-container">
    <div class="paso completado">
      <div class="circulo">1</div>
      <span>Producto</span>
    </div>
    <div class="paso completado">
      <div class="circulo">2</div>
      <span>Entrega</span>
    </div>
    <div class="paso completado">
      <div class="circulo">3</div>
      <span>Pago</span>
    </div>
    <div class="paso actual">
      <div class="circulo">4</div>
      <span>Confirmación</span>
    </div>
  </div>



<div class="confirmacion-box">
    <i class="fas fa-check-circle"></i>
    <h2>Tu pedido se a registrar con exito</h2>
    <p>¡Gracias por tu compra!. Tu pedido está en proceso de verificación de pago por parte de nuestro equipo. Una vez confirmado, se procederá al retiro o envío según lo que seleccionaste.</p>

    <p class="footer-text">¡Agradecemos tu confianza y preferencia!</p>
    <a href="?pagina=catalogo" class="btn btn-primary">Continuar</a>
  </div>





    </div>

  </section>




<!-- php Publicidad Insta, Publicidad calidad, footer y JS--> 
<?php include 'vista/complementos/footer_catalogo.php' ?>
  
</body>

</html>