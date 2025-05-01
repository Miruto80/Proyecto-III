<!DOCTYPE html>
<html lang="es">

<head>
  <!-- php barra de navegacion-->
  <?php include 'complementos/head.php' ?> 
  <title> Usuario | LoveMakeup  </title> 
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
                  <th class="text-white">ID</th>
                  <th class="text-white">ID</th>
                  <th class="text-white">ID</th>
                  <th class="text-white">ID</th>
                  <th class="text-white">ACCION</th>
                </tr>
              </thead>
              <tbody>
    
                <tr>
                 <td>1</td>
                 <td>1</td>
                 <td>1</td>
                 <td>1</td>
                  <td>
                    <form method="POST" action="">
                       <button name="modificar" class="btn btn-primary btn-sm modificar"> 
                        <i class="fas fa-pencil-alt" title="Editar"> </i> 
                       </button>
                        
                        <button name="eliminar" class="btn btn-danger btn-sm eliminar">
                          <i class="fas fa-trash-alt" title="Eliminar"> </i>
                        </button>
                     </form>
                  </td>
                </tr>
            
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
        <h1 class="modal-title fs-5" id="1">Registrar Usuario</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body"> <!-- Modal contenido -->
      
      <form action="">
        <input type="text" class="form-control "name=""  id=""> <br>
        <input type="text" class="form-control "name=""  id=""> <br>
<br>
      <div class="text-center">
        <button type="button" class="btn btn-primary">Registrar</button>
        <button type="reset" class="btn btn-primary">Limpiar</button>
        </div>
      </form>

      


      </div> <!-- FIN Modal contenido -->
      
    </div>
  </div>
</div>
<!-- php barra de navegacion-->
<?php include 'complementos/footer.php' ?>
<!-- para el datatable-->
 <script src="assets/js/demo/datatables-demo.js"></script>

</body>

</html>