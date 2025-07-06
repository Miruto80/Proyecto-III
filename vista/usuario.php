<!DOCTYPE html>
<html lang="es">

<head>
  <!-- php barra de navegacion-->
  <?php include 'complementos/head.php' ?> 
  <title> Usuario | LoveMakeup  </title> 
 <link rel="stylesheet" href="assets/css/estatus.css">
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
        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="#">Administrar Usuario </a></li>
        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Usuario</li>
      </ol>
      <h6 class="font-weight-bolder text-white mb-0">Gestionar Usuario</h6>
    </nav>
<!-- php barra de navegacion-->    
<?php include 'complementos/nav.php' ?>

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
    
    <!--Titulo de página -->
     <div class="d-sm-flex align-items-center justify-content-between mb-5">
       <h4 class="mb-0"><i class="fa-solid fa-user-gear me-2" style="color: #f6c5b4;"></i>
        Usuario</h5>
         
      <div class="d-flex gap-2">

    
       <!-- Button que abre el Modal N1 Registro -->
      <?php
          $re_usuario = false;
          if ($_SESSION["nivel_rol"] == 3 && !empty($_SESSION['permisos'])) {
              foreach ($_SESSION['permisos'] as $permiso) {
                  if (
                      $permiso['id_modulo'] == 13 &&
                      $permiso['accion'] === 'registrar' &&
                      $permiso['estado'] == 1
                  ) {
                      $re_usuario = true;
                      break;
                  }
              }
          }

          if ($re_usuario) {
          ?>
          <button type="button" class="btn btn-success registrar" data-bs-toggle="modal" data-bs-target="#registro">
            <span class="icon text-white">
            <i class="fas fa-file-medical me-2"></i>
            </span>
            <span class="text-white" id="btnAbrirRegistrar">Registrar</span>
          </button>
        <?php } ?>
          
  <button type="button" class="btn btn-primary" id="ayuda">
    <span class="icon text-white">
      <i class="fas fa-info-circle me-2"></i>
    </span>
    <span class="text-white">Ayuda</span>
  </button>
      </div>
          
  </div>  
      <div class="table-responsive"> <!-- comienzo div table-->
           <!-- comienzo de tabla-->                      
          <table class="table table-bordered table-hover" id="myTable" width="100%" cellspacing="0">
              <thead class="table-color">
                <tr>
                  <th class="text-white text-center">Cedula</th>
                  <th class="text-white text-center">Nombre</th>
                  <th class="text-white text-center">Apellido</th>
                  <th class="text-white text-center">Rol</th>
                     <?php
                          $ess_usuario = false;
                          if ($_SESSION["nivel_rol"] == 3 && !empty($_SESSION['permisos'])) {
                              foreach ($_SESSION['permisos'] as $permiso) {
                                  if (
                                      $permiso['id_modulo'] == 13 &&
                                      $permiso['accion'] === 'especial' &&
                                      $permiso['estado'] == 1
                                  ) {
                                      $ess_usuario = true;
                                      break;
                                  }
                              }
                          }

                          if ($ess_usuario) {
                          ?>
                  <th class="text-white text-center">Permisos</th>
                      <?php } ?>
                  <th class="text-white text-center">Estatus</th>
                  <th class="text-white text-center">Acción</th>
                </tr>
              </thead>
              <tbody>
              <?php
                $estatus_texto = array(
                    1 => "Activo",
                    2 => "Inactivo",
                    3 => "Online"
                  );
              
                  $estatus_classes = array(
                    1 =>  'badge bg-success',
                    2 =>  'badge bg-warning',
                    3 =>  'badge bg-primary'
                  );

                  foreach ($registro as $dato){
                    if ($dato['id_persona'] == 1) {
                    continue;
                     }
                ?>
                <tr>
                  <td><?php echo $dato['cedula']?></td>
                  <td><?php echo $dato['nombre']?></td>
                  <td><?php echo $dato['apellido']?></td>
                  <td><?php echo $dato['nombre_tipo']?></td>
                  
                      <?php
                          $es_usuario = false;
                          if ($_SESSION["nivel_rol"] == 3 && !empty($_SESSION['permisos'])) {
                              foreach ($_SESSION['permisos'] as $permiso) {
                                  if (
                                      $permiso['id_modulo'] == 13 &&
                                      $permiso['accion'] === 'especial' &&
                                      $permiso['estado'] == 1
                                  ) {
                                      $es_usuario = true;
                                      break;
                                  }
                              }
                          }

                          if ($es_usuario) {
                          ?>
                    <td>
                      <form action="?pagina=usuario" method="POST">
                    <button type="submit" class="btn btn-warning btn-sm permisotur" name="modificar" value="<?php echo $dato['id_persona']?>">
                        <i class="fa-solid fa-users-gear" title="Modificar Permiso"></i>
                    </button>
                        
                    </form>      
                  </td>
                   <?php } ?>
                  <td>
                      <span class="<?= $estatus_classes[$dato['estatus']] ?>">
                        <?php echo $estatus_texto[$dato['estatus']] ?>
                      </span>
                  </td>

                  <td>
                    

                    <form method="POST" action="?pagina=usuario">
                   
                      <button type="button" class="btn btn-info btn-sm informacion"
                      data-bs-toggle="modal"
                      data-bs-target="#infoModal"
                      data-nombre="<?php echo $dato['nombre']; ?>"
                      data-apellido="<?php echo $dato['apellido']; ?>"
                      data-rol="<?php echo $dato['nombre_tipo']; ?>"
                      data-telefono="<?php echo $dato['telefono']; ?>"
                      data-correo="<?php echo $dato['correo']; ?>"
                      data-estatus="<?php echo $dato['estatus']; ?>"
                    >
                      <i class="fas fa-eye" title="Ver Detalles"></i>
                    </button>
                 

                     <?php
                          $ed_usuario = false;
                          if ($_SESSION["nivel_rol"] == 3 && !empty($_SESSION['permisos'])) {
                              foreach ($_SESSION['permisos'] as $permiso) {
                                  if (
                                      $permiso['id_modulo'] == 13 &&
                                      $permiso['accion'] === 'editar' &&
                                      $permiso['estado'] == 1
                                  ) {
                                      $ed_usuario = true;
                                      break;
                                  }
                              }
                          }

                          if ($ed_usuario) {
                          ?>
                      <button type="button" class="btn btn-primary btn-sm modificar"
                      data-bs-toggle="modal"
                      data-bs-target="#editarModal"
                      data-id="<?php echo $dato['id_persona']; ?>"
                      data-cedula="<?php echo $dato['cedula']; ?>" 
                      data-correo="<?php echo $dato['correo']; ?>"
                      data-nombre_rol="<?php echo $dato['nombre_tipo']; ?>"
                      data-estatus="<?php echo $dato['estatus']; ?>"
                      data-id_tipo="<?php echo $dato['id_rol'];
                    ?>" >
                   
                  <i class="fas fa-pencil-alt" title="Editar"></i> 
                </button>
                         <?php } ?>
                          <?php
                          $el_usuario = false;
                          if ($_SESSION["nivel_rol"] == 3 && !empty($_SESSION['permisos'])) {
                              foreach ($_SESSION['permisos'] as $permiso) {
                                  if (
                                      $permiso['id_modulo'] == 13 &&
                                      $permiso['accion'] === 'eliminar' &&
                                      $permiso['estado'] == 1
                                  ) {
                                      $el_usuario = true;
                                      break;
                                  }
                              }
                          }

                          if ($el_usuario) {
                          ?>
                        <button name="eliminar" class="btn btn-danger btn-sm eliminar" value="<?php echo $dato['id_persona']?>">
                          <i class="fas fa-trash-alt" title="Eliminar"> </i>
                        </button>
                        <input type="hidden" name="eliminar" value="<?php echo $dato['id_persona']?>">
                    
                        <?php } ?>

                     </form>
                  </td>
                </tr>
               <?php } ?>
              </tbody>
                               
          </table> <!-- Fin tabla--> 
      </div>  <!-- Fin div table-->


            </div><!-- FIN CARD N-1 -->  
    </div>
    </div>  
    </div><!-- FIN CARD PRINCIPAL-->  


