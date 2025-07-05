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
          <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#registroModal">
            <span class="icon text-white">
            <i class="fas fa-file-medical"></i>
            </span>
            <span class="text-white">Registrar Venta</span>
          </button>

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
          <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
              <thead class="table-color">
                <tr>
                  <th class="text-white">Cliente</th>
                  <th class="text-white">Fecha</th>
                  <th class="text-white">Total (USD)</th>
                  <th class="text-white">Acción</th>
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
                      <td class="text-center">
                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#verDetallesModal<?php echo $venta['id_pedido']; ?>">
                          <i class="fas fa-eye" title="Ver Detalles"></i>
                        </button>
                      </td>
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
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header header-color">
            <h5 class="modal-title" id="verDetallesModalLabel<?php echo $venta['id_pedido']; ?>">Detalles de la venta</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row mb-3">
              <div class="col-md-6">
                <h5><strong>Información del Pedido</strong></h5>
                <p><strong>Total (USD):</strong> $<?php echo number_format($venta['precio_total'], 2); ?></p>
                <p><strong>Total (Bs):</strong> <span id="total-bs-<?php echo $venta['id_pedido']; ?>">Calculando...</span></p>
              </div>
              <div class="col-md-6">
                <h5><strong>Información del Cliente</strong></h5>
                <p><strong>Nombre:</strong> <?php echo htmlspecialchars($venta['cliente']); ?></p>
                <p><strong>Fecha:</strong> <?php echo date('d/m/Y', strtotime($venta['fecha'])); ?></p>
              </div>
            </div>
            
            <script>
            // Función para obtener la tasa de cambio y calcular el monto en bolívares
            async function calcularTotalBs(pedidoId, totalUsd) {
                try {
                    const respuesta = await fetch('https://ve.dolarapi.com/v1/dolares/oficial');
                    if (!respuesta.ok) {
                        throw new Error(`Error HTTP: ${respuesta.status}`);
                    }
                    const datos = await respuesta.json();
                    const tasaBCV = parseFloat(datos.promedio);
                    const totalBs = (totalUsd * tasaBCV).toFixed(2);
                    document.getElementById(`total-bs-${pedidoId}`).textContent = `Bs ${totalBs}`;
                } catch (error) {
                    document.getElementById(`total-bs-${pedidoId}`).textContent = 'Error al calcular';
                    console.error("Error al obtener la tasa:", error);
                }
            }
            
            // Calcular el total en bolívares cuando se abre el modal
            document.addEventListener('DOMContentLoaded', function() {
                const modal = document.getElementById('verDetallesModal<?php echo $venta['id_pedido']; ?>');
                if (modal) {
                    modal.addEventListener('show.bs.modal', function() {
                        calcularTotalBs(<?php echo $venta['id_pedido']; ?>, <?php echo $venta['precio_total']; ?>);
                    });
                }
            });
            </script>
            
            <?php
            $detalles_venta = $salida->consultarDetallesPedido($venta['id_pedido']);
            $total = 0;
            ?>
            <hr style="border-top: 2px solid #ccc;">
            <h5><strong>Detalles de la Venta</strong></h5>
            <div class="table-responsive">
              <table class="table table-sm">
                <thead>
                  <tr class="table-color">
                    <th class="text-white">Producto</th>
                    <th class="text-white">Cantidad</th>
                    <th class="text-white">Precio Unit.</th>
                    <th class="text-white">Subtotal</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach($detalles_venta as $detalle): 
                    $subtotal = $detalle['cantidad'] * $detalle['precio_unitario'];
                    $total += $subtotal;
                  ?>
                  <tr>
                    <td class="text-center"><?php echo htmlspecialchars($detalle['nombre_producto']); ?></td>
                    <td class="text-center"><?php echo $detalle['cantidad']; ?></td>
                    <td class="text-center">$<?php echo number_format($detalle['precio_unitario'], 2); ?></td>
                    <td class="text-center">$<?php echo number_format($subtotal, 2); ?></td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
                <tfoot>
                  <tr>
                    <th colspan="3" class="text-end">Total USD:</th>
                    <th>$<?php echo number_format($total, 2); ?></th>
                  </tr>
                </tfoot>
              </table>
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
        <h5 class="modal-title" id="registroModalLabel">Registrar Venta</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="POST" action="?pagina=salida" id="formRegistroVenta">
          <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
          
          <!-- Campo oculto para el ID del cliente -->
          <input type="hidden" name="id_persona" id="id_cliente_hidden">
          
          <!-- Sección: Datos del Cliente -->
          <div class="mb-4">
            <h6>Datos del Cliente</h6>
            
            <!-- Buscar por Cédula -->
            <div class="mb-3">
              <label class="form-label fw-bold">Buscar por Cédula</label>
              <div class="row">
                <div class="col-md-6">
                  <input type="text" class="form-control" name="cedula_cliente" id="cedula_cliente" 
                           placeholder="Ingrese la cédula" minlength="7" maxlength="8" pattern="[0-9]{7,8}" required>
                    <div class="invalid-feedback">
                      La cédula debe tener entre 7 y 8 dígitos
                    </div>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-outline-primary w-100" id="btnBuscarCliente">
                      <i class="fas fa-search"></i> Buscar
                    </button>
                    <button type="button" class="btn btn-outline-danger w-100" id="cancelarRegistro" style="display: none;">
                      <i class="fas fa-times"></i> Cancelar
                    </button>
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

            <?php if(isset($_SESSION['cliente_encontrado'])): ?>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        document.getElementById('campos-cliente').style.display = 'block';
                        
                        <?php if($_SESSION['cliente_encontrado']): ?>
                            const nombreInput = document.getElementById('nombre_cliente');
                            const apellidoInput = document.getElementById('apellido_cliente');
                            const telefonoInput = document.getElementById('telefono_cliente');
                            const correoInput = document.getElementById('correo_cliente');

                            nombreInput.value = '<?php echo htmlspecialchars($cliente['nombre']); ?>';
                            apellidoInput.value = '<?php echo htmlspecialchars($cliente['apellido']); ?>';
                            telefonoInput.value = '<?php echo htmlspecialchars($cliente['telefono']); ?>';
                            correoInput.value = '<?php echo htmlspecialchars($cliente['correo']); ?>';
                            
                            nombreInput.readOnly = true;
                            apellidoInput.readOnly = true;
                            telefonoInput.readOnly = true;
                            correoInput.readOnly = true;
                        <?php endif; ?>
                    });
                </script>
                <?php
                unset($_SESSION['cliente_encontrado']);
                unset($_SESSION['datos_cliente']);
                ?>
            <?php endif; ?>
          </div>

          <!-- Sección: Datos de la Venta -->
          <div class="mb-4 seccion-venta" style="display: none;">
            <h6>Datos de venta</h6>
            
            <!-- Métodos de Pago -->
            <div class="mb-3">
              <label class="form-label fw-bold">Métodos de Pago *</label>
              <div id="metodos-pago-container">
                <div class="row metodo-pago-fila mb-2">
                  <div class="col-md-8">
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
            
            <!-- Campos dinámicos según método de pago -->
            <div id="campos-metodo-pago-dinamicos" style="display: none;">
              <!-- Campos para Divisa $ -->
              <div id="campos-divisa" class="campos-metodo" style="display: none;">
                <div class="row">
                  <div class="col-md-6">
                    <div class="mb-3">
                      <label class="form-label">Monto en USD</label>
                      <input type="number" class="form-control" name="monto_divisa" placeholder="Ingrese monto en USD" step="0.01" min="0">
                    </div>
                  </div>
                </div>
              </div>

              <!-- Campos para Efectivo Bs -->
              <div id="campos-efectivo" class="campos-metodo" style="display: none;">
                <div class="row">
                  <div class="col-md-6">
                    <div class="mb-3">
                      <label class="form-label">Monto en USD</label>
                      <input type="number" class="form-control" name="monto_efectivo_usd" placeholder="Monto USD" step="0.01" min="0" readonly>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="mb-3">
                      <label class="form-label">Monto en Bs</label>
                      <input type="number" class="form-control" name="monto_efectivo_bs" placeholder="Monto Bs" step="0.01" min="0" readonly>
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

          <!-- Sección: Productos -->
          <div class="mb-4 seccion-productos" style="display: none;">
            <div class="d-flex justify-content-between align-items-center mb-3">
            </div>
            
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
                    </div>
                  </div>
                </div>
                          <!-- Botones de acción en la parte inferior -->
          <div class="text-center">
            <button type="submit" name="registrar_venta" class="btn btn-primary">
              Registrar Venta
            </button>
            <button type="reset" class="btn btn-secondary">
              Limpiar
            </button>
          </div>
              </div>
            </div>
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

<!-- Script para el cálculo de precios en ventas -->
<script src="assets/js/salida.js"></script>

</main>
</body>
</html>