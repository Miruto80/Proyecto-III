<!DOCTYPE html>
<html lang="es">

<head>
  <?php include 'complementos/head.php' ?>
  <title> Producto | LoveMakeup  </title>
</head>

<style>
  .producto-desactivado {
    background-color: #6c757d !important; /* Gris oscuro */
    color: #ffffff !important; /* Texto en blanco */
}

.producto-desactivado td, 
.producto-desactivado th {
    color: #ffffff !important; /* Asegurar que el texto en las celdas también sea blanco */
}

.producto-desactivado button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.driver-popover.driverjs-theme {
  background-color: #fde047;
  color: #000;
}

.driver-popover.driverjs-theme .driver-popover-title {
  font-size: 20px;
}

.driver-popover.driverjs-theme .driver-popover-title,
.driver-popover.driverjs-theme .driver-popover-description,
.driver-popover.driverjs-theme .driver-popover-progress-text {
  color: #000;
}

.driver-popover.driverjs-theme button {
  flex: 1;
  text-align: center;
  background-color: #000;
  color: #ffffff;
  border: 2px solid #000;
  text-shadow: none;
  font-size: 14px;
  padding: 5px 8px;
  border-radius: 6px;
}

.driver-popover.driverjs-theme button:hover {
  background-color: #000;
  color: #ffffff;
}

.driver-popover.driverjs-theme .driver-popover-navigation-btns {
  justify-content: space-between;
  gap: 3px;
}

.driver-popover.driverjs-theme .driver-popover-close-btn {
  color: #fff;
  width: 20px; /* Reducir el tamaño del botón */
  height: 20px;
  font-size: 16px;
  transition: all 0.5 ease-in-out;
}

.driver-popover.driverjs-theme .driver-popover-close-btn:hover {
 background-color: #fff;
 color: #000;
 border: #000;
}

.driver-popover.driverjs-theme .driver-popover-arrow-side-left.driver-popover-arrow {
  border-left-color: #fde047;
}

.driver-popover.driverjs-theme .driver-popover-arrow-side-right.driver-popover-arrow {
  border-right-color: #fde047;
}

.driver-popover.driverjs-theme .driver-popover-arrow-side-top.driver-popover-arrow {
  border-top-color: #fde047;
}

.driver-popover.driverjs-theme .driver-popover-arrow-side-bottom.driver-popover-arrow {
  border-bottom-color: #fde047;
}


</style>

<body class="g-sidenav-show bg-gray-100">

<?php include 'complementos/sidebar.php' ?>

<main class="main-content position-relative border-radius-lg ">

<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur" data-scroll="false">
  <div class="container-fluid py-1 px-3">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="#">Administrar</a></li>
        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Producto</li>
      </ol>
      <h6 class="font-weight-bolder text-white mb-0">Gestionar Producto</h6>
    </nav>
<?php include 'complementos/nav.php' ?>

<div class="container-fluid py-4">

  <div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header pb-0">

        <div class="d-sm-flex align-items-center justify-content-between mb-5">
  <h4 class="mb-0">
    <i class="fa-solid fa-pump-soap mr-2" style="color: #f6c5b4;"></i> Producto
  </h4>

  <div>
    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#registro" id="btnAbrirRegistrar">
      <span class="icon text-white">
        <i class="fas fa-file-medical"></i>
      </span>
      <span class="text-white">Registrar</span>
    </button>

    <button type="button" class="btn btn-primary" id="btnExtra">
      <span class="icon text-white">
        <i class="fas fa-info-circle"></i>
      </span>
      <span class="text-white">Ayuda</span>
    </button>
  </div>