<!-- Modal -->
<div class="modal fade" id="registro" tabindex="1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog  modal-lg modal-dialog-centered">
    <div class="modal-content">
    <div class="modal-header header-color">
        <h1 class="modal-title fs-5" id="1"> <i class="fa-solid fa-user-plus"></i> Registrar Usuario</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body"> <!-- Modal contenido -->
      
      <form action="?pagina=usuario" method="POST" id="u" autocomplete="off">


        <div class="row">  <!-- F1 -->
          <div class="col">
            <label>NOMBRE</label>
              <div class="input-group mb-3">
                  <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-user"></i></span>
                  <input type="text" class="form-control "name="nombre"  id="nombre" placeholder="nombre: juan">
              </div>
               <span id="textonombre" class="alert-text"></span>
          </div>

          <div class="col">
             <label>APELLIDO</label>
               <div class="input-group mb-3">
                  <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-user"></i></span>
                   <input type="text" class="form-control "name="apellido"  id="apellido" placeholder="apellido: perez">
              </div>
               <span id="textoapellido" class="alert-text"></span>
          </div>
        </div>

          <div class="row">  <!-- F2 -->
          <div class="col">
             <label>N° DE CEDULA</label>
                 <div class="input-group mb-3">
                  <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-id-card"></i></span>
                  <input type="text" class="form-control "name="cedula"  id="cedula" placeholder="cedula: 11222333">
              </div>
         
               <span id="textocedula" class="alert-text"></span>
          </div>
          <div class="col">
             <label>ROL</label>
              <div class="input-group mb-3">
                  <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-user-tag"></i></span>
                    <select class="form-select" name="id_rol"  id="rolSelect" required >
                      <option value="">Seleccione una Rol:</option>
                        <?php foreach($rol as $item) {?>
                      <option value="<?php echo $item['id_rol'];?>" data-nivel="<?php echo $item['nivel'];?>"> <?php echo $item['nombre']." - Nivel ".$item['nivel'];?> </option>
                        <?php } ?>
                    </select>
                  
              </div>
             <input type="hidden" id="nivelHidden" name="nivel">
          </div>
        </div>

          <div class="row">  <!-- F3 -->
          <div class="col">
              <label>TELEFONO</label>
                <div class="input-group mb-3">
                  <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-mobile-screen-button"></i></span>
                  <input type="text" class="form-control "name="telefono"  id="telefono" placeholder="Telefono: 04240001122">
              </div>  
               <span id="textotelefono" class="alert-text"></span>
          </div>
          <div class="col">
              <label>CORREO</label>
              <div class="input-group mb-3">
                  <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-envelope"></i></span>
                  <input type="text" class="form-control "name="correo"  id="correo" placeholder="Correo: tucorreo@dominio.com">
              </div>  
              <span id="textocorreo" class="alert-text"></span>
          </div>
        </div>

          <div class="row">  <!-- F4 -->
          <div class="col">
             <label>CONSTRASEÑA</label>
              <div class="input-group mb-3">
                  <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-unlock"></i></span>
                  <input type="text" class="form-control "name="clave"  id="clave" placeholder="Constraseña:">
              </div>  
              <span id="textoclave" class="alert-text"></span>
          </div>
        
        </div>


      <br>

      <div class="text-center">
        <button type="button" class="btn btn-success" id="registrar"> <i class="fa-solid fa-floppy-disk"></i> Registrar</button>
        <button type="reset" class="btn btn-primary"> <i class="fa-solid fa-eraser"></i>  Limpiar</button>
        </div>



      </form>

      </div> <!-- FIN Modal contenido -->
      
    </div>
  </div>
