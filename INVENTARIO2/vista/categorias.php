<?php 
require_once("comunes/encabezado.php");//Incluye el encabezado común
require_once('comunes/menu.php');//Incluye el menú común 
?> 

<div class="container"> 
	<h1> Gestionar Categorías</h1>
	<p style="text-align: justify;">"En la sección de Categorías podrás crear y administrar las diferentes clasificaciones para tus productos. Una estructura bien organizada de categorías permite agrupar los productos de manera lógica, facilitando su búsqueda y gestión. Cada categoría puede tener una descripción detallada que ayude a identificar qué tipos de productos pertenecen a ella."</p> 
	<div class="container">
		<div class="row mt-1 justify-content-center">
			<div class="col-md-2 text-center">
				<button type="button" class="btn-sm btn-success w-75 small-width" id="incluir" title="Registrar Categoría"><i class="bi bi-plus-square"></i></button><!-- Botón para registrar una nueva categoría -->
			</div>				
		</div>
	</div>
	<!-- Tabla para mostrar las categorías -->
	<div class="container">
		<div class="table-responsive" id="tt">
			<table class="table table-striped table-hover table-center" id="tablacategoria">
				<thead class="tableh">
					<tr>
						<th class="text-center">#</th>	
						<th class="text-center">Nombre</th>
						<th class="text-center">Descripción</th>
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
<!-- Modal para agregar/editar categorías -->
<div class="modal fade" tabindex="-1" role="dialog"  id="modal1">
	<div class=" modal-dialog modal-lg" role="document" id="lm">
		<div class="modal-header" id="hm">
			<h5 class="modal-title"><i class="zmdi zmdi-label"></i> Categoría</h5>
		</div>
		<div class="modal-content">
			<div class="container" id="mtm"> 
				<form method="post" id="f" autocomplete="off" enctype="multipart/form-data">
					<input autocomplete="off" type="text" class="form-control" name="accion" id="accion">
					<div class="container" >	
						<!-- Campos del formulario para nombre y descripción -->
						<div class="row mb-3" >
							<div class="col-md-6">
								<label for="nombre">Nombre</label>
								<input class="form-control" type="text" id="nombre" name="nombre" placeholder="Nombre obligatorio"/>
								<span id="snombre"></span>
							</div>
							<div class="col-md-6">
								<label for="descripcion">Descripción</label>
								<input class="form-control" type="text" id="descripcion" name="descripcion" placeholder="Descripción de la categoría" />
								<span id="sdescripcion"></span>
							</div>
						</div>
						<!-- Campo para subir imagen y mostrar imagen actual -->
						<div class="row mb-3">
							<div class="col-md-6">
								<label for="imagen">Imagen</label>
								<input class="col-md-11 inpImg" type="file" id="imagen" name="imagen" accept=".png,.jpg,.jpeg"/>
							</div>
							<div class="col-md-6">
								<span id="simagen"></span>
								<img id="imagen_actual" src="" alt="Imagen de la categoría" class="img"/>
							</div>					
						</div>
						<!-- Botones del modal -->
						<div class="modal-footer  justify-content-between">
							<button type="button" class="btn" data-dismiss="modal" id="bc"><i class="bi bi-x-square"></i> Cerrar</button>
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
<script type="text/javascript" src="js/categorias.js"></script>
<div id="loader" class="loader-container" style="display: none;">
	<div class="loader"></div>
	<p>Procesando solicitud...</p>
</div>
</body>
</html>