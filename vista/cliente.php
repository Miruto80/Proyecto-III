<!DOCTYPE html>
<html lang="es">

<head> 
  <!-- php barra de navegacion-->
  <?php include 'complementos/head.php' ?> 
  <link rel="stylesheet" href="assets/css/formulario.css">
  <title> Clientes | LoveMakeup  </title> 
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
        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="#">Web</a></li>
        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Cliente</li>
      </ol>
      <h6 class="font-weight-bolder text-white mb-0">Gestionar Cliente</h6>
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
     <div class="d-sm-flex align-items-center justify-content-between mb-3">
       <h4 class="mb-0"><i class="fa-solid fa-user me-2" style="color: #f6c5b4;"></i>
        Clientes</h4>
           
       <!-- Button que abre el Modal N1 Registro -->
       <button type="button" class="btn btn-primary"  title="click para ver la ayuda">
            <span class="icon text-white">
            <i class="fas fa-info-circle me-2"></i>
            </span>
            <span class="text-white" id="ayudacliente">Ayuda</span>
          </button>
      </div>
        

      <div class="table-responsive"> <!-- comienzo div table-->
           <!-- comienzo de tabla-->                      
           <table class="table table-hover" id="myTable" width="100%" cellspacing="0">
              <thead class="table-color">
                <tr>
                  <th class="text-white text-center">Nombre y Cédula</th>
                  <th class="text-white text-center">Telefono</th>
                  <th class="text-white text-center">Correo</th>
                  <th class="text-white text-center">Estatus</th>
                    <?php if ($_SESSION["nivel_rol"] == 3 && tieneAcceso(8, 'editar')): ?>
                  <th class="text-white text-center">Acción</th>
                    <?php endif; ?>
                </tr>
              </thead>
              <tbody>
              <?php
                  $estatus_texto = array(
                    1 => "Activo",
                    2 => "Inactivo"
                  );
              
                  $estatus_classes = array(
                    1 =>  'badge bg-success text-dark',
                    2 =>  'badge bg-danger text-white'
                  );

                  foreach ($registro as $dato){
                ?>
                <tr>
                  <td>
                    <div class="d-flex align-items-center">
                      <div class="me-3">
                        <i class="fas fa-user-circle fa-2x" style="color: #f6c5b4;"></i>
                      </div>
                      <div>
                        <div class="text-dark"><b><?php echo $dato['nombre'] . ' ' . $dato['apellido']; ?></b></div>
                        <div>N° Cédula: <?php echo $dato['cedula']; ?></div>
                      </div>
                    </div>
                  </td>
                  <td>
                      <div class="text-dark">
                        <?php echo $dato['telefono'] ?>
                      </div>
                  <?php
                    $telefono_limpio = str_replace('-', '', $dato['telefono']);
                    $telefono_sin_cero = ltrim($telefono_limpio, '0');
                    $link_whatsapp = "https://wa.me/58" . $telefono_sin_cero;
                  ?>
                  <a href="<?= $link_whatsapp ?>" target="_blank" class="btn btn-success btn-sm mt-1 Ayudatelefono" title="Contactar por WhatsApp">
                    <i class="fab fa-whatsapp me-1"></i> WhatsApp
                  </a>
                </td>

                 <td>
                    <div class="text-dark">
                      <?php echo $dato['correo'] ?>
                    </div>
                      <a href="mailto:<?= $dato['correo'] ?>" class="btn btn-info btn-sm mt-1 Ayudacorreo" title="Enviar correo">
                    <i class="fas fa-envelope me-1"></i> Enviar correo
                  </a>
                </td>

                  <td class="text-center">
                  <span class="<?= $estatus_classes[$dato['estatus']] ?>">
                    <?php echo $estatus_texto[$dato['estatus']] ?>
                  </span>
                  </td>
                    
                  <?php if ($_SESSION["nivel_rol"] == 3 && tieneAcceso(8, 'editar')): ?>
                  <td class="text-center">
                      <form method="POST" action="?pagina=cliente" id="formestatus">
                  
                        <button type="button" class="btn btn-primary btn-sm modificar" title="Editar datos del cliente"
                            data-bs-toggle="modal"
                            data-bs-target="#editarModal"
                            data-id="<?php echo $dato['id_persona']; ?>"
                            data-cedula="<?php echo $dato['cedula']; ?>" 
                            data-correo="<?php echo $dato['correo']; ?>"
                            data-estatus="<?php echo $dato['estatus']; ?>">
                            <i class="fas fa-pencil-alt" title="Editar"></i> 
                      </button>
                    </form>
                 </td>
                     <?php endif; ?>
                </tr>
               <?php } ?>
              </tbody>
                               
          </table> <!-- Fin tabla-->
      </div>  <!-- Fin div table-->


            </div><!-- FIN CARD N-1 -->  
    </div>
    </div>  
    </div><!-- FIN CARD PRINCIPAL-->  

<style>
  .text-g{
    font-size:15px;
  }
</style>

<!-- Modal -->
<div class="modal fade" id="editarModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content modal-producto">
      <div class="modal-header ">
        <h5 class="modal-title text-dark" id="modalLabel"><i class="fas fa-pencil-alt"></i> Editar Datos del Cliente</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="POST" action="?pagina=cliente" id="formdatosactualizar">
           <div class="seccion-formulario">
              <h6><i class="fas fa-boxes"></i> Modificar datos del cliente </h6>
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
              <span id="textocorreomodal" class="text-danger"></span>
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
            <input type="hidden" id="modalIdPersona" name="id_persona">
            <input type="hidden" id="modalce" name="cedulaactual">
            <input type="hidden" id="modalco" name="correoactual">
           </div>
        
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-modern btn-guardar me-3" name="actualizar" id="actualizar">
          <i class="fa-solid fa-floppy-disk me-2"></i> Actualizar datos</button>
        <button type="button" class="btn btn-modern btn-limpiar" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!--FIN Modal -->


<!-- php barra de navegacion-->
<?php include 'complementos/footer.php' ?>
<!-- para el datatable-->
<script src="assets/js/demo/datatables-demo.js"></script>
<script src="assets/js/cliente.js"></script>
</body>

</html>