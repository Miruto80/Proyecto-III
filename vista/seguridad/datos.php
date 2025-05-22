<!DOCTYPE html>
<html lang="es">

<head> 
  <!-- php barra de navegacion-->
  <?php include 'vista/complementos/head.php' ?> 
  <title> Cambiar Datos | LoveMakeup  </title> 
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
        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="#">Administrar Usuario</a></li>
        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Usuario</li>
      </ol>
      <h6 class="font-weight-bolder text-white mb-0">Cambiar Datos del Usuario</h6>
    </nav>
<!-- php barra de navegacion-->    
<?php include 'vista/complementos/nav.php' ?>

<style>
  .text-g{
    font-size: 15px;
  }
</style>
<div class="container-fluid py-4"> <!-- DIV CONTENIDO -->

    <div class="row"> <!-- CARD PRINCIPAL-->  
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0">  <!-- CARD N-1 -->  
    <a href="?pagina=home" class="btn btn-primary"><i class="fa-solid fa-reply"></i> Regresar</a>
            <h5>Informacion Personal</h5>
            <?php
            if (isset($_GET['m']) && $_GET['m'] == 'a') {
              echo '<div class="alert alert-info alert-dismissible fade show text-center text-white" role="alert"><b>
            <i class="fa-solid fa-circle-info"></i> Los cambios se aplicarán cuando cierre la sesión.</b>
            <button type="button" class="btn-close text-danger" data-bs-dismiss="alert" aria-label="Cerrar"></button>
          </div>';
            }
            ?>  
          <form action="?pagina=usuario" method="POST" autocomplete="off" id="datos">
            <div class="row">
              <div class="col">
                <label class="text-g">Cedula</label>
                
              <div class="input-group mb-3">
                  <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-id-card" style="color:#ff2bc3;"></i></span>
                    <input type="text" class="form-control" name="cedula" id="cedula" value="<?php echo $_SESSION['cedula']?>">
              </div>
              <span id="textocedula"></span>
              </div>
              <div class="col">
                <label class="text-g">Nombre</label>
                
                 <div class="input-group mb-3">
                  <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-user" style="color:#ff2bc3;"></i></span>
                    <input type="text" class="form-control" name="nombre" id="nombre" value="<?php echo $_SESSION['nombre']?>">
              </div>
                <span id="textonombre"></span>
              </div>
              <div class="col">
                <label class="text-g">Apellido</label>
                
                 <div class="input-group mb-3">
                  <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-user" style="color:#ff2bc3;"></i></span>
                    <input type="text" class="form-control" name="apellido" id="apellido" value="<?php echo $_SESSION['apellido']?>">
              </div>
                <span id="textoapellido"></span>
              </div>
            </div>
            
            <div class="row">
              <div class="col">
                <label class="text-g">Telefono</label>
               
                    <div class="input-group mb-3">
                  <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-mobile-screen-button"  style="color:#ff2bc3;"></i></span>
                     <input type="text" class="form-control" name="telefono" id="telefono" value="<?php echo $_SESSION['telefono']?>">  
              </div>
                <span id="textotelefono"></span>
              </div>
              <div class="col">
                <label class="text-g">Correo Electrónico</label>
                
                <div class="input-group mb-3">
                  <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-envelope" style="color:#ff2bc3;"></i></span>
                    <input type="text" class="form-control" id="correo" name="correo" value="<?php echo $_SESSION['correo']?>">
              </div>
                <span id="textocorreo"></span>
              </div>
            </div>
            <br>
        
            <div class="row">
              <div class="col">
                <div class="d-flex justify-content-between">
                    
                   
                        
                    </form>
                </div>
                   <div class="row">
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
             <button type="button" class="btn btn-success" name="actualizar" id="actualizar"><i class="fa-solid fa-floppy-disk"></i> Actulizar Datos</button>
             <button class="btn btn-primary" type="reset"> <i class="fa-solid fa-eraser"></i> Limpiar</button>
        </div>
             </form>
                 <hr class="bg-dark">

               
          <h5>Seguridad </h5>
        
      </div>
      <form action="?pagina=datos" method="POST" autocomplete="off" id="formclave">
      <div class="row">
         <label class="text-g">Clave Actual</label>
            <div class="col-sm-5">
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-key" style="color:#ff2bc3;"></i></span>
                    <input type="text" class="form-control" id="clave" name="clave">
                </div>
                 <p id="textoclave" class="text-danger"></p>
            </div>
        </div>
         <div class="row">
            <div class="col">
                  <label class="text-g"><b>Clave Nueva</b></label>
                 <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-unlock" style="color:#ff2bc3;"></i></span>
                   <input type="text" class="form-control" id="clavenueva" name="clavenueva">
                </div>
                <p id="textoclavenueva" class="text-danger"></p>
            </div>
            <div class="col">
                  <label class="text-g"><b>Confirmar Clave Nueva</b></label>
                 <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-unlock" style="color:#ff2bc3;"></i></span>
                    <input type="text" class="form-control" id="clavenuevac" name="clavenuevac">
                </div>
                 <p id="textoclavenuevac" class="text-danger"></p>
            </div>
                <input type="hidden" name="persona" value="<?php echo $_SESSION['id'] ?>" >
        </div>
        <div class="row">
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
             <button class="btn btn-success me-md-2" type="button" id="actualizarclave"> <i class="fa-solid fa-key"></i> Cambiar Clave</button>
             <button class="btn btn-primary" type="reset"> <i class="fa-solid fa-eraser"></i> Limpiar</button>
        </div>
             </form>
        </div>
            </div>

            
 </div>
            </div><!-- FIN CARD N-1 -->  
    </div>
    </div>  
    </div><!-- FIN CARD PRINCIPAL-->  




<!-- php barra de navegacion-->
<?php include 'vista/complementos/footer.php' ?>
 <script src="assets/js/datos.js"></script>
</body>

</html>