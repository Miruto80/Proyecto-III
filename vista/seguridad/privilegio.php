<!DOCTYPE html>
<html lang="es">

<head>
  <!-- php barra de navegacion-->
  <?php include 'vista/complementos/head.php' ?> 
  <title> Acceso Denegado | LoveMakeup  </title> 
</head>

<body class="g-sidenav-show bg-gray-100">
  
<!-- php barra de navegacion-->
<?php include 'vista/complementos/sidebar.php' ?>

<main class="main-content position-relative border-radius-lg ">
<!-- ||| Navbar ||-->
<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur" data-scroll="false">
  <div class="container-fluid py-1 px-3">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="#">Error</a></li>
        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Acceso Denegado</li>
      </ol>
      <h6 class="font-weight-bolder text-white mb-0">Acceso Denegado</h6>
    </nav>
<!-- php barra de navegacion-->    
<?php include 'vista/complementos/nav.php' ?>
<!-- |||||||||||||||| LOADER ||||||||||||||||||||-->
  <div class="preloader-wrapper">
    <div class="preloader">
    </div>
  </div> 
<!-- |||||||||||||||| LOADER ||||||||||||||||||||-->

<div class="container-fluid py-4"> <!-- DIV CONTENIDO -->

    <div class="row"> <!-- CARD PRINCIPAL-->  
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0">  <!-- CARD N-1 -->  
    
   
          

     <div class="row">
  <div class="col margen">

  <h2 class="texto"> <b> Estimado Usuario: </b> </h2>
  <h2 class="text-primary"> <b> <?php echo $_SESSION['nombre']." ".$_SESSION['apellido']; ?> </b> </h2>
  <br>  
  <h3 class="text-dark mb-3">Acceso denegado. Por favor, contacte al administrador para solicitar los permisos correspondientes.</h3>  

  <a href="?pagina=home" class="btn btn-primary"> <i class="fas fa-home mr-2"></i> Volver al Inicio</a>

  </div>
  <div class="col">
  <img src="assets/img/S1.svg" alt="" class="mx-auto d-block img-fluid">
  </div>

</div>

</div> <!-- Fin Div contenido Margen-->

     


            </div><!-- FIN CARD N-1 -->  
    </div>
    </div>  
    </div><!-- FIN CARD PRINCIPAL-->  




<!-- php barra de navegacion-->
<?php include 'vista/complementos/footer.php' ?>

</body>

</html>