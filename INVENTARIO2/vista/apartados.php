<?php 
require_once("comunes/encabezado.php");//Incluye el encabezado común
require_once('comunes/menu.php');//Incluye el menú común 
?>
<div class="container"> 
	<h1> Gestionar Apartado de productos</h1> 
	<p style="text-align: justify;">"En la sección de Apartados podrás gestionar las reservas de productos realizadas por los clientes. Selecciona el cliente, los productos deseados y registra el apartado. El sistema controlará automáticamente el inventario reservado. Cuando el cliente complete el pago, podrás convertir el apartado en una venta definitiva."</p>
	<div class="container">
		<div class="row mt-1 justify-content-center">
			<div class="col-md-2 text-center">
				<button type="button" class="btn-sm btn-success w-75 small-width" id="incluir" title="Registrar Apartado"><i class="bi bi-plus-square"></i></button><!-- Botón para registrar un nuevo apartado -->
			</div>				
		</div>
	</div>
	<!-- Tabla para mostrar los Apartados -->
	<div class="container">
		<div class="table-responsive" id="tt">
			<table class="table table-striped table-hover table-center" id="tablapartados">
				<thead class="tableh">
					<tr>
						<th class="text-center">#</th>
						<th class="text-center">Finalizar</th>
						<th class="text-center">Fecha del apartado</th>
						<th class="text-center">Cliente</th>
						<th class="text-center">Productos</th>
						<th class="text-center">Total</th>

					</tr>
				</thead>
				<tbody id="resultadoconsulta">			  
				</tbody>
			</table>
		</div>
	</div>
</div> 

<!-- Modal para agregar apartados de productos -->
<div class="modal fade" tabindex="-1" role="dialog"  id="modal1">
	<div class=" modal-dialog modal-xl" role="document" id="lm">
		<div class="modal-header" id="hm">
			<h5 class="modal-title"><i class="bi bi-bag-check-fill"></i> Apartado de Productos</h5>
		</div>
		<div class="modal-content">
			<div class="container" id="mtm"> 
				<form method="post" id="f" autocomplete="off" enctype="multipart/form-data">
					<input autocomplete="off" type="text" class="form-control" name="accion" id="accion">
					<div class="container" >	
						<!-- FILA DE INPUT Y BUSCAR CLIENTE -->
						<div class="row">
							<div class="col-12 col-md-6 input-group">
								<input class="form-control" type="text" id="cedulacliente" name="cedulacliente" placeholder="Numero de documento..." />
								<input class="form-control" type="text" id="idcliente" name="idcliente" style="display:none"/>
								<button type="button" class="btn btn-primary" id="listadodeclientes" name="listadodeclientes"  title="Listado de clientes"><i class='bi bi-search'></i> Buscar Cliente</button>
							</div>
							<div class="col-12 col-md-6 input-group">
								<input class="form-control" type="text" id="codigoproducto" name="codigoproducto" placeholder="Codigo de producto..."  />
								<input class="form-control" type="text" id="idproducto" name="idproducto" style="display:none"/> 
								<button type="button" class="btn btn-primary" id="listadodeproductos" name="listadodeproductos"  title="Listado de productos"><i class='bi bi-search'></i> Buscar Producto</button>
							</div>
						</div>
						<!-- FILA DE DATOS DEL CLIENTE -->
						<div class="row">
							<h5><strong>Cliente</strong></h5>
							<div class="col-md-12" id="datosdelcliente">
							</div>
						</div>				
						<!-- FILA DE DETALLES DE LA VENTA -->
						<br>
						<div class="row">
							<h5><strong>Productos seleccionados</strong></h5>
							<div class="table-responsive" id="tt">
								<table class="table table-striped table-hover table-center ">
									<thead class="tableh">
										<tr>
											<th class="text-center"  style="display:none">Id</th>
											<th class="text-center" >Codigo</th>
											<th class="text-center" >Nombre</th>
											<th class="text-center" >Cantidad</th>
											<th class="text-center" >Precio de venta</th>
											<th class="text-center" >Sub Total</th>
											<th class="text-center" >X</th>
										</tr>
									</thead>
									<tbody id="detalledeventa">
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<!-- FIN DE FILA DETALLES DE LA VENTA -->
				</div>
				<!-- Botones del modal -->
				<div class="modal-footer  justify-content-between">
					<button type="button" class="btn" data-dismiss="modal" id="bc"><i class="bi bi-x-square"></i> Cerrar</button>
					<button type="button" class="btn btn-success bi bi-check-square" id="apartar" name="apartar">INCLUIR</button>
				</div>
			</div>	
		</form>
	</div> 
