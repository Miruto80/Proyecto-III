<?php 
require_once("comunes/encabezado.php");//Incluye el encabezado común
require_once('comunes/menu.php');//Incluye el menú común 
?>
<div class="container"> 
	<h1> Gestionar Entradas de productos</h1> 
	<p style="text-align: justify;">"El módulo de Entradas permite registrar todos los productos que ingresan al inventario. Selecciona el proveedor, ingresa los productos con sus cantidades y precios de compra. Puedes adjuntar la nota de entrega para mantener un respaldo de la operación. Esta información es vital para el control de inventario y costos."</p>
	<div class="container">
		<div class="row mt-1 justify-content-center">
			<div class="col-md-2 text-center">
				<button type="button" class="btn-sm btn-success w-75 small-width" id="incluir" title="Registrar Entrada"><i class="bi bi-plus-square"></i></button><!-- Botón para registrar una nueva entrada -->
			</div>				
		</div>
	</div>
	<!-- Tabla para mostrar las entradas -->
	<div class="container">
		<div class="table-responsive" id="tt">
			<table class="table table-striped table-hover table-center" id="tablaentradas">
				<thead class="tableh">
					<tr>
						<th class="text-center">#</th>
						<th class="text-center">Fecha</th>
						<th class="text-center">Proveedor</th>
						<th class="text-center">Nota de entrega</th>
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
<!-- Modal para agregar entradass de productos -->
<div class="modal fade" tabindex="-1" role="dialog"  id="modal1">
	<div class=" modal-dialog modal-xl" role="document" id="lm">
		<div class="modal-header" id="hm">
			<h5 class="modal-title"><i class="bi bi-cart-plus-fill"></i> Entrada de Productos</h5>
		</div>
		<div class="modal-content">
			<div class="container" id="mtm"> 
				<form method="post" id="f" autocomplete="off" enctype="multipart/form-data">
					<input autocomplete="off" type="text" class="form-control" name="accion" id="accion">
					<div class="container" >	
						<!-- FILA DE INPUT Y BUSCAR CLIENTE -->
						<div class="row">
							<div class="col-md-6 input-group">
								<input class="form-control" type="text" id="cedulacliente" name="cedulacliente" placeholder="Numero de documento..." />
								<input class="form-control" type="text" id="idcliente" name="idcliente" style="display:none"/>
								<button type="button" class="btn btn-primary" id="listadodeclientes" name="listadodeclientes"  title="Listado de proveedores"><i class='bi bi-search'></i> Buscar Proveedor</button>
							</div>
							<div class="col-md-6 input-group">
								<input class="form-control" type="text" id="codigoproducto" name="codigoproducto" placeholder="Codigo de producto..."  />
								<input class="form-control" type="text" id="idproducto" name="idproducto" style="display:none"/> 
								<button type="button" class="btn btn-primary" id="listadodeproductos" name="listadodeproductos"  title="Listado de productos"><i class='bi bi-search'></i> Buscar Producto</button>
							</div>
						</div>
						<!-- FILA DE DATOS DEL CLIENTE -->
						<div class="row">
							<h5><strong>Proveedor</strong></h5>
							<div class="col-md-12" id="datosdelcliente">
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-md-5">
								<label for="nota_entrega"><strong>Nota de entrega</strong></label>
								<div class="d-flex align-items-center">
									<input class="col-md-11 inpImg" type="file"  id="nota_entrega" name="nota_entrega"/>
									<i class="bi bi-check-circle-fill text-success ms-2" id="check_nota_entrega" style="display: none;"></i>
								</div>
							</div>					
						</div>
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
											<th class="text-center" >Precio de compra</th>
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
					<button type="button" class="btn btn-success bi bi-check-square" id="entrada" name="entrada">INCLUIR</button>
				</div>
			</div>	
		</form>
	</div> 
</div>
</div>
</div>
<!-- seccion del modal clientes -->
<div class="modal fade" tabindex="-1" role="dialog"  id="modalclientes">
	<div class="modal-dialog modal-xl"  role="document" style=" border: 1px solid black;"> 
		<div class="modal-header text-light" style="border: 1px solid black; 	background-color: #14345a; ">
			<h5 class="modal-title"><i class="zmdi zmdi-truck"></i> Listado de proveedores</h5>
		</div>
		<div class="modal-content" class="table-responsive" id="tt">
			<div class="modal-body" style="background-color: #14345a; ">
				<div class="row">
					<div class="col-12 col-md-6">
						<input type="text" class="form-control" id="buscarProveedor" placeholder="Buscar proveedor...">
					</div>
				</div>
			</div>				
			<table class="table table-striped table-hover table-center">
				<thead style="background-color: #f1c40f;">
					<tr>
						<th style="display:none">Id</th>
						<th class="text-center">Documento</th>
						<th class="text-center">Nombre</th>
						<th class="text-center">Direccion</th>
						<th class="text-center">Correo</th>
						<th class="text-center">Telefono</th>
						<th class="text-center">Catalogo</th>
						<th class="text-center">Archivo</th>				
						<th class="text-center">Imagen</th>
					</tr>
				</thead>
				<tbody id="listadoproveedores">
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
						<th class="text-center">Precio compra</th>
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
<!--fin de seccion modal-->
</section>
</section>
<script type="text/javascript" src="js/entradas.js"></script>
<div id="loader" class="loader-container" style="display: none;">
	<div class="loader"></div>
	<p>Procesando solicitud...</p>
</div>
</body>
</html>