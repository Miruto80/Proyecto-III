<!DOCTYPE html>
<html lang="es">

<head>
  <!-- php barra de navegacion-->
  <?php include 'complementos/head.php' ?> 
  <title> Venta | LoveMakeup  </title> 
  <!-- SweetAlert2 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <!-- SweetAlert2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    @media (forced-colors: active) {
      .modal-header .btn-close {
        border: 2px solid currentColor;
      }
    }

    /* Estilos para el indicador de pasos */
    .progress-steps {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2rem;
      position: relative;
    }

    .progress-steps::before {
      content: '';
      position: absolute;
      top: 50%;
      left: 0;
      right: 0;
      height: 2px;
      background-color: #e9ecef;
      z-index: 1;
    }

    .step {
      position: relative;
      z-index: 2;
      display: flex;
      flex-direction: column;
      align-items: center;
      background-color: white;
      padding: 0.5rem;
    }

    .step-number {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background-color: #e9ecef;
      color: #6c757d;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      margin-bottom: 0.5rem;
      transition: all 0.3s ease;
    }

    .step-label {
      font-size: 0.875rem;
      color: #6c757d;
      text-align: center;
      font-weight: 500;
    }

    .step.active .step-number {
      background-color: #007bff;
      color: white;
    }

    .step.active .step-label {
      color: #007bff;
      font-weight: 600;
    }

    .step.completed .step-number {
      background-color: #28a745;
      color: white;
    }

    .step.completed .step-label {
      color: #28a745;
      font-weight: 600;
    }

    /* Animación para las secciones */
    .seccion-venta, .seccion-productos {
      transition: all 0.3s ease;
    }

    .seccion-venta.show, .seccion-productos.show {
      opacity: 1;
      transform: translateY(0);
    }

    .seccion-venta:not(.show), .seccion-productos:not(.show) {
      opacity: 0.5;
      transform: translateY(10px);
    }

    /* Estilos para el contenido de pasos */
    .step-content {
      transition: all 0.3s ease;
    }

    .step-content.active {
      display: block !important;
      opacity: 1;
      transform: translateX(0);
    }

    .step-content:not(.active) {
      display: none !important;
      opacity: 0;
      transform: translateX(20px);
    }

    /* Estilos para los botones de navegación */
    .modal-footer {
      border-top: 1px solid #dee2e6;
      padding: 1rem;
      background-color: #f8f9fa;
    }

    .btn-navigation {
      min-width: 120px;
    }

    /* Estilos para el resumen de confirmación */
    #resumen-venta {
      background-color: #f8f9fa;
      border: 1px solid #dee2e6;
      border-radius: 0.375rem;
      padding: 1.5rem;
    }

    .resumen-item {
      margin-bottom: 1rem;
      padding-bottom: 1rem;
      border-bottom: 1px solid #dee2e6;
    }

    .resumen-item:last-child {
      border-bottom: none;
      margin-bottom: 0;
    }

    .resumen-total {
      font-size: 1.25rem;
      font-weight: bold;
      color: #28a745;
      text-align: center;
      padding: 1rem;
      background-color: #d4edda;
      border-radius: 0.375rem;
      margin-top: 1rem;
    }

    /* Estilos para el resumen completo de venta */
    .resumen-venta-completo {
      max-height: 500px;
      overflow-y: auto;
    }

    .resumen-seccion {
      margin-bottom: 1.5rem;
      border: 1px solid #dee2e6;
      border-radius: 0.375rem;
      overflow: hidden;
    }

    .resumen-titulo {
      background-color: #f8f9fa;
      padding: 0.75rem 1rem;
      margin: 0;
      border-bottom: 1px solid #dee2e6;
      font-weight: 600;
      color: #495057;
    }

    .resumen-titulo i {
      margin-right: 0.5rem;
    }

    .resumen-contenido {
      padding: 1rem;
      background-color: white;
    }

    .resumen-contenido p {
      margin-bottom: 0.5rem;
    }

    .resumen-contenido p:last-child {
      margin-bottom: 0;
    }

    .metodo-pago-item {
      padding: 0.5rem;
      margin-bottom: 0.5rem;
      background-color: #f8f9fa;
      border-radius: 0.25rem;
      border-left: 3px solid #007bff;
    }

    .metodo-pago-item:last-child {
      margin-bottom: 0;
    }

    .resumen-final {
      background-color: #e7f3ff;
      border-color: #b3d9ff;
    }

    .resumen-final .resumen-titulo {
      background-color: #cce7ff;
      color: #0056b3;
    }

    .resumen-validaciones {
      background-color: #fff3cd;
      border-color: #ffeaa7;
    }

    .resumen-validaciones .resumen-titulo {
      background-color: #ffeaa7;
      color: #856404;
    }

    .resumen-validaciones .text-success {
      color: #28a745 !important;
    }

    .resumen-validaciones .text-danger {
      color: #dc3545 !important;
    }

    .resumen-validaciones .text-warning {
      color: #ffc107 !important;
    }

    /* Estilos para la tabla de productos en el resumen */
    .resumen-contenido .table {
      margin-bottom: 0;
    }

    .resumen-contenido .table th,
    .resumen-contenido .table td {
      padding: 0.5rem;
      font-size: 0.875rem;
    }

    .resumen-contenido .table thead th {
      background-color: #f8f9fa;
      border-bottom: 2px solid #dee2e6;
      font-weight: 600;
    }

    /* Estilos para métodos de pago aplicados (tarjetas grises con texto blanco) */
    .metodo-pago-aplicado {
      background: #343a40 !important; /* gris oscuro */
      color: #fff !important;
      border-radius: 8px;
      padding: 1rem;
      margin-bottom: 1rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border: none;
      box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .metodo-pago-aplicado strong,
    .metodo-pago-aplicado .badge,
    .metodo-pago-aplicado .text-muted,
    .metodo-pago-aplicado p {
      color: #fff !important;
    }
    .metodo-pago-aplicado .btn-outline-danger {
      color: #fff;
      border-color: #f6c5b4;
      background: #f6c5b4;
    }
    .metodo-pago-aplicado .btn-outline-danger:hover {
      background: #dc3545;
      border-color: #dc3545;
    }

    .badge-monto-metodo {
      background: #adb5bd !important; /* gris más claro */
      color: #fff !important;
      font-size: 1rem;
      font-weight: 600;
      border-radius: 0.5rem;
      padding: 0.5em 1em;
    }
    /* Fondo gris claro y texto blanco para la alerta de conversión de monedas */
    #info-conversion.alert-info {
      background: #adb5bd !important;
      color: #fff !important;
      border: none;
    }
    #info-conversion .text-success,
    #info-conversion .text-primary,
    #info-conversion .text-warning {
      color: #fff !important;
    }

    .modal-header.header-color {
      display: flex;
      align-items: center;
      justify-content: space-between;
      background: #f6c5b4;
      border-top-left-radius: 0.5rem;
      border-top-right-radius: 0.5rem;
      padding: 1rem 1.5rem 1rem 1.5rem;
    }
    .modal-header .modal-title {
      margin: 0;
      font-weight: 700;
      font-size: 1.3rem;
      color: #fff;
    }
    .modal-header .d-flex.align-items-center {
      gap: 0.5rem;
    }
    .modal-header .btn-link {
      color: #fff;
      text-decoration: none;
      padding: 0.25rem 0.5rem;
      font-size: 1.2rem;
    }
    .modal-header .btn-link:focus {
      outline: none;
      box-shadow: none;
    }
    .modal-header .btn-close {
      filter: invert(1);
      opacity: 0.8;
      margin-left: 0.25rem;
    }
    .modal-header .btn-close:focus {
      box-shadow: none;
      outline: none;
    }
  </style>
