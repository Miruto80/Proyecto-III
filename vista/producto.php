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

/* Estilos para el modal de registro mejorado */
.modal-producto {
  border-radius: 15px;
  border: none;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
}

.modal-producto .modal-header {
  background: linear-gradient(135deg, #f6c5b4 0%, #e8a87c 100%);
  border-radius: 15px 15px 0 0;
  border-bottom: none;
  padding: 1.5rem;
}

.modal-producto .modal-title {
  color: #2c3e50;
  font-weight: 700;
  font-size: 1.5rem;
  display: flex;
  align-items: center;
  gap: 10px;
}

.modal-producto .modal-title i {
  font-size: 1.8rem;
  color: #e74c3c;
}

.modal-producto .modal-body {
  padding: 2rem;
  background: #f8f9fa;
}

.modal-producto .btn-close {
  background-color: rgba(8, 6, 6, 0.8);
  border-radius: 50%;
  padding: 8px;
  transition: all 0.3s ease;
}

.modal-producto .btn-close:hover {
  background-color: #eb0f0f;
  transform: scale(1.1);
}

/* Estilos para las secciones del formulario */
.seccion-formulario {
  background: white;
  border-radius: 12px;
  padding: 1.5rem;
  margin-bottom: 1.5rem;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  border-left: 4px solid #f6c5b4;
  transition: all 0.3s ease;
}

.seccion-formulario:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.seccion-formulario h6 {
  color: #2c3e50;
  font-weight: 600;
  font-size: 1.1rem;
  margin-bottom: 1.2rem;
  display: flex;
  align-items: center;
  gap: 8px;
}

.seccion-formulario h6 i {
  color: #f6c5b4;
  font-size: 1.2rem;
}

/* Estilos para los campos de formulario */
.form-control, .form-select {
  border: 2px solid #e9ecef;
  border-radius: 8px;
  padding: 12px 15px;
  font-size: 0.95rem;
  transition: all 0.3s ease;
  background-color: #f8f9fa;
}

.form-control:focus, .form-select:focus {
  border-color: #f6c5b4;
  box-shadow: 0 0 0 0.2rem rgba(246, 197, 180, 0.25);
  background-color: white;
}

.form-label {
  font-weight: 600;
  color: #495057;
  margin-bottom: 0.5rem;
  font-size: 0.9rem;
}

/* Estilos para el área de imagen */
.area-imagen {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  border: 2px dashed #dee2e6;
  border-radius: 12px;
  padding: 2rem;
  text-align: center;
  cursor: pointer;
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}

.area-imagen:hover {
  border-color: #f6c5b4;
  background: linear-gradient(135deg, #fff5f2 0%, #f8f9fa 100%);
  transform: scale(1.02);
}

.area-imagen label {
  color: #6c757d;
  font-weight: 500;
  margin-bottom: 1rem;
  display: block;
}

.area-imagen img {
  border-radius: 12px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease;
}

.area-imagen:hover img {
  transform: scale(1.05);
}

/* Estilos para los botones */
.btn-modern {
  border-radius: 8px;
  padding: 12px 24px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  transition: all 0.3s ease;
  border: none;
  position: relative;
  overflow: hidden;
}

.btn-modern::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
  transition: left 0.5s;
}

.btn-modern:hover::before {
  left: 100%;
}

.btn-guardar {
  background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
  color: white;
}

.btn-guardar:hover {
  background: linear-gradient(135deg, #218838 0%, #1ea085 100%);
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
}

.btn-limpiar {
  background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
  color: white;
}

.btn-limpiar:hover {
  background: linear-gradient(135deg, #5a6268 0%, #343a40 100%);
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(108, 117, 125, 0.3);
}

/* Estilos para mensajes de error */
.error-message {
  color: #dc3545;
  font-size: 0.85rem;
  margin-top: 0.25rem;
  font-weight: 500;
}

.driver-popover.driverjs-theme {
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
<!-- |||||||||||||||| LOADER ||||||||||||||||||||-->
  <div class="preloader-wrapper">
    <div class="preloader">
    </div>
  </div> 
<!-- |||||||||||||||| LOADER ||||||||||||||||||||-->
<div class="container-fluid py-4">

  <div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header pb-0">

        <div class="d-sm-flex align-items-center justify-content-between mb-5">
  <h4 class="mb-0">
    <i class="fa-solid fa-pump-soap mr-2" style="color: #f6c5b4;"></i> Producto
  </h4>
 
  <div class="d-flex gap-2">
      <?php if ($_SESSION["nivel_rol"] >= 2 && tieneAcceso(3, 'registrar')): ?>
  <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#registro" id="btnAbrirRegistrar">
    <span class="icon text-white">
      <i class="fas fa-file-medical"></i>
    </span>
    <span class="text-white">Registrar</span>
  </button>
  <?php endif; ?>
  <button type="button" class="btn btn-primary" id="btnAyuda">
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
                    <td><?php echo htmlspecialchars($dato['precio_detal']) ?> <i class="fa-solid fa-dollar-sign"></i></td>
                    <td><?php echo htmlspecialchars($dato['stock_disponible']) ?></td>
                    <td><img src="<?php echo htmlspecialchars($dato['imagen']) ?>" alt="Imagen del producto" width="60" height="60"></td>
                    <td><?php echo htmlspecialchars($dato['nombre_categoria']) ?></td>
                    <td>
                      <form method="POST" action="">
                      
                      <?php if ($_SESSION["nivel_rol"] >= 2 && tieneAcceso(3, 'editar')): ?>
                      <button type="button" class="btn btn-primary btn-s modificar" 
                          onclick="abrirModalModificar(this)" <?php echo ($dato['estatus'] == 2) ? 'disabled' : ''; ?>> 
                          <i class="fas fa-pencil-alt" title="Editar"></i>
                      </button>
                       <?php endif; ?>
                       
                      <?php if ($_SESSION["nivel_rol"] >= 2 && tieneAcceso(3, 'eliminar')): ?>
                        <button type="button" class="btn btn-danger btn-s eliminar"
                              onclick="eliminarproducto(<?php echo $dato['id_producto']; ?>)" <?php echo ($dato['estatus'] == 2) ? 'disabled' : ''; ?>>
                              <i class="fas fa-trash-alt" title="Eliminar"></i>
                          </button>
                      <?php endif; ?>

                        <button type="button" class="btn btn-s btn-info ver-detalles" title="Click para ver detalles">
                          <i class="fa fa-eye"></i>
                        </button>

                          <?php if ($_SESSION["nivel_rol"] >= 2 && tieneAcceso(3, 'especial')): ?>
                            <button type="button" class="btn btn-warning text-light btn-desactivar" 
                                onclick="cambiarEstatusProducto(<?php echo $dato['id_producto']; ?>, <?php echo $dato['estatus']; ?>)">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                            </button>
                        <?php endif; ?>

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
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content modal-producto">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTitle">
            <i class="fas fa-pump-soap"></i>
            Registrar Producto
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <form id="u" autocomplete="off" enctype='multipart/form-data'>
            <input type="hidden" id="id_producto" name="id_producto" value="" />   
            <input type="hidden" id="imagenActual" name="imagenActual" value="" />
            <input type="hidden" id="accion" name="accion" value="registrar" />

            <!-- Sección: Datos Básicos del Producto -->
            <div class="seccion-formulario">
              <h6><i class="fas fa-info-circle"></i> Datos Básicos</h6>
              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="nombre" class="form-label">Nombre del producto</label>
                  <input class="form-control" type="text" id="nombre" name="nombre" placeholder="Ingrese el nombre del producto" />
                  <span id="snombre" class="error-message"></span>
                </div>
                <div class="col-md-6">
                  <label for="marca" class="form-label">Marca del producto</label>
                  <input class="form-control" type="text" id="marca" name="marca" placeholder="Ingrese la marca del producto" />
                  <span id="smarca" class="error-message"></span>
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-12">
                  <label for="descripcion" class="form-label">Descripción</label>
                  <textarea class="form-control" id="descripcion" name="descripcion" placeholder="Escribe una descripción detallada del producto" rows="3" required></textarea>
                  <span id="sdescripcion" class="error-message"></span>
                </div>
              </div>
            </div>

            <!-- Sección: Precios y Cantidades -->
            <div class="seccion-formulario">
              <h6><i class="fas fa-dollar-sign"></i> Precios y Cantidades</h6>
              <div class="row mb-3">
                <div class="col-md-4">
                  <label for="cantidad_mayor" class="form-label">Cantidad al mayor</label>
                  <input class="form-control" type="number" id="cantidad_mayor" name="cantidad_mayor" placeholder="Ej: 10" min="1" />
                  <span id="scantidad_mayor" class="error-message"></span>
                </div>
                 <div class="col-md-4">
                  <label for="precio_detal" class="form-label">Precio al detal</label>
                  <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input class="form-control" type="number" id="precio_detal" name="precio_detal" placeholder="0.00" step="0.01" min="0" />
                  </div>
                  <span id="sprecio_detal" class="error-message"></span>
                </div>
                <div class="col-md-4">
                  <label for="precio_mayor" class="form-label">Precio al mayor</label>
                  <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input class="form-control" type="number" id="precio_mayor" name="precio_mayor" placeholder="0.00" step="0.01" min="0" />
                  </div>
                  <span id="sprecio_mayor" class="error-message"></span>
                </div>
              </div>
            </div>

            <!-- Sección: Control de Stock -->
            <div class="seccion-formulario">
              <h6><i class="fas fa-boxes"></i> Control de Stock</h6>
              <div class="row mb-3">
                <div class="col-md-4">
                  <label for="stock_maximo" class="form-label">Stock máximo</label>
                  <input class="form-control" type="number" id="stock_maximo" name="stock_maximo" placeholder="Ej: 100" min="1" />
                  <span id="sstock_maximo" class="error-message"></span>
                </div>
                <div class="col-md-4">
                  <label for="stock_minimo" class="form-label">Stock mínimo</label>
                  <input class="form-control" type="number" id="stock_minimo" name="stock_minimo" placeholder="Ej: 10" min="0" />
                  <span id="sstock_minimo" class="error-message"></span>
                </div>
                <div class="col-md-4">
                  <label for="categoria" class="form-label">Categoría</label>
                  <select class="form-select" name="categoria" id="categoria" required>
                    <option disabled selected>Seleccione una Categoría</option>
                    <?php foreach ($categoria as $cat) { ?>
                      <option value="<?php echo htmlspecialchars($cat['id_categoria']); ?>"> <?php echo htmlspecialchars($cat['nombre']); ?> </option>
                    <?php } ?>
                  </select>
                  <span id="scategoria" class="error-message"></span>
                </div>
              </div>
            </div>

            <!-- Sección: Imagen del Producto -->
            <div class="seccion-formulario">
              <h6><i class="fas fa-image"></i> Imagen del Producto</h6>
              <div class="row">
                <div class="col-md-12">
                  <div class="area-imagen">
                    <label for="archivo">
                      <i class="fas fa-cloud-upload-alt fa-2x mb-3" style="color: #f6c5b4;"></i>
                      <br>
                      <strong>Click aquí para subir la foto del producto (Formato: .png,.jpg,.jpeg,.webp)</strong>
                      <br>
                      <img src="assets/img/logo.PNG" id="imagen" class="img-fluid rounded mt-3" style="max-width: 200px; max-height: 200px; object-fit: cover;">
                    </label>
                    <input id="archivo" type="file" style="display:none" accept=".png,.jpg,.jpeg,.webp" name="imagenarchivo" />
                  </div>
                </div>
              </div>
            </div>

            <!-- Botones de Acción -->
            <div class="text-center mt-4">
              <button type="button" class="btn btn-modern btn-guardar me-3" id="btnEnviar">
                <i class="fas fa-save me-2"></i>Guardar Producto
              </button>
              <button type="reset" class="btn btn-modern btn-limpiar" id="btnLimpiar">
                <i class="fas fa-eraser me-2"></i>Limpiar Formulario
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
 </div>
  <?php include 'complementos/footer.php' ?>
  <script src="assets/js/demo/datatables-demo.js"></script>

  <script src="assets/js/producto.js"></script>

  <!-- Modal de Detalles del Producto -->
  <div class="modal fade" id="modalDetallesProducto" tabindex="-1" aria-labelledby="tituloModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header table-color">
          <h5 class="modal-title text-white" id="tituloModal">Detalles del Producto</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <!-- Sección: Información General -->
          <div class="mb-4">
            <h6 class="mb-3">Información General</h6>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label class="form-label fw-bold">Nombre del Producto:</label>
                  <p class="form-control-static" id="modal-nombre-producto"></p>
                </div>
              </div>
            </div>
          </div>

          <!-- Sección: Información de Ventas al Mayor -->
          <div class="mb-4">
            <h6 class="mb-3">Información de Ventas al Mayor</h6>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="form-label fw-bold">Cantidad mínima al mayor:</label>
                  <p class="form-control-static" id="modal-cantidad-mayor"></p>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="form-label fw-bold">Precio al Mayor:</label>
                  <p class="form-control-static" id="modal-precio-mayor"></p>
                </div>
              </div>
            </div>
          </div>

          <!-- Sección: Control de Inventario -->
          <div class="mb-4">
            <h6 class="mb-3">Control de Inventario</h6>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="form-label fw-bold">Stock Máximo:</label>
                  <p class="form-control-static" id="modal-stock-maximo"></p>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="form-label fw-bold">Stock Mínimo:</label>
                  <p class="form-control-static" id="modal-stock-minimo"></p>
                </div>
              </div>
            </div>
          </div>
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
