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


<!--Banner IMG-->
  <section>
    <div class="slideshow slide-in arrow-absolute text-white position-relative">
      <div class="swiper-wrapper">
        <div class="swiper-slide jarallax">
          <img src="assets/img/d1.png" class="jarallax-img w-100" alt="slideshow" />
          <div class="banner-content w-100 my-3">
            <div class="container">
              <div class="row justify-content-center text-center">
                <div class="col-md-12 pt-1">
                  <p class="fs-6 text-dark fw-bold">
                    VER
                  </p>
                  <h2 class="fs-3 text-dark text-uppercase ls-0 fw-bold">
                    Mis Datos
                  </h2>
                  
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
    </div>
  </section>



<section id="latest-blog" class="section-padding pt-0">
    <div class="container-lg">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="?pagina=catalogo">Inicio</a></li>
            <li class="breadcrumb-item" aria-current="page">Ver</li>
             <li class="breadcrumb-item active" aria-current="page">Mis Datos</li>
        </ol>
      </nav>
      <div class="row">
        <div class="section-header d-flex align-items-center justify-content-between mb-lg-2">
          <h2 class="section-title">Datos Personales </h2>
        </div>
      </div>
       <form action="?pagina=catalogo_datos" method="POST" autocomplete="off" id="u">
      <div class="row">
         <h5>información personal</h5>
            <?php
            if (isset($_GET['m']) && $_GET['m'] == 'a') {
              echo '<div class="alert alert-info alert-dismissible fade show text-center" role="alert">
            <i class="fa-solid fa-circle-info"></i> Los cambios se aplicarán cuando cierre la sesión.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
          </div>';
            }
            ?>  
        </div>  
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="cedula">Cédula</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-id-card" style="color:#ff2bc3;"></i></span>
                <input type="text" class="form-control text-dark" id="cedula" name="cedula" value="<?php echo $_SESSION['cedula'] ?>">
              </div>
              <p id="textocedula"></p>
            </div>

            <div class="col-md-6 mb-3">
              <label for="nombre">Nombre</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-user" style="color:#ff2bc3;"></i></span>
                <input type="text" class="form-control text-dark" id="nombre" name="nombre" value="<?php echo $_SESSION['nombre'] ?>">
              </div>
              <p id="textonombre"></p>
            </div>

            <div class="col-md-6 mb-3">
              <label for="apellido">Apellido</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-user" style="color:#ff2bc3;"></i></span>
                <input type="text" class="form-control text-dark" id="apellido" name="apellido" value="<?php echo $_SESSION['apellido'] ?>">
              </div>
              <p id="textoapellido"></p>
            </div>

            <div class="col-md-6 mb-3">
              <label for="telefono">Teléfono</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-mobile-screen-button" style="color:#ff2bc3;"></i></span>
                <input type="text" class="form-control text-dark" id="telefono" name="telefono" value="<?php echo $_SESSION['telefono'] ?>">
              </div>
              <p id="textotelefono"></p>
            </div>

            <div class="col-md-12 mb-3">
              <label for="correo">Correo Electrónico</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-envelope" style="color:#ff2bc3;"></i></span>
                <input type="text" class="form-control text-dark" id="correo" name="correo" value="<?php echo $_SESSION['correo'] ?>">
              </div>
              <p id="textocorreo"></p>
            </div>
          </div>

        <div class="row">
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button class="btn btn-dark me-md-2" type="button" id="actualizar"> <i class="fa-solid fa-floppy-disk"></i> Actualizar Datos</button>
                <button class="btn btn-primary" type="reset"> <i class="fa-solid fa-repeat"></i> Restaurar</button>
            </div>
     

        </div>
     </form>   
