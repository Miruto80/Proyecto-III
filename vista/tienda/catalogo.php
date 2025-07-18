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

<!--Banner IMG-->
  <section>
    <div class="slideshow slide-in arrow-absolute text-white position-relative">
      <div class="swiper-wrapper">
        <div class="swiper-slide jarallax">
          <img src="assets/img/b01.webp" class="jarallax-img w-100" alt="slideshow" />
          <div class="banner-content w-100 my-3">
            <div class="container">
              <div class="row justify-content-center text-center">
                <div class="col-md-12 pt-1">
                  <p class="fs-4 text-dark fw-bold">
                    Haz que tu belleza deslumbre con nuestra selección de alta gama.
                  </p>
                  <h2 class="fs-2 text-dark text-uppercase ls-0 fw-bold">
                    Venta al Mayor y Detal
                  </h2>
                  <a href="?pagina=catalogo_producto" class="btn btn-dark rounded-3 px-3 py-1 mt-2">Compra Ahora</a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="swiper-slide jarallax">
          <img src="assets/img/b02.webp" class="jarallax-img w-100" alt="slideshow" />
          <div class="banner-content w-100 my-3">
            <div class="container">
              <div class="row justify-content-center text-center">
                <div class="col-md-12 pt-1">
                  <p class="fs-4 text-dark fw-bold">
                    Tu piel, tu arte. Embellece cada día con lo mejor del maquillaje.
                  </p>
                  <h2 class="fs-2 text-dark text-uppercase ls-0 fw-bold">
                    Venta al Mayor y Detal
                  </h2>
                  <a href="?pagina=catalogo_producto" class="btn btn-dark rounded-3 px-3 py-1 mt-2">Compra Ahora</a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="swiper-slide jarallax">
          <img src="assets/img/b03.webp" class="jarallax-img w-100" alt="slideshow" />
          <div class="banner-content w-100 my-3">
            <div class="container">
              <div class="row justify-content-center text-center">
                <div class="col-md-12 pt-1">
                  <p class="fs-4 text-dark fw-bold">
                    Eleva tu belleza con maquillaje exclusivo. ¡Brilla con confianza!
                  </p>
                  <h2 class="fs-2 text-dark text-uppercase ls-0 fw-bold">
                    Venta al Mayor y Detal
                  </h2>
                  <a href="?pagina=catalogo_producto" class="btn btn-dark rounded-3 px-3 py-1 mt-2">Compra Ahora</a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="swiper-slide jarallax">
          <img src="assets/img/b04.webp" class="jarallax-img w-100" alt="slideshow" />
          <div class="banner-content w-100 my-3">
            <div class="container">
              <div class="row justify-content-center text-center">
                <div class="col-md-12 pt-1">
                  <p class="fs-4 text-dark fw-bold">
                    Estilo, elegancia y calidad en cada aplicación. ¡Pruébalo!
                  </p>
                  <h2 class="fs-2 text-dark text-uppercase ls-0 fw-bold">
                    Venta al Mayor y Detal
                  </h2>
                  <a href="?pagina=catalogo_producto" class="btn btn-dark rounded-3 px-3 py-1 mt-2">Compra Ahora</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="pagination-wrapper position-absolute bottom-0 mb-2 text-center">
        <div class="container">
          <div class="slideshow-swiper-pagination dark"></div>
        </div>
      </div>
    </div>
  </section>

  
  <section id="shop-categories" class="section-padding">
  <div class="container-lg">
    <div class="row">
      <!-- PRODUCTOS -->
      <div class="col-12">
        <div class="section-header d-flex flex-wrap justify-content-between pb-2 mt-5 mt-lg-0">
          <h2 class="section-title">Productos más vendidos</h2>
          <div class="d-flex align-items-center">
            <a href="?pagina=catalogo_producto" id="Botonlado" class="btn btn-dark rounded-1">Ver todo</a>
          </div>
        </div>

        <!-- GRID DE PRODUCTO -->
        <div class="product-grid row row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-3 row-cols-xl-4 g-4 mt-3">
          <?php if (!empty($registro)): ?>
            <?php foreach ($registro as $producto): ?>
              <div class="col">
                <div class="product-item" data-categoria="<?php echo $producto['id_categoria']; ?>" data-bs-toggle="modal"
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
                     onclick="openModal(this)">
                  <figure class="position-relative">
                    <p title="<?php echo htmlspecialchars($producto['nombre']); ?>">
                      <img src="<?php echo $producto['imagen']; ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>" class="tab-image img-fluid rounded-3">
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
                      <p class="text-decoration-none"><?php echo htmlspecialchars($producto['nombre']); ?></p>
                    </h3>
                    <div class="d-flex justify-content-center align-items-center gap-2">
                      <?php if ($producto['precio_mayor']): ?>
                        <span class="text-dark fw-semibold">M $<?php echo $producto['precio_mayor']; ?></span>
                      <?php endif; ?>
                      <span class="text-dark fw-semibold">D $<?php echo $producto['precio_detal']; ?></span>
                    </div>
                    <div class="button-area p-3">
                      <button class="btn btn-dark rounded-1 p-2 fs-7 btn-cart">
                        <i class="fa-solid fa-cart-shopping"></i> Añadir al carrito
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="col-12 text-center">
              <p class="fs-4 text-muted">No se encontraron productos vendidos.</p>
            </div>
          <?php endif; ?>
        </div>

        <div class="text-center mt-4">
          <a href="?pagina=catalogo_producto" class="btn btn-primary btn-lg px-5">Ver Todos los productos</a>
        </div>

        <!-- MODAL -->
        <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="modal-title" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="modal-title"></h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Cerrar"></button>
              </div>
              <div class="modal-body">
                <div class="row">
                  <!-- Imagen -->
                  <div class="col-md-6 text-center">
                    <img id="modal-imagen" src="" alt="Producto" class="img-fluid mb-3" style="max-height: 400px; object-fit: contain;">
                  </div>
                  <!-- Datos -->
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
                          <button type="button" id="btn-agregar-carrito" class="btn btn-primary w-100 mt-2">
                            <i class="fa fa-cart-plus me-2"></i> Añadir al carrito
                          </button>

                          <button type="button" class="btn btn-primary w-100 mt-2 btn-favorito" 
                                  data-id="<?php echo $producto['id_producto']; ?>">
                            <i class="fa-solid fa-heart"></i> Añadir a deseos
                          </button>
                        <?php else: ?>
                          <a href="?pagina=catalogo" class="btn btn-primary w-100 mt-2">
                            <i class="fa fa-cart-plus me-2"></i> Añadir al carrito
                          </a>

                          <a href="?pagina=catalogo" class="btn btn-primary w-100 mt-2">
                            <i class="fa-solid fa-heart"></i> Añadir a deseos
                          </a>
                        <?php endif; ?>
                      <?php else: ?>
                        <button class="btn btn-primary w-100 mt-2" href="?pagina=login">
                          <i class="fa fa-cart-plus me-2"></i> Añadir al carrito
                        </button>
                        <button class="btn btn-primary w-100 mt-2" href="?pagina=login">
                          <i class="fa-solid fa-heart"></i> Añadir a Deseos
                        </button>
                      <?php endif; ?>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- FIN MODAL -->

      </div>
    </div>
  </div>
