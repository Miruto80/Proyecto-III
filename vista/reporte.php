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
        
        /* Estilos para filtros avanzados */
        .filtros-avanzados {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            border-left: 4px solid #f6c5b4;
        }
        
        .preset-fechas {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
            margin-bottom: 10px;
        }
        
        .preset-btn {
            font-size: 12px;
            padding: 4px 8px;
            border-radius: 4px;
            border: 1px solid #ddd;
            background: white;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .preset-btn:hover {
            background: #f6c5b4;
            border-color: #f6c5b4;
        }
        
        .preset-btn.active {
            background: #f6c5b4;
            border-color: #f6c5b4;
            color: white;
        }
        
        .filtro-grupo {
            background: white;
            border-radius: 6px;
            padding: 12px;
            margin-bottom: 10px;
            border: 1px solid #e9ecef;
        }
        
        .filtro-grupo h6 {
            color: #495057;
            font-size: 14px;
            margin-bottom: 8px;
            font-weight: 600;
        }
        
        .rango-montos {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        
        .rango-montos .form-control {
            flex: 1;
        }
        
        .toggle-filtros {
            background: #f6c5b4;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .toggle-filtros:hover {
            background: #e8a87c;
        }
        
        .toggle-filtros i {
            margin-right: 5px;
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
          <div class="card mb-4 div-oscuro-2">
            <!-- CABECERA -->
            <div class="card-header pb-0 div-oscuro-2">
              <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h4 class="mb-0 texto-quinto">
                  <i class="fa-solid fa-file-pdf me-2" style="color: #f6c5b4;"></i>
                  Generar Reporte
                </h4>
                <div>
                  <button id="btnAyudaRapida" class="btn btn-outline-info">
                    <i class="fas fa-question-circle"></i> Ayuda Rápida
                  </button>
                  <button id="btnAyuda" class="btn btn-info ms-2">
                    <i class="fas fa-info-circle"></i> Tutorial Completo
                  </button>
                </div>
              </div>
            </div>

              <!-- BOTONES DE REPORTES -->
<div class="card-body">
          
        <div class="row g-4">
            <!-- Card Compras -->
            <div class="col-md-6 col-lg-3">
                <div id="cardCompra" class="report-card h-100 d-flex flex-column card-m">
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
                <div id="cardProducto" class="report-card h-100 d-flex flex-column card-m">
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
                <div id="cardVentas" class="report-card h-100 d-flex flex-column card-m">
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
                <div id="cardPedidoWeb" class="report-card h-100 d-flex flex-column card-m">
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
<div class="modal fade" id="modalCompra" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content bg-s">
      <div class="modal-header">
        <h5 class="modal-title texto-secundario"><i class="fas fa-file-invoice-dollar me-2"></i>Reporte de Compras</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"
                style="font-size: 1.2em; opacity: 0.8; transition: all 0.3s ease;"
                onmouseover="this.style.opacity='1'; this.style.transform='scale(1.1)'"
                onmouseout="this.style.opacity='0.8'; this.style.transform='scale(1)'"></button>
      </div>
      <form
        class="report-form"
        method="post"
        action="?pagina=reporte&accion=compra"
        target="_blank"
      >
        <div class="modal-body">
          <!-- Filtros Básicos -->
          <div class="filtro-grupo card-m">
            <h6 class="texto-quinto"><i class="fas fa-calendar me-2"></i>Rango de Fechas 
              <i class="fas fa-question-circle text-info ms-1" 
                 data-bs-toggle="tooltip" 
                 data-bs-html="true" 
                 title="<strong>📅 Filtros de Fechas:</strong><br>• <strong>Sin fechas:</strong> Reporte GENERAL COMPLETO<br>• <strong>Solo inicio:</strong> Desde esa fecha hasta hoy<br>• <strong>Solo fin:</strong> Hasta esa fecha<br>• <strong>Ambas:</strong> Rango específico<br>• <strong>Personalizado:</strong> Limpia para elegir manualmente"></i>
            </h6>
            <div class="preset-fechas">
              <button type="button" class="preset-btn" data-preset="hoy">Hoy</button>
              <button type="button" class="preset-btn" data-preset="ayer">Ayer</button>
              <button type="button" class="preset-btn" data-preset="semana">Esta Semana</button>
              <button type="button" class="preset-btn" data-preset="mes">Este Mes</button>
              <button type="button" class="preset-btn" data-preset="personalizado">Personalizado</button>
            </div>
            <div class="row g-2">
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
            </div>
          </div>

          <!-- Filtros Avanzados -->
          <button type="button" class="toggle-filtros w-100 mb-3" onclick="toggleFiltrosAvanzados('compra')">
            <i class="fas fa-filter"></i>Filtros Avanzados
          </button>
          
          <div id="filtrosAvanzadosCompra" class="filtros-avanzados card-m" style="display: none;">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Proveedor 
                  <i class="fas fa-question-circle text-info ms-1" 
                     data-bs-toggle="tooltip" 
                     data-bs-html="true" 
                     title="<strong>🏢 Proveedores:</strong><br>• <strong>Vacío:</strong> Compras de TODOS los proveedores<br>• <strong>Seleccionado:</strong> Solo compras de ese proveedor específico"></i>
                </label>
                <select name="f_prov" class="form-select">
                  <option value="">— Todos —</option>
                  <?php foreach($proveedores_lista as $prov): ?>
                    <option value="<?= $prov['id_proveedor'] ?>">
                      <?= htmlspecialchars($prov['nombre']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label">Producto 
                  <i class="fas fa-question-circle text-info ms-1" 
                     data-bs-toggle="tooltip" 
                     data-bs-html="true" 
                     title="<strong>🛍️ Productos:</strong><br>• <strong>Vacío:</strong> Compras de TODOS los productos<br>• <strong>Seleccionado:</strong> Solo compras que incluyan ese producto"></i>
                </label>
                <select name="f_id" class="form-select">
                  <option value="">— Todos —</option>
                  <?php foreach($productos_lista as $p): ?>
                    <option value="<?= $p['id_producto'] ?>">
                      <?= htmlspecialchars($p['nombre']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label">Categoría 
                  <i class="fas fa-question-circle text-info ms-1" 
                     data-bs-toggle="tooltip" 
                     data-bs-html="true" 
                     title="<strong>📂 Categorías:</strong><br>• <strong>Vacío:</strong> Compras de TODAS las categorías<br>• <strong>Seleccionado:</strong> Solo compras de productos de esa categoría"></i>
                </label>
                <select name="f_cat" class="form-select">
                  <option value="">— Todas —</option>
                  <?php foreach($categorias_lista as $c): ?>
                    <option value="<?= $c['id_categoria'] ?>">
                      <?= htmlspecialchars($c['nombre']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label">Rango de Montos 
                  <i class="fas fa-question-circle text-info ms-1" 
                     data-bs-toggle="tooltip" 
                     data-bs-html="true" 
                     title="<strong>💰 Montos:</strong><br>• <strong>Vacíos:</strong> Compras de CUALQUIER monto<br>• <strong>Solo mínimo:</strong> Compras desde ese monto en adelante<br>• <strong>Solo máximo:</strong> Compras hasta ese monto<br>• <strong>Ambos:</strong> Rango específico de montos"></i>
                </label>
                <div class="rango-montos">
                  <input type="number" name="monto_min" class="form-control" placeholder="Mínimo" step="0.01" min="0">
                  <span>-</span>
                  <input type="number" name="monto_max" class="form-control" placeholder="Máximo" step="0.01" min="0">
                </div>
              </div>
            </div>
          </div>
          
          <p class="text-center text-muted texto-secundario">¿Generar listado de compras con los filtros seleccionados?</p>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-danger">
            <i class="fas fa-file-pdf me-2"></i>GENERAR PDF
          </button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            Cerrar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Productos -->
<div class="modal fade" id="modalProducto" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content bg-s">
      <div class="modal-header">
        <h5 class="modal-title texto-secundario"><i class="fas fa-boxes me-2"></i>Reporte de Productos</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"
                style="font-size: 1.2em; opacity: 0.8; transition: all 0.3s ease;"
                onmouseover="this.style.opacity='1'; this.style.transform='scale(1.1)'"
                onmouseout="this.style.opacity='0.8'; this.style.transform='scale(1)'"></button>
      </div>
      <form
        class="report-form"
        method="post"
        action="?pagina=reporte&accion=producto"
        target="_blank"
      >
        <div class="modal-body">
          <!-- Filtros Básicos -->
          <div class="filtro-grupo card-m">
            <h6 class="texto-quinto"><i class="fas fa-filter me-2"></i>Filtros de Productos 
              <i class="fas fa-question-circle text-info ms-1" 
                 data-bs-toggle="tooltip" 
                 data-bs-html="true" 
                 title="<strong>📦 Filtros de Productos:</strong><br>• <strong>Categoría:</strong> Filtra productos por tipo<br>• <strong>Producto:</strong> Busca producto específico<br>• <strong>Vacíos:</strong> Muestra todos los productos"></i>
            </h6>
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Categoría 
                  <i class="fas fa-question-circle text-info ms-1" 
                     data-bs-toggle="tooltip" 
                     data-bs-html="true" 
                     title="<strong>📂 Categorías:</strong><br>• <strong>Vacío:</strong> Productos de TODAS las categorías<br>• <strong>Seleccionado:</strong> Solo productos de esa categoría específica<br>• <strong>Filtrado:</strong> Organiza productos por tipo (maquillaje, cuidado, etc.)"></i>
                </label>
                <select name="f_cat" class="form-select" id="categoriaProducto">
                  <option value="">— Todas —</option>
                  <?php foreach($categorias_lista as $c): ?>
                    <option value="<?= $c['id_categoria'] ?>">
                      <?= htmlspecialchars($c['nombre']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label">Producto 
                  <i class="fas fa-question-circle text-info ms-1" 
                     data-bs-toggle="tooltip" 
                     data-bs-html="true" 
                     title="<strong>🛍️ Productos:</strong><br>• <strong>Vacío:</strong> TODOS los productos del inventario<br>• <strong>Seleccionado:</strong> Solo ese producto específico<br>• <strong>Filtrado por categoría:</strong> Se actualiza según la categoría seleccionada"></i>
                </label>
                <select name="f_id" class="form-select" id="productoFiltrado">
                  <option value="">— Todos —</option>
                  <?php foreach($productos_lista as $p): ?>
                    <option value="<?= $p['id_producto'] ?>" data-categoria="<?= $p['id_categoria'] ?>">
                      <?= htmlspecialchars($p['nombre']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>

          <!-- Filtros Avanzados -->
          <button type="button" class="toggle-filtros w-100 mb-3" onclick="toggleFiltrosAvanzados('producto')">
            <i class="fas fa-filter"></i>Filtros Avanzados
          </button>
          
          <div id="filtrosAvanzadosProducto" class="filtros-avanzados card-m" style="display: none;">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Proveedor 
                  <i class="fas fa-question-circle text-info ms-1" 
                     data-bs-toggle="tooltip" 
                     data-bs-html="true" 
                     title="<strong>🏢 Proveedores:</strong><br>• <strong>Vacío:</strong> Productos de TODOS los proveedores<br>• <strong>Seleccionado:</strong> Solo productos comprados a ese proveedor<br>• <strong>Historial:</strong> Basado en últimas compras registradas"></i>
                </label>
                <select name="f_prov" class="form-select">
                  <option value="">— Todos —</option>
                  <?php foreach($proveedores_lista as $prov): ?>
                    <option value="<?= $prov['id_proveedor'] ?>">
                      <?= htmlspecialchars($prov['nombre']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label">Estado del Producto 
                  <i class="fas fa-question-circle text-info ms-1" 
                     data-bs-toggle="tooltip" 
                     data-bs-html="true" 
                     title="<strong>⚙️ Estado:</strong><br>• <strong>Todos:</strong> Productos disponibles y no disponibles<br>• <strong>Disponible:</strong> Solo productos activos para venta<br>• <strong>No disponible:</strong> Productos desactivados o descontinuados"></i>
                </label>
                <select name="estado" class="form-select">
                  <option value="">— Todos —</option>
                  <option value="1">Disponible</option>
                  <option value="0">No disponible</option>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label">Stock Mínimo 
                  <i class="fas fa-question-circle text-info ms-1" 
                     data-bs-toggle="tooltip" 
                     data-bs-html="true" 
                     title="<strong>📦 Stock Mínimo:</strong><br>• <strong>Vacío:</strong> Productos con CUALQUIER cantidad en stock<br>• <strong>Con valor:</strong> Solo productos con esa cantidad mínima o más<br>• <strong>Inventario:</strong> Útil para identificar productos con bajo stock"></i>
                </label>
                <input type="number" name="stock_min" class="form-control" placeholder="Cantidad mínima" min="0">
              </div>
              <div class="col-md-6">
                <label class="form-label">Stock Máximo 
                  <i class="fas fa-question-circle text-info ms-1" 
                     data-bs-toggle="tooltip" 
                     data-bs-html="true" 
                     title="<strong>📦 Stock Máximo:</strong><br>• <strong>Vacío:</strong> Productos con CUALQUIER cantidad en stock<br>• <strong>Con valor:</strong> Solo productos con esa cantidad máxima o menos<br>• <strong>Rango:</strong> Combinar con Stock Mínimo para un rango específico"></i>
                </label>
                <input type="number" name="stock_max" class="form-control" placeholder="Cantidad máxima" min="0">
              </div>
              <div class="col-md-12">
                <label class="form-label">Rango de Precios 
                  <i class="fas fa-question-circle text-info ms-1" 
                     data-bs-toggle="tooltip" 
                     data-bs-html="true" 
                     title="<strong>💰 Precios:</strong><br>• <strong>Vacíos:</strong> Productos de CUALQUIER precio<br>• <strong>Solo mínimo:</strong> Productos desde ese precio en adelante<br>• <strong>Solo máximo:</strong> Productos hasta ese precio<br>• <strong>Ambos:</strong> Rango específico de precios"></i>
                </label>
                <div class="rango-montos">
                  <input type="number" name="precio_min" class="form-control" placeholder="Mínimo" step="0.01" min="0">
                  <span>-</span>
                  <input type="number" name="precio_max" class="form-control" placeholder="Máximo" step="0.01" min="0">
                </div>
              </div>
            </div>
          </div>
          
          <p class="text-center text-muted texto-secundario">¿Generar listado de productos con los filtros seleccionados?</p>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger">
            <i class="fas fa-file-pdf me-2"></i>GENERAR PDF
          </button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            Cerrar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Ventas -->
<div class="modal fade" id="modalVenta" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content bg-s">
      <div class="modal-header">
        <h5 class="modal-title texto-secundario"><i class="fas fa-chart-line me-2"></i>Reporte de Ventas</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"
                style="font-size: 1.2em; opacity: 0.8; transition: all 0.3s ease;"
                onmouseover="this.style.opacity='1'; this.style.transform='scale(1.1)'"
                onmouseout="this.style.opacity='0.8'; this.style.transform='scale(1)'"></button>
      </div>
      <form
        class="report-form"
        method="post"
        action="?pagina=reporte&accion=venta"
        target="_blank"
      >
        <div class="modal-body">
          <!-- Filtros Básicos -->
          <div class="filtro-grupo card-m">
            <h6 class="texto-quinto"><i class="fas fa-calendar me-2"></i>Rango de Fechas 
              <i class="fas fa-question-circle text-info ms-1" 
                 data-bs-toggle="tooltip" 
                 data-bs-html="true" 
                 title="<strong>📅 Filtros de Fechas:</strong><br>• <strong>Sin fechas:</strong> Reporte GENERAL COMPLETO<br>• <strong>Solo inicio:</strong> Desde esa fecha hasta hoy<br>• <strong>Solo fin:</strong> Hasta esa fecha<br>• <strong>Ambas:</strong> Rango específico<br>• <strong>Personalizado:</strong> Limpia para elegir manualmente"></i>
            </h6>
            <div class="preset-fechas">
              <button type="button" class="preset-btn" data-preset="hoy">Hoy</button>
              <button type="button" class="preset-btn" data-preset="ayer">Ayer</button>
              <button type="button" class="preset-btn" data-preset="semana">Esta Semana</button>
              <button type="button" class="preset-btn" data-preset="mes">Este Mes</button>
              <button type="button" class="preset-btn" data-preset="personalizado">Personalizado</button>
            </div>
            <div class="row g-2">
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
            </div>
          </div>

          <!-- Filtros Avanzados -->
          <button type="button" class="toggle-filtros w-100 mb-3" onclick="toggleFiltrosAvanzados('venta')">
            <i class="fas fa-filter"></i>Filtros Avanzados
          </button>
          
          <div id="filtrosAvanzadosVenta" class="filtros-avanzados card-m" style="display: none;">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Producto 
                  <i class="fas fa-question-circle text-info ms-1" 
                     data-bs-toggle="tooltip" 
                     data-bs-html="true" 
                     title="<strong>🛍️ Productos:</strong><br>• <strong>Vacío:</strong> Ventas de TODOS los productos<br>• <strong>Seleccionado:</strong> Solo ventas que incluyan ese producto<br>• <strong>Tienda física:</strong> Solo transacciones presenciales"></i>
                </label>
                <select name="f_id" class="form-select">
                  <option value="">— Todos —</option>
                  <?php foreach($productos_lista as $p): ?>
                    <option value="<?= $p['id_producto'] ?>">
                      <?= htmlspecialchars($p['nombre']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label">Categoría 
                  <i class="fas fa-question-circle text-info ms-1" 
                     data-bs-toggle="tooltip" 
                     data-bs-html="true" 
                     title="<strong>📂 Categorías:</strong><br>• <strong>Vacío:</strong> Ventas de TODAS las categorías<br>• <strong>Seleccionado:</strong> Solo ventas con productos de esa categoría<br>• <strong>Agrupación:</strong> Organiza productos por tipo"></i>
                </label>
                <select name="f_cat" class="form-select">
                  <option value="">— Todas —</option>
                  <?php foreach($categorias_lista as $c): ?>
                    <option value="<?= $c['id_categoria'] ?>">
                      <?= htmlspecialchars($c['nombre']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label">Método de Pago 
                  <i class="fas fa-question-circle text-info ms-1" 
                     data-bs-toggle="tooltip" 
                     data-bs-html="true" 
                     title="<strong>💳 Métodos de Pago (Tienda Física):</strong><br>• <strong>Vacío:</strong> TODOS los métodos<br>• <strong>Efectivo:</strong> Dinero en efectivo<br>• <strong>Transferencia:</strong> Transferencias bancarias<br>• <strong>Pago Móvil:</strong> Transferencias por teléfono"></i>
                </label>
                <select name="f_mp" class="form-select">
                  <option value="">— Todos —</option>
                  <option value="1">Efectivo</option>
                  <option value="2">Transferencia</option>
                  <option value="3">Pago Móvil</option>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label">Rango de Montos 
                  <i class="fas fa-question-circle text-info ms-1" 
                     data-bs-toggle="tooltip" 
                     data-bs-html="true" 
                     title="<strong>💵 Montos en USD:</strong><br>• <strong>Vacíos:</strong> Ventas de CUALQUIER monto<br>• <strong>Solo mínimo:</strong> Ventas desde ese monto en adelante<br>• <strong>Solo máximo:</strong> Ventas hasta ese monto<br>• <strong>Ambos:</strong> Rango específico de montos<br>• <strong>Moneda:</strong> Todos los montos en dólares americanos"></i>
                </label>
                <div class="rango-montos">
                  <input type="number" name="monto_min" class="form-control" placeholder="Mínimo" step="0.01" min="0">
                  <span>-</span>
                  <input type="number" name="monto_max" class="form-control" placeholder="Máximo" step="0.01" min="0">
                </div>
              </div>
            </div>
          </div>
          
          <p class="text-center text-muted texto-secundario">¿Generar listado de ventas con los filtros seleccionados?</p>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger">
            <i class="fas fa-file-pdf me-2"></i>GENERAR PDF
          </button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            Cerrar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Pedido Web -->
<div class="modal fade" id="modalPedidoWeb" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content bg-s">
      <div class="modal-header">
        <h5 class="modal-title texto-secundario"><i class="fas fa-shopping-cart me-2"></i>Reporte de Pedidos Web</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"
                style="font-size: 1.2em; opacity: 0.8; transition: all 0.3s ease;"
                onmouseover="this.style.opacity='1'; this.style.transform='scale(1.1)'"
                onmouseout="this.style.opacity='0.8'; this.style.transform='scale(1)'"></button>
      </div>
      <form
        class="report-form"
        method="post"
        action="?pagina=reporte&accion=pedidoWeb"
        target="_blank"
      >
        <div class="modal-body">
          <!-- Filtros Básicos -->
          <div class="filtro-grupo card-m">
            <h6 class="texto-quinto"><i class="fas fa-calendar me-2"></i>Rango de Fechas 
              <i class="fas fa-question-circle text-info ms-1" 
                 data-bs-toggle="tooltip" 
                 data-bs-html="true" 
                 title="<strong>📅 Filtros de Fechas:</strong><br>• <strong>Sin fechas:</strong> Reporte GENERAL COMPLETO<br>• <strong>Solo inicio:</strong> Desde esa fecha hasta hoy<br>• <strong>Solo fin:</strong> Hasta esa fecha<br>• <strong>Ambas:</strong> Rango específico<br>• <strong>Personalizado:</strong> Limpia para elegir manualmente"></i>
            </h6>
            <div class="preset-fechas">
              <button type="button" class="preset-btn" data-preset="hoy">Hoy</button>
              <button type="button" class="preset-btn" data-preset="ayer">Ayer</button>
              <button type="button" class="preset-btn" data-preset="semana">Esta Semana</button>
              <button type="button" class="preset-btn" data-preset="mes">Este Mes</button>
              <button type="button" class="preset-btn" data-preset="personalizado">Personalizado</button>
            </div>
            <div class="row g-2">
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
            </div>
          </div>

          <!-- Filtros Avanzados -->
          <button type="button" class="toggle-filtros w-100 mb-3" onclick="toggleFiltrosAvanzados('pedidoWeb')">
            <i class="fas fa-filter"></i>Filtros Avanzados
          </button>
          
          <div id="filtrosAvanzadosPedidoWeb" class="filtros-avanzados card-m" style="display: none;">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Producto 
                  <i class="fas fa-question-circle text-info ms-1" 
                     data-bs-toggle="tooltip" 
                     data-bs-html="true" 
                     title="<strong>🛍️ Productos Web:</strong><br>• <strong>Vacío:</strong> Pedidos con TODOS los productos<br>• <strong>Seleccionado:</strong> Solo pedidos que incluyan ese producto<br>• <strong>Online:</strong> Solo compras realizadas por internet"></i>
                </label>
                <select name="f_id" class="form-select">
                  <option value="">— Todos —</option>
                  <?php foreach($productos_lista as $p): ?>
                    <option value="<?= $p['id_producto'] ?>">
                      <?= htmlspecialchars($p['nombre']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label">Estado del Pedido 
                  <i class="fas fa-question-circle text-info ms-1" 
                     data-bs-toggle="tooltip" 
                     data-bs-html="true" 
                     title="<strong>📊 Estados de Pedidos Web:</strong><br>• <strong>Vacío:</strong> TODOS los estados<br>• <strong>Pago verificado:</strong> Pago confirmado<br>• <strong>Pendiente envío:</strong> Preparando pedido<br>• <strong>En camino:</strong> Pedido enviado<br>• <strong>Entregado:</strong> Recibido por el cliente"></i>
                </label>
                <select name="estado" class="form-select">
                  <option value="">— Todos —</option>
                  <option value="2">Pago verificado</option>
                  <option value="3">Pendiente envío</option>
                  <option value="4">En camino</option>
                  <option value="5">Entregado</option>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label">Método de Pago 
                  <i class="fas fa-question-circle text-info ms-1" 
                     data-bs-toggle="tooltip" 
                     data-bs-html="true" 
                     title="<strong>🌐 Métodos de Pago Web:</strong><br>• <strong>Vacío:</strong> TODOS los métodos web<br>• <strong>Transferencia:</strong> Transferencias bancarias<br>• <strong>Pago Móvil:</strong> Transferencias por teléfono<br>• <strong>Online:</strong> Solo métodos digitales disponibles"></i>
                </label>
                <select name="metodo_pago" class="form-select">
                  <option value="">— Todos —</option>
                  <option value="2">Transferencia</option>
                  <option value="3">Pago Móvil</option>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label">Rango de Montos 
                  <i class="fas fa-question-circle text-info ms-1" 
                     data-bs-toggle="tooltip" 
                     data-bs-html="true" 
                     title="<strong>💵 Montos de Pedidos Web:</strong><br>• <strong>Vacíos:</strong> Pedidos de CUALQUIER monto<br>• <strong>Solo mínimo:</strong> Pedidos desde ese monto en adelante<br>• <strong>Solo máximo:</strong> Pedidos hasta ese monto<br>• <strong>Ambos:</strong> Rango específico de montos<br>• <strong>Online:</strong> Incluye envío y otros cargos web"></i>
                </label>
                <div class="rango-montos">
                  <input type="number" name="monto_min" class="form-control" placeholder="Mínimo" step="0.01" min="0">
                  <span>-</span>
                  <input type="number" name="monto_max" class="form-control" placeholder="Máximo" step="0.01" min="0">
                </div>
              </div>
            </div>
          </div>
          
          <p class="text-center text-muted texto-secundario">¿Generar listado de pedidos web con los filtros seleccionados?</p>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger">
            <i class="fas fa-file-pdf me-2"></i>GENERAR PDF
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