</div>


<!-- Modal MODIFCAR -->
<div class="modal fade" id="editarModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header header-color">
        <h5 class="modal-title text-dark" id="modalLabel"><i class="fas fa-pencil-alt"></i> Editar Datos del Usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="POST" action="?pagina=cliente" id="formdatosactualizar">
          <div class="mb-3">
            <label for="cedula" class="form-label text-g">Cédula</label>
             <div class="input-group mb-3">
                  <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-id-card"></i></span>
                   <input type="text" class="form-control" id="modalCedula" name="cedula">
              </div>
                <span id="textocedulamodal" class="text-danger"></span>
          </div>
          <div class="mb-3">
            <label for="correo" class="form-label text-g">Correo Electrónico</label>
             <div class="input-group mb-3">
                  <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-envelope"></i></span>
                  <input type="email" class="form-control" id="modalCorreo" name="correo">
              </div>  
              <span id="textocorreomodal" class="invalid-feedback"> El formato debe incluir @ y ser válido.</span>
          </div>
          <div class="mb-3">
             <label for="rol" class="form-label text-g">Rol</label>
                 <div class="input-group mb-3">
                  <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-user-tag"></i></span>
                    <select class="form-select" name="id_rol" id="rolSelectedit">
                      <option id="modalrol"> </option>
                        <?php foreach($roll as $item) {?>
                          <option value="<?php echo $item['id_rol'];?>" data-nivel="<?php echo $item['nivel'];?>"> <?php echo $item['nombre']." - Nivel ".$item['nivel'];?> </option>
                        <?php } ?>
                    </select>

                    <input type="hidden" name="rol_actual" id="rolactual">
                    <input type="hidden" name="nivel" id="idnivel">
              </div> 
              

              <div class="mb-3">
             <label for="rol" class="form-label text-g">Estatus</label>
                 <div class="input-group mb-3">
                  <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-user-tag"></i></span>
                    <select class="form-select" name="estatus">
                      <option id="modalestatus"> </option>
                       <option value="1">Activo</option>
                       <option value="2">Inactivo</option>
                    </select>
              </div>            
          </div>
          <input type="hidden" id="modalce" name="cedulaactual">
          <input type="hidden" id="modalco" name="correoactual">
          
          <input type="hidden" id="modalIdPersona" name="id_persona">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success text-dark" name="actualizar" id="actualizar"><i class="fa-solid fa-floppy-disk"></i> Actualizar datos</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!--FIN Modal -->
 </div>


