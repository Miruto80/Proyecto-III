<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Login | LoveMakeup C.A</title>
    <link rel="stylesheet" href="assets/css/login.css" />
    <link rel="stylesheet" href="assets/fontawesome/css/all.min.css" />
    <link rel="shortcut icon" type="image/png" href="assets/img/icono.png"/>

    <script src="assets/js/libreria/jquery.min.js"></script>
    <script src="assets/js/libreria/sweetalert2.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </head>
  <body>
    <div class="form-section">
      <div class="back-button-container">
        <a href="?pagina=catalago" class="back-button">
          <i class="fa-solid fa-arrow-left"></i>
        </a>
      </div>
      
      <span class="logo"><i class="fa-solid fa-fingerprint"></i></span>
      <h2>Inicio de Sesión</h2>
      <span class="sub-heading">Ingrese su usuario y clave para acceder al sistema</span>
    
      <form action="?pagina=login" method="POST" autocomplete="off">
        <label for="name"><i class="fa-solid fa-user"></i> Cédula</label>
        <input class="form-control" type="text" name="usuario" id="usuario" placeholder="Cédula:" required>

        <label for="password"><i class="fa-solid fa-lock"></i> Contraseña</label>
        <div class="password-input">
          <input type="password" name="clave" id="password" class="form-control" placeholder="Contraseña:" required>
          <span id="show-password" class="fa fa-eye"></span>
        </div>

        <button type="submit" name="ingresar" class="btn-primary mt">Iniciar Sesión</button>
      </form>
  
      <div class="button-group">
        <a href="?pagina=forgot-password" class="btn-small btn-left">Olvidó su contraseña</a>
        <a href="#" class="btn-small btn-right" data-bs-toggle="modal" data-bs-target="#registroCliente">Registrar</a>
      </div>
    </div>

    <div class="hero-section">
      <h2>
        PLATAFORMA DE COMERCIO ELECTRÓNICO <br> PARA LA LÍNEA DE PRODUCTOS DE BELLEZA
      </h2>
      <span class="sub-heading">
        <b>LoveMakeup RIF J-</b>
      </span>
      <img src="assets/img/t2.svg" alt="" />
    </div>

    <!-- Modal para registrar cliente -->
    <div class="modal fade" id="registroCliente" tabindex="-1" aria-labelledby="registroClienteLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header header-color">
            <h1 class="modal-title fs-5" id="registroClienteLabel">Registrar Cliente</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body">
            <form id="formRegistrarCliente" method="POST" action="controlador/registrocliente.php" autocomplete="off">
              <div class="row">
                <div class="col-md-6">
                  <label for="cedula">Cédula</label>
                  <input type="text" class="form-control" name="cedula" required>
                </div>
                <div class="col-md-6">
                  <label for="nombre">Nombre</label>
                  <input type="text" class="form-control" name="nombre" required>
                </div>
                <div class="col-md-6">
                  <label for="apellido">Apellido</label>
                  <input type="text" class="form-control" name="apellido" required>
                </div>
                <div class="col-md-6">
                  <label for="correo">Correo</label>
                  <input type="email" class="form-control" name="correo" required>
                </div>
                <div class="col-md-6">
                  <label for="telefono">Teléfono</label>
                  <input type="text" class="form-control" name="telefono" required>
                </div>
                <div class="col-md-6">
                  <label for="clave">Contraseña</label>
                  <input type="password" class="form-control" name="clave" required>
                </div>
              </div>
              <br>
              <div class="text-center">
                <button type="submit" class="btn btn-primary">Registrar</button>
                <button type="reset" class="btn btn-secondary">Limpiar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <script src="assets/js/login.js"></script>

    <?php if(isset($_SESSION['message'])): ?>
      <script>
        Swal.fire({
          title: '<?php echo $_SESSION['message']['title']; ?>',
          text: '<?php echo $_SESSION['message']['text']; ?>',
          icon: '<?php echo $_SESSION['message']['icon']; ?>',
          confirmButtonColor: '#fa48f2',
          confirmButtonText: 'OK'
        });
      </script>
      <?php unset($_SESSION['message']); ?>
    <?php endif; ?>
  </body>
</html>
