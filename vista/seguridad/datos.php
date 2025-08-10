<!DOCTYPE html>
<html lang="es">

<head> 
  <!-- php barra de navegacion-->
  <?php include 'vista/complementos/head.php' ?>
  <link rel="stylesheet" href="assets/css/formulario.css"> 
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
              
            <div class="card shadow-lg">
      <div class="card-body p-3">
        <div class="row gx-4">
          <div class="col-auto">
            <div class="avatar avatar-xl position-relative">
              <img src="assets/img/icono.png" alt="profile_image" class="w-100 border-radius-lg shadow-sm">
            </div>
          </div>
          <div class="col-auto my-auto">
            <div class="h-100">
              <h5 class="mb-1">
                <?php echo $_SESSION['nombre'] . " " . $_SESSION['apellido']; ?>
              </h5>
              <p class="mb-0 font-weight-bold text-sm">
              <?php echo $_SESSION['nombre_usuario'];?>
              </p>
            </div>
          </div>
          <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
            <div class="nav-wrapper position-relative end-0">
              <ul class="nav nav-pills nav-fill p-1" role="tablist">
                <li class="nav-item me-2">
                  <a href="?pagina=home" class="regresar bg-primary nav-link mb-0 px-0 py-1 d-flex align-items-center justify-content-center">
                    <i class="ni ni-app"></i>
                    <span class="ms-2 text-white"> <i class="fa-solid fa-reply me-2"></i> Regresar</span>
                  </a>
                </li>
                <li class="nav-item">
                  <buttom class="bg-primary nav-link mb-0 px-0 py-1 d-flex align-items-center justify-content-center" id="datosayuda">
                    <i class="ni ni-app"></i>
                    <span class="ms-2 text-white"> <i class="fas fa-info-circle me-2"></i> ayuda</span>
                  </buttom>
                </li>
                
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>

            <div class="container mt-4">
  <div class="row">
    <!-- Información Personal -->
    <div class="col-md-6 informacion">
      <h5><i class="fa-solid fa-user me-2" style="color:#f6c5b4;"></i> Información Personal</h5>
      <?php if (isset($_GET['m']) && $_GET['m'] == 'a'): ?>
  
       <div class="info-box">
        <div class="info-icon">
          <i class="fa-solid fa-circle-info"></i>
        </div>

        <div class="info-content">
          <strong>Aviso Importante:</strong>
          <p>Los cambios se aplicarán cuando cierre la sesión.</p>
        </div>
      </div>
      <?php endif; ?>
        <p class="text-muted">
          En esta área podrás <strong>actualizar tu información personal</strong>, como cédula, nombre, apellido, teléfono y correo electrónico.
          <span class="text-primary"> Recuerda que los cambios se aplicarán al cerrar sesión. </span>
        </p>
       <form action="?pagina=usuario" method="POST" autocomplete="off" id="datos">
        <!-- Cédula -->
        <div class="row mb-3">
          <div class="col-12">
            <label for="cedula" class="form-label text-g">Cédula</label>
            <input type="text" class="form-control" name="cedula" id="cedula" value="<?php echo $_SESSION['cedula'] ?>">
            <span id="textocedula" class="text-danger"></span>
          </div>
        </div>

        <!-- Nombre -->
        <div class="row mb-3">
          <div class="col-12">
            <label for="nombre" class="form-label text-g">Nombre</label>
            <input type="text" class="form-control" name="nombre" id="nombre" value="<?php echo $_SESSION['nombre'] ?>">
            <span id="textonombre" class="text-danger"></span>
          </div>
        </div>

        <!-- Apellido -->
        <div class="row mb-3">
          <div class="col-12">
            <label for="apellido" class="form-label text-g">Apellido</label>
            <input type="text" class="form-control" name="apellido" id="apellido" value="<?php echo $_SESSION['apellido'] ?>">
            <span id="textoapellido" class="text-danger"></span>
          </div>
        </div>

        <!-- Teléfono -->
        <div class="row mb-3">
          <div class="col-12">
            <label for="telefono" class="form-label text-g">Teléfono</label>
            <input type="text" class="form-control" name="telefono" id="telefono" value="<?php echo $_SESSION['telefono'] ?>">
            <span id="textotelefono" class="text-danger"></span>
          </div>
        </div>

        <!-- Correo -->
        <div class="row mb-3">
          <div class="col-12">
            <label for="correo" class="form-label text-g">Correo Electrónico</label>
            <input type="text" class="form-control" id="correo" name="correo" value="<?php echo $_SESSION['correo'] ?>">
            <span id="textocorreo" class="text-danger"></span>
          </div>
        </div>

        <!-- Botones -->
        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3">
          <button type="button" class="btn btn-success" name="actualizar" id="actualizar"><i class="fa-solid fa-floppy-disk me-2"></i> Actualizar Datos</button>
          <button class="btn btn-primary" type="reset"><i class="fa-solid fa-eraser me-2"></i> Limpiar</button>
        </div>
      </form>
    </div>

    <!-- Separador vertical mejor ajustado -->
    <div class="col-md-1 d-none d-md-flex justify-content-center align-items-stretch">
      <div style="width: 2px; background-color: #ff2bc3;"></div>
    </div>

    <!-- Seguridad con collapse -->
    <div class="col-md-5 seguridad">
      <h5><i class="fa-solid fa-shield-halved me-2" style="color:#f6c5b4;"></i> Seguridad</h5>
      <p class="text-muted">
  En la sección de <strong>seguridad</strong> puedes <span class="text-warning">modificar tu clave de acceso</span> de forma segura. Solo haz clic en el botón para desplegar el formulario y completa los campos requeridos.
</p>
      <a class="btn btn-warning w-100 mb-2" data-bs-toggle="collapse" href="#claveCollapse" role="button" aria-expanded="false" aria-controls="claveCollapse">
        <i class="fa-solid fa-shield-halved me-2"></i> Si quieres cambiar la clave, haz clic aquí
      </a>

      <div class="collapse" id="claveCollapse">
        <form action="?pagina=datos" method="POST" autocomplete="off" id="formclave">
          <div class="row mb-3">
            <div class="col-md-12">
              <label for="clave" class="form-label text-g">Clave Actual</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-key" style="color:#ff2bc3;"></i></span>
                <input type="text" class="form-control" id="clave" name="clave">
              </div>
              <p id="textoclave" class="text-danger mt-1"></p>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label for="clavenueva" class="form-label text-g"><b>Clave Nueva</b></label>
              <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-unlock" style="color:#ff2bc3;"></i></span>
                <input type="text" class="form-control" id="clavenueva" name="clavenueva">
              </div>
              <p id="textoclavenueva" class="text-danger mt-1"></p>
            </div>

            <div class="col-md-6">
              <label for="clavenuevac" class="form-label text-g"><b>Confirmar Clave Nueva</b></label>
              <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-unlock" style="color:#ff2bc3;"></i></span>
                <input type="text" class="form-control" id="clavenuevac" name="clavenuevac">
              </div>
              <p id="textoclavenuevac" class="text-danger mt-1"></p>
            </div>
          </div>

          <input type="hidden" name="persona" value="<?php echo $_SESSION['id'] ?>">

          <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <button class="btn btn-success me-md-2" type="button" id="actualizarclave"><i class="fa-solid fa-key me-2"></i> Cambiar Clave</button>
            <button class="btn btn-primary" type="reset"><i class="fa-solid fa-eraser me-2"></i> Limpiar</button> 
          </div>
        </form>
      </div>
    </div>
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