</section>



  <section class="mt-5 bg-light">
    <div class="container">
      <div class="row justify-content-center align-items-center">
        <div class="col-md-5">
          <h2 class="fw-bold fs-1 mt-5">
            ¡Resalta tu belleza con los mejores productos de maquillaje!
          </h2>
          <p>Síguenos en Instagram <b>@lovemakeupyk</b> para descubrir nuestra colección</p>
          <form>
           
            <div class="d-grid gap-2">
              <a href="https://www.instagram.com/lovemakeupyk/" class="btn btn-dark btn-lg" target="_blank">Seguir en Instagram</a>
            </div>
          </form>
        </div>
        <div class="col-md-7">
          <img src="assets/img/02.jpg" alt="image" class="img-fluid">
        </div>
      </div>
    </div>
  </section>

  <section class="section-padding">
    <div class="container">
      <div class="row justify-content-center align-items-center">
          <div class="col-md-3">
            <div class="mb-3">
              <i class="fa-solid fa-truck" style="font-size: 50px; color: #fa48c9;"></i>
            </div>
            <div>
              <h5 class="fs-5 fw-normal">Envíos nacionales</h5>
              <p class="card-text">Recibe tus productos en todo el país con seguridad y rapidez.</p>
            </div>
          </div>

          <div class="col-md-3">
            <div class="mb-3">
              <i class="fa-solid fa-money-bill-wave" style="font-size: 50px; color: #fa48c9;"></i>
            </div>
            <div>
              <h5 class="fs-5 fw-normal">Pagos seguros</h5>
              <p class="card-text">Opciones confiables para que compres sin preocupaciones.</p>
            </div>
          </div>

          <div class="col-md-3">
            <div class="mb-3">
                  <i class="fa-solid fa-comments" style="font-size: 50px; color: #fa48c9;"></i>
            </div>
            <div>
              <h5 class="fs-5 fw-normal">Atención personalizada</h5>
              <p class="card-text">Asesoría experta para ayudarte a elegir el maquillaje perfecto.</p>
            </div>
          </div>

          <div class="col-md-3">
            <div class="mb-3">
                <i class="fa-solid fa-percent" style="font-size: 50px; color: #fa48c9;"></i>
            </div>
            <div>
              <h5 class="fs-5 fw-normal">Ofertas y promociones</h5>
              <p class="card-text">Descuentos especiales en tus marcas favoritas.</p>
            </div>
          </div>
      </div>
    </div>
  </section>

  
<!-- php Publicidad Insta, Publicidad calidad, footer y JS--> 

<?php include 'vista/complementos/footer_catalogo.php' ?>

  
</body>

</html>