</head>

<body class="g-sidenav-show bg-gray-100">
  
<!-- php barra de navegacion-->
<?php include 'complementos/sidebar.php' ?>

<main class="main-content position-relative border-radius-lg ">
<!-- ||| Navbar ||-->
<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur" data-scroll="false">
  <div class="container-fluid py-1 px-3">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="#">Administrar</a></li>
        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Venta</li>
      </ol>
      <h6 class="font-weight-bolder text-white mb-0">Gestionar venta</h6>
    </nav>
<!-- php barra de navegacion-->    
<?php include 'complementos/nav.php' ?>
<!-- |||||||||||||||| LOADER ||||||||||||||||||||-->
  <div class="preloader-wrapper">
    <div class="preloader">
    </div>
  </div> 
<!-- |||||||||||||||| LOADER ||||||||||||||||||||-->
<div class="container-fluid py-4"> <!-- DIV CONTENIDO -->

    <div class="row"> <!-- CARD PRINCIPAL-->  
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0">  <!-- CARD N-1 -->  
    
    <!--Titulo de página -->
     <div class="d-sm-flex align-items-center justify-content-between mb-5">
       <h4 class="mb-0">
         <i class="fa-solid fa-cash-register mr-2" style="color: #f6c5b4;"></i> Venta
      </h4>
           
       <!-- Button que abre el Modal N1 Registro -->
       <div class="d-flex gap-2"> 
          <?php if ($_SESSION["nivel_rol"] >= 2 && tieneAcceso(4, 'registrar')): ?>
          <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#registroModal">
            <span class="icon text-white">
            <i class="fas fa-file-medical"></i>
            </span>
            <span class="text-white">Registrar Venta</span>
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
          
      <div class="table-responsive"> <!-- comienzo div table-->
           <!-- comienzo de tabla-->                      
          <table class="table table-bordered table-hover" id="myTable" width="100%" cellspacing="0">
              <thead class="table-color">
                <tr>
                  <th class="text-white">Cliente</th>
                  <th class="text-white">Fecha</th>
                  <th class="text-white">Total (USD)</th>
                     <?php if ($_SESSION["nivel_rol"] >= 2 && tieneAcceso(4, 'especial')): ?>
                        <th class="text-white">Acción</th>
                       <?php endif; ?>
                </tr>
              </thead>
              <tbody>
                <?php if(isset($ventas) && !empty($ventas)): ?>
                  <?php foreach($ventas as $venta): ?>
                    <?php
                    $fecha_formateada = date('d/m/Y', strtotime($venta['fecha']));
                    $precio_formateado = '$' . number_format($venta['precio_total'], 2);
                    ?>
                    <tr>
                      <td><?php echo htmlspecialchars($venta['cliente']); ?></td>
                      <td><?php echo $fecha_formateada; ?></td>
                      <td><?php echo $precio_formateado; ?></td>

                          <?php if ($_SESSION["nivel_rol"] >= 2 && tieneAcceso(4, 'especial')){ ?>
                          <td class="text-center">
                            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#verDetallesModal<?php echo $venta['id_pedido']; ?>">
                              <i class="fas fa-eye" title="Ver Detalles"></i>
                            </button>
                          </td>
                          <?php } ?>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="4" class="text-center">No hay ventas registradas</td>
                  </tr>
                <?php endif; ?>
              </tbody>
          </table> <!-- Fin tabla--> 
      </div>  <!-- Fin div table-->

            </div><!-- FIN CARD N-1 -->  
    </div>
    </div>  
    </div><!-- FIN CARD PRINCIPAL-->  

