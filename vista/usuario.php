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


<div class="container-fluid py-4"> <!-- DIV CONTENIDO -->

    <div class="row"> <!-- CARD PRINCIPAL-->  
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0">  <!-- CARD N-1 -->  
    
    <!--Titulo de página -->
     <div class="d-sm-flex align-items-center justify-content-between mb-5">
       <h4 class="mb-0"><i class="fa-solid fa-user-gear mr-2" style="color: #f6c5b4;"></i>
        Usuario</h5>
           
       <!-- Button que abre el Modal N1 Registro -->
          <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#registro">
            <span class="icon text-white">
            <i class="fas fa-file-medical"></i>
            </span>
            <span class="text-white">Registrar</span>
          </button>
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
                  <th class="text-white">Rol</th>
                  <th class="text-white">Estatus</th>
                  <th class="text-white">ACCION</th>
                </tr>
              </thead>
              <tbody>
              <?php
                $estatus_texto = array(
                    1 => "Activo",
                    2 => "Cliente Favorito",
                    3 => "Inactivo"
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
                  <td><?php echo $dato['nombre_tipo']?></td>
                  <td>
                    <span class="<?= $estatus_classes[$dato['estatus']] ?>">
                      <?php echo $estatus_texto[$dato['estatus']] ?>
                    </span>
                  </td>
                
                  <td>
                    <form method="POST" action="?pagina=usuario">
                      <button type="button" class="btn btn-primary btn-sm"
                   data-bs-toggle="modal"
                   data-bs-target="#editarModal"
                   data-id="<?php echo $dato['id_persona']; ?>"
                   data-cedula="<?php echo $dato['cedula']; ?>" 
                   data-correo="<?php echo $dato['correo']; ?>"
                   data-nombre_rol="<?php echo $dato['nombre_tipo']; ?>"
                   data-id_rol="<?php echo $dato['id_rol']; ?>" >
                   
                  <i class="fas fa-pencil-alt" title="Editar"></i> 
                </button>
                        
                        <button name="eliminar" class="btn btn-danger btn-sm eliminar" value="<?php echo $dato['id_persona']?>">
                          <i class="fas fa-trash-alt" title="Eliminar"> </i>
                        </button>
                        <input type="hidden" name="eliminar" value="<?php echo $dato['id_persona']?>">
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
                    <select class="form-select" name="id_rol" required>
                      <option value="">Seleccione una Rol:</option>
                        <?php foreach($rol as $rol) {?>
                      <option value="<?php echo $rol['id_tipo'];?>"> <?php echo $rol['nombre']." - Nivel ".$rol['nivel'];?> </option>
                        <?php } ?>
                    </select>
              </div>
             
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
              <span id="textocorreomodal" class="text-danger"></span>
          </div>
          <div class="mb-3">
             <label for="rol" class="form-label text-g">Rol</label>
                 <div class="input-group mb-3">
                  <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-user-tag"></i></span>
                    <select class="form-select" name="id_rol">
                      <option id="modalrol"> </option>
                        <?php foreach($roll as $rol) {?>
                          <option value="<?php echo $rol['id_rol'];?>"> <?php echo $rol['nombre']." - Nivel ".$rol['nivel'];?> </option>
                        <?php } ?>
                    </select>
              </div>             
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
 <script src="assets/js/usuario.js"></script>

</body>

</html>