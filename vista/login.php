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
  </head>
  <body>
    <div class="form-section">
      <div class="back-button-container">
        <a href="?pagina=catalago" class="back-button">
          <i class="fa-solid fa-arrow-left"></i>
        </a>
      </div>
      
      <span class="logo"><i class="fa-solid fa-fingerprint"></i></span>
      <h2>Inicio de Session</h2>
      <span class="sub-heading">Ingrese su usuario y clave para acceder al sistema</span>
    

       <form action="?pagina=login" method="POST" autocomplete="off">
        
        <label for="name"><i class="fa-solid fa-user"></i> Cedula</label>
        <input class="form-control" type="text" name="usuario" id="usuario" placeholder="Cedula:" required>
       

        <label for="password"><i class="fa-solid fa-lock"></i> Constraseña</label>
        <div class="password-input">
                <input type="password"  name="clave" id="password"  class="form-control" placeholder="Contraseña:" required>
                <span id="show-password" class="fa fa-eye"></span>
         </div>
        
        

        <button type="submit" name="ingresar" class="btn-primary mt">Iniciar Session</button>
      </form>
  
    <div class="button-group">
        <a href="?pagina=forgot-password" class="btn-small btn-left">Olvidó su contraseña</a>
        <a href="?pagina=register" class="btn-small btn-right">Registrar si eres nuevo</a>
    </div>
    </div>

    <div class="hero-section">
      <h2>
      PLATAFORMA DE COMERCIO ELECTRONICO <br> PARA LA LÍNEA DE PRODUCTOS DE BELLEZA
      </h2>
      <span class="sub-heading">
      <b>LoveMakeup RIF J-</b>
    </span>
      <img src="assets/img/t2.svg" alt="" />
    </div>
  
    <script src="assets/js/login.js"></script>


    <!--||||      MENSAJE TEMPORAL   Error Usuario/Clave     |||-->  
<?php if(isset($_SESSION['message'])): ?>  <!-- Mensaje de Alertas -->
    <script>
        Swal.fire({      
            title: '<?php echo $_SESSION['message']['title']; ?>',
            text: '<?php echo $_SESSION['message']['text']; ?>',
            icon: '<?php echo $_SESSION['message']['icon']; ?>',
            confirmButtonColor: '#7E112E',
            confirmButtonText: 'OK'
        });
    </script>
    <?php unset($_SESSION['message']); ?> <!-- Eliminar el mensaje de la sesión -->
<?php endif; ?>


  </body>
</html>