<!-- Modal de detalles -->
<?php if(isset($ventas) && !empty($ventas)): ?>
  <?php foreach($ventas as $venta): ?>
    <!-- Modal para Ver Detalles -->
    <div class="modal fade" id="verDetallesModal<?php echo $venta['id_pedido']; ?>" tabindex="-1" aria-labelledby="verDetallesModalLabel<?php echo $venta['id_pedido']; ?>" aria-hidden="true">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header header-color">
            <h5 class="modal-title text-white" id="verDetallesModalLabel<?php echo $venta['id_pedido']; ?>">
              Detalles de la venta
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <!-- Información de Fecha y Hora -->
            <div class="row mb-4">
              <div class="col-12">
                <div class="card">
                  <div class="card-header bg-light" data-bs-toggle="collapse" data-bs-target="#collapse-fecha" aria-expanded="false" aria-controls="collapse-fecha" style="cursor: pointer;">
                    <h6 class="mb-0">
                      <i class="fas fa-calendar-alt" style="color: #f6c5b4;"></i> Fecha y Hora de Registro
                      <i class="fas fa-chevron-down float-end" style="font-size: 0.8em;"></i>
                    </h6>
                  </div>
                  <div class="collapse" id="collapse-fecha">
                  <div class="card-body">
                    <div class="row">
              <div class="col-md-6">
                        <strong>Fecha de Venta:</strong> <?php echo date('d/m/Y', strtotime($venta['fecha'])); ?>
              </div>
              <div class="col-md-6">
                        <strong>Hora de Venta:</strong> <?php echo date('H:i:s', strtotime($venta['fecha'])); ?>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Información Detallada del Cliente -->
            <div class="row mb-4">
              <div class="col-12">
                <div class="card">
                  <div class="card-header bg-light" data-bs-toggle="collapse" data-bs-target="#collapse-cliente-detalle" aria-expanded="false" aria-controls="collapse-cliente-detalle" style="cursor: pointer;">
                    <h6 class="mb-0">
                      <i class="fas fa-user" style="color: #f6c5b4;"></i> Información Detallada del Cliente
                      <i class="fas fa-chevron-down float-end" style="font-size: 0.8em;"></i>
                    </h6>
                  </div>
                  <div class="collapse" id="collapse-cliente-detalle">
                  <div class="card-body">
                    <?php
                    // Obtener información detallada del cliente
                    $cliente_detalle = $salida->consultarClienteDetalle($venta['id_pedido']);
                    ?>
                    <div class="row">
                      <div class="col-md-3">
                        <strong>Nombre Completo:</strong><br>
                        <?php echo htmlspecialchars($cliente_detalle['nombre'] . ' ' . $cliente_detalle['apellido']); ?>
                      </div>
                      <div class="col-md-3">
                        <strong>Cédula:</strong><br>
                        <?php echo htmlspecialchars($cliente_detalle['cedula']); ?>
                      </div>
                      <div class="col-md-3">
                        <strong>Teléfono:</strong><br>
                        <?php echo htmlspecialchars($cliente_detalle['telefono']); ?>
                      </div>
                      <div class="col-md-3">
                        <strong>Correo:</strong><br>
                        <?php echo htmlspecialchars($cliente_detalle['correo']); ?>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Información de Productos -->
            <div class="row mb-4">
              <div class="col-12">
                <div class="card">
                  <div class="card-header bg-light" data-bs-toggle="collapse" data-bs-target="#collapse-productos-detalle" aria-expanded="false" aria-controls="collapse-productos-detalle" style="cursor: pointer;">
                    <h6 class="mb-0">
                      <i class="fas fa-shopping-cart" style="color: #f6c5b4;"></i> Productos de la Venta
                      <i class="fas fa-chevron-down float-end" style="font-size: 0.8em;"></i>
                    </h6>
                  </div>
                  <div class="collapse" id="collapse-productos-detalle">
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-bordered table-hover">
                        <thead class="table-color">
                          <tr>
                            <th class="text-white">#</th>
                            <th class="text-white">Producto</th>
                            <th class="text-center text-white" >Cantidad</th>
                            <th class="text-center text-white">Precio Unitario</th>
                            <th class="text-center text-white">Subtotal</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $detalles_venta = $salida->consultarDetallesPedido($venta['id_pedido']);
                          $total = 0;
                          $contador = 1;
                          foreach($detalles_venta as $detalle): 
                            $subtotal = $detalle['cantidad'] * $detalle['precio_unitario'];
                            $total += $subtotal;
                          ?>
                          <tr>
                            <td class="text-center"><?php echo $contador++; ?></td>
                            <td><?php echo htmlspecialchars($detalle['nombre_producto']); ?></td>
                            <td class="text-center"><?php echo $detalle['cantidad']; ?></td>
                            <td class="text-center">$<?php echo number_format($detalle['precio_unitario'], 2); ?></td>
                            <td class="text-center">$<?php echo number_format($subtotal, 2); ?></td>
                          </tr>
                          <?php endforeach; ?>
                        </tbody>
                        <tfoot class="table-light">
                          <tr>
                            <th colspan="4" class="text-end">Total USD:</th>
                            <th class="text-center">$<?php echo number_format($total, 2); ?></th>
                          </tr>
                        </tfoot>
                      </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Información de Métodos de Pago -->
            <div class="row mb-4">
              <div class="col-12">
                <div class="card">
                  <div class="card-header bg-light" data-bs-toggle="collapse" data-bs-target="#collapse-metodos-detalle" aria-expanded="false" aria-controls="collapse-metodos-detalle" style="cursor: pointer;">
                    <h6 class="mb-0">
                      <i class="fas fa-credit-card" style="color: #f6c5b4;"></i> Métodos de Pago Utilizados
                      <i class="fas fa-chevron-down float-end" style="font-size: 0.8em;"></i>
                    </h6>
                  </div>
                  <div class="collapse" id="collapse-metodos-detalle">
                  <div class="card-body">
                    <?php
                    // Obtener información de métodos de pago
                    $metodos_pago_venta = $salida->consultarMetodosPagoVenta($venta['id_pedido']);
                    if (!empty($metodos_pago_venta)):
                    ?>
                    <div class="table-responsive">
                      <table class="table table-bordered">
                        <thead class="table-color">
                          <tr>
                            <th class="text-white">#</th>
                            <th class="text-white">Método de Pago</th>
                            <th class="text-center text-white">Monto USD</th>
                            <th class="text-center text-white">Monto Bs</th>
                            <th class="text-white">Detalles Adicionales</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php 
                          $contador_metodos = 1;
                          foreach($metodos_pago_venta as $metodo): 
                          ?>
                          <tr>
                            <td class="text-center"><?php echo $contador_metodos++; ?></td>
                            <td><?php echo htmlspecialchars($metodo['nombre_metodo']); ?></td>
                            <td class="text-center">$<?php echo number_format($metodo['monto_usd'], 2); ?></td>
                            <td class="text-center">Bs <?php echo number_format($metodo['monto_bs'], 2); ?></td>
                            <td>
                              <?php if (!empty($metodo['referencia'])): ?>
                                <strong>Ref:</strong> <?php echo htmlspecialchars($metodo['referencia']); ?><br>
                              <?php endif; ?>
                              <?php if (!empty($metodo['banco_emisor'])): ?>
                                <strong>Banco Emisor:</strong> <?php echo htmlspecialchars($metodo['banco_emisor']); ?><br>
                              <?php endif; ?>
                              <?php if (!empty($metodo['banco_receptor'])): ?>
                                <strong>Banco Receptor:</strong> <?php echo htmlspecialchars($metodo['banco_receptor']); ?><br>
                              <?php endif; ?>
                              <?php if (!empty($metodo['telefono_emisor'])): ?>
                                <strong>Teléfono:</strong> <?php echo htmlspecialchars($metodo['telefono_emisor']); ?>
                              <?php endif; ?>
                            </td>
                          </tr>
                          <?php endforeach; ?>
                        </tbody>
                      </table>
                    </div>
                    <?php else: ?>
                    <div class="alert alert-warning">
                      <i class="fas fa-exclamation-triangle"></i> No hay información de métodos de pago registrada para esta venta.
                    </div>
                    <?php endif; ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            

          </div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
