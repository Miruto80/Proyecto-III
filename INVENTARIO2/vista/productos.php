<?php 
require_once("comunes/encabezado.php"); //Incluye el encabezado común 
require_once('comunes/menu.php'); //Incluye el menú común 
?> 

<div class="container"> 
	<h1> Gestionar Productos</h1> <!-- Título de la sección -->
	<p style="text-align: justify;">"El módulo de Gestión de Productos te permite administrar tu inventario. Cada producto debe tener un código único, información detallada (nombre, marca, modelo) y control de existencias (stock actual y mínimo). Los precios de compra y venta son fundamentales para el control financiero. Mantén actualizada esta información para evitar faltantes y optimizar tus ventas."</p>
	<div class="container">
		<div class="row mt-1 justify-content-center">
			<div class="col-md-2 text-center">
				<!-- Botón para incluir un nuevo producto -->
				<button type="button" class="btn-sm btn-success w-75 small-width" id="incluir" title="Registrar Producto">
					<i class="bi bi-plus-square"></i>
				</button>			   
			</div>				
		</div>
	</div>
	<div class="container">
		<div class="table-responsive" id="tt">
			<table class="table table-striped table-hover table-center" id="tablaproducto">
				<thead class="tableh">
					<tr>
						<!-- Encabezados de la tabla de productos -->
						<th class="text-center">#</th>
						<th class="text-center">Código</th>
						<th class="text-center">Nombre</th>
						<?php if ($_SESSION['tipo_usuario'] == 'administrador'): ?>						
							<th class="text-center">Precio compra</th>
						<?php endif; ?>
						<th class="text-center">Precio venta</th>
						<th class="text-center">Stock o existencia</th>
						<th class="text-center">Stock minimo</th>
						<th class="text-center">Marca</th>
						<th class="text-center">Modelo</th>
						<th class="text-center">Presentación del producto</th>
						<th class="text-center">Categoría</th>
						<th class="text-center">Ubicación</th>
						<th class="text-center">Imagen</th>
						<th class="text-center">Acciones</th>
					</tr>
				</thead>
				<tbody id="resultadoconsulta">			  
				</tbody>
			</table>
		</div>
	</div>
</div> 
<div class="modal fade" tabindex="-1" role="dialog"  id="modal1"> <!-- Modal para agregar/editar productos -->
	<div class="modal-dialog modal-lg" role="document"id="lm">
		<div class="modal-header" id="hm">
			<h5 class="modal-title"><i class="bi bi-box-seam-fill"></i> Producto</h5> <!-- Título del modal -->
		</div>
		<div class="modal-content">
			<div class="container" id="mtm"> 
				<form method="post" id="f" autocomplete="off" enctype="multipart/form-data"> <!-- Formulario para el producto -->
					<input autocomplete="off" type="text" class="form-control" name="accion" id="accion"> <!-- Campo oculto para la acción -->
					<div class="container">	
						<div class="row mb-3">
							<!-- Campos para ingresar información del producto -->
							<div class="col-md-3">
								<label for="codigo">Código</label>
								<input class="form-control" type="text" id="codigo" placeholder="Código obligatorio" title="El codigo del producto no puede ser modificado..."/>
								<span id="scodigo"></span>
							</div>
							<div class="col-md-3">
								<label for="nombre">Nombre</label>
								<input class="form-control" type="text" id="nombre" placeholder="Nombre obligatorio" />
								<span id="snombre"></span>
							</div>
							<div class="col-md-3">
								<label for="marca">Marca</label>
								<input class="form-control" type="text" id="marca"/>
								<span id="smarca"></span>
							</div>
							<div class="col-md-3">
								<label for="modelo">Modelo</label>
								<input class="form-control" type="text" id="modelo" name="modelo"/>
								<span id="smodelo"></span>
							</div>							
						</div>
						<div class="row mb-3">

							<div class="col-md-3">
								<label for="precio_compra">Precio compra</label>
								<input class="form-control" type="text" id="precio_compra" placeholder="Obligatorio..."/>
								<span id="sprecio_compra"></span>
							</div>	

							<div class="col-md-3">
								<label for="precio_venta">Precio venta</label>
								<input class="form-control" type="text" id="precio_venta" placeholder="Obligatorio..."/>
								<span id="sprecio_venta"></span>
							</div>	

							<div class="col-md-3">
								<label for="stock_total">Stock o existencia</label>
								<input class="form-control" type="text" id="stock_total" name="stock_total" placeholder="Producto existente obligatorio"/>
								<span id="sstock_total"></span>
							</div>
							<div class="col-md-3">
								<label for="stock_minimo">Stock mínimo</label>
								<input class="form-control" type="text" id="stock_minimo" name="stock_minimo" placeholder="Stock mínimo obligatorio"/>
								<span id="sstock_minimo"></span>
							</div>
						</div>
						<div class="row mb-3">
							<div class="col-md-4">
								<label for="tipo_unidad">Presentación del producto</label>
								<select class="form-control select2" id="tipo_unidad">
									<option value="" selected disabled> Seleccione una opción </option>
									<!-- Opciones para la presentación del producto -->
									<option value="Unidad">Unidad</option>
									<option value="Libra">Libra</option>
									<option value="Kilogramo">Kilogramo</option>
									<option value="Caja">Caja</option>
									<option value="Paquete">Paquete</option>
									<option value="Lata">Lata</option>
									<option value="Galon">Galon</option>
									<option value="Botella">Botella</option>
									<option value="Tira">Tira</option>
									<option value="Sobre">Sobre</option>
									<option value="Bolsa">Bolsa</option>
									<option value="Saco">Saco</option>
									<option value="Tarjeta">Tarjeta</option>
									<option value="Otro">Otro</option>												
								</select>
							</div>
							<div class="col-md-4">
								<label for="categoria">Categoría</label>
								<select class="form-control select2" id="categoria" name="categoria">
									<option value="" selected disabled >Seleccione una categoría</option>
								</select>
							</div>
							<div class="col-md-4">
								<label for="ubicacion">Ubicación</label>
								<select class="form-control select2" id="ubicacion" name="ubicacion">
									<option value="" selected disabled >Seleccione una ubicación</option>
								</select>
							</div>														
						</div>
						<div class="row mb-3">

							<div class="col-md-5">
								<label for="imagen">Imagen del Producto</label>
								<input class="col-md-11 inpImg" type="file" id="imagen" name="imagen"accept=".png,.jpg,.jpeg" />
							</div>
							<div class="col-md-3">
								<span id="simagen"></span>
								<img id="imagen_actual" src="" alt="Imagen del producto" class="img"/> <!-- Imagen actual del producto -->
							</div>						
						</div>
					</div>
					<div class="modal-footer justify-content-between">
						<!-- Botones para cerrar el modal o confirmar la acción -->
						<button type="button" class="btn btn-secondary" data-dismiss="modal" id="bc"><i class="bi bi-x-square"></i> Cerrar</button>
						<button type="button" class="btn btn-success bi bi-check-square" id="proceso"></button>
					</div>
				</div>	
			</form>
		</div> 
	</div>
</div>
</div>
</section>
</section>
<script type="text/javascript" src="js/productos.js"></script> <!-- Script para manejar la lógica de productos -->
<div id="loader" class="loader-container" style="display: none;">
	<div class="loader"></div>
	<p>Procesando solicitud...</p>
</div>
<link href="css/select2.min.css" rel="stylesheet" />
<script src="js/select2.min.js"></script>
</body>
</html>