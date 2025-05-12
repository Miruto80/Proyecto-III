<!DOCTYPE html>
<html lang="en">

<head>
<!-- php CSS, Meta y titulo--> 
<?php include 'vista/complementos/head_catalogo.php' ?>

</head>

<body>

<!-- |||||||||||||||| LOADER ||||||||||||||||||||-->
  <div class="preloader-wrapper">
    <div class="preloader">
    </div>
  </div>
<!-- |||||||||||||||| LOADER ||||||||||||||||||||-->

<!-- php CARRITO--> 
<?php include 'vista/complementos/carrito.php' ?>

<!-- php ENCABEZADO LOGO, ICONO CARRITO Y LOGIN--> 
<?php include 'vista/complementos/nav_catalogo.php' ?>

  <section id="shop-categories" class="section-padding pt-0">
    <div class="container-lg">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
         <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="?pagina=catalogo">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Mis Favoritos</li>
        </ol>
      </nav>



        <div class="row g-md-5">
            <!-- CATEGORÍAS -->
          <div class="col-md-3">
    <h5>Categoría</h5>
    <ul class="navbar-nav justify-content-end menu-list list-unstyled d-flex flex-column">
        <?php foreach ($categorias as $cat): ?>
            <li class="nav-item">
                <label for="cat-<?php echo $cat['id_categoria']; ?>" class="nav-link d-flex align-items-center gap-3 p-2">
                    <input type="checkbox" id="cat-<?php echo $cat['id_categoria']; ?>" value="<?php echo $cat['id_categoria']; ?>" class="filtro-checkbox">
                    <svg width="24" height="24" viewBox="0 0 24 24">
                        <use xlink:href="#icon-<?php echo strtolower($cat['nombre']); ?>"></use>
                    </svg>
                    <span><?php echo htmlspecialchars($cat['nombre']); ?></span>
                </label>
            </li>
        <?php endforeach; ?>
    </ul>
</div>


            <!-- PRODUCTOS -->
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-12">
                        <div class="section-header d-flex flex-wrap justify-content-between pb-2 mt-5 mt-lg-0">
                            <h2 class="section-title">Mis Favoritos</h2>
                           
                        </div>
                    </div>
                </div>

                <!-- GRIP DE PRODUCTO -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="product-grid row row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-3 row-cols-xl-4 g-4">
                            <?php foreach ($registro as $producto): ?>
                                <div class="col">
                                    <div class="product-item">
                                        <figure>
                                            <p title="<?php echo htmlspecialchars($producto['nombre']); ?>">
                                                <img src="<?php echo $producto['imagen']; ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>" class="tab-image img-fluid rounded-3">
                                            </p>
                                        </figure>
                                        <div class="d-flex flex-column text-center">
                                            <h3 class="fs-5 fw-normal">
                                                <p href="#" class="text-decoration-none">
                                                    <?php echo htmlspecialchars($producto['nombre']); ?>
                                                </p>
                                            </h3>
                                            <div class="d-flex justify-content-center align-items-center gap-2">
                                                <?php if ($producto['precio_mayor']): ?>
                                                    <span class="text-dark fw-semibold"> M $<?php echo $producto['precio_mayor']; ?></span>
                                                <?php endif; ?>
                                                <span class="text-dark fw-semibold">D $<?php echo $producto['precio_detal']; ?></span>
                                            </div>
                                            <div class="button-area p-3">
                                                <button class="btn btn-dark rounded-1 p-2 fs-7 btn-cart"
                                                    data-id="<?php echo $producto['id_producto']; ?>"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#productModal">
                                                    <i class="fa-solid fa-cart-shopping"></i> Añadir al carrito
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="modal-title" aria-hidden="true"> <div class="modal-dialog"> <div class="modal-content"> <div class="modal-header"> <h5 class="modal-title" id="modal-title">Detalles del Producto</h5> <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> </div> <div class="modal-body"> <img id="modal-imagen" src="" alt="Imagen del producto" class="img-fluid"> <p><strong>Marca:</strong> <span id="modal-marca"></span></p> <p><strong>Descripción:</strong> <span id="modal-descripcion"></span></p> <p><strong>Precio:</strong> <span id="modal-precio"></span></p> <p><strong>Precio Mayor:</strong> <span id="modal-precio-mayor"></span></p> <p><strong>Stock Disponible:</strong> <span id="modal-stock-disponible"></span></p> </div> </div>
                    </div>
                </div>
                <!-- FIN GRIP DE PRODUCTO -->
            </div>
        </div>
    </div>
</section>

<!-- php Publicidad Insta, Publicidad calidad, footer y JS--> 
<?php include 'vista/complementos/footer_catalogo.php' ?>
  
</body>

</html>