<hr>
<div class="row bg-light">
        <div class="section-header d-flex align-items-center justify-content-between mb-lg-2">
          <h2 class="section-title">Seguridad </h2>
        </div>
      </div>
      <form action="?pagina=catalogo_datos" method="POST" autocomplete="off" id="formclave">
    
  <div class="row mb-3">
    <div class="col-12">
      <h5 class="mb-3">Clave Actual</h5>
    </div>
    <div class="col-md-6 col-lg-5">
      <div class="input-group">
        <span class="input-group-text"><i class="fa-solid fa-key" style="color:#ff2bc3;"></i></span>
        <input type="password" class="form-control" id="clave" name="clave">
      </div>
      <p id="textoclave" class="text-danger"></p>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-6">
      <label for="clavenueva" class="text-dark"><b>Clave Nueva</b></label>
      <div class="input-group">
        <span class="input-group-text"><i class="fa-solid fa-unlock" style="color:#ff2bc3;"></i></span>
        <input type="password" class="form-control" id="clavenueva" name="clavenueva">
      </div>
      <p id="textoclavenueva" class="text-danger"></p>
    </div>

    <div class="col-md-6">
      <label for="clavenuevac" class="text-dark"><b>Confirmar Clave Nueva</b></label>
      <div class="input-group">
        <span class="input-group-text"><i class="fa-solid fa-unlock" style="color:#ff2bc3;"></i></span>
        <input type="password" class="form-control" id="clavenuevac" name="clavenuevac">
      </div>
      <p id="textoclavenuevac" class="text-danger"></p>
    </div>
  </div>

  <input type="hidden" name="persona" value="<?php echo $_SESSION['id'] ?>">


        <div class="row">
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
             <button class="btn btn-dark me-md-2" type="button" id="actualizarclave"> <i class="fa-solid fa-key"></i> Cambiar Clave</button>
             <button class="btn btn-primary" type="reset"> <i class="fa-solid fa-eraser"></i> Limpiar</button>
        </div>
             </form>
        </div>

<hr>
        <div class="row bg-light">
        <div class="section-header d-flex align-items-center justify-content-between mb-lg-2">
          <h2 class="section-title">Estado de la Cuenta </h2>
        </div>
      </div>
      
 
        <div class="row">
          <div class="col">
            <p class="text-dark">
              <i class="fa-solid fa-user-xmark"></i> ¿Deseas Eliminar la Cuenta? 
              <button class="btn btn-dark ms-2" data-bs-toggle="modal" data-bs-target="#cuenta">Eliminar Cuenta</button>
            </p>
          </div>
        </div>


     
      </div>
    </div>
  </section>




<!-- php Publicidad Insta, Publicidad calidad, footer y JS--> 
<?php include 'vista/complementos/footer_catalogo.php' ?>
   <script src="assets/js/catalago_datos.js"></script>


<div class="modal fade" id="cuenta" tabindex="2" aria-labelledby="s" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">¿Deseas Eliminar la Cuenta?</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h5>Aviso Importante sobre la Eliminación de Cuenta</h5>
        <p class="text-dark"> <b>Estimado/a, <?php echo $nombreCompleto ?> </b></p>

        <p class="text-dark">Queremos informarte que al eliminar tu cuenta, se perderá de forma permanente toda la información relacionada con tus pedidos, tu historial de compras y la lista de tus productos favoritos.</p>

        <p class="text-dark">Esta acción es irreversible, y una vez eliminada tu cuenta, no podremos recuperar la información eliminada.</p>

 <form id="eliminarForm" action="?pagina=catalogo_datos" method="POST" autocomplete="off"> 
    <label>Escriba la palabra ACEPTAR, para confimar la eliminación</label>
    <input type="text" name="confirmar" id="confirmar" class="form-control text-dark" placeholder="ACEPTAR">
    <p id="textoconfirmar"></p>
    <input type="hidden" name="persona" value="<?php echo $_SESSION['id'] ?>" >
    <div class="modal-footer">
        <button type="button" class="btn btn-dark" name="eliminar" id="btnEliminar">Continuar</button>
    </div>
</form>
    </div>
  </div>
</div>



</body>

</html>