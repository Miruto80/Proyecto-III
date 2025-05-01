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
           <table class="table table-bordered table-hover" id="myTable" width="100%" cellspacing="0">
              <thead class="table-color">
                <tr>
                  <th class="text-white">Nombre</th>
                  <th class="text-white">Descripcion</th>
                  <th class="text-white">Marca</th>
                  <th class="text-white">Al mayor</th>
                  <th class="text-white">Precio M</th>
                  <th class="text-white">Precio D</th>
                  <th class="text-white">Stock_dis</th>
                  <th class="text-white">Stock_m</th>
                  <th class="text-white">Stock_m</th>
                  <th class="text-white"><i class="fa-solid fa-image"></i></th>
                  <th class="text-white">ACCION</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  foreach ($registro as $dato){
                ?>
                <tr>
                  <td><?php echo $dato['nombre']?></td>
                  <td><?php echo $dato['descripcion']?></td>
                  <td><?php echo $dato['marca']?></td>
                  <td><?php echo $dato['cantidad_mayor']?></td>
                  <td><?php echo $dato['precio_mayor']?></td>
                  <td><?php echo $dato['precio_detal']?></td>
                  <td><?php echo $dato['stock_disponible']?></td>
                  <td><?php echo $dato['stock_maximo']?></td>
                  <td><?php echo $dato['stock_minimo']?></td>
                  <td><?php echo $dato['imagen']?></td>
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
        <h1 class="modal-title fs-5" id="1">Registrar Producto</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body"> <!-- Modal contenido -->
      
      <form method="post" id="f" autocomplete="off" enctype='multipart/form-data'>
      <input autocomplete="off" type="text" class="form-control" name="accion" id="accion" style="display: none;">
      <div class="row mb-3">
								<div class="col-md-6">
									<label for="nombre">Nombre del producto</label>
									<input class="form-control" type="text" id="nombre" name="nombre" />
									<span id="snombre"></span>
								</div>
                <div class="col-md-6">
									<label for="marca">Marca del producto</label>
									<input class="form-control" type="text" id="marca" name="marca" />
									<span id="smarca"></span>
								</div>
				</div>
        <div class="row mb-3">
        <div class="col-md-12">
									<label for="descripcion">Descripcion</label>
									<textarea class="form-control" type="textarea" id="descripcion" name="descripcion" placeholder="Escribe la descripcion"></textarea>
									<span id="sdescripcion"></span>
								</div>
        </div> 
      <div class="row mb-3">
								<div class="col-md-3">
									<label for="cantidad_mayor">C al mayor</label>
									<input class="form-control" type="text" id="cantidad_mayor" name="cantidad_mayor" />
									<span id="scantidad_mayor"></span>
								</div>
								<div class="col-md-3">
									<label for="precio_mayor">Precio al mayor</label>
									<input class="form-control" type="text" id="precio_mayor" name="precio_mayor" />
									<span id="sprecio_mayor"></span>
								</div>
								<div class="col-md-3">
									<label for="precio_detal">Precio al detal</label>
									<input class="form-control" type="text" id="precio_detal" name="precio_detal" />
									<span id="sprecio_detal"></span>
								</div>
								<div class="col-md-3">
									<label for="stock_disponible">Stock disponible</label>
									<input class="form-control" type="text" id="stock_disponible" name="stock_disponible"/>
									<span id="sstock_disponible"></span>
								</div>
								
				</div>
      <div class="row mb-3">
                  <div class="col-md-4">
                    <label for="stock_maximo">Stock maximo</label>
                    <input class="form-control" type="text" id="stock_maximo" name="stock_maximo" />
                    <span id="sstock_maximo"></span>
                  </div>
                  <div class="col-md-4">
                    <label for="stock_minimo">Stock minimo</label>
                    <input class="form-control" type="text" id="stock_minimo" name="stock_minimo" />
                    <span id="sstock_minimo"></span>
                  </div>
								<div class="col-md-4">
                <label for="categoria">Categoria</label>
									<select class="form-select text-gray-900 " name="categoria" id="categoria" required>
                            <option value="">Seleccione una Categoria</option>
                               <?php foreach($categoria as $categoria) {?>
                                   <option value="<?php echo $categoria['id_categoria'];?>"> <?php echo $categoria['nombre'];?> </option>
                                <?php } ?>
              </select>
								</div>
								
				</div>

        <div class="row">
								<div class="col-md-12">
									<center>
										<label for="archivo" style="cursor:pointer">

											<img src="assets/img/logo.PNG" id="imagen"
												class="img-fluid rounded-circle w-25 mb-3 centered"
												style="object-fit:scale-down">
                        <br>
											Click aqui para subir la foto del producto
										</label>
										<input id="archivo" type="file"
											style="display:none"
											accept=".png,.jpg,.jpeg,.webp"
											name="imagenarchivo" />
									</center>
								</div>
							</div>
						
              
<br>
      <div class="text-center">
      <button type="button" class="btn btn-primary" name="registrar" id="registrar">Registrar</button>
      <button type="reset" class="btn btn-primary">Limpiar</button>
        </div>
      </form>

      


      </div> <!-- FIN Modal contenido -->
      
    </div>
  </div>
</div>

<!-- php barra de navegacion-->
<?php include 'complementos/footer.php' ?>
<script src="assets/js/demo/datatables-demo.js"></script>

<script src="/Lovemakeup/assets/js/producto.js"></script>


</body>

</html>