<?php 
require_once("comunes/encabezado.php");//Incluye el encabezado común de la página 
require_once('comunes/menu.php');//Incluye el menú común de la página 
?> 

<div class="container"> 
	<h1>Gestionar Clientes</h1> <!-- Título principal de la página -->
	<!-- Contenedor para el botón de agregar nuevo cliente -->
	<p style="text-align:justify;">"En el módulo de Gestión de Clientes podrás mantener organizada tu base de datos de clientes. Registra información esencial como documento de identidad (CI o RIF), datos de contacto y dirección. Un registro actualizado de clientes te permitirá dar seguimiento a las operaciones comerciales de manera eficiente."</p>
	<div class="container">
		<div class="row mt-1 justify-content-center">
			<div class="col-md-2 text-center">
				<!-- Botón para registrar un nuevo cliente -->
				<button type="button" class="btn-sm btn-success w-75 small-width" id="incluir" title="Registrar Cliente"><i class="bi bi-plus-square"></i></button>		    	
			</div>				
		</div>
	</div>
	<!-- Contenedor para la tabla de clientes -->
	<div class="container">
		<div class="table-responsive" id="tt">
			<!-- Tabla para mostrar la lista de clientes -->
			<table class="table table-striped table-hover table-center" id="tablacliente">
				<thead class="tableh">
					<tr>
						<!-- Encabezados de la tabla -->
						<th class="text-center">#</th>
						<th class="text-center">Documento</th>
						<th class="text-center">Nombre</th>
						<th class="text-center">Apellido</th>
						<th class="text-center">Correo</th>
						<th class="text-center">Telefono</th>
						<th class="text-center">Dirección</th>
						<th class="text-center">Acciones</th>
					</tr>
				</thead>
				<tbody id="resultadoconsulta">
					<!-- Aquí se cargarán dinámicamente los datos de los clientes -->
				</tbody>
			</table>
		</div>
	</div>
</div> 
<!-- Modal para agregar o editar clientes -->
<div class="modal fade" tabindex="-1" role="dialog"  id="modal1">
	<div class="modal-dialog modal-lg" role="document" id="lm">
		<div class="modal-header" id="hm">
			<h5 class="modal-title"><i class="zmdi zmdi-accounts"></i> Cliente</h5>
		</div>
		<div class="modal-content">
			<div class="container" id="mtm"> 
				<!-- Formulario para los datos del cliente -->
				<form method="post" id="f" autocomplete="off"  enctype="multipart/form-data">
					<input autocomplete="off" type="text" class="form-control" name="accion" id="accion">
					<div class="container">	
						<!-- Campos para tipo y número de documento -->
						<div class="row mb-3">
							<div class="col-md-6" title="El tipo de documento no puede ser modificado...">
								<label for="tipo_documento">Tipo de documento</label>
								<select class="form-control" id="tipo_documento">
									<option value="seleccionar" selected disabled>Seleccione una opción </option>
									<option value="Cédula"title="Cédula de Identidad">CI</option>
									<option value="RIF"title="Registro Único de Información Fiscal">RIF</option>
								</select>
							</div>
							<div class="col-md-6">
								<label for="numero_documento">Numero de documento</label>
								<input class="form-control" type="text" id="numero_documento" title="El numero de documento no puede ser modificado..."/>
								<span id="snumero_documento"></span>
							</div>
						</div>
						<!-- Campos para nombre y apellido -->
						<div class="row mb-3">
							<div class="col-md-6">
								<label for="nombre">Nombre</label>
								<input class="form-control" type="text" id="nombre"  placeholder="Nombre obligatorio"/>
								<span id="snombre"></span>
							</div>
							<div class="col-md-6">
								<label for="apellido">Apellido</label>
								<input class="form-control" type="text" id="apellido" placeholder="Apellido obligatorio" />
								<span id="sapellido"></span>
							</div>
						</div>
						<!-- Campos para correo y teléfono -->
						<div class="row mb-3">
							<div class="col-md-6">
								<label for="correo">Correo</label>
								<input class="form-control" type="text" id="correo" placeholder="Ejemplo: Correo@email.com" />
								<span id="scorreo"></span>
							</div>
							<div class="col-md-6">
								<label for="telefono">Teléfono</label>
								<input class="form-control" type="text" id="telefono" placeholder="Ejemplo: 04123456789"/>
								<span id="stelefono"></span>
							</div>
						</div>
						<!-- Campo para dirección -->
						<div class="row mb-3">
							<div class="col-md-12">
								<label for="direccion">Dirección</label>
								<input class="form-control" type="text" id="direccion" placeholder="Dirección de su domicilio" />
								<span id="sdireccion"></span>
							</div>
						</div>	
						<div class="row">
							<div class="col-md-12">
							</div>
						</div>
						<!-- Botones del modal -->
						<div class="modal-footer justify-content-between">
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
<script type="text/javascript" src="js/clientes.js"></script><!-- Inclusión del archivo JavaScript de clientes -->
<div id="loader" class="loader-container" style="display: none;">
	<div class="loader"></div>
	<p>Procesando solicitud...</p>
</div>
</body>
</html>