<div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow">
      <div class="modal-header header-color text-white">
        <h5 class="modal-title" id="infoModalLabel">Información del Usuario</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <table class="table ">
          <tr>
            <th>Nombre Completo</th>
            <td id="modalNombreCompleto"></td>
          </tr>
          <tr>
            <th>Rol</th>
            <td id="modalRol"></td>
          </tr>
          <tr>
            <th>Teléfono</th>
            <td id="modalTelefonoss"></td>
          </tr>
          <tr>
            <th>Correo</th>
            <td id="modalCorreoss"></td>
          </tr>
          <tr>
            <th>Estatus</th>
            <td id="modalEstatus"></td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>
<script>
  const infoModal = document.getElementById('infoModal');
  infoModal.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;

    const nombre = button.getAttribute('data-nombre');
    const apellido = button.getAttribute('data-apellido');
    const rol = button.getAttribute('data-rol');
    const telefono = button.getAttribute('data-telefono');
    const correo = button.getAttribute('data-correo');
    const estatus = button.getAttribute('data-estatus');

    const estatusTexto = {
      1: 'Activo',
      2: 'Inactivo',
      3: 'El usuario esta adentro del sistema'
    };

    const estatusClase = {
      1: 'badge bg-success',
      2: 'badge bg-warning',
      3: 'badge bg-primary'
    };

    document.getElementById('modalNombreCompleto').textContent = `${nombre} ${apellido}`;
    document.getElementById('modalRol').textContent = rol;
    document.getElementById('modalTelefonoss').textContent = telefono;
    document.getElementById('modalCorreoss').textContent = correo;
    document.getElementById('modalEstatus').innerHTML = `<span class="${estatusClase[estatus]}">${estatusTexto[estatus]}</span>`;
  });
</script>
<div class="modal fade" id="permisosModal" tabindex="-1" aria-labelledby="permisosModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="permisosModalLabel">Permisos por Módulo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-bordered text-center align-middle">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>Módulo</th>
                <th>Ver</th>
                <th>Crear</th>
                <th>Actualizar</th>
                <th>Eliminar</th>
                <th>Especial</th>
              </tr>
            </thead>
            <tbody>
              <!-- Ejemplo de fila -->
              <tr>
                <td>1</td>
                <td>Dashboard</td>
                <td><div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" checked="">
                   
                    </div></td>
              <td><center><div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" checked="">
                    
                    </div></center></td>
              <td><div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" checked="">
                   
                    </div></td>
              <td><div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" checked="">
                    
                    </div></td>
              <td><div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" checked="">
                    
                    </div></td>                  
              
              </tr>
              <!-- Agrega más filas según los módulos -->
              <tr>
                <td>2</td>
                <td>Usuarios</td>
                <td><input type="checkbox" class="form-check-input"></td>
                <td><input type="checkbox" class="form-check-input"></td>
                <td><input type="checkbox" class="form-check-input"></td>
                <td><input type="checkbox" class="form-check-input"></td>
                <td><input type="checkbox" class="form-check-input"></td>
              </tr>
              <!-- ... -->
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Guardar</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Salir</button>
      </div>
    </div>
  </div>
</div>

<script>
document.getElementById('rolSelect').addEventListener('change', function() {
  const selectedOption = this.options[this.selectedIndex];
  const nivel = selectedOption.getAttribute('data-nivel');
  document.getElementById('nivelHidden').value = nivel;
});

document.getElementById('rolSelectedit').addEventListener('change', function() {
  const selectedOption = this.options[this.selectedIndex];
  const nivel = selectedOption.getAttribute('data-nivel');
  document.getElementById('idnivel').value = nivel;
});
</script>

<!-- php barra de navegacion-->
<?php include 'complementos/footer.php' ?>
<!-- para el datatable-->
 <script src="assets/js/demo/datatables-demo.js"></script>
 <script src="assets/js/usuario.js"></script>

</body>

</html>