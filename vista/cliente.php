<!DOCTYPE html>
<html lang="es">

<head> 
  <!-- php barra de navegacion-->
  <?php include 'complementos/head.php' ?> 
  <link rel="stylesheet" href="assets/css/estatus.css">
  <title> Cliente | LoveMakeup  </title> 
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


<div class="container-fluid py-4"> <!-- DIV CONTENIDO -->

    <div class="row"> <!-- CARD PRINCIPAL-->  
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0">  <!-- CARD N-1 -->  
    
    <!--Titulo de página -->
     <div class="d-sm-flex align-items-center justify-content-between mb-5">
       <h4 class="mb-0"><i class="fa-solid fa-user mr-2" style="color: #f6c5b4;"></i>
        Cliente</h4>
           
       <!-- Button que abre el Modal N1 Registro -->
       
      </div>
          

      <div class="table-responsive"> <!-- comienzo div table-->
           <!-- comienzo de tabla-->                      
           <table class="table table-bordered table-hover" id="myTable" width="100%" cellspacing="0">
              <thead class="table-color">
                <tr>
                   <th class="text-white">Cedula</th>
                  <th class="text-white">Nombre</th>
                  <th class="text-white">Apellido</th>
                  <th class="text-white">Telefono</th>
                  <th class="text-white">Correo</th>

                  <th class="text-white">Estatus</th>
                  <th class="text-white">ACCION</th>
                </tr>
              </thead>
              <tbody>
              <?php
                  $estatus_texto = array(
                    1 => "Cliente Activo",
                    2 => "Cliente Favorito",
                    3 => "Mal Cliente"
                  );
              
                  $estatus_classes = array(
                    1 => 'activos',
                    2 => 'favoritos',
                    3 => 'malclientes' 
                  );

                  foreach ($registro as $dato){
                ?>
                <tr>
                   <td><?php echo $dato['cedula']?></td>
                  <td><?php echo $dato['nombre']?></td>
                  <td><?php echo $dato['apellido']?></td>
                  <td><?php echo $dato['telefono']?></td>
                  <td><?php echo $dato['correo']?></td>
                  <td>
                  <span class="<?= $estatus_classes[$dato['estatus']] ?>">
                    <?php echo $estatus_texto[$dato['estatus']] ?>
                  </span>
                  </td>
                
                  <td>
                      <form method="POST" action="?pagina=cliente" id="formestatus">
                        <input type="hidden" name="id_persona" id="id_persona_hidden">

                  <?php if ($dato['estatus'] == 1) { ?>
                    <button type="button" class="btn btn-primary btn-sm favorito" data-id="<?php echo $dato['id_persona']; ?>">
                        <i class="fa-solid fa-star"></i>
                    </button>
                  <?php } else { ?>
                    <button type="button" class="btn btn-dark btn-sm clienteactivo" data-id="<?php echo $dato['id_persona']; ?>">
                       <i class="fa-solid fa-star-half"></i>
                    </button>
                  <?php } ?>

                  <?php if ($dato['estatus'] <= 2) { ?>
                    <button type="button" class="btn btn-warning btn-sm malcliente" data-id="<?php echo $dato['id_persona']; ?>">
                       <i class="fa-solid fa-face-angry"></i>
                   </button>
                  <?php } ?>

                  <button type="button" class="btn btn-info btn-sm"
                   data-bs-toggle="modal"
                   data-bs-target="#editarModal"
                   data-id="<?php echo $dato['id_persona']; ?>"
                   data-cedula="<?php echo $dato['cedula']; ?>" 
                   data-correo="<?php echo $dato['correo']; ?>">
                  <i class="fas fa-pencil-alt" title="Editar"></i> 
                </button>
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

<style>
  .text-g{
    font-size:15px;
  }
</style>

<!-- Modal -->
<div class="modal fade" id="editarModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header header-color">
        <h5 class="modal-title text-dark" id="modalLabel"><i class="fas fa-pencil-alt"></i> Editar Datos del Cliente</h5>
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
              <span id="textocorreomodal" class="text-danger"></span>
          </div>
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

<!-- php barra de navegacion-->
<?php include 'complementos/footer.php' ?>
<!-- para el datatable-->
<script src="assets/js/demo/datatables-demo.js"></script>
 <script src="assets/js/cliente.js"></script>
</body>

</html>