</div>

          <div class="table-responsive">
            <table class="table table-bordered table-hover" id="myTable" width="100%" cellspacing="0">
              <thead class="table-color">
                <tr>
                  <th class="text-white">Nombre</th>
                  <th class="text-white">Descripcion</th>
                  <th class="text-white">Marca</th>
                  <th class="text-white">Precio</th>
                  <th class="text-white">Stock</th>
                  <th class="text-white"><i class="fa-solid fa-image"></i></th>
                  <th class="text-white">Categoria</th>
                  <th class="text-white">ACCION</th>
                </tr>
              </thead>
              <tbody>
              <?php foreach ($registro as $dato) { 
        $claseFila = ($dato["estatus"] == 2) ? "producto-desactivado" : "";
        $botonesDeshabilitados = ($dato["estatus"] == 2) ? "disabled" : "";
    ?>
                 <tr class="<?php echo ($dato['estatus'] == 2) ? 'producto-desactivado' : ''; ?>
                   "data-cantidad-mayor="<?php echo htmlspecialchars($dato['cantidad_mayor']); ?>"
                    data-precio-mayor="<?php echo htmlspecialchars($dato['precio_mayor']); ?>"
                    data-stock-maximo="<?php echo htmlspecialchars($dato['stock_maximo']); ?>"
                    data-stock-minimo="<?php echo htmlspecialchars($dato['stock_minimo']); ?>">

                    <td><?php echo htmlspecialchars($dato['nombre']) ?></td>
                    <td><?php echo htmlspecialchars($dato['descripcion']) ?></td>
                    <td><?php echo htmlspecialchars($dato['marca']) ?></td>
                    <td><?php echo htmlspecialchars($dato['precio_detal']) ?></td>
                    <td><?php echo htmlspecialchars($dato['stock_disponible']) ?></td>
                    <td><img src="<?php echo htmlspecialchars($dato['imagen']) ?>" alt="Imagen del producto" width="60" height="60"></td>
                    <td><?php echo htmlspecialchars($dato['nombre_categoria']) ?></td>
                    <td>
                      <form method="POST" action="">
                      <button type="button" class="btn btn-primary btn-s modificar" 
            onclick="abrirModalModificar(this)" <?php echo ($dato['estatus'] == 2) ? 'disabled' : ''; ?>> 
            <i class="fas fa-pencil-alt" title="Editar"></i>
        </button>

                        <button type="button" class="btn btn-danger btn-s eliminar"
            onclick="eliminarproducto(<?php echo $dato['id_producto']; ?>)" <?php echo ($dato['estatus'] == 2) ? 'disabled' : ''; ?>>
            <i class="fas fa-trash-alt" title="Eliminar"></i>
        </button>

                        <button type="button" class="btn btn-s btn-info ver-detalles" title="Click para ver detalles">
                          <i class="fa fa-eye"></i>
                        </button>
                        <button type="button" class="btn btn-warning text-light btn-desactivar" 
            onclick="cambiarEstatusProducto(<?php echo $dato['id_producto']; ?>, <?php echo $dato['estatus']; ?>)">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </button>
                      </form>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>
  </div>

  <!-- Modal Universal para Registrar y Modificar -->
  <div class="modal fade" id="registro" tabindex="1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header header-color">
          <h1 class="modal-title fs-5" id="modalTitle">Registrar Producto</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <form id="u" autocomplete="off" enctype='multipart/form-data'>
            <input type="hidden" id="id_producto" name="id_producto" value="" />   
            <input type="hidden" id="imagenActual" name="imagenActual" value="" />
            <input type="hidden" id="accion" name="accion" value="registrar" />

            <div class="row mb-3">
              <div class="col-md-6">
                <label for="nombre">Nombre del producto</label>
                <input class="form-control" type="text" id="nombre" name="nombre" />
                <span id="snombre" style="color: red;"></span>
              </div>
              <div class="col-md-6">
                <label for="marca">Marca del producto</label>
                <input class="form-control" type="text" id="marca" name="marca" />
                <span id="smarca" style="color: red;"></span>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-12">
                <label for="descripcion">Descripcion</label>
                <textarea class="form-control" id="descripcion" name="descripcion" placeholder="Escribe la descripción" required></textarea>
                <span id="sdescripcion"></span>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-4">
                <label for="cantidad_mayor">Cantidad al mayor</label>
                <input class="form-control" type="text" id="cantidad_mayor" name="cantidad_mayor" />
                <span id="scantidad_mayor"  style="color: red;"></span>
              </div>
              <div class="col-md-4">
                <label for="precio_detal">Precio al detal</label>
                <input class="form-control" type="text" id="precio_detal" name="precio_detal" />
                <span id="sprecio_detal"  style="color: red;"></span>
              </div>
              <div class="col-md-4">
                <label for="precio_mayor">Precio al mayor</label>
                <input class="form-control" type="text" id="precio_mayor" name="precio_mayor" />
                <span id="sprecio_mayor"  style="color: red;"></span>
              </div>
            <div class="row mb-3">
              <div class="col-md-4">
                <label for="stock_maximo">Stock maximo</label>
                <input class="form-control" type="text" id="stock_maximo" name="stock_maximo" />
                <span id="sstock_maximo"  style="color: red;"></span>
              </div>
              <div class="col-md-4">
                <label for="stock_minimo">Stock minimo</label>
                <input class="form-control" type="text" id="stock_minimo" name="stock_minimo" />
                <span id="sstock_minimo"  style="color: red;"></span>
              </div>
              <div class="col-md-4">
                <label for="categoria">Categoria</label>
                <select class="form-select text-gray-900 " name="categoria" id="categoria" required>
                  <option disabled selected>Seleccione una Categoria</option>
                  <?php foreach ($categoria as $cat) { ?>
                    <option value="<?php echo htmlspecialchars($cat['id_categoria']); ?>"> <?php echo htmlspecialchars($cat['nombre']); ?> </option>
                  <?php } ?>
                </select>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <center>
                  <label for="archivo" style="cursor:pointer">
                    Click aqui subir la foto del producto
                    <br>
                    <img src="assets/img/logo.PNG" id="imagen" class="img-fluid rounded-circle w-25 mt-3 centered" style="object-fit:scale-down">
                  </label>
                  <input id="archivo" type="file" style="display:none" accept=".png,.jpg,.jpeg,.webp" name="imagenarchivo" />
                </center>
              </div>
            </div>

            <br>
            <div class="text-center">
              <button type="button" class="btn btn-primary" id="btnEnviar">Guardar</button>
              <button type="reset" class="btn btn-primary" id="btnLimpiar">Limpiar</button>
            </div>
          </form>
        </div>

      </div>
    </div>
  </div>

  <?php include 'complementos/footer.php' ?>
  <script src="assets/js/demo/datatables-demo.js"></script>

  <script src="assets/js/producto.js"></script>

  <div class="modal fade" id="modalDetallesProducto" tabindex="-1" aria-labelledby="tituloModal" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="tituloModal">Detalles del Producto</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <p><strong>Cantidad de venta al mayor:</strong> <span id="modal-cantidad-mayor"></span></p>
          <p><strong>Precio al Mayor:</strong> <span id="modal-precio-mayor"></span></p>
          <p><strong>Stock Máximo:</strong> <span id="modal-stock-maximo"></span></p>
          <p><strong>Stock Mínimo:</strong> <span id="modal-stock-minimo"></span></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

</main>

</body>

</html>
