<!DOCTYPE html>
<html lang="es">

<head>
  <!-- php barra de navegacion-->
  <?php include 'complementos/head.php' ?> 
  <title> Usuario | LoveMakeup  </title> 
  <link rel="stylesheet" href="assets/css/formulario.css">
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
       <?php if ($_SESSION["nivel_rol"] >= 2 && tieneAcceso(13, 'registrar')): ?>
          <button type="button" class="btn btn-success registrar" title="(CONTROL + ALT + N) Registrar usuario" data-bs-toggle="modal" data-bs-target="#registro">
            <span class="icon text-white">
            <i class="fas fa-file-medical me-2"></i>
            </span>
            <span class="text-white" id="btnAbrirRegistrar">Registrar</span>
          </button>
         <?php endif; ?>
         
  <button type="button" class="btn btn-primary" id="ayuda" title="(CONTROL + ALT + A) click para ver la ayuda">
    <span class="icon text-white">
      <i class="fas fa-info-circle me-2"></i>
    </span>
    <span class="text-white">Ayuda</span>
  </button>

      </div>
          
  </div>  
      <div class="table-responsive"> <!-- comienzo div table-->
           <!-- comienzo de tabla-->                      
          <table class="table  table-hover" id="myTable" width="100%" cellspacing="0">
              <thead class="table-color">
                <tr>
                  <th class="text-white text-center">Nombre y Cédula</th>
                 
                  <th class="text-white text-center">Rol</th>
                    <?php if ($_SESSION["nivel_rol"] >= 2 && tieneAcceso(13, 'especial')): ?>
                  <th class="text-white text-center">Permisos</th>
                    <?php endif; ?>
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
                    1 =>  'badge bg-success text-dark',
                    2 =>  'badge bg-danger',
                    3 =>  'badge bg-primary'
                  );

                  foreach ($registro as $dato){
                ?>
                <tr>
                 <td>
                    <div class="d-flex align-items-center">
                      <div class="me-3">
                        <i class="fa-solid fa-id-card-clip fa-2x" style="color: #f6c5b4;"></i>
                      </div>
                      <div>
                        <div class="text-dark">
                          <b>
                            <?php echo $dato['nombre'] . ' ' . $dato['apellido']; ?>
                            <?php if ($dato['id_persona'] == 2): ?>
                              <i class="fa-solid fa-circle-check text-primary ms-1" title="Jefa Lovemakeup C.A"></i>
                            <?php endif; ?>
                          </b>
                        </div>
                        <div>N° Cédula: <?php echo $dato['cedula']; ?></div>
                      </div>
                    </div>
                  </td>

           
                  <td class="text-center text-dark">
                     <div>
                        <?php echo $dato['nombre_tipo']; ?>
                      </div>
                      <div style="font-size: 11px; color: #d67888">
                        <b> Nivel: <?php echo $dato['nivel']; ?> </b>
                      </div>
                  </td>
                  
                 <?php if ($_SESSION["nivel_rol"] >= 2 && tieneAcceso(13, 'especial')): ?>
                    <td class="text-center">
                        <form action="?pagina=usuario" method="POST">
                            <?php
                                $idActual = $_SESSION["id"];
                                $idFila = $dato['id_persona'];
                                $deshabilitado = ($idActual == $idFila || $idFila == 2) ? 'disabled' : '';
                            ?>
                            <button type="submit" class="btn btn-warning btn-sm permisotur" name="modificar" title="Modificar Permiso del usuario" value="<?php echo $idFila ?>" <?php echo $deshabilitado ?>>
                                <i class="fa-solid fa-users-gear" title="Modificar Permiso"></i>
                            </button> 
                            <input type="hidden" name="permisonombre" value=" <?php echo $dato['nombre']; ?>">
                            <input type="hidden" name="permisoapellido" value=" <?php echo $dato['apellido']; ?>">  
                        </form>      
                    </td>
                <?php endif; ?>

                  <td class="text-center">
                      <span class="<?= $estatus_classes[$dato['estatus']] ?>">
                        <?php echo $estatus_texto[$dato['estatus']] ?>
                      </span>
                  </td>

                  <td class="text-center">

                    <form method="POST" action="?pagina=usuario">
                   
                      <button type="button" class="btn btn-info btn-sm informacion" title="Ver mas informacion del usuario"
                      data-bs-toggle="modal"
                      data-bs-target="#infoModal"
                      data-nombre="<?php echo $dato['nombre']; ?>"
                      data-apellido="<?php echo $dato['apellido']; ?>"
                      data-cedula="<?php echo $dato['cedula']; ?>"
                      data-rol="<?php echo $dato['nombre_tipo']; ?>"
                      data-telefono="<?php echo $dato['telefono']; ?>"
                      data-correo="<?php echo $dato['correo']; ?>"
                      data-estatus="<?php echo $dato['estatus']; ?>"
                    >
                      <i class="fas fa-eye" title="Ver Detalles"></i>
                    </button>
                
                      <?php if ($_SESSION["nivel_rol"] >= 2 && tieneAcceso(13, 'editar')): ?>
                      <button type="button" class="btn btn-primary btn-sm modificar" title="Editar datos del usuario"
                      data-bs-toggle="modal"
                      data-bs-target="#editarModal"
                      data-id="<?php echo $dato['id_persona']; ?>"
                      data-cedula="<?php echo $dato['cedula']; ?>" 
                      data-correo="<?php echo $dato['correo']; ?>"
                      data-nombre_rol="<?php echo $dato['nombre_tipo']; ?>"
                      data-estatus="<?php echo $dato['estatus']; ?>"
                      data-id_tipo="<?php echo $dato['id_rol'];
                    ?>" >
                   
                  <i class="fas fa-pencil-alt"></i> 
                </button>
                    <?php endif; ?>

                        <?php if ($_SESSION["nivel_rol"] == 3 && tieneAcceso(13, 'eliminar')): ?>
                          <button 
                            name="eliminar" 
                            id="eliminar" 
                            class="btn btn-danger btn-sm eliminar" 
                            value="<?php echo $dato['id_persona']?>" 
                            title="Eliminar Usuario"
                            data-nombre="<?php echo $dato['nombre']; ?>"
                            data-apellido="<?php echo $dato['apellido']; ?>"
                          >
                            <i class="fas fa-trash-alt" title="Eliminar"></i>
                          </button>
                          <input type="hidden" name="eliminar" value="<?php echo $dato['id_persona']; ?>">
                        <?php endif; ?>


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
    <div class="modal-content modal-producto">
    <div class="modal-header">
        <h5 class="modal-title fs-5" id="1">
        <i class="fa-solid fa-user-plus"></i>
         Registrar Usuario</h5>
        <button type="button" class="btn-close"  title="(CONTROL + ALT + X) Cerrar" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        
  <form action="?pagina=usuario" method="POST" id="u" autocomplete="off">
     <div class="seccion-formulario">
              <h6><i class="fas fa-boxes"></i> Datos del Usuario </h6>
    <div class="row g-3">
      <!-- F1: Nombre y Apellido -->
      <div class="col-md-6">
        <label for="nombre">NOMBRE</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
          <input type="text" class="form-control" name="nombre" id="nombre" placeholder="nombre: juan">
        </div>
        <span id="textonombre" class="error-message"></span>
      </div>

      <div class="col-md-6">
        <label for="apellido">APELLIDO</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
          <input type="text" class="form-control" name="apellido" id="apellido" placeholder="apellido: perez">
        </div>
        <span id="textoapellido" class="error-message"></span>
      </div>

      <!-- F2: Cédula y Rol -->
      <div class="col-md-6">
        <label for="cedula">N° DE CÉDULA</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fa-solid fa-id-card"></i></span>
          <input type="text" class="form-control" name="cedula" id="cedula" placeholder="cedula: 11222333">
        </div>
        <span id="textocedula" class="error-message"></span>
      </div>

      <div class="col-md-6">
        <label for="rolSelect">ROL</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fa-solid fa-user-tag"></i></span>
          <select class="form-select" name="id_rol" id="rolSelect" required>
            <option value="">Seleccione un Rol:</option>
            <?php foreach($rol as $item) {?>
            <option value="<?php echo $item['id_rol'];?>" data-nivel="<?php echo $item['nivel'];?>">
              <?php echo $item['nombre']." - Nivel ".$item['nivel'];?>
            </option>
            <?php } ?>
          </select>
        </div>
         <span id="textorol" class="error-message"></span>
        <input type="hidden" id="nivelHidden" name="nivel">
      </div>

      <!-- F3: Teléfono y Correo -->
      <div class="col-md-6">
        <label for="telefono">TELÉFONO</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fa-solid fa-mobile-screen-button"></i></span>
          <input type="text" class="form-control" name="telefono" id="telefono" placeholder="Telefono: 04240001122">
        </div>
        <span id="textotelefono" class="error-message"></span>
      </div>

      <div class="col-md-6 ">
        <label for="correo">CORREO</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
          <input type="text" class="form-control" name="correo" id="correo" placeholder="Correo: tucorreo@dominio.com">
        </div>
        <span id="textocorreo" class="error-message"></span>
      </div>

      <!-- F4: Contraseña -->
      <div class="col-md-6">
        <label for="clave">CONTRASEÑA</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fa-solid fa-unlock"></i></span>
          <input type="text" class="form-control" name="clave" id="clave" placeholder="Contraseña:">
        </div>
        <span id="textoclave" class="error-message"></span>
      </div>

      <div class="col-md-6">
        <label for="confirmar_clave">CONFIRMAR CONTRASEÑA</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fa-solid fa-unlock-keyhole"></i></span>
          <input type="text" class="form-control" id="confirmar_clave" placeholder="Confirmar contraseña:">
        </div>
        <span id="textoconfirmar" class="error-message"></span>
      </div>
 </div>
 <hr class="bg-primary">
 <div class="col">
     <div class="info-box">
        <div class="info-icon">
          <i class="fa-solid fa-circle-info"></i>
        </div>

        <div class="info-content">
          <strong>Aviso Importante:</strong>
          <p>Los permisos predeterminados se asignan según el nivel del usuario:</p>
          <p>
            <b>Nivel 3: acceso completo a los módulos:</b> <br>
            <span class="text-muted">Reporte, Compra, Producto, Venta, Reserva, Proveedor, Categoría, Cliente, Pedido Web, Método de Pago, Método de Entrega, Usuario, Tipo de Usuario.</span>
          </p>
          <p><b>Nivel 2: acceso limitado a los módulos: </b><br>
              <span class="text-muted">Reporte, Producto, Venta, Reserva, Pedido Web.</span></p>
          <p>Si deseas modificar los permisos asignados, dirígete a la sección <b>Permisos</b> </p>
        </div>
      </div>
  </div>
 </div>

  
      <!-- Botones -->
      <div class="col-12 text-center ">
        <button type="button" class="btn btn-modern btn-guardar me-3" id="registrar">
          <i class="fa-solid fa-floppy-disk me-2"></i> Registrar
        </button>
        <button type="reset" class="btn btn-modern btn-limpiar">
          <i class="fa-solid fa-eraser me-2"></i> Limpiar
        </button>
      </div>
    </div>
  </form>
</div>

      
    </div>
  </div>
</div>


<!-- Modal MODIFCAR -->
<div class="modal fade" id="editarModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content modal-producto">
      <div class="modal-header">
        <h5 class="modal-title text-dark" id="modalLabel"><i class="fas fa-pencil-alt"></i> Editar Datos del Usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" title="(CONTROL + ALT + X) Cerrar"  aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="POST" action="?pagina=cliente" id="formdatosactualizar">
              <div class="seccion-formulario">
              <h6><i class="fas fa-boxes"></i> Modicar datos del Usuario </h6>
          <div class="mb-3">
            <label for="cedula" class="form-label text-g">Cédula</label>
             <div class="input-group mb-3">
                  <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-id-card"></i></span>
                   <input type="text" class="form-control" id="modalCedula" name="cedula">
              </div>
                <span id="textocedulamodal" class="error-message"> </span>
          </div>
          <div class="mb-3">
            <label for="correo" class="form-label text-g">Correo Electrónico</label>
             <div class="input-group mb-3">
                  <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-envelope"></i></span>
                  <input type="email" class="form-control" id="modalCorreo" name="correo">
              </div>  
              <span id="textocorreomodal" class="error-message"> </span>
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
 </div>
  </div>
     <div class="col-12 text-center ">
           <button type="button" class="btn btn-modern btn-guardar me-3" name="actualizar" id="actualizar">
            <i class="fa-solid fa-floppy-disk me-2"></i> Actualizar datos</button>
        <button type="button" class="btn btn-modern btn-limpiar" data-bs-dismiss="modal">Cerrar</button>
        </form>
       </div>
      </div>

       

    </div>
  </div>
</div>

<!--FIN Modal -->
 </div>


<div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content modal-producto">
      <div class="modal-header header-color text-white">
        <h5 class="modal-title" id="infoModalLabel">Información del Usuario</h5>
        <button type="button" class="btn-close" title="(CONTROL + ALT + X) Cerrar" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
         <div class="seccion-formulario table-responsive">
        <table class="table">
          <tr>
            <th>Nombre Completo</th>
            <td id="modalNombreCompleto"></td>
          </tr>
          <tr>
            <th>N° Cedula</th>
            <td id="modalcedula"></td>
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
</div>

<script>
  const infoModal = document.getElementById('infoModal');
  infoModal.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;

    const nombre = button.getAttribute('data-nombre');
    const apellido = button.getAttribute('data-apellido');
    const cedula = button.getAttribute('data-cedula');
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
      1: 'badge bg-success text-dark',
      2: 'badge bg-danger text-white',
      3: 'badge bg-primary'
    };

    document.getElementById('modalNombreCompleto').textContent = `${nombre} ${apellido}`;
    document.getElementById('modalcedula').textContent = cedula;
    document.getElementById('modalRol').textContent = rol;
    document.getElementById('modalTelefonoss').textContent = telefono;
    document.getElementById('modalCorreoss').textContent = correo;
    document.getElementById('modalEstatus').innerHTML = `<span class="${estatusClase[estatus]}">${estatusTexto[estatus]}</span>`;
  });
</script>

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