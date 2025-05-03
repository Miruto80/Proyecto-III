<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
	<meta mane="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-compatible" content="ie=edge">
	<script src="https://kit.fontawesome.com/3ed72884f3.js" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="assets/css/catalogo.css">
	<link rel="shortcut icon" type="img/x-icon" href="assets/img/icono.png"/>
	<?php include 'complementos/head.php' ?> 

	<title>	LoveMakeup - Web </title>
</head>

<body>

<header>
		<nav class="text-b">
			<a href="?pagina=login">Acceso Interno</a>
			
		</nav>
		<section class="textos-header">
			<h1>LOVEMAKEUP</h1>
			<h2>Venta al mayor y detal de productos de maquillaje</h2>
		</section>
		<div class="ola" style="height: 150px; overflow: hidden;" ><svg viewBox="0 0 500 150" preserveAspectRatio="none" style="height: 100%; width: 100%;"><path d="M0.00,49.98 C149.99,150.00 349.20,-49.98 500.00,49.98 L500.00,150.00 L0.00,150.00 Z" style="stroke: none; fill: #fff;"></path></svg></div>
	</header>
	

<h1 class="text-center">TIENDA VIRTUAL</h1>

    <br><br>

	<div class="contenedor">
    <aside>
        <h3>Categorías</h3>
            <?php foreach ($categorias as $cat): ?>
                <div>
                    <input type="checkbox" id="cat-<?php echo $cat['id_categoria']; ?>" value="<?php echo $cat['id_categoria']; ?>" class="filtro-checkbox">
                    <label for="cat-<?php echo $cat['id_categoria']; ?>"><?php echo htmlspecialchars($cat['nombre']); ?></label>
                </div>
            <?php endforeach; ?>
    </aside>

    <div class="contenedor-productos" id="productos">
        <?php foreach ($registro as $producto): ?>
            <div class="producto"
			data-bs-toggle="modal"
     data-bs-target="#productModal"
	 data-id="<?php echo $producto['id_producto']; ?>"
     data-nombre="<?php echo htmlspecialchars($producto['nombre']); ?>"
     data-precio="<?php echo $producto['precio_detal']; ?>"
     data-marca="<?php echo htmlspecialchars($producto['marca']); ?>"
     data-descripcion="<?php echo htmlspecialchars($producto['descripcion']); ?>"
     data-cantidad-mayor="<?php echo $producto['cantidad_mayor']; ?>"
     data-precio-mayor="<?php echo $producto['precio_mayor']; ?>"
     data-stock-disponible="<?php echo $producto['stock_disponible']; ?>"
     data-imagen="<?php echo $producto['imagen']; ?>"
	 data-categoria="<?php echo $producto['id_categoria']; ?>"
     onclick="openModal(this)">
                
                <img src="<?php echo $producto['imagen']; ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                <h3><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                <p><strong>Precio:</strong> $<?php echo $producto['precio_detal']; ?></p>
				<button class="btn btn-primary btn-cart w-100 mt-2">
                <i class="fa fa-cart-plus me-2"></i> Añadir al carrito
                </button>
            </div>
        <?php endforeach; ?>
    </div>

</div>





		<footer class="pie-pagina"> 
		<div class="grupo-1">
				<div class="box">
					<figure>
						<a href="#">
						<img src="assets/img/logo1.png" alt="logo" width="200" height="200">
						</a>
					</figure>
				</div>
				<div class="box">
                <center><h2> LOVEMAKEUP</h2> </center>
					<center> <p> RIF: J-</p> </center>
					
				</div>
					<div class="box">
						<h2> SIGUENOS </h2>
				
						<div class="red-social">
						<a href="https://www.instagram.com/lovemakeupyk/" class="fa fa-instagram" > </a>
					</div>
				</div>
		</div>
		<div class="grupo-2"> <!--Copi-->
			<small>&copy; 2025 ESTUDIANTE DEL UPTAEB T3 - LOVEMAKEUP | Todos los derechos Revervados.</small>  
		</div> 
	</footer>

	<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="modal-title" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-title"></h5>
        <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <!-- Imagen a la izquierda -->
          <div class="col-md-6 text-center">
            <img id="modal-imagen" src="" alt="Producto" class="img-fluid mb-3" style="max-height: 400px; object-fit: contain;">
          </div>
          <!-- Datos a la derecha -->
          <div class="col-md-6">
            <p><strong>Precio Detal:</strong> <span id="modal-precio"></span></p>
            <p><strong>Marca:</strong> <span id="modal-marca"></span></p>
            <p><strong>Descripción:</strong> <span id="modal-descripcion"></span></p>
            <p><strong>Cantidad al mayor:</strong> <span id="modal-cantidad-mayor"></span></p>
            <p><strong>Precio al mayor:</strong> <span id="modal-precio-mayor"></span></p>
            <p><strong>Stock disponible:</strong> <span id="modal-stock-disponible"></span></p>
			<button class="btn btn-primary btn-cart w-100 mt-2">
  <i class="fa fa-cart-plus me-2"></i> Añadir al carrito
</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


    <script src="assets/js/catalogo.js"></script>
	<script src="assets/js/bootstrap.bundle.min.js"></script>
	<!-- Bootstrap JS Bundle con Popper -->


</body>

</html>