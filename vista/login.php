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
    <div class="form-section1">
      <div class="back-button-container1">
        <a href="?pagina=catalogo" class="back-button1">
          <i class="fa-solid fa-arrow-left"></i>
        </a>
      </div>
      
      <span class="logo"><i class="fa-solid fa-shop"></i></span>
      <h2>Inicio de Sesión</h2>
      <span class="sub-heading1">Ingrese su usuario y clave para acceder al sistema</span>
    
      <form action="?pagina=login" method="POST" autocomplete="off" class="form-login" id="login">
        <label for="name"><i class="fa-solid fa-user"></i> Cédula</label>
        <input class="form-control1" type="text" name="usuario" id="usuario" placeholder="Cédula:" >
        <p id="textousuario"></p>

        <label for="password"><i class="fa-solid fa-lock"></i> Contraseña</label>
        <div class="password-input1">
          <input type="password" name="clave" id="password" class="form-control1" placeholder="Contraseña:" >
          <span id="show-password" class="fa fa-eye"></span>
         <p id="textopassword"></p>
        </div>

        <button type="button" name="ingresar" id="ingresar" class="btn-primary1 mt1">Iniciar Sesión</button>
      </form>
  
      <div class="button-group1">
        <a id="openModalclave" class="btn-small1 btn-left1">Olvidó su Contraseña</a>
        <a id="openModal" class="btn-small1 btn-right1"><i class="fa-solid fa-user-plus"></i> Registrarse</a>
      </div>
    </div>

    <div class="hero-section1">
      <h2>
        PLATAFORMA DE COMERCIO ELECTRÓNICO <br> PARA LA LÍNEA DE PRODUCTOS DE BELLEZA
      </h2>
      <span class="sub-heading1">
        <b>LoveMakeup RIF J-</b>
      </span>
      <img src="assets/img/t2.svg" alt="" />
    </div>

 
    <!-- |||||||||||| MODAL |||||||||||-->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
               <h2 style="color:#fc6998;"> <i class="fa-solid fa-user-plus" ></i> Registro Para Nuevo Usuario </h2>
                <span class="close" id="closeModal">X</span>
            </div>
           <form method="POST" action="?pagina=login" autocomplete="off" id="registrocliente" class="form-registro">
            <div class="modal-body">

                <label class="labelform"> <i class="fa-solid fa-id-card"></i> Cedula:</label>
                <input type="text" class="inputform" id="cedula" name="cedula" placeholder="Cedula: 11222333 ">
                <span id="textocedula" class="alert-text"></span>
                
               <div class="input-group">
                    <div class="input-wrapper">
                      <label class="labelform"> <i class="fa-solid fa-user"></i> Nombre:</label>
                        <input type="text" class="inputform" id="nombre" name="nombre" placeholder="Nombre: juan">
                        <span id="textonombre" class="alert-text"></span>
                    </div>
    
                 <div class="input-wrapper">
                    <label class="labelform"> <i class="fa-solid fa-user"></i> Apellido:</label>
                     <input type="text" class="inputform" id="apellido" name="apellido" placeholder="Apellido: perez">
                    <span id="textoapellido" class="alert-text"></span>
                 </div>
                 </div>

                <label class="labelform"> <i class="fa-solid fa-mobile"></i> Telefono:</label>
                <input type="text"class="inputform"  id="telefono" name="telefono" placeholder="Telefono: 04000000000 ">
                 <span id="textotelefono" class="alert-text"></span>

                <label class="labelform"> <i class="fa-solid fa-envelope"></i> Correo:</label>
                <input type="text" class="inputform" id="correo" name="correo" placeholder="Correo: tucorreo@dominio.com">
                 <span id="textocorreo" class="alert-text"></span>

                <label class="labelform"> <i class="fa-solid fa-lock"></i> Contraseña:</label>
                <input type="text" class="inputform" id="clave" name="clave" placeholder="Contraseña:">
                 <span id="textoclave" class="alert-text"></span>

            </div>
            <div class="modal-footer">
                <button class="save-btn" id="registrar"> <i class="fa-solid fa-user-plus"></i> Registrar</button>
                 </form>
               <button class="cancel-btn" id="closeModalFooter" type="button">Cancelar</button>
            </div>
      
        </div>
    </div>


<!-- |||||||||||| MODAL |||||||||||-->
    <div id="myModalclave" class="modal">
        <div class="modal-content">
            <div class="modal-header">
               <h2 style="color:#fc6998;"><i class="fa-solid fa-lock"></i> Olvido de Contraseña</h2>
                <span class="close" id="closeModalclave">X</span>
            </div>
           <form method="POST" action="?pagina=login" autocomplete="off" id="olvidoclave" class="form-registro">
            <div class="modal-body">

                <label class="labelform"> <i class="fa-solid fa-id-card"></i> Introducir el Nro. de Cedula</label>
                <input type="text" class="inputform" id="cedulac" name="cedula" placeholder="Cedula: 11222333 ">
                <span id="textocedulac" class="alert-text"></span>
                

            </div>
            <div class="modal-footer">
                <button class="save-btn" id="validarolvido">Validar</button>
                 </form>
               <button class="cancel-btn" id="closeModalFooterclave" type="button">Cancelar</button>
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
