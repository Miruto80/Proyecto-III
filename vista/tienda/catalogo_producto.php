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


   <section id="shop-categories" class="section-padding pt-0" style="background-color:#fff;">
  
    <div class="container-lg">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb" class="custom-breadcrumb mt-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="?pagina=catalogo">Inicio</a></li>
             <li class="breadcrumb-item active" aria-current="page">Todos los productos</li>
        </ol>
      </nav>

        <div class="row g-md-5 pt-0">
            <!-- CATEGORÍAS -->
            <div class="row g-md-5">
            <!-- CATEGORÍAS -->
            <div class="col-md-3">
              <div class="p-3 border rounded bg-white shadow-sm">
                <h5 class="mb-3">Filtrado por categorias</h5>
                <ul class="navbar-nav menu-list list-unstyled d-flex flex-column gap-2 categorias">
                  <?php if (empty($categorias)): ?>
                    <li class="text-muted">No se encontraron categorías.</li>
                  <?php endif; ?>
                  <?php foreach ($categorias as $cat): ?>
                    <li class="nav-item">
                      <label for="cat-<?php echo $cat['id_categoria']; ?>" class="categoria-label nav-link d-flex align-items-center gap-2 px-2 py-1 rounded-2 transition">
                        <input type="checkbox" id="cat-<?php echo $cat['id_categoria']; ?>" value="<?php echo $cat['id_categoria']; ?>" class="form-check-input filtro-checkbox">
                        <svg width="20" height="20" viewBox="0 0 24 24">
                          <use xlink:href="#icon-<?php echo strtolower($cat['nombre']); ?>"></use>
                        </svg>
                        <span><?php echo htmlspecialchars($cat['nombre']); ?></span>
                      </label>
                    </li>
                  <?php endforeach; ?>
                </ul>
              </div>
            </div>


            <!-- PRODUCTOS -->
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-12">
                        <div class="section-header d-flex flex-wrap justify-content-between pb-2 mt-5 mt-lg-0">
                            <h2 class="section-title">Productos</h2>
                              <?php if ($sesion_activa): ?>
                                 <?php if($_SESSION["nivel_rol"] == 1) { ?>
                            <div class="d-flex align-items-center">
                                <a href="?pagina=vercarrito" id="Botonlado" class="btn btn-dark rounded-1"><i class="fa fa-cart-plus me-2"></i> Ver Carrito</a>
                            </div>
                              <?php } ?>
                               <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- GRIP DE PRODUCTO -->
                <div class="col-md-12">
    <div class="product-grid row row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-3 row-cols-xl-4 g-4">
        <?php if (!empty($registro)): ?>
            <?php foreach ($registro as $producto): ?>
                <div class="col">
                   <div class="product-item" data-categoria="<?php echo $producto['id_categoria']; ?>" 
                     data-id="<?php echo $producto['id_producto']; ?>"
                     data-nombre="<?php echo htmlspecialchars($producto['nombre']); ?>"
                     data-precio="<?php echo $producto['precio_detal']; ?>"
                     data-marca="<?php echo htmlspecialchars($producto['marca']); ?>"
                     data-descripcion="<?php echo htmlspecialchars($producto['descripcion']); ?>"
                     data-cantidad-mayor="<?php echo $producto['cantidad_mayor']; ?>"
                     data-precio-mayor="<?php echo $producto['precio_mayor']; ?>"
                     data-stock-disponible="<?php echo $producto['stock_disponible']; ?>"
                     data-imagen="<?php echo $producto['imagen']; ?>">
                  <figure class="position-relative">
                    <p title="<?php echo htmlspecialchars($producto['nombre']); ?>">
                      <img
                       src="<?php echo $producto['imagen']; ?>"
                       alt="<?php echo htmlspecialchars($producto['nombre']); ?>"
                       class="tab-image img-fluid rounded-3"
                       data-bs-toggle="modal"
                     data-bs-target="#productModal"
                       onclick="openModal(this.closest('.product-item'))"
                      />
                    </p>
                           <?php if ($sesion_activa): ?>

