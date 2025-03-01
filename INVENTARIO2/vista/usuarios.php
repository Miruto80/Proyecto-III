<?php 
require_once("comunes/encabezado.php"); //Incluye el encabezado común 
require_once('comunes/menu.php'); //Incluye el menú común  
?> 
<div class="container">
	<h1>Gestionar Usuario</h1> <!-- Título de la página -->
	<p  style="text-align:justify;">"Bienvenido al módulo de Gestión de Usuarios. Aquí podrás administrar las cuentas de usuario del sistema, incluyendo la creación de nuevos usuarios, modificación de datos existentes y asignación de roles (administrador o usuario regular). Cada usuario debe tener una cédula única, datos personales básicos y credenciales de acceso seguras. Las imágenes de perfil son opcionales y ayudan a personalizar cada cuenta."</p>	
	<div class="container">
		<div class="row mt-1 justify-content-center">
			<div class="col-md-2 text-center">
				<!-- Botón para registrar un nuevo usuario -->
				<button type="button" class="btn-sm btn-success w-75 small-width" id="incluir" title="Registrar Usuario">
					<i class="bi bi-plus-square"></i>
				</button>			   	
			</div>				
		</div>
	</div>
	<div class="container">
		<div class="table-responsive" id="tt">
			<table class="table table-striped table-hover table-center" id="tablausuario">
				<thead class="tableh">
					<tr>
						<!-- Encabezados de la tabla de usuarios -->
						<th class="text-center">#</th>
						<th class="text-center">Cedula</th>
						<th class="text-center">Nombre</th>
						<th class="text-center">Apellido</th>
						<th class="text-center">Correo</th>
						<th class="text-center">Telefono</th>
						<th class="text-center">Usuario</th>
						<th class="text-center">Tipo de usuario</th>
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
<div class="modal fade" tabindex="-1" role="dialog"  id="modal1">
	<div class="modal-dialog modal-lg" role="document" id="lm">
		<div class="modal-header" id="hm">
			<h5 class="modal-title"><i class="zmdi zmdi-account"></i> Usuario</h5> <!-- Título del modal -->
		</div>
		<div class="modal-content">
			<div class="container" id="mtm">  
				<form method="post" id="f" autocomplete="off" enctype="multipart/form-data">
					<input autocomplete="off" type="text" class="form-control" name="accion" id="accion"> <!-- Campo oculto para la acción -->
					<div class="container">	
						<div class="row mb-3">
							<!-- Campos para ingresar datos del usuario -->
							<div class="col-md-4">
								<label for="cedula">Cedula</label>
								<input class="form-control" type="text" id="cedula" placeholder="Cedula obligatorio" title="No se puede modificar la cedula..."/>
								<span id="scedula"></span>
							</div>
							<div class="col-md-4">
								<label for="nombre">Nombre</label>
								<input class="form-control" type="text" id="nombre"  placeholder="Nombre obligatorio"/>
								<span id="snombre"></span>
							</div>
							<div class="col-md-4">
								<label for="apellido">Apellido</label>
								<input class="form-control" type="text" id="apellido" placeholder="Apellido obligatorio" />
								<span id="sapellido"></span>
							</div>		
						</div>
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
						<div class="row mb-3">
							<div class="col-md-6">
								<label for="nombre_usuario">Nombre de usuario</label>
								<input class="form-control" type="text" id="nombre_usuario" placeholder="Ejemplo: Usuario24" />
								<span id="snombre_usuario"></span>
							</div>
							<div class="col-md-6" >
								<label for="tipo_usuario">Tipo de usuario</label>
								<select class="form-control" id="tipo_usuario" title="No se puede modificar el tipo de usuario...">
									<option value="ninguno" selected>Seleccione una opción </option>
									<option value="usuario">Usuario</option>
									<option value="administrador">Administrador</option>
								</select>
							</div>
						</div>
						<div class="row mb-3">
							<div class="col-md-6">
								<label for="contraseña">Contraseña</label>
								<input class="form-control" type="text" id="contraseña" placeholder="Ejemplo: Usuario24"/>
								<span id="scontraseña"></span>
							</div>
							<div class="col-md-6">
								<label for="repetir_contraseña">Repetir Contraseña</label>
								<input class="form-control" type="password" id="repetir_contraseña"/>
								<span id="srepetir_contraseña"></span>
							</div>
						</div>
						<div class="row mb-3">
							<div class="col-md-6">
								<label for="imagen">Imagen del Usuario</label>
								<input class="col-md-11 inpImg" type="file" id="imagen" name="imagen" accept=".png,.jpg,.jpeg"/>
							</div>
							<div class="col-md-6">
								<span id="simagen"></span>
								<img id="imagen_actual" src="" alt="Imagen del usuario" class="img"/> <!-- Imagen actual del usuario -->
							</div>
						</div>
						<div class="modal-footer bg-light justify-content-between">
							<!-- Botones para cerrar el modal y para procesar la información -->
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
<script type="text/javascript" src="js/usuarios.js"></script> <!-- Script para la funcionalidad de usuarios -->
<div id="loader" class="loader-container" style="display: none;">
	<div class="loader"></div>
	<p>Procesando solicitud...</p>
</div>
</body>
</html>