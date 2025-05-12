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

<section id="latest-blog" class="section-padding pt-0">
    <div class="container-lg">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="?pagina=catalogo">Inicio</a></li>
             <li class="breadcrumb-item active" aria-current="page">Consejos</li>
        </ol>
      </nav>
      <div class="row">
        <div class="section-header d-flex align-items-center justify-content-between mb-lg-2">
          <h2 class="section-title">Consejos</h2>
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
          <article class="post-item card border-1 border-light shadow-sm p-3">
            <div class="image-holder zoom-effect">
              <a href="#">
                <img src="images/post-thumbnail-1.jpg" alt="post" class="card-img-top">
              </a>
            </div>
            <div class="card-body">
              <div class="post-meta d-flex text-uppercase gap-3 my-3 align-items-center">
                <div class="meta-date"><a href="blog.html" class="text-decoration-none">22 Aug 2021</a></div>
                <div class="meta-categories"><a href="blog.html" class="text-decoration-none">tips & tricks</a></div>
              </div>
              <div class="post-header">
                <h3 class="fs-5 fw-normal">
                  <a href="#" class="text-decoration-none">Tips for Keeping Your Furry Friend Happy and Healthy</a>
                </h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipi elit. Aliquet eleifend viverra enim tincidunt donec
                  quam...</p>
              </div>
            </div>
          </article>
        </div>
        <div class="col-md-4">
          <article class="post-item card border-1 border-light shadow-sm p-3">
            <div class="image-holder zoom-effect">
              <a href="#">
                <img src="images/post-thumbnail-2.jpg" alt="post" class="card-img-top">
              </a>
            </div>
            <div class="card-body">
              <div class="post-meta d-flex text-uppercase gap-3 my-3 align-items-center">
                <div class="meta-date"><a href="blog.html" class="text-decoration-none">22 Aug 2021</a></div>
                <div class="meta-categories"><a href="blog.html" class="text-decoration-none">tips & tricks</a></div>
              </div>
              <div class="post-header">
                <h3 class="fs-5 fw-normal">
                  <a href="#" class="text-decoration-none">Top 10 Must-Have Pet Products Every Pet Owner Needs</a>
                </h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipi elit. Aliquet eleifend viverra enim tincidunt donec
                  quam...</p>
              </div>
            </div>
          </article>
        </div>
        <div class="col-md-4">
          <article class="post-item card border-1 border-light shadow-sm p-3">
            <div class="image-holder zoom-effect">
              <a href="#">
                <img src="images/post-thumbnail-3.jpg" alt="post" class="card-img-top">
              </a>
            </div>
            <div class="card-body">
              <div class="post-meta d-flex text-uppercase gap-3 my-3 align-items-center">
                <div class="meta-date"><a href="blog.html" class="text-decoration-none">22 Aug 2021</a></div>
                <div class="meta-categories"><a href="blog.html" class="text-decoration-none">tips & tricks</a></div>
              </div>
              <div class="post-header">
                <h3 class="fs-5 fw-normal">
                  <a href="#" class="text-decoration-none">How to Choose the Perfect Pet: A Guide for First-Time Pet Owners</a>
                </h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipi elit. Aliquet eleifend viverra enim tincidunt donec
                  quam...</p>
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