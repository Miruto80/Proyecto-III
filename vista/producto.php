<!DOCTYPE html>
<html lang="es">

<head>
  <!-- php barra de navegacion-->
  <?php include 'complementos/head.php' ?> 
  <title> Producto | LoveMakeup  </title> 
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
        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="#">Administrar</a></li>
        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Producto</li>
      </ol>
      <h6 class="font-weight-bolder text-white mb-0">Gestionar Producto</h6>
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
       <h4 class="mb-0"><i class="fa-solid fa-pump-soap mr-2" style="color: #f6c5b4;"></i>
        Producto</h4>
           
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
          <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0" id="tablapersona">
              <thead class="table-color">
                <tr>
                  <th class="text-white">Nombre</th>
                  <th class="text-white">Descripcion</th>
                  <th class="text-white">Marca</th>
                  <th class="text-white">Cantidad_mayor</th>
                  <th class="text-white">Precio_mayor</th>
                  <th class="text-white">Precio_detal</th>
                  <th class="text-white">Stock_disponible</th>
                  <th class="text-white">Stock_maximo</th>
                  <th class="text-white">Stock_minimo</th>
                  <th class="text-white">Imagen</th>
                  <th class="text-white">Estatus</th>
                  <th class="text-white">ACCION</th>
                </tr>
              </thead>
              <tbody id="resultadoconsulta">
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
        <h1 class="modal-title fs-5" id="1">Registrar Producto</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body"> <!-- Modal contenido -->
      
      <form method="post" id="f" autocomplete="off" enctype='multipart/form-data'>
      <div class="row mb-3">
								<div class="col-md-4">
									<label for="nombre">Nombre del producto</label>
									<input class="form-control" type="text" id="nombre" name="nombre" />
									<span id="snombre"></span>
								</div>
								<div class="col-md-8">
									<label for="descripcion">Descripcion</label>
									<textarea class="form-control" type="textarea" id="descripcion" name="descripcion" placeholder="Escribe la descripcion"></textarea>
									<span id="sdescripcion"></span>
								</div>
							</div>
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
<script src="/Lovemakeup/assets/js/producto.js"></script>


</body>

</html>