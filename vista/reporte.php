<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'complementos/head.php'; ?>
  <title> Reporte | LoveMakeup </title>
</head>
<body class="g-sidenav-show bg-gray-100">
  <?php include 'complementos/sidebar.php'; ?>

  <main class="main-content position-relative border-radius-lg">
    <!-- Navbar superior -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl"
         id="navbarBlur" data-scroll="false">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm">
              <a class="opacity-5 text-white" href="#">Bienvenid@</a>
            </li>
            <li class="breadcrumb-item text-sm text-white active" aria-current="page">
              Inicio
            </li>
          </ol>
          <h6 class="font-weight-bolder text-white mb-0">Generar Reporte</h6>
        </nav>
        <?php include 'complementos/nav.php'; ?>
      </div>
    </nav>

    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-12">
          <div class="card mb-4">
            <!-- CABECERA -->
            <div class="card-header pb-0">
              <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h4 class="mb-0">
                  <i class="fa-solid fa-file-pdf" style="color: #f6c5b4;"></i>
                  Generar Reporte
                </h4>
              </div>
            </div>

              <!-- BOTONES DE REPORTES -->
<div class="card-body">
  <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-5">
    <div class="col">
      <button class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#modalCompra">
        Compras
      </button>
    </div>
    <div class="col">
      <button class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#modalProducto">
        Productos
      </button>
    </div>
    <div class="col">
      <button class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#modalVenta">
        Ventas
      </button>
    </div>
    <div class="col">
      <button class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#modalPedidoWeb">
        Pedido Web
      </button>
    </div>
  </div>
</div>

            
            <!-- FIN CABECERA -->
          </div>
        </div>
      </div>
    </div>
    <?php include 'complementos/footer.php'; ?>
  </main>

  <!-- MODALS ====================================== -->

 <?php $hoy = date('Y-m-d'); ?>
