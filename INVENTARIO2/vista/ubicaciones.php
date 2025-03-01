<?php 
require_once("comunes/encabezado.php");//Incluye el encabezado común
require_once('comunes/menu.php');//Incluye el menú común 
?>

<div class="container">
	<h1> Gestionar Ubicaciones</h1>
	<p style="text-align: justify;">"El módulo de Ubicaciones te permite organizar y controlar los diferentes espacios de almacenamiento en tu negocio. Aquí podrás crear y gestionar las distintas áreas donde se almacenan los productos, como almacenes, estantes, o depósitos. Una buena organización de las ubicaciones facilita la localización rápida de productos y optimiza el proceso de inventario."</p>
	<div class="container"> 
		<div class="row mt-1 justify-content-center">
			<div class="col-md-2 text-center">
				<button type="button" class="btn-sm btn-success w-75 small-width" id="incluir" title="Registrar Ubicación"><i class="bi bi-plus-square"></i></button><!-- Botón para registrar una nueva Ubicación -->	    
			</div>				
		</div>
	</div>
	<div class="container">
		<div class="table-responsive" id="tt"><!-- Tabla para mostrar las Ubicaciones --> 
			<table class="table table-striped table-hover table-center" id="tablaubicacion">
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
<div class="modal fade" tabindex="-1" role="dialog" id="modal1">
	<div class="modal-dialog modal-lg" role="document" id="lm">
		<div class="modal-header" id="hm">
			<h5 class="modal-title"><i class="bi bi-geo-alt-fill"></i>Ubicación</h5>
		</div>
		<div class="modal-content">
			<div class="container" id="mtm"> 
				<form method="post" id="f" autocomplete="off" enctype="multipart/form-data">
					<input autocomplete="off" type="text" class="form-control" name="accion" id="accion">
					<div class="container">	
						<!-- Campos del formulario para nombre y descripción -->
						<div class="row mb-3">
							<div class="col-md-6">
								<label for="nombre">Nombre</label>
								<input class="form-control" type="text" id="nombre"  name="nombre" placeholder="Nombre obligatorio" title="El nombre no puede ser modificado..."/>
								<span id="snombre"></span>
							</div>
							<div class="col-md-6">
								<label for="descripcion">Descripción</label>
								<input class="form-control" type="text" id="descripcion" name="descripcion" placeholder="Descripción de la ubicacion" />
								<span id="sdescripcion"></span>
							</div>
						</div>
						<div class="row mb-3">
							<!-- Campo para subir imagen y mostrar imagen actual -->
							<div class="col-md-6">
								<label for="imagen">Imagen</label>
								<input class="col-md-11 inpImg" type="file" id="imagen" name="imagen" accept=".png,.jpg,.jpeg"/>
							</div>
							<div class="col-md-6">
								<span id="simagen"></span>
								<img id="imagen_actual" src="" alt="Imagen de la ubicación" class="img"/>
							</div>						
						</div>
						<div class="modal-footer justify-content-between">
							<!-- Botones del modal -->
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
<script type="text/javascript" src="js/ubicaciones.js"></script>
<div id="loader" class="loader-container" style="display: none;">
	<div class="loader"></div>
	<p>Procesando solicitud...</p>
</div>
</body>
</html>