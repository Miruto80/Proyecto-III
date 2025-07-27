<!DOCTYPE html>
<html lang="en">

<head>
  <?php include 'vista/complementos/head_catalogo.php' ?>

  <style>
.product-item {
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  height: 100%;
  border: 1px solid #e0e0e0;
  border-radius: 12px;
  padding: 10px;
  transition: box-shadow 0.3s ease;
  background-color: #fff;
}

.product-item:hover {
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

.product-item figure {
  flex-shrink: 0;
  text-align: center;
  min-height: 200px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.product-item img {
  max-height: 180px;
  object-fit: contain;
}

.product-item .button-area {
  margin-top: auto;
}
</style>

</head>

<body>

  <!-- LOADER -->
  <div class="preloader-wrapper">
    <div class="preloader"></div>
  </div>

  <!-- CARRITO -->
  <?php include 'vista/complementos/carrito.php' ?>

  <!-- NAV -->
  <?php include 'vista/complementos/nav_catalogo.php' ?>

  <section class="section-padding pt-0">
    <div class="container-lg">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb" class="custom-breadcrumb mt-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="?pagina=catalogo">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Mi lista de deseos</li>
        </ol>
      </nav>
      <br>
      <div class="row">
        <div class="col-12 d-flex justify-content-between align-items-center mb-4">
          <h2 class="section-title">Lista de Deseos</h2>
          <button id="btn-vaciar-lista" class="btn btn-outline-danger">
            <i class="fa fa-trash me-2"></i> Vaciar Lista
          </button>
        </div>
      </div>

      <div class="product-grid row row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-3 row-cols-xl-4 g-4">
        <?php if (!empty($lista)): ?>
          <?php foreach ($lista as $producto): ?>
            <div class="col">
              <div class="product-item" 
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
                   onclick="openModal(this)">

                <figure class="position-relative">
                  <img src="<?php echo $producto['imagen']; ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>" class="img-fluid rounded-3">
                </figure>
                <div class="d-flex flex-column text-center">
                  <h3 class="fs-5 fw-normal"><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                  <div class="d-flex justify-content-center gap-2">
                    <span class="text-dark fw-semibold">D $<?php echo $producto['precio_detal']; ?></span>
                    <?php if ($producto['precio_mayor']): ?>
                      <span class="text-muted">M $<?php echo $producto['precio_mayor']; ?></span>
                    <?php endif; ?>
                  </div>
                    <div class="button-area p-3 gap 2">
                         <button class="btn btn-dark rounded-1 p-2 fs-7 btn-cart">
                          <i class="fas fa-eye"></i> ver
                        </button>
                    </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="col-12 text-center">
            <p class="fs-4 text-muted">Tu lista de deseos está vacía.</p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- MODAL PRODUCTO -->
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
            <!-- Información -->
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

                <!-- Botones SIEMPRE visibles (sin lógica de sesión) -->
                <button type="button" id="btn-agregar-carrito" class="btn btn-primary w-100 mt-2">
                  <i class="fa fa-cart-plus me-2"></i> Añadir al carrito
                </button>

                  <button type="button" class="btn btn-outline-danger w-100 btn-eliminar-deseo" data-id-lista="<?php echo $producto['id_lista']; ?>">
                        <i class="fa fa-times me-2"></i> Eliminar
                  </button>
              

              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php include 'vista/complementos/footer_catalogo.php' ?>
  <script src="assets/js/catalogo/catalogo.js"></script>
  <script src="assets/js/lista_deseo.js"></script>

</body>
</html>
