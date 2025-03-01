<?php 
require_once("comunes/encabezado.php"); //Incluye el encabezado común 
require_once('comunes/menu.php'); //Incluye el menú común 
?>

<div class="container"> 
	<h1> Gestionar Proveedores</h1> <!-- Título de la sección -->
	<p style="text-align: justify;">"En el módulo de Gestión de Proveedores podrás administrar la información de tus distribuidores y fabricantes. Registra datos importantes como documento de identificación (CI o RIF), información de contacto, dirección y catálogo de productos. Mantener actualizada la información de proveedores es esencial para gestionar eficientemente las entradas de mercancía y mantener un suministro constante de productos."</p>
	<div class="container">
		<div class="row mt-1 justify-content-center">
			<div class="col-md-2 text-center">
				<!-- Botón para incluir un nuevo proveedor -->
				<button type="button" class="btn-sm btn-success w-75 small-width" id="incluir" title="Registrar Proveedor">
					<i class="bi bi-plus-square"></i>
				</button>					   
			</div>				
		</div>
	</div>
	<div class="container">
		<div class="table-responsive" id="tt">
			<table class="table table-striped table-hover" id="tablaproveedor">
				<thead class="tableh">
					<tr>
						<!-- Encabezados de la tabla de proveedores -->
						<th class="text-center">#</th>
						<th class="text-center">Documento</th>
						<th class="text-center">Nombre</th>
						<th class="text-center">Dirección</th>
						<th class="text-center">Correo</th>
						<th class="text-center">teléfono</th>
						<th class="text-center">Catálogo</th>
						<th class="text-center">Archivo</th>		
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
<div class="modal fade" tabindex="-1" role="dialog"  id="modal1"> <!-- Modal para agregar/editar proveedor -->
	<div class="modal-dialog modal-lg" role="document" id="lm">
		<div class="modal-header" id="hm">
			<h5 class="modal-title"><i class="zmdi zmdi-truck"></i> Proveedor</h5> <!-- Título del modal -->
		</div>
		<div class="modal-content">
			<div class="container" id="mtm"> 
				<form method="post" id="f" autocomplete="off" enctype="multipart/form-data"> <!-- Formulario para proveedor -->
					<input autocomplete="off" type="text" class="form-control" name="accion" id="accion"> <!-- Campo oculto para la acción -->
					<div class="container">	
						<div class="row mb-3">
							<div class="col-md-6" title="El tipo de documento no puede ser modificado...">
								<label for="tipo_documento">Tipo de documento</label>
								<select class="form-control" id="tipo_documento"> <!-- Selector de tipo de documento -->
									<option value="seleccionar" selected disabled>Seleccione una opción </option>
									<option value="Cédula" title="Cédula de Identidad">CI</option>
									<option value="RIF" title="Registro Único de Información Fiscal">RIF</option>
								</select>
							</div>
							<div class="col-md-6">
								<label for="numero_documento">Numero de documento</label>
								<input class="form-control" type="text" id="numero_documento" title="El numero de documento no puede ser modificado..."/> <!-- Campo para número de documento -->
								<span id="snumero_documento"></span> <!-- Mensaje de error -->
							</div>
						</div>
						<div class="row mb-3">
							<div class="col-md-6">
								<label for="nombre">Nombre</label>
								<input class="form-control" type="text" id="nombre"  placeholder="Nombre obligatorio"/> <!-- Campo para nombre -->
								<span id="snombre"></span> <!-- Mensaje de error -->
							</div>
							<div class="col-md-6">
								<label for="direccion">Dirección</label>
								<input class="form-control" type="text" id="direccion" placeholder="Dirección de sucursal" /> <!-- Campo para dirección -->
								<span id="sdireccion"></span> <!-- Mensaje de error -->
							</div>
						</div>
						<div class="row mb-3">
							<div class="col-md-6">
								<label for="correo">Correo</label>
								<input class="form-control" type="text" id="correo" placeholder="Ejemplo: Correo@email.com" /> <!-- Campo para correo -->
								<span id="scorreo"></span> <!-- Mensaje de error -->
							</div>
							<div class="col-md-6">
								<label for="telefono">Teléfono</label>
								<input class="form-control" type="text" id="telefono" placeholder="Ejemplo: 04123456789"/> <!-- Campo para teléfono -->
								<span id="stelefono"></span> <!-- Mensaje de error -->
							</div>
						</div>
						<div class="row mb-3">
							<div class="col-md-6">
								<label for="catalogo">Catálogo</label>
								<input class="form-control" type="text" id="catalogo" placeholder="nombre de los productos" /> <!-- Campo para catálogo -->
								<span id="scatalogo"></span> <!-- Mensaje de error -->
							</div>

							<div class="col-md-6">
								<label for="catalogo_archivo">Archivo de Catálogo</label>
								<div class="d-flex align-items-center">
									<input class="col-md-11 inpImg" type="file" id="catalogo_archivo" name="catalogo_archivo" accept=".pdf,.doc,.docx"/>
									<i class="bi bi-check-circle-fill text-success ms-2" id="check_catalogo" style="display: none;"></i>
								</div>
								<br>
								<input type="hidden" id="catalogo_archivo_actual" name="catalogo_archivo_actual">
								<span id="scatalogo_archivo"></span>
								<a id="ver_catalogo" href="#" target="_blank" style="display: none;">Ver catálogo</a>
							</div>

						</div>
						<div class="row mb-3">
							<div class="col-md-6">
								<label for="imagen">Imagen del Producto</label>
								<input class="col-md-11 inpImg" type="file" id="imagen" name="imagen" accept=".png,.jpg,.jpeg"/> <!-- Campo para imagen del producto -->
							</div>
							<div class="col-md-6">
								<span id="simagen"></span> <!-- Mensaje de error -->
								<img id="imagen_actual" src="" alt="Imagen del producto" class="img"/> <!-- Imagen actual del producto -->
							</div>						
						</div>
						<div class="modal-footer justify-content-between">
							<button type="button" class="btn btn-secondary" data-dismiss="modal" id="bc"><i class="bi bi-x-square"></i> Cerrar</button> <!-- Botón para cerrar el modal -->
							<button type="button" class="btn btn-success bi bi-check-square" id="proceso"></button> <!-- Botón para procesar la acción -->
						</div>
					</div>	
				</form>
			</div> 
		</div>
	</div>
</div>
</section>
</section>
<script type="text/javascript" src="js/proveedores.js"></script> <!-- Script para la funcionalidad de proveedores -->
<div id="loader" class="loader-container" style="display: none;">
	<div class="loader"></div>
	<p>Procesando solicitud...</p>
</div>
</body>
</html>