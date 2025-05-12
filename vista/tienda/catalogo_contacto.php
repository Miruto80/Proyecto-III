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
       
      <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
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
        <div class="col-md-5">
          <h2 class="fw-bold fs-1 mt-5">
            Somos tienda física ubicada en la av 20 con calles 29 y 30 CC Barquisimeto plaza, Estado Lara, Venezuela. Ven y visítanos
          </h2>   
            <div class="d-grid gap-2">
              <a href="https://www.instagram.com/lovemakeupyk/" class="btn btn-dark btn-lg" target="_blank">Ir a Google Maps</a>
            </div>
        
        </div>
        <div class="col-md-7">
          <img src="assets/img/02.jpg" alt="image" class="img-fluid">
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
                <img src="assets/img/b5.png" alt="post" class="card-img-top">
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