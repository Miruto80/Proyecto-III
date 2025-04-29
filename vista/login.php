<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Login Acceso Empleados | LoveMakeup C.A</title>
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
    

      <form action="?pagina=home" method="POST" autocomplete="off">
        
        <label for="name"><i class="fa-solid fa-user"></i> Usuario</label>
        <input class="form-control" type="text" name="usuario" id="usuario" placeholder="Usuario:" />
       

        <label for="password"><i class="fa-solid fa-lock"></i> Constraseña</label>
        <div class="password-input">
                <input type="password" name="clave" id="password"  class="form-control" placeholder="Contraseña:">
                <span id="show-password" class="fa fa-eye"></span>
         </div>
        
        

        <button type="submit" name="ingresar" class="btn-primary mt">Iniciar Session</button>
      </form>
    
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
  </body>
</html>