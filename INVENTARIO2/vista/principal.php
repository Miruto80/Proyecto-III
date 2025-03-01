<?php 
require_once("comunes/encabezado.php"); // Incluye el encabezado de la página
require_once('comunes/menu.php'); // Incluye el menú de navegación
require_once('modelo/principal.php'); // Incluye el modelo principal para obtener datos
$conteos = new principal(); // Crea una instancia de la clase principal
$datos = $conteos->obtenerConteos(); // Obtiene los conteos de diferentes categorías
?>

<div class="container"> <!-- Contenedor principal -->
	<div class="container"> <!-- Contenedor secundario -->
		<h1 class="text-center full-width">Principal</h1> <!-- Título de la página -->
		<figure class="full-width navLateral-body-tittle-menu"> <!-- Figura para el avatar y saludo -->
			<div>
				<img src="<?php echo isset($_SESSION['imagen_usuario']) ? $_SESSION['imagen_usuario'] : 'otros/img/menu/avatar.jpg'; ?>" alt="Avatar" class="img-responsive" > <!-- Imagen del usuario -->
				<div><span class>¡Bienvenido <?php echo ucfirst($_SESSION['nombre_usuario']); ?>!</span></div> <!-- Saludo al usuario -->
			</figure>		
		</div>		
	</figure>
	<section class="full-width text-center"> <!-- Sección para los tiles de conteo -->
		<article class="full-width tile" > <!-- Tile para categorías -->
			<a href="?pagina=categorias" class="full-width">				
				<div class="tile-text">
					<span class="text-condensedLight">
						<?php echo $datos['categorias']; ?><br> <!-- Muestra el conteo de categorías -->
						<small>Categorías</small> <!-- Etiqueta de categorías -->
					</span>
				</div>
				<i class="bi bi-tags-fill tile-icon"></i> <!-- Icono de categorías -->
			</a>	
		</article>
		<!-- Repite el mismo patrón para ubicaciones, proveedores, productos, clientes -->
		<article class="full-width tile">
			<a href="?pagina=ubicaciones" class="full-width">
				<div class="tile-text">
					<span class="text-condensedLight">
						<?php echo $datos['ubicaciones']; ?><br> <!-- Muestra el conteo de ubicaciones -->
						<small>Ubicaciones</small> <!-- Etiqueta de ubicaciones -->
					</span>
				</div>
				<i class="bi bi-geo-alt-fill tile-icon"></i> <!-- Icono de ubicaciones -->
			</a>
		</article>
		<article class="full-width tile">
			<a href="?pagina=proveedores" class="full-width">
				<div class="tile-text">
					<span class="text-condensedLight">
						<?php echo $datos['proveedores']; ?><br>
						<small>Proveedores</small>
					</span>
				</div>
				<i class="zmdi zmdi-truck tile-icon"></i>
			</a>
		</article>
		<article class="full-width tile">
			<a href="?pagina=productos" class="full-width">
				<div class="tile-text">
					<span class="text-condensedLight">
						<?php echo $datos['productos']; ?><br>
						<small>Productos</small>
					</span>
				</div>
				<i class="bi bi-box-seam-fill tile-icon"></i>
			</a>
		</article>
		<article class="full-width tile">
			<a href="?pagina=clientes" class="full-width">
				<div class="tile-text">
					<span class="text-condensedLight">
						<?php echo $datos['clientes']; ?><br>
						<small>Clientes</small>
					</span>
				</div>
				<i class="zmdi zmdi-accounts tile-icon"></i>
			</a>
		</article>
		<?php if ($_SESSION['tipo_usuario'] == 'administrador'): ?>
			<article class="full-width tile">
				<a href="?pagina=usuarios" class="full-width">
					<div class="tile-text">
						<span class="text-condensedLight">
							<?php echo $datos['usuarios']; ?><br> <!-- Muestra el conteo de usuarios -->
							<small>Usuarios</small> <!-- Etiqueta de usuarios -->
						</span>
					</div>
					<i class="zmdi zmdi-account tile-icon"></i> <!-- Icono de usuarios -->
				</a>
			</article>				
		<?php endif; ?>
		<article class="full-width tile"> <!-- Tile para apartados -->
			<a href="?pagina=apartados" class="full-width">
				<div class="tile-text">
					<span class="text-condensedLight">
						<?php echo $datos['apartados']; ?><br> <!-- Muestra el conteo de apartados -->
						<small>Apartados</small> <!-- Etiqueta de apartados -->
					</span>
				</div>
				<i class="bi bi-bag-check-fill tile-icon"></i> <!-- Icono de apartados -->
			</a>
		</article>
	</section>
</div>
</div>
</section>
</section>
</body>
</html>