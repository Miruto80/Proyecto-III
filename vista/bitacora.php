<!DOCTYPE html>
<html lang="es">

<head>
  <!-- php barra de navegacion-->
  <?php include 'complementos/head.php' ?>
  <title> Clave de Seguridad | LoveMakeup </title> 
</head>

<body class="g-sidenav-show bg-gray-100">
  
<!-- php barra de navegacion-->
<?php include 'complementos/sidebar.php' ?>

<main class="main-content position-relative border-radius-lg ">
<!-- ||| Navbar ||-->
<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur" data-scroll="false">
  <div class="container-fluid py-1 px-3">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="#">Administrar Usuario</a></li>
        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Bitacora</li>
      </ol>
      <h6 class="font-weight-bolder text-white mb-0">Clave de Seguridad</h6>
    </nav>
<!-- php barra de navegacion-->    
<?php include 'complementos/nav.php' ?>

<div class="container-fluid py-4"> <!-- DIV CONTENIDO -->

<div class="row"> <!-- CARD PRINCIPAL-->
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0"> <!-- CARD N-1 -->
                <!-- Nota -->
                <div class="alert alert-light" role="alert">
                    <i class="fa-solid fa-circle-info"></i> Nota: coloque la clave especial para ingresar a la bitácora
                </div>
                <br>
                <!-- Campo de entrada para Clave Dinámica -->
                <div class="mb-3 text-center">
                    <h5>Clave Especial</h5>
                    <div class="input-group" style="justify-content: center; width: 40%; max-width: 400px; margin: auto;">
                        <div class="password-input">  
                            <input id="password"  type="password" class="form-control" placeholder="Escriba su clave aquí">
                            <span id="show-password" class="fa fa-eye"></span>
                        </div> 
                    </div>
                </div>
                <br>
                <!-- Botones -->
                <div class="d-flex justify-content-between">
                    <a href="?pagina=home" class="btn btn-danger">Regresar</a>
                    <form method="POST" action="?pagina=bitacora">
                        <button type="submit" class="btn btn-primary" name="entrar">Continuar</button>
                    </form>
                </div>
            </div> <!-- FIN CARD N-1 -->
        </div>
    </div>
    <br><br>
</div><!-- FIN CARD PRINCIPAL-->


<!-- php barra de navegacion-->
<?php include 'complementos/footer.php' ?>
<script src="assets/js/bitacora.js"></script>
</body>

</html>