</div>
</div>
</div>
<!-- seccion del modal clientes -->
<div class="modal fade" tabindex="-1" role="dialog"  id="modalclientes">
	<div class=" modal-dialog modal-lg"  role="document" style=" border: 1px solid black;"> 
		<div class="modal-header text-light" style="border: 1px solid black; 	background-color: #14345a; ">
			<h5 class="modal-title"> <i class="zmdi zmdi-accounts"></i> Listado de clientes</h5>
		</div>		
		<div class="modal-content" class="table-responsive" id="tt">

			<div class="modal-body" style="background-color: #14345a; ">
				<div class="row">
					<div class="col-12 col-md-6">
						<input type="text" class="form-control" id="buscarCliente" placeholder="Buscar cliente...">
					</div>
				</div>
			</div>

			<table class="table table-striped table-hover table-center">
				<thead style="background-color: #f1c40f;">
					<tr>
						<th style="display:none">Id</th>
						<th class="text-center" >Documento</th>
						<th class="text-center" >Nombre</th>
						<th class="text-center" >Apellido</th>
						<th class="text-center" >Correo</th>
						<th class="text-center" >Telefono</th>
						<th class="text-center" >Direccion</th>
					</tr>
				</thead>
				<tbody id="listadoclientes">
				</tbody>
			</table>
		</div>
		<div class="modal-footer bg-light">
			<button type="button" class="btn" data-dismiss="modal" id="bc"><i class="bi bi-x-square"></i> Cerrar</button>

		</div>
	</div>
</div>
<!-- seccion del modal productos -->
<div class="modal fade" tabindex="-1" role="dialog"  id="modalproductos">
	<div class="modal-dialog modal-lg" role="document" style="border: 1px solid black;">
		<div class="modal-header text-light" style="border: 1px solid black; 	background-color: #14345a; ">
			<h5 class="modal-title"><i class="bi bi-box-seam-fill"></i> Listado de productos</h5>
		</div>
		<div class="modal-content" class="table-responsive" id="tt">
			<div class="modal-body" style="background-color: #14345a; ">
				<div class="row">
					<div class="col-12 col-md-6">
						<input type="text" class="form-control" id="buscarProducto" placeholder="Buscar producto...">
					</div>
				</div>
			</div>			
			<table class="table table-striped table-hover table-center">
				<thead style="background-color: #f1c40f;">
					<tr>
						<th style="display:none">Id</th>
						<th class="text-center">Código</th>
						<th class="text-center">Nombre</th>
						<th class="text-center">Precio venta</th>
						<th class="text-center">Existencia</th>
						<th class="text-center">Marca</th>
						<th class="text-center">Modelo</th>
						<th class="text-center">Presentación del producto</th>
						<th class="text-center">Imagen</th>
					</tr>
				</thead>
				<tbody id="listadoproductos"> 
				</tbody>
			</table>
		</div>
		<div class="modal-footer bg-light">
			<button type="button" class="btn" data-dismiss="modal" id="bc"><i class="bi bi-x-square"></i> Cerrar</button>
		</div>
	</div>
</div>
</section>
</section>
<script type="text/javascript" src="js/apartados.js"></script>
<div id="loader" class="loader-container" style="display: none;">
	<div class="loader"></div>
	<p>Procesando solicitud...</p>
</div>
</body>
</html>