<?php if ($_SESSION["nivel_rol"] == 1): ?>
   

           <button type="button" 
                class="btn btn-light position-absolute top-0 end-0 m-2 btn-favorito <?php echo in_array($producto['id_producto'], $idsProductosFavoritos) ? 'favorito-activo' : ''; ?>" 
                data-id="<?php echo $producto['id_producto']; ?>">
            <i class="fa-solid fa-heart"></i>
        </button>

<?php else: ?>
 

    <a href="?pagina=catalogo" class="btn btn-light position-absolute top-0 end-0 m-2">
        <i class="fa-solid fa-heart"></i>
    </a>
<?php endif; ?>

<?php else: ?>


        <button  href="?pagina=login" class="btn btn-light position-absolute top-0 end-0 m-2">
            <i class="fa-solid fa-heart"></i>
        </button>
<?php endif; ?>
                        

<style>.btn-favorito.favorito-activo i.fa-heart {
  color: red;
}
</style>
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
                            <form class="form-carrito-exterior">
  <input type="hidden" name="id" value="<?php echo $producto['id_producto']; ?>">
  <input type="hidden" name="nombre" value="<?php echo htmlspecialchars($producto['nombre']); ?>">
  <input type="hidden" name="precio_detal" value="<?php echo $producto['precio_detal']; ?>">
  <input type="hidden" name="precio_mayor" value="<?php echo $producto['precio_mayor']; ?>">
  <input type="hidden" name="cantidad_mayor" value="<?php echo $producto['cantidad_mayor']; ?>">
  <input type="hidden" name="imagen" value="<?php echo $producto['imagen']; ?>">
  <input type="hidden" name="stockDisponible" value="<?php echo $producto['stock_disponible']; ?>">



  <?php if ($sesion_activa): ?>
                        <?php if ($_SESSION["nivel_rol"] == 1): ?>
                          <button type="button"  class="btn btn-dark rounded-1 p-2 fs-7 btn-cart btn-agregar-carrito-exterior">
                            <i class="fa fa-cart-plus me-2"></i> Añadir al carrito
                          </button>

                        <?php else: ?>
                          <a href="?pagina=catalogo" class="btn btn-dark rounded-1 p-2 fs-7 btn-cart">
                            <i class="fa fa-cart-plus me-2"></i> Añadir al carrito
                          </a>

                        
                        <?php endif; ?>
                      <?php else: ?>
                        <button class="btn btn-dark rounded-1 p-2 fs-7 btn-cart" href="?pagina=login">
                          <i class="fa fa-cart-plus me-2"></i> Añadir al carrito
                        </button>
                      
                      <?php endif; ?>
</form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center">
                <p class="fs-4 text-muted">No se encontraron productos para tu búsqueda.</p>
            </div>
        <?php endif; ?>
    </div>

                <!-- FIN GRIP DE PRODUCTO -->
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
            <form id="form-carrito">
  <input type="hidden" name="id" id="form-id">
  <input type="hidden" name="nombre" id="form-nombre">
  <input type="hidden" name="precio_detal" id="form-precio-detal">
  <input type="hidden" name="precio_mayor" id="form-precio-mayor">
  <input type="hidden" name="cantidad_mayor" id="form-cantidad-mayor">
  <input type="hidden" name="imagen" id="form-imagen">
  <input type="hidden" name="stockDisponible" id="form-stock-disponible">
  <hr>
 
  <?php if ($sesion_activa): ?>

<?php if ($_SESSION["nivel_rol"] == 1): ?>
   
  <button type="button" id="btn-agregar-carrito-exterior"  class="btn btn-dark rounded-1 p-2 fs-7 btn-cart">
                            <i class="fa fa-cart-plus me-2"></i> Añadir al carrito
                          </button>




<?php else: ?>
 
    <a href="?pagina=catalogo" class="btn btn-dark w-100 mt-2">
        <i class="fa fa-cart-plus me-2"></i> Añadir al carrito
    </a>


<?php endif; ?>

<?php else: ?>

<button  href="?pagina=login" class="btn btn-dark w-100 mt-2">
            <i class="fa fa-cart-plus me-2"></i> Añadir al carrito
      </button>

<?php endif; ?>




</form>
          </div>
        </div>
      </div>



            </div>
        </div>
    </div>
</section>

<!-- php Publicidad Insta, Publicidad calidad, footer y JS--> 
<?php include 'vista/complementos/footer_catalogo.php' ?>
  
</body>

</html>