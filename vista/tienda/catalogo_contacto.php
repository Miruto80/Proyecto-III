<!DOCTYPE html>
<html lang="en">

<head>
<!-- php CSS, Meta y titulo--> 
<?php include 'vista/complementos/head_catalogo.php' ?>

</head>

<body>

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


<section class="section-padding pt-0">
    <div class="container">

       <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb" class="custom-breadcrumb mt- mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="?pagina=catalogo">Inicio</a></li>
             <li class="breadcrumb-item active" aria-current="page">Contacto</li>
        </ol>
      </nav>
      <div class="row">
        <div class="section-header d-flex align-items-center justify-content-between mb-lg-2">
          <h2 class="section-title">Contacto y Ubicación</h2>
        </div>
      </div>
      <div class="row justify-content-center align-items-center">
        <div class="col-md-12">
          <h2 class="fw-bold fs-1 mt-5">
            Tienda física ubicada en la av 20 con calles 29 y 30 CC Barquisimeto plaza, Estado Lara, Venezuela. Ven y visítanos
          </h2>   
        </div>
   <div class="col-md-12 mt-3">
  <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3928.392977210938!2d-69.3236822!3d10.0668507!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8e8767c1ba2d21fb%3A0x6864564ca75c44e4!2sBarquisimeto%20Plaza!5e0!3m2!1ses!2sve!4v1747239622868!5m2!1ses!2sve"  
    width="100%" 
    height="450" 
    style="border: 0;" 
    allowfullscreen="" 
    loading="lazy" 
    referrerpolicy="no-referrer-when-downgrade">
  </iframe>
   </div>
      </div>
    </div>
  </section>

<section id="latest-blog" class="section-padding pt-0">
    <div class="container-lg">
      
      <div class="row">
        <div class="col-md-4">
          <div class="post-item card border-1 border-light shadow-sm p-3">
            <div class="image-holder zoom-effect">
              <a href="#">
                <img src="assets/img/b7.jfif" alt="post" class="card-img-top">
              </a>
            </div>
            <div class="card-body">
              
              <div class="post-header">
                <h3 class="fs-5 fw-normal">
                  <a href="#" class="text-decoration-none">WhatsApp | +58 424 5115414 </a>
                </h3>
                <p>¡Escríbenos por WhatsApp! Te brindamos la mejor atención para que hagas tu compra con confianza.</p>
                   <a href="https://wa.link/0e2clu" class="btn btn-primary btn-lg" target="_blank"> Ir al WhatsApp </a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <article class="post-item card border-1 border-light shadow-sm p-3">
            <div class="image-holder zoom-effect">
              <a href="#">
                <img src="assets/img/b50.jpg" alt="post" class="card-img-top">
              </a>
            </div>
            <div class="card-body">
              
              <div class="post-header">
                <h3 class="fs-5 fw-normal">
                  <a href="#" class="text-decoration-none">Horario de Atención</a>
                </h3>
                <p>¡Te atendemos con gusto! Nuestro horario es de lunes a sábado, de 9:00 AM a 5:00 PM, Tienda Fisica</p>
              </div>
            </div>
          </article>
        </div>
        <div class="col-md-4">
          <article class="post-item card border-1 border-light shadow-sm p-3">
            <div class="image-holder zoom-effect">
              <a href="#">
                <img src="assets/img/b6.png" alt="post" class="card-img-top">
              </a>
            </div>
            <div class="card-body">
             
              <div class="post-header">
                <h3 class="fs-5 fw-normal">
                  <a href="#" class="text-decoration-none">Instagram | @lovemakeupyk</a>
                </h3>
                <p>¡Síguenos en Instagram! Te brindamos la mejor atención para que hagas tu compra con confianza..</p>
                  <a href="https://www.instagram.com/lovemakeupyk/" class="btn btn-primary btn-lg" target="_blank">Ir a Instagram</a>

              </div>
            </div>
          </article>
        </div>
      </div>
    </div>
  </section>
<!-- php Publicidad Insta, Publicidad calidad, footer y JS--> 
<?php include 'vista/complementos/footer_catalogo.php' ?>
  
</body>

</html>