<?php endif; ?>

<!-- Modal de Registro -->
<div class="modal fade" id="registroModal" tabindex="-1" aria-labelledby="registroModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header header-color">
        <h5 class="modal-title text-white" id="registroModalLabel">Registrar Venta</h5>
        <div class="d-flex align-items-center">
          <button type="button" class="btn btn-link text-white me-2" id="btnAyudaModal" title="Ayuda">
            <i class="fa-solid fa-circle-question" style="color: #ffffff; font-size: 1.2rem;"></i>
          </button>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
      </div>
      <div class="modal-body">
        <!-- Indicador de pasos -->
        <div class="mb-4">
          <div class="row">
            <div class="col-12">
              <div class="progress-steps">
                <div class="step active" id="step-cliente">
                  <div class="step-number">1</div>
                  <div class="step-label">Cliente</div>
                </div>
                <div class="step" id="step-productos">
                  <div class="step-number">2</div>
                  <div class="step-label">Productos</div>
                </div>
                <div class="step" id="step-pago">
                  <div class="step-number">3</div>
                  <div class="step-label">Pago</div>
                </div>
                <div class="step" id="step-confirmar">
                  <div class="step-number">4</div>
                  <div class="step-label">Confirmar</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <form method="POST" action="?pagina=salida" id="formRegistroVenta">
          <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
          
          <!-- Campo oculto para el ID del cliente -->
          <input type="hidden" name="id_persona" id="id_cliente_hidden">
          
          <!-- PASO 1: Datos del Cliente -->
          <div class="step-content" id="step-1-content">
            <div class="mb-4">
              <h6>Paso 1: Datos del Cliente</h6>
              <p class="text-muted mb-3">
                <i class="fas fa-info-circle"></i> 
                Ingrese la cédula del cliente. Si ya está registrado, sus datos se cargarán. Si es nuevo, complete los campos y se registrará al finalizar la venta.
              </p>
              
              <!-- Buscar por Cédula -->
              <div class="mb-3">
                <label class="form-label fw-bold">Cédula del Cliente</label>
                <div class="row">
                  <div class="col-md-6">
                    <input type="text" class="form-control" name="cedula_cliente" id="cedula_cliente" 
                           placeholder="Ingrese la cédula" minlength="7" maxlength="8" pattern="[0-9]{7,8}" required>
                      <div class="invalid-feedback">
                        La cédula debe tener entre 7 y 8 dígitos
                      </div>
                  </div>
                </div>
              </div>

              <!-- Campos del cliente -->
              <div id="campos-cliente" style="display: none;">
                <div class="row">
                  <div class="col-md-6">
                    <div class="mb-3">
                      <label for="nombre_cliente" class="form-label">Nombre</label>
                      <input type="text" class="form-control" name="nombre_cliente" id="nombre_cliente" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="mb-3">
                      <label for="apellido_cliente" class="form-label">Apellido</label>
                      <input type="text" class="form-control" name="apellido_cliente" id="apellido_cliente" required>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="mb-3">
                      <label for="telefono_cliente" class="form-label">Teléfono</label>
                      <input type="text" class="form-control" name="telefono_cliente" id="telefono_cliente" maxlength="11" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="mb-3">
                      <label for="correo_cliente" class="form-label">Correo</label>
                      <input type="email" class="form-control" name="correo_cliente" id="correo_cliente" required>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- PASO 2: Productos -->
          <div class="step-content" id="step-2-content" style="display: none;">
            <div class="mb-4">
              <h6>Paso 2: Selección de Productos</h6>
              
              <div class="table-responsive">
                <table class="table table-bordered">
                  <thead class="table-color">
                    <tr>
                      <th class="text-white">Producto</th>
                      <th class="text-white">Cantidad</th>
                      <th class="text-white">Precio Unitario</th>
                      <th class="text-white">Subtotal</th>
                      <th class="text-white">Acción</th>
                    </tr>
                  </thead>
                  <tbody id="productos-container-venta">
                    <tr class="producto-fila">
                      <td>
                        <select class="form-select producto-select-venta" name="id_producto[]" required>
                          <option value="">Seleccione un producto</option>
                          <?php if(isset($productos_lista) && !empty($productos_lista)): ?>
                            <?php foreach($productos_lista as $producto): ?>
                              <?php 
                                $stock = isset($producto['stock_disponible']) ? intval($producto['stock_disponible']) : 0;
                                $precio = isset($producto['precio_detal']) ? floatval($producto['precio_detal']) : 0;
                              ?>
                              <option value="<?php echo $producto['id_producto']; ?>" 
                                      data-precio="<?php echo number_format($precio, 2, '.', ''); ?>"
                                      data-stock="<?php echo $stock; ?>">
                                <?php echo htmlspecialchars($producto['nombre']); ?>
                              </option>
                            <?php endforeach; ?>
                          <?php endif; ?>
                        </select>
                      </td>
                      <td>
                        <div class="input-group">
                          <input type="number" class="form-control cantidad-input-venta" 
                                 name="cantidad[]" value="1" min="1" required>
                          <span class="input-group-text stock-info"></span>
                        </div>
                      </td>
                      <td>
                        <input type="text" class="form-control precio-input-venta" 
                               name="precio_unitario[]" value="0.00" readonly>
                      </td>
                      <td>
                        <span class="subtotal-venta">0.00</span>
                      </td>
                      <td class="text-center">
                        <button type="button" class="btn btn-success btn-sm agregar-producto-venta">
                          <i class="fas fa-plus"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-sm remover-producto-venta">
                          <i class="fas fa-trash-alt"></i>
                        </button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              
              <!-- Total general -->
              <div class="row mt-3">
                <div class="col-md-8"></div>
                <div class="col-md-4">
                  <div class="card">
                    <div class="card-body text-center">
                      <h5 class="mb-0">Total: <span id="total-general-venta" class="text-success">$0.00</span></h5>
                      <!-- Campo oculto para el precio total -->
                      <input type="hidden" name="precio_total" value="0.00">
                      <!-- Campo oculto para el precio total en bolívares -->
                      <input type="hidden" name="precio_total_bs" value="0.00">
                    </div>
                  </div>
                  
                  <!-- Información de conversión de moneda -->
                  <div class="alert alert-info mt-2" id="info-conversion" style="display: none;">
                    <i class="fas fa-info-circle"></i>
                    <strong>Tasa de cambio:</strong> Calculando...
                    <br><strong>Total en bolívares:</strong> Calculando...
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- PASO 3: Métodos de Pago -->
          <div class="step-content" id="step-3-content" style="display: none;">
            <div class="mb-4">
              <h6>Paso 3: Métodos de Pago</h6>
              
              <!-- Resumen de costos -->
              <div class="row mb-3">
                <div class="col-md-4">
                  <div class="card bg-light">
                    <div class="card-body text-center">
                      <h6 class="card-title">Total de la Venta</h6>
                      <h4 class="text-primary" id="total-venta-display">$0.00</h4>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="card bg-light">
                    <div class="card-body text-center">
                      <h6 class="card-title">Total Pagado</h6>
                      <h4 class="text-success" id="total-pagado-display">$0.00</h4>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="card bg-light">
                    <div class="card-body text-center">
                      <h6 class="card-title">Saldo Restante</h6>
                      <h4 class="text-warning" id="saldo-restante-display">$0.00</h4>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Métodos de Pago -->
              <div class="mb-3">
                <label class="form-label fw-bold">Métodos de Pago *</label>
                <div id="metodos-pago-container">
                  <div class="row metodo-pago-fila mb-2">
                    <div class="col-md-6">
                      <select class="form-select metodo-pago-select" name="id_metodopago[]" required>
                        <option value="">Seleccione un método de pago</option>
                        <?php 
                        // Consulta para obtener métodos de pago activos
                        if(isset($metodos_pago) && !empty($metodos_pago)): 
                          foreach($metodos_pago as $metodo): 
                        ?>
                          <option value="<?php echo $metodo['id_metodopago']; ?>" data-nombre="<?php echo htmlspecialchars($metodo['nombre']); ?>">
                            <?php echo htmlspecialchars($metodo['nombre']); ?>
                          </option>
                        <?php 
                          endforeach; 
                        else: 
                        ?>
                          <option value="" disabled>No hay métodos de pago disponibles</option>
                        <?php endif; ?>
                      </select>
                    </div>
                    <div class="col-md-4">
                      <input type="number" class="form-control monto-metodopago" name="monto_metodopago[]" placeholder="Monto USD" step="0.01" min="0" required>
                    </div>
                    <div class="col-md-2">
                      <button type="button" class="btn btn-success btn-sm agregar-metodo-pago">
                        <i class="fas fa-plus"></i>
                      </button>
                      <button type="button" class="btn btn-danger btn-sm remover-metodo-pago">
                        <i class="fas fa-trash-alt"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Lista de métodos de pago aplicados -->
              <div class="mb-3" id="lista-metodos-aplicados" style="display: none;">
                <label class="form-label fw-bold">Métodos de Pago Aplicados:</label>
                <div id="metodos-aplicados-container">
                  <!-- Se llenará dinámicamente -->
                </div>
              </div>
              
              <!-- Campos dinámicos según método de pago -->
              <div id="campos-metodo-pago-dinamicos" style="display: none;">
                <!-- Campos para Divisa $ -->
                <div id="campos-divisa" class="campos-metodo" style="display: none;">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="mb-3">
                        <!-- Campo vacío - el monto se ingresa en el campo principal -->
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Campos para Efectivo Bs -->
                <div id="campos-efectivo" class="campos-metodo" style="display: none;">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="mb-3">
                        <label class="form-label">Monto en Bs</label>
                        <input type="number" class="form-control" name="monto_efectivo_bs" placeholder="Monto Bs" step="0.01" min="0" readonly>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="mb-3">
                        <!-- Campo vacío - el monto se ingresa en el campo principal -->
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Campos para Pago Móvil -->
                <div id="campos-pago-movil" class="campos-metodo" style="display: none;">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="mb-3">
                        <label class="form-label">Banco Emisor</label>
                        <select class="form-select" name="banco_emisor_pm">
                          <option value="">Seleccione banco emisor</option>
                          <option value="0102-Banco De Venezuela">0102-Banco De Venezuela</option>
                          <option value="0105-Banco Mercantil">0105-Banco Mercantil</option>
                          <option value="0172-Bancamiga Banco Universal,C.A">0172-Bancamiga Banco Universal,C.A</option>
                          <option value="0114-Bancaribe">0114-Bancaribe</option>
                          <option value="0171-Banco Activo">0171-Banco Activo</option>
                          <option value="0166-Banco Agricola De Venezuela">0166-Banco Agricola De Venezuela</option>
                          <option value="0128-Bancon Caroni">0128-Bancon Caroni</option>
                          <option value="0163-Banco Del Tesoro">0163-Banco Del Tesoro</option>
                          <option value="0175-Banco Digital De Los Trabajadores, Banco Universal">0175-Banco Digital De Los Trabajadores, Banco Universal</option>
                          <option value="0115-Banco Exterior">0115-Banco Exterior</option>
                          <option value="0151-Banco Fondo Comun">0151-Banco Fondo Comun</option>
                          <option value="0173-Banco Internacional De Desarrollo">0173-Banco Internacional De Desarrollo</option>
                          <option value="0191-Banco Nacional De Credito">0191-Banco Nacional De Credito</option>
                          <option value="0138-Banco Plaza">0138-Banco Plaza</option>
                          <option value="0137-Banco Sofitasa">0137-Banco Sofitasa</option>
                          <option value="0104-Banco Venezolano De Credito">0104-Banco Venezolano De Credito</option>
                          <option value="0168-Bancrecer">0168-Bancrecer</option>
                          <option value="0134-Banesco">0134-Banesco</option>
                          <option value="0177-Banfanb">0177-Banfanb</option>
                          <option value="0146-Bangente">0146-Bangente</option>
                          <option value="0174-Banplus">0174-Banplus</option>
                          <option value="0108-BBVA Provincial">0108-BBVA Provincial</option>
                          <option value="0157-Delsur Banco Universal">0157-Delsur Banco Universal</option>
                          <option value="0178-N58 Banco Digital Banco Microfinanciero S.A">0178-N58 Banco Digital Banco Microfinanciero S.A</option>
                          <option value="0169-R4 Banco Microfinanciero C.A.">0169-R4 Banco Microfinanciero C.A.</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="mb-3">
                        <label class="form-label">Banco Receptor</label>
                        <select class="form-select" name="banco_receptor_pm">
                          <option value="">Seleccione banco receptor</option>
                          <option value="0102-Banco De Venezuela">0102-Banco De Venezuela</option>
                          <option value="0105-Banco Mercantil">0105-Banco Mercantil</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4">
                      <div class="mb-3">
                        <label class="form-label">Referencia</label>
                        <input type="text" class="form-control" name="referencia_pm" placeholder="4-6 dígitos" minlength="4" maxlength="6" pattern="[0-9]{4,6}">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="mb-3">
                        <label class="form-label">Teléfono Emisor</label>
                        <input type="text" class="form-control" name="telefono_emisor_pm" placeholder="Ej: 04141234567" pattern="[0-9]{11}">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="mb-3">
                        <label class="form-label">Monto en Bs</label>
                        <input type="number" class="form-control" name="monto_pm_bs" placeholder="Monto Bs" step="0.01" min="0" readonly>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Campos para Punto de Venta -->
                <div id="campos-punto-venta" class="campos-metodo" style="display: none;">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="mb-3">
                        <label class="form-label">Referencia del Punto</label>
                        <input type="text" class="form-control" name="referencia_pv" placeholder="4-6 dígitos" minlength="4" maxlength="6" pattern="[0-9]{4,6}">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="mb-3">
                        <label class="form-label">Monto en Bs</label>
                        <input type="number" class="form-control" name="monto_pv_bs" placeholder="Monto Bs" step="0.01" min="0" readonly>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Campos para Transferencia Bancaria -->
                <div id="campos-transferencia" class="campos-metodo" style="display: none;">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="mb-3">
                        <label class="form-label">Referencia del Pago</label>
                        <input type="text" class="form-control" name="referencia_tb" placeholder="4-6 dígitos" minlength="4" maxlength="6" pattern="[0-9]{4,6}">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="mb-3">
                        <label class="form-label">Monto en Bs</label>
                        <input type="number" class="form-control" name="monto_tb_bs" placeholder="Monto Bs" step="0.01" min="0" readonly>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- PASO 4: Confirmación -->
          <div class="step-content" id="step-4-content" style="display: none;">
            <div class="mb-4">
              <h6>Paso 4: Confirmación de Venta</h6>
              
              <!-- Previsualización de los datos de los pasos anteriores -->
              <div id="resumen-venta">
                <!-- Información del Cliente (Paso 1) -->
                <div class="resumen-seccion">
                  <h6 class="resumen-titulo" data-bs-toggle="collapse" data-bs-target="#collapse-cliente" aria-expanded="false" aria-controls="collapse-cliente" style="cursor: pointer;">
                    <i class="fas fa-user text-primary"></i> Información del Cliente
                    <i class="fas fa-chevron-down float-end" style="font-size: 0.8em;"></i>
                  </h6>
                  <div class="collapse" id="collapse-cliente">
                    <div class="resumen-contenido">
                      <div class="row">
                        <div class="col-md-3">
                          <label class="form-label">Cédula</label>
                          <input type="text" class="form-control" id="preview_cedula" readonly>
              </div>
                        <div class="col-md-3">
                          <label class="form-label">Nombre</label>
                          <input type="text" class="form-control" id="preview_nombre" readonly>
                        </div>
                        <div class="col-md-3">
                          <label class="form-label">Apellido</label>
                          <input type="text" class="form-control" id="preview_apellido" readonly>
                        </div>
                        <div class="col-md-3">
                          <label class="form-label">Teléfono</label>
                          <input type="text" class="form-control" id="preview_telefono" readonly>
                        </div>
                        <div class="col-md-6 mt-2">
                          <label class="form-label">Correo</label>
                          <input type="text" class="form-control" id="preview_correo" readonly>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- Información de Productos (Paso 2) -->
                <div class="resumen-seccion">
                  <h6 class="resumen-titulo" data-bs-toggle="collapse" data-bs-target="#collapse-productos" aria-expanded="false" aria-controls="collapse-productos" style="cursor: pointer;">
                    <i class="fas fa-shopping-cart text-success"></i> Productos Seleccionados
                    <i class="fas fa-chevron-down float-end" style="font-size: 0.8em;"></i>
                  </h6>
                  <div class="collapse" id="collapse-productos">
                    <div class="resumen-contenido">
                      <div class="table-responsive">
                        <table class="table table-sm table-bordered" id="preview_tabla_productos">
                          <thead class="table-">
                            <tr>
                              <th>Producto</th>
                              <th class="text-center">Cantidad</th>
                              <th class="text-center">Precio Unit.</th>
                              <th class="text-center">Subtotal</th>
                            </tr>
                          </thead>
                          <tbody>
                            <!-- Se llenará por JS -->
                          </tbody>
                          <tfoot class="table-light">
                            <tr>
                              <th colspan="3" class="text-end">Total USD:</th>
                              <th class="text-center" id="preview_total_usd">$0.00</th>
                            </tr>
                          </tfoot>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- Información de Métodos de Pago (Paso 3) -->
                <div class="resumen-seccion">
                  <h6 class="resumen-titulo" data-bs-toggle="collapse" data-bs-target="#collapse-metodos" aria-expanded="false" aria-controls="collapse-metodos" style="cursor: pointer;">
                    <i class="fas fa-credit-card text-info"></i> Métodos de Pago
                    <i class="fas fa-chevron-down float-end" style="font-size: 0.8em;"></i>
                  </h6>
                  <div class="collapse" id="collapse-metodos">
                    <div class="resumen-contenido" id="preview_metodos_pago">
                      <!-- Se llenará por JS -->
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Botones de navegación -->
          <div class="modal-footer">
            <div class="d-flex justify-content-between w-100">
              <button type="button" class="btn btn-secondary" id="btnAnterior" style="display: none;">
                <i class="fas fa-arrow-left"></i> Anterior
              </button>
              <button type="button" class="btn btn-primary" id="btnSiguiente">
                Siguiente <i class="fas fa-arrow-right"></i>
              </button>
              <button type="submit" class="btn btn-success" id="btnRegistrarVenta" style="display: none;">
                <i class="fas fa-check"></i> Registrar Venta
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

