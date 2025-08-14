<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Login | LoveMakeup C.A</title>

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="assets/fontawesome/css/all.min.css" />
    <link rel="shortcut icon" type="image/png" href="assets/img/icono.png"/>
  
      <!-- CSS Files -->
    <link id="pagestyle" href="assets/css/argon-dashboard.css?v=2.1.0" rel="stylesheet" />
    <link id="pagestyle" href="assets/css/sidebar.css" rel="stylesheet" />

    <script src="assets/js/libreria/jquery.min.js"></script>
    <script src="assets/js/libreria/sweetalert2.js"></script>
    <link rel="stylesheet" href="assets/css/login.css" />
    <script src="assets/js/loader.js"></script>

  </head>
  <body>
  <?php
if (isset($_GET['mensaje']) && $_GET['mensaje'] === 'sesion_expirada') {
    echo "<script>
        Swal.fire({
            icon: 'info',
            title: 'Sesión expirada',
            text: 'Tu sesión ha caducado por inactividad. Por favor, inicia sesión nuevamente.',
            confirmButtonColor: '#fa8fb1',
            confirmButtonText: 'Entendido'
        });
    </script>";
}  ?>

<!-- |||||||||||||||| LOADER ||||||||||||||||||||-->
  <div class="preloader-wrapper">
    <div class="preloader">
    </div>
  </div> 
<!-- |||||||||||||||| LOADER ||||||||||||||||||||-->

  <div class="d-md-flex half">
    <div class="bg" style="background-image: url('assets/img/g03.jpg');"></div>
    <div class="contents">

      <div class="container">
       
        <div class="row align-items-center justify-content-center">
           <div class="">
        <div class="back-button-container">
        <center>
        <a href="?pagina=catalogo" class="back-button1">
         <i class="fa-solid fa-shop text-h"> </i> Volver a Tienda
        </a> <br>
        </center>
      </div>
          <div class="col-md-12">
  
            <div class="form-block mx-auto">

              <div class="text-center mb-2">

              <h3>Inicio de Sesión</h3> 
              <p class="text-primary">Ingrese su cedula y clave para acceder al sistema</p>
              </div>
              <form action="?pagina=login" method="POST" autocomplete="off" id="login">
                <div class="form-group first">
                  <i class="fa-solid fa-id-card tex-i"></i> <label for="usuario" class="text-g">N° Cedula</label>
                  <input class="form-control text-dark" type="text" name="usuario" id="usuario" placeholder="00100200" >
                  <p id="textousuario" class="text-danger"></p>
                </div>
                <div class="form-group last mb-2">
                  <i class="fa-solid fa-lock tex-i"></i> <label for="password" class="text-g">Contraseña</label>
                   <div class="password-input1">
                  <input type="password" name="clave" id="pid" class="form-control text-dark" placeholder="contraseña:" >
                  <span id="show-password" class="fa fa-eye"></span>
                    </div>
                <p id="textop" class="text-danger"></p>
                </div>
                
 
                <div class="d-sm-flex mb-4 justify-content-md-end">
                  
                  <span class="ml-auto"><a href="#" class="forgot-pass text-primary" data-bs-toggle="modal" data-bs-target="#olvido"><b>¿Olvidaste tu Constraseña?</b></a></span> 
                  </div>

                <button type="submit" name="ingresar" id="ingresar"class="btn btn-block btt">Ingresar</button>
              </form>
              <hr class="bg-dark">
              <center>
              <a href="#" class="btn-sm badge rounded-pill tex-c" data-bs-toggle="modal" data-bs-target="#registroclientess"
               style="text-decoration: none !important; color:#000000;">
                <i class="fa-solid fa-user-plus"></i>  Crea tu cuenta y empieza a comprar</a>
            </center>
            </div>
          </div>
        </div>
      </div>
    </div>

    
  </div>
    
    
      <!-- Modal olvido de clave-->
      <div class="modal fade" id="olvido" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel"><i class="fa-solid fa-lock"></i> Olvido de Contraseña</h5>
              <button type="button" class="btn-close bg-danger" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form method="POST" action="?pagina=login" autocomplete="off" id="olvidoclave" class="form-registro">
                              
                  <div class="row">
                    <div class="col">
                      <label class="labelform text-g"> <i class="fa-solid fa-id-card tex-i"></i> Introducir el Nro. de Cedula</label>
                          <input type="text" class="form-control" id="cedulac" name="cedula" placeholder="Cedula: 11222333 ">
                          <span id="textocedulac" class="text-danger"></span>
                    </div>
                  </div>
                  <br>
                  <div class="row">
                    <div class="col">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                          <button type="button" class="btn btn-secondary btn-sm me-md-2 tex-modal" data-bs-dismiss="modal" aria-label="Cerrar">Cancelar</button>
                         <button type="button" class="btn btn-success btn-sm tex-modal text-dark"  id="validarolvido">Validar</button>
                  </div>
                    </div>
                  </div>
              
                </form> 
            </div>
          </div>
        </div>
      </div>



