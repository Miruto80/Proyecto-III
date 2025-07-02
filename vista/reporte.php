<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'complementos/head.php'; ?>
  <title> Reporte | LoveMakeup </title>
   <style>
        .report-card {
            transition: transform 0.3s ease;
            border-radius: 15px;
            overflow: hidden;
            border: none;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .report-card:hover {
            transform: translateY(-5px);
        }
        .card-img-container {
            height: 180px;
            overflow: hidden;
        }
        .card-img-top {
            object-fit: cover;
            height: 100%;
            width: 100%;
        }
        .card-body {
            padding: 1.5rem;
        }
        .report-btn {
            border-radius: 8px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s;
        }
        .report-btn:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
        }
    </style>
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
            <!-- CABECERA -->
            <div class="card-header pb-0">
              <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h4 class="mb-0">
                  <i class="fa-solid fa-file-pdf" style="color: #f6c5b4;"></i>
                  Generar Reporte
                </h4>
                <button id="btnAyuda" class="btn btn-info">
                  <i class="fas fa-info-circle"></i> Ayuda
                </button>
              </div>
            </div>

              <!-- BOTONES DE REPORTES -->
<div class="card-body">
          
        <div class="row g-4">
            <!-- Card Compras -->
            <div class="col-md-6 col-lg-3">
                <div id="cardCompra" class="report-card h-100 d-flex flex-column">
                    <div class="card-img-container">
                        <img src="https://placehold.co/600x400/f6c5b4/FFFFFF?text=Compra" class="card-img-top" alt="Reporte gráfico de niveles de inventario con productos de maquillaje organizados">
                    </div>
                    <div class="card-body flex-grow-1">
                        <h5 class="card-title fw-bold">Reporte de Compras</h5>
                        <p class="card-text text-secondary">-</p>
                    </div>
                    <div class="card-footer bg-transparent border-0 pb-3 pt-0">
                        <button class="btn btn-primary w-100 report-btn py-2" data-bs-toggle="modal" data-bs-target="#modalCompra">
                           <i class="fas fa-file-invoice-dollar me-2"></i> Generar
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Card Productos --> 
            <div class="col-md-6 col-lg-3">
                <div id="cardProducto" class="report-card h-100 d-flex flex-column">
                    <div class="card-img-container">
                        <img src="https://placehold.co/600x400/d67888/FFFFFF?text=Producto" class="card-img-top" alt="Vista de productos de maquillaje organizados por categorías con precios visibles">
                    </div>
                    <div class="card-body flex-grow-1">
                        <h5 class="card-title fw-bold">Reporte de Productos</h5>
                        <p class="card-text text-secondary">-</p>
                    </div>
                    <div class="card-footer bg-transparent border-0 pb-3 pt-0">
                        <button class="btn btn-primary w-100 report-btn py-2" data-bs-toggle="modal" data-bs-target="#modalProducto">
                           <i class="fas fa-boxes me-2"></i> Generar
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Card Ventas -->
            <div class="col-md-6 col-lg-3">
                <div id="cardVentas" class="report-card h-100 d-flex flex-column">
                    <div class="card-img-container">
                        <img src="https://placehold.co/600x400/fc91a3/000000?text=Ventas" class="card-img-top" alt="Gráfico de crecimiento de ventas de maquillaje con tendencia alcista">
                    </div>
                    <div class="card-body flex-grow-1">
                        <h5 class="card-title fw-bold">Reporte de Ventas</h5>
                        <p class="card-text text-secondary">-</p>
                    </div>
                    <div class="card-footer bg-transparent border-0 pb-3 pt-0">
                        <button class="btn btn-primary w-100 report-btn py-2" data-bs-toggle="modal" data-bs-target="#modalVenta">
                             <i class="fas fa-chart-line me-2"></i> Generar
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Card Pedidos Web -->
            <div class="col-md-6 col-lg-3">
                <div id="cardPedidoWeb" class="report-card h-100 d-flex flex-column">
                    <div class="card-img-container">
                        <img src="https://placehold.co/600x400/7f7f7f/FFFFFF?text=Pedidos+Web" class="card-img-top" alt="Dashboard digital mostrando pedidos online de productos de belleza">
                    </div>
                    <div class="card-body flex-grow-1">
                        <h5 class="card-title fw-bold">Reporte Web</h5>
                        <p class="card-text text-secondary">-</p>
                    </div>
                    <div class="card-footer bg-transparent border-0 pb-3 pt-0">
                        <button class="btn btn-primary w-100 report-btn py-2" data-bs-toggle="modal" data-bs-target="#modalPedidoWeb">
                             <i class="fas fa-shopping-cart me-2"></i> Generar
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
<!-- Modal Compras -->
<div class="modal fade" id="modalCompra" tabindex="-1">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Reporte Compras</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form
        class="report-form"
        method="post"
        action="?pagina=reporte&accion=compra"
        target="_blank"
      >
        <div class="modal-body">
          <div class="row g-2 mb-3">
            <div class="col-6">
              <label class="form-label">Fecha Inicio</label>
              <input
                type="date"
                name="f_start"
                class="form-control"
                max="<?= date('Y-m-d') ?>"
              >
            </div>
            <div class="col-6">
              <label class="form-label">Fecha Fin</label>
              <input
                type="date"
                name="f_end"
                class="form-control"
                max="<?= date('Y-m-d') ?>"
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
          <p class="text-center">¿Generar listado de compras?</p>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-danger">GENERAR PDF</button>
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
      <form
        class="report-form"
        method="post"
        action="?pagina=reporte&accion=producto"
        target="_blank"
      >
        <!-- Sin input reportType -->
        <div class="modal-body">
          <div class="row g-2 mb-3">
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
          <button type="submit" class="btn btn-danger">GENERAR PDF</button>
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
<!-- Modal Ventas -->
<div class="modal fade" id="modalVenta" tabindex="-1">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Reporte Ventas</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form
        class="report-form"
        method="post"
        action="?pagina=reporte&accion=venta"
        target="_blank"
      >
        <div class="modal-body">
          <div class="row g-2 mb-3">
            <div class="col-6">
              <label class="form-label">Fecha Inicio</label>
              <input
                type="date"
                name="f_start"
                class="form-control"
                max="<?= date('Y-m-d') ?>"
              >
            </div>
            <div class="col-6">
              <label class="form-label">Fecha Fin</label>
              <input
                type="date"
                name="f_end"
                class="form-control"
                max="<?= date('Y-m-d') ?>"
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
          <p class="text-center">¿Generar listado de ventas?</p>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger">GENERAR PDF</button>
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
<!-- Modal Pedido Web -->
<div class="modal fade" id="modalPedidoWeb" tabindex="-1">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Reporte Pedidos Web</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form
        class="report-form"
        method="post"
        action="?pagina=reporte&accion=pedidoWeb"
        target="_blank"
      >
        <div class="modal-body">
          <div class="row g-2 mb-3">
            <div class="col-6">
              <label class="form-label">Fecha Inicio</label>
              <input
                type="date"
                name="f_start"
                class="form-control"
                max="<?= date('Y-m-d') ?>"
              >
            </div>
            <div class="col-6">
              <label class="form-label">Fecha Fin</label>
              <input
                type="date"
                name="f_end"
                class="form-control"
                max="<?= date('Y-m-d') ?>"
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
          <p class="text-center">¿Generar listado de pedidos web?</p>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger">
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

 

  <!-- Cargamos Driver.js para Admin (3) y Asesora (2) -->
  <?php if(in_array($_SESSION['nivel_rol'], [2,3], true)): ?>
    <link   rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/driver.js@1.0.7/dist/driver.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/driver.js@1.0.7/dist/driver.min.js"></script>
  <?php endif; ?>

  <script src="assets/js/reporte.js"></script>
</body>
</html>
