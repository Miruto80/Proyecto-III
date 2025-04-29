<!DOCTYPE html>
<html lang="es">

<head>
  <!-- php barra de navegacion-->
  <?php include 'vista/complementos/head.php' ?> 
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
        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Bitacora</li>
      </ol>
      <h6 class="font-weight-bolder text-white mb-0">Bitacora</h6>
    </nav>
<!-- php barra de navegacion-->    
<?php include 'vista/complementos/nav.php' ?>


<div class="container-fluid py-4"> <!-- DIV CONTENIDO -->

    <div class="row"> <!-- CARD PRINCIPAL-->  
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0">  <!-- CARD N-1 -->  
    
    <!--Titulo de página -->
     <div class="d-sm-flex align-items-center justify-content-between mb-5">
       <h4 class="mb-0"><i class="fa-solid fa-book  mr-2" style="color: #f6c5b4;"></i>
        Bitacora</h4>
           
      </div>
          

      <div class="table-responsive"> <!-- comienzo div table-->
           <!-- comienzo de tabla-->                      
          <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
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
                     
                        <button name="eliminar" class="btn btn-danger btn-sm eliminar">
                          <i class="fas fa-trash-alt" title="Eliminar"> </i>
                        </button>
                     
                  </td>
                </tr>
            
              </tbody>
                               
          </table> <!-- Fin tabla--> 
      </div>  <!-- Fin div table-->


            </div><!-- FIN CARD N-1 -->  
    </div>
    </div>  
    </div><!-- FIN CARD PRINCIPAL-->  



<!-- php barra de navegacion-->
<?php include 'vista/complementos/footer.php' ?>

</body>

</html>