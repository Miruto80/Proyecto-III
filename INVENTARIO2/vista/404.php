<?php 
require_once("comunes/encabezado.php");
?>

<div style="">
	<i class="bi bi-ban"></i>	
	<h2>Error 404</h2>
	<h3>Pagina no encontrada</h3>
	<i class="bi bi-emoji-frown"></i>
</div>	
<div class="container">
	<div class="row mt-1 justify-content-center">
		<div class="col-md-2 text-center">
			<button type="button" class="btn-sm btn-danger  small-width" ><a href="?pagina=login"  style="color: white;">Volver a login</button>
			</div>				
		</div>
	</div>
</body>
<style> 
	body{
		text-align: center;
		background-color: #f1c40f;
	}
	.bi-ban,.bi-emoji-frown {
		font-size: 100px;
	}
</style>