<!-- Modal Compras -->
<div class="modal fade" id="modalCompra" tabindex="-1">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Reporte Compras</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form class="report-form" method="post" action="?pagina=reporte" target="_blank">
        <input type="hidden" name="reportType" value="compra">

        <div class="modal-body">
          <!-- mini‐formulario de filtros -->
          <div class="row g-2 mb-3">
            <div class="col-6">
              <label class="form-label">Fecha Inicio</label>
              <input
                type="date"
                name="f_start"
                class="form-control"
                max="<?= $hoy ?>"
              >
            </div>
            <div class="col-6">
              <label class="form-label">Fecha Fin</label>
              <input
                type="date"
                name="f_end"
                class="form-control"
                max="<?= $hoy ?>"
              >
            </div>
            <div class="col-12">
              <label class="form-label">Producto (opcional)</label>
              <select name="f_id" class="form-select">
                <option value="">— Todos —</option>
                <?php foreach($productos_lista as $p): ?>
                  <option value="<?= $p['id_producto'] ?>">
                    <?= htmlspecialchars($p['nombre']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <!-- /mini‐formulario -->

          <p class="text-center">¿Generar listado de compras?</p>
        </div>

        <div class="modal-footer">
          <button type="submit" name="generar" class="btn btn-danger">
            GENERAR PDF
          </button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            Cerrar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>


<?php $hoy = date('Y-m-d'); ?>
<!-- Modal Productos -->
<div class="modal fade" id="modalProducto" tabindex="-1">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Reporte Productos</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form class="report-form" method="post" action="?pagina=reporte" target="_blank">
        <input type="hidden" name="reportType" value="producto">

        <div class="modal-body">
          <div class="row g-2 mb-3">

            <!-- Filtro por Producto -->
            <div class="col-12">
              <label class="form-label">Producto (opcional)</label>
              <select name="f_id" class="form-select">
                <option value="">— Todos —</option>
                <?php foreach($productos_lista as $p): ?>
                  <option value="<?= $p['id_producto'] ?>">
                    <?= htmlspecialchars($p['nombre']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <!-- Filtro por Proveedor -->
            <div class="col-12">
              <label class="form-label">Proveedor (opcional)</label>
              <select name="f_prov" class="form-select">
                <option value="">— Todos —</option>
                <?php foreach($proveedores_lista as $prov): ?>
                  <option value="<?= $prov['id_proveedor'] ?>">
                    <?= htmlspecialchars($prov['nombre']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <!-- Filtro por Categoría -->
            <div class="col-12">
              <label class="form-label">Categoría (opcional)</label>
              <select name="f_cat" class="form-select">
                <option value="">— Todas —</option>
                <?php foreach($categorias_lista as $c): ?>
                  <option value="<?= $c['id_categoria'] ?>">
                    <?= htmlspecialchars($c['nombre']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

          </div>
          <p class="text-center">¿Generar listado de productos?</p>
        </div>

        <div class="modal-footer">
          <button type="submit" name="generar" class="btn btn-danger">
            GENERAR PDF
          </button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            Cerrar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>





<?php $hoy = date('Y-m-d'); ?>
<!-- Modal Ventas -->
<div class="modal fade" id="modalVenta" tabindex="-1">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Reporte Ventas</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form class="report-form" method="post" action="?pagina=reporte" target="_blank">
        <input type="hidden" name="reportType" value="venta">

        <div class="modal-body">
          <!-- mini‐formulario de filtros -->
          <div class="row g-2 mb-3">
            <div class="col-6">
              <label class="form-label">Fecha Inicio</label>
              <input
                type="date"
                name="f_start"
                class="form-control"
                max="<?= $hoy ?>"
              >
            </div>
            <div class="col-6">
              <label class="form-label">Fecha Fin</label>
              <input
                type="date"
                name="f_end"
                class="form-control"
                max="<?= $hoy ?>"
              >
            </div>
            <div class="col-12">
              <label class="form-label">Producto (opcional)</label>
              <select name="f_id" class="form-select">
                <option value="">— Todos —</option>
                <?php foreach($productos_lista as $p): ?>
                  <option value="<?= $p['id_producto'] ?>">
                    <?= htmlspecialchars($p['nombre']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <!-- /mini‐formulario -->

          <p class="text-center">¿Generar listado de ventas?</p>
        </div>

        <div class="modal-footer">
          <button type="submit" name="generar" class="btn btn-danger">
            GENERAR PDF
          </button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            Cerrar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>






<?php $hoy = date('Y-m-d'); ?>
 <!-- Modal Pedido Web -->
<div class="modal fade" id="modalPedidoWeb" tabindex="-1">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Reporte Pedido Web</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form class="report-form" method="post" action="?pagina=reporte" target="_blank">
        <input type="hidden" name="reportType" value="pedidoWeb">

        <div class="modal-body">
          <!-- mini‐formulario de filtros -->
          <div class="row g-2 mb-3">
            <div class="col-6">
              <label class="form-label">Fecha Inicio</label>
              <input
                type="date"
                name="f_start"
                class="form-control"
                max="<?= $hoy ?>"
              >
            </div>
            <div class="col-6">
              <label class="form-label">Fecha Fin</label>
              <input
                type="date"
                name="f_end"
                class="form-control"
                max="<?= $hoy ?>"
              >
            </div>
            <div class="col-12">
              <label class="form-label">Producto (opcional)</label>
              <select name="f_id" class="form-select">
                <option value="">— Todos —</option>
                <?php foreach($productos_lista as $p): ?>
                  <option value="<?= $p['id_producto'] ?>">
                    <?= htmlspecialchars($p['nombre']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <!-- /mini‐formulario -->

          <p class="text-center">¿Generar listado de pedidos web?</p>
        </div>

        <div class="modal-footer">
          <button type="submit" name="generar" class="btn btn-danger">
            GENERAR PDF
          </button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            Cerrar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>



  <script src="assets/js/reporte.js"></script>
</body>
</html>