</div> <!-- FIN DIV CONTENIDO -->



<!-- php barra de navegacion-->
<?php include 'complementos/footer.php' ?>

  <!-- jQuery -->
  <script src="assets/js/libreria/jquery.min.js"></script>
  
  <!-- Script para inicializar DataTable -->
  <script src="assets/js/demo/datatables-demo.js"></script>

  <!-- Script para el cálculo de precios en ventas -->
  <script src="assets/js/salida.js"></script>

<!-- Script para manejar collapsibles del modal de detalles -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configurar eventos para los collapsibles del modal de detalles
    function configurarCollapsiblesDetalles() {
        // Eventos para cambiar iconos cuando se expande/colapsa en el modal de detalles
        document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(titulo => {
            titulo.addEventListener('click', function() {
                const icono = this.querySelector('.fas.fa-chevron-down, .fas.fa-chevron-up');
                if (icono) {
                    // Cambiar el icono
                    if (icono.classList.contains('fa-chevron-down')) {
                        icono.classList.remove('fa-chevron-down');
                        icono.classList.add('fa-chevron-up');
                    } else {
                        icono.classList.remove('fa-chevron-up');
                        icono.classList.add('fa-chevron-down');
                    }
                }
            });
        });
    }

    // Configurar collapsibles cuando se abre cualquier modal de detalles
    document.querySelectorAll('[id^="verDetallesModal"]').forEach(modal => {
        modal.addEventListener('shown.bs.modal', function() {
            configurarCollapsiblesDetalles();
        });
    });

    // Configurar collapsibles inicialmente si ya están en el DOM
    configurarCollapsiblesDetalles();
});
</script>

</main>
</body>
</html>