<!-- Modal registro cliente-->
<div class="modal fade" id="registroclientess" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
     
      <div class="modal-body"  style="background-color:#423f42 ;">
        
        <div class="container-fluid">
    
          <div class="card">
    <div class="row g-0">

    <div class="col-md-4 image-side d-none d-md-block">
         <img src="assets/img/e4.jpg"  style="width: 100%; height: 100%;">
    </div>

    <!-- Formulario a la derecha -->
    <div class="col-md-8 d-flex align-items-center justify-content-center p-4">
      <div class="form-container w-100" style="max-width: 500px;">
       
          <div class="modal-header mb-3">
            <h5 class="modal-title w-100 text-center">Formulario de Registro</h5>
            <button type="button" class="btn-close bg-danger position-absolute end-0 me-3" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
        
        <form method="POST" action="?pagina=login" autocomplete="off" id="registrocliente">
          <div class="row">
             <div class="mb-1">
            <label for="usuario" class="form-label">Cedula</label>
            <input type="text" class="form-control" id="cedula"  name="cedula" placeholder="00100300">
            <span id="textocedula" class="text-danger"></span>
          </div>

            <div class="mb-1 col-md-6">
              <label for="nombre" class="form-label">Nombre</label>
              <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Tu nombre">
              <span id="textonombre" class="text-danger"></span>
            </div>
            <div class="mb-1 col-md-6">
              <label for="apellido" class="form-label">Apellido</label>
              <input type="text" class="form-control" id="apellido" name="apellido" placeholder="Tu apellido">
              <span id="textoapellido" class="text-danger"></span>
            </div>
          </div>

          <div class="mb-1">
            <label for="email" class="form-label">Correo electrónico</label>
            <input type="email" class="form-control" id="correo" name="correo" placeholder="ejemplo@correo.com">
            <span id="textocorreo" class="text-danger"></span>
          </div>

            <div class="mb-1">
            <label for="telefono" class="form-label">Telefono</label>
            <input type="text" class="form-control" id="telefono" name="telefono" placeholder="0422-0001122">
            <span id="textotelefono" class="text-danger"></span>
          </div>

          <div class="mb-1">
            <label for="password" class="form-label">Contraseña</label>
            <input type="text" class="form-control" id="recontrasena"  placeholder="Tu contraseña">
             <span id="textorecontrasena" class="text-danger"></span>
          </div>

          <div class="mb-3">
            <label for="confirmar" class="form-label">Confirmar Contraseña</label>
            <input type="text" class="form-control" id="clave" name="clave" placeholder="Repite la contraseña">
            <span id="textoclave" class="text-danger"></span>
          </div>

          <button type="button" class="btn btn-primary w-100" id="registrar"> <i class="fa-solid fa-user-plus"></i> Registrarse</button>
        </form>
      </div>
    </div>
    </div>
  </div>
</div>



      </div>
      
    </div>
  </div>
</div>

  
  <script src="assets/js/catalago/js/bootstrap.min.js"></script>
  <script src="assets/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/login.js"></script>
  </body>
</html>
