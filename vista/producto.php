<!DOCTYPE html>
<html lang="es">

<head>
  <?php include 'complementos/head.php' ?>
  <title> Producto | LoveMakeup  </title>
</head>

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
            <h4 class="mb-0"><i class="fa-solid fa-pump-soap mr-2" style="color: #f6c5b4;"></i>
              Producto</h4>

            <!-- Botón para abrir modal Registrar -->
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#registro" id="btnAbrirRegistrar">
              <span class="icon text-white">
                <i class="fas fa-file-medical"></i>
              </span>
              <span class="text-white">Registrar</span>
            </button>
          </div>

          <div class="table-responsive">
            <table class="table table-bordered table-hover" id="myTable" width="100%" cellspacing="0">
              <thead class="table-color">
                <tr>
                  <th class="text-white">Nombre</th>
                  <th class="text-white">Descripcion</th>
                  <th class="text-white">Marca</th>
                  <th class="text-white" style="display:none;">Al mayor</th>
                  <th class="text-white" style="display:none;">Precio M</th>
                  <th class="text-white">Precio</th>
                  <th class="text-white">Stock</th>
                  <th class="text-white" style="display:none;">Stock_m</th>
                  <th class="text-white" style="display:none;">Stock_m</th>
                  <th class="text-white"><i class="fa-solid fa-image"></i></th>
                  <th class="text-white">Categoria</th>
                  <th class="text-white">ACCION</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($registro as $dato) { ?>
                  <tr>
                    <td><?php echo htmlspecialchars($dato['nombre']) ?></td>
                    <td><?php echo htmlspecialchars($dato['descripcion']) ?></td>
                    <td><?php echo htmlspecialchars($dato['marca']) ?></td>
                    <td class="cantidad_mayor" style="display:none;"><?php echo htmlspecialchars($dato['cantidad_mayor']) ?></td>
                    <td class="precio_mayor" style="display:none;"><?php echo htmlspecialchars($dato['precio_mayor']) ?></td>
                    <td><?php echo htmlspecialchars($dato['precio_detal']) ?></td>
                    <td><?php echo htmlspecialchars($dato['stock_disponible']) ?></td>
                    <td class="stock_maximo" style="display:none;"><?php echo htmlspecialchars($dato['stock_maximo']) ?></td>
                    <td class="stock_minimo" style="display:none;"><?php echo htmlspecialchars($dato['stock_minimo']) ?></td>
                    <td><img src="<?php echo htmlspecialchars($dato['imagen']) ?>" alt="Imagen del producto" width="60" height="60"></td>
                    <td><?php echo htmlspecialchars($dato['nombre_categoria']) ?></td>
                    <td>
                      <form method="POST" action="">
                        <button type="button" class="btn btn-primary btn-sm modificar"
                          onclick="abrirModalModificar(this)"> 
                          <i class="fas fa-pencil-alt" title="Editar"></i>
                        </button>

                        <button type="button" class="btn btn-danger btn-sm eliminar"
                          onclick="eliminarproducto(<?php echo $dato['id_producto']; ?>)">
                          <i class="fas fa-trash-alt" title="Eliminar"></i>
                        </button>

                        <button type="button" class="btn btn-sm btn-info ver-detalles" title="Click para ver detalles">
                          <i class="fa fa-eye"></i>
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
                <textarea class="form-control" type="textarea" id="descripcion" name="descripcion" placeholder="Escribe la descripcion" required></textarea>
                <span id="sdescripcion"></span>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-4">
                <label for="cantidad_mayor">C al mayor</label>
                <input class="form-control" type="text" id="cantidad_mayor" name="cantidad_mayor" />
                <span id="scantidad_mayor"  style="color: red;"></span>
              </div>
              <div class="col-md-4">
                <label for="precio_mayor">Precio al mayor</label>
                <input class="form-control" type="text" id="precio_mayor" name="precio_mayor" />
                <span id="sprecio_mayor"  style="color: red;"></span>
              </div>
              <div class="col-md-4">
                <label for="precio_detal">Precio al detal</label>
                <input class="form-control" type="text" id="precio_detal" name="precio_detal" />
                <span id="sprecio_detal"  style="color: red;"></span>
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

<<<<<<< HEAD
<script src="assets/js/producto.js"></script>
=======
            <br>
            <div class="text-center">
              <button type="button" class="btn btn-primary" id="btnEnviar">Guardar</button>
              <button type="reset" class="btn btn-primary" id="btnLimpiar">Limpiar</button>
            </div>
          </form>
        </div>
>>>>>>> 37d6e2a378c94adf3b2a23575fc49d99b9821942

      </div>
    </div>
  </div>

  <?php include 'complementos/footer.php' ?>
  <script src="assets/js/demo/datatables-demo.js"></script>

  <script src="assets/js/producto.js"></script>

  <!-- Modal detalles producto, sin cambios -->
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
