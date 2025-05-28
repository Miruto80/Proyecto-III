<!DOCTYPE html>
<html lang="es">

<head>
  <!-- php barra de navegacion-->
  <?php include 'complementos/head.php' ?> 
  <title> Venta | LoveMakeup  </title> 
  <!-- SweetAlert2 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
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

<div class="container-fluid py-4"> <!-- DIV CONTENIDO -->

    <!-- Mostrar mensajes de éxito o error -->
    <?php if(isset($_SESSION['mensaje']) && isset($_SESSION['tipo_mensaje'])): ?>
      <div class="alert alert-<?php echo $_SESSION['tipo_mensaje']; ?> alert-dismissible fade show" role="alert">
        <?php echo $_SESSION['mensaje']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      <?php unset($_SESSION['mensaje']); unset($_SESSION['tipo_mensaje']); ?>
    <?php endif; ?>

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
          <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#registroModal">
            <span class="icon text-white">
            <i class="fas fa-file-medical"></i>
            </span>
            <span class="text-white">Registrar Venta</span>
          </button>
      </div>
          
      <div class="table-responsive"> <!-- comienzo div table-->
           <!-- comienzo de tabla-->                      
          <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
              <thead class="table-color">
                <tr>
                  <th class="text-white">Cliente</th>
                  <th class="text-white">Fecha</th>
                  <th class="text-white">Estado</th>
                  <th class="text-white">Total</th>
                  <th class="text-white">Método Pago</th>
                  <th class="text-white">Método Entrega</th>
                  <th class="text-white">Accion</th>
                </tr>
              </thead>
              <tbody>
                <?php if(isset($ventas) && !empty($ventas)): ?>
                  <?php foreach($ventas as $venta): ?>
                    <?php
                    // Array para mapear estados numéricos a texto
                    $estados_texto = array(
                        '0' => 'Rechazado',
                        '1' => 'Pendiente',
                        '2' => 'Aprobado'
                    );

                    // Determinar el color del badge según el estado
                    $badgeClass = '';
                    switch (strtolower($venta['estado'])) {
                      case '2':
                          $badgeClass = 'bg-primary';
                          break;
                      case '1':
                          $badgeClass = 'bg-warning';
                          break;
                      case '0':
                          $badgeClass = 'bg-danger';
                          break;
                      default:
                          $badgeClass = 'bg-secondary';
                    }
                    
                    // Formatear la fecha
                    $fecha_formateada = date('d/m/Y', strtotime($venta['fecha']));
                    
                    // Formatear el precio
                    $precio_formateado = '$' . number_format($venta['precio_total'], 2);
                    ?>
                    <tr>
                      <td><?php echo htmlspecialchars($venta['cliente']); ?></td>
                      <td><?php echo $fecha_formateada; ?></td>
                      <td><span class="badge <?php echo $badgeClass; ?>"><?php echo htmlspecialchars($estados_texto[$venta['estado']] ?? 'Desconocido'); ?></span></td>
                      <td><?php echo $precio_formateado; ?></td>
                      <td><?php echo htmlspecialchars($venta['metodo_pago'] ?? 'N/A'); ?></td>
                      <td><?php echo htmlspecialchars($venta['metodo_entrega'] ?? 'N/A'); ?></td>
                      <td class="text-center">
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editarModal<?php echo $venta['id_pedido']; ?>">
                          <i class="fas fa-pencil-alt" title="Editar"></i>
                        </button>
                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#verDetallesModal<?php echo $venta['id_pedido']; ?>">
                          <i class="fas fa-eye" title="Ver Detalles"></i>
                        </button>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="7" class="text-center">No hay ventas registradas</td>
                  </tr>
                <?php endif; ?>
              </tbody>
          </table> <!-- Fin tabla--> 
      </div>  <!-- Fin div table-->

            </div><!-- FIN CARD N-1 -->  
    </div>
    </div>  
    </div><!-- FIN CARD PRINCIPAL-->  

<!-- Modal Detalles -->
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
                <h5><strong>Información del Cliente</strong></h5>
                <p><strong>Nombre:</strong> <?php echo htmlspecialchars($venta['cliente']); ?></p>
                <p><strong>Fecha:</strong> <?php echo date('d/m/Y', strtotime($venta['fecha'])); ?></p>
                <p><strong>Estado:</strong> <span class="badge <?php 
                  $badgeClass = '';
                  switch (strtolower($venta['estado'])) {
                    case '2':
                        $badgeClass = 'bg-primary';
                        break;
                    case '1':
                        $badgeClass = 'bg-warning';
                        break;
                    case '0':
                        $badgeClass = 'bg-danger';
                        break;
                    default:
                        $badgeClass = 'bg-secondary';
                  }
                  echo $badgeClass; 
                ?>"><?php echo htmlspecialchars($estados_texto[$venta['estado']] ?? 'Desconocido'); ?></span></p>
              </div>
              <div class="col-md-6">
                <h5><strong>Información del Pedido</strong></h5>
                <p><strong>Método de Pago:</strong> <?php echo htmlspecialchars($venta['metodo_pago'] ?? 'N/A'); ?></p>
                <?php if(!empty($venta['banco']) || !empty($venta['banco_destino'])): ?>
                  <p><strong>Banco Emisor:</strong> <?php echo htmlspecialchars($venta['banco'] ?? 'N/A'); ?></p>
                  <p><strong>Banco Receptor:</strong> <?php echo htmlspecialchars($venta['banco_destino'] ?? 'N/A'); ?></p>
                <?php endif; ?>
                <p><strong>Método de Entrega:</strong> <?php echo htmlspecialchars($venta['metodo_entrega'] ?? 'N/A'); ?></p>
                <p><strong>Total:</strong> $<?php echo number_format($venta['precio_total'], 2); ?></p>
              </div>
            </div>
            
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
                    <th colspan="3" class="text-end">Total:</th>
                    <th>$<?php echo number_format($total, 2); ?></th>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal para Editar -->
    <div class="modal fade" id="editarModal<?php echo $venta['id_pedido']; ?>" tabindex="-1" aria-labelledby="editarModalLabel<?php echo $venta['id_pedido']; ?>" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header header-color">
            <h5 class="modal-title" id="editarModalLabel<?php echo $venta['id_pedido']; ?>">Editar venta</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form method="POST" action="?pagina=salida" id="formEditarVenta<?php echo $venta['id_pedido']; ?>">
              <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
              <input type="hidden" name="id_pedido" value="<?php echo $venta['id_pedido']; ?>">
              <input type="hidden" name="modificar_venta" value="1">
              
              <div class="row mb-3">
                <div class="col-md-6">
                  <h5><strong>Información del Cliente</strong></h5>
                  <div class="mb-3">
                    <label for="cliente_nombre" class="form-label">Nombre del Cliente</label>
                    <input type="text" class="form-control" id="cliente_nombre" value="<?php echo htmlspecialchars($venta['cliente']); ?>" readonly>
                  </div>
                </div>
                <div class="col-md-6">
                  <h5><strong>Estado del Pedido</strong></h5>
                  <div class="mb-3">
                    <label for="estado_pedido" class="form-label">Estado</label>
                    <select class="form-select" name="estado_pedido" required>
                      <option value="1" <?php echo ($venta['estado'] == '1') ? 'selected' : ''; ?>>Pendiente</option>
                      <option value="2" <?php echo ($venta['estado'] == '2') ? 'selected' : ''; ?>>Aprobado</option>
                      <option value="0" <?php echo ($venta['estado'] == '0') ? 'selected' : ''; ?>>Rechazado</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="row mb-3">
                <div class="col-md-6">
                  <h5><strong>Información del Pedido</strong></h5>
                  <label for="metodo_pago_edit" class="form-label">Método de Pago</label>
                  <input type="text" class="form-control" value="<?php echo htmlspecialchars($venta['metodo_pago'] ?? 'N/A'); ?>" readonly>
                </div>
                <div class="col-md-6">
                  <label for="metodo_entrega_edit" class="form-label">Método de Entrega</label>
                  <input type="text" class="form-control" value="<?php echo htmlspecialchars($venta['metodo_entrega'] ?? 'N/A'); ?>" readonly>
                </div>
              </div>
              
              <div class="mb-3">
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
                      <?php 
                      $detalles_venta = $salida->consultarDetallesPedido($venta['id_pedido']);
                      $total = 0;
                      foreach($detalles_venta as $detalle): 
                        $subtotal = $detalle['cantidad'] * $detalle['precio_unitario'];
                        $total += $subtotal;
                      ?>
                      <tr>
                        <td><?php echo htmlspecialchars($detalle['nombre_producto']); ?></td>
                        <td><?php echo $detalle['cantidad']; ?></td>
                        <td>$<?php echo number_format($detalle['precio_unitario'], 2); ?></td>
                        <td>$<?php echo number_format($subtotal, 2); ?></td>
                      </tr>
                      <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th colspan="3" class="text-end">Total:</th>
                        <th>$<?php echo number_format($total, 2); ?></th>
                      </tr>
                    </tfoot>
                  </table>
                </div>
                <small class="text-muted">Los productos son solo de lectura. Solo se puede modificar el estado del pedido.</small>
              </div>
              
              <div class="text-center mt-4">
                <button type="submit" name="modificar_venta" class="btn btn-primary">Guardar Cambios</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              </div>
            </form>
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
              <div class="col-md-3">
                <button type="button" class="btn btn-outline-success w-100" id="registrarCliente">
                  <i class="fas fa-user-plus"></i> Registrar cliente
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
                    <input type="text" class="form-control" name="telefono_cliente" id="telefono_cliente" maxlength="10" required>
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
          <div class="mb-4">
            <h6>Datos de venta</h6>
            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="metodo_pago" class="form-label">Método de Pago *</label>
                  <select class="form-select" name="id_metodopago" id="metodo_pago" required>
                    <option value="">Seleccione un método de pago</option>
                    <?php 
                    // Consulta para obtener métodos de pago activos
                    if(isset($metodos_pago) && !empty($metodos_pago)): 
                      foreach($metodos_pago as $metodo): 
                    ?>
                      <option value="<?php echo $metodo['id_metodopago']; ?>">
                        <?php echo htmlspecialchars($metodo['descripcion']); ?>
                      </option>
                    <?php 
                      endforeach; 
                    else: 
                    ?>
                      <option value="" disabled>No hay métodos de pago disponibles</option>
                    <?php endif; ?>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="metodo_entrega" class="form-label">Método de Entrega *</label>
                  <select class="form-select" name="id_entrega" id="metodo_entrega" required>
                    <option value="">Seleccione un método de entrega</option>
                    <?php 
                    // Consulta para obtener métodos de entrega activos
                    if(isset($metodos_entrega) && !empty($metodos_entrega)): 
                      foreach($metodos_entrega as $metodo): 
                    ?>
                      <option value="<?php echo $metodo['id_entrega']; ?>">
                        <?php echo htmlspecialchars($metodo['nombre']); ?>
                      </option>
                    <?php 
                      endforeach; 
                    else: 
                    ?>
                      <option value="" disabled>No hay métodos de entrega disponibles</option>
                    <?php endif; ?>
                  </select>
                </div>
              </div>
            </div>
            
            <!-- Campos adicionalessegún el método de pago seleccionado -->
            <div class="row" id="campos_pago_adicionales" style="display: none;">
              <div class="col-md-3">
                <div class="mb-3">
                  <label for="referencia_bancaria" class="form-label">Referencia Bancaria</label>
                  <input type="number" class="form-control" name="referencia_bancaria" id="referencia_bancaria" 
                        placeholder="Número de referencia">
                </div>
              </div>
              <div class="col-md-3">
                <div class="mb-3">
                  <label for="telefono_emisor" class="form-label">Teléfono Emisor</label>
                  <input type="text" class="form-control" name="telefono_emisor" id="telefono_emisor" 
                        placeholder="Teléfono del emisor">
                </div>
              </div>
              <div class="col-md-3">
                <div class="mb-3">
                  <label for="banco" class="form-label">Banco Emisor</label>
                  <select class="form-select" name="banco" id="banco" placeholder="Seleccione banco emisor">
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
                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <div class="mb-3">
                  <label for="banco_destino" class="form-label">Banco Receptor</label>
                  <select class="form-select" name="banco_destino" id="banco_destino" placeholder="Seleccione banco receptor">
                    <option value="">Seleccione banco receptor</option>
                    <option value="0102-Banco De Venezuela">0102-Banco De Venezuela</option>
                    <option value="0105-Banco Mercantil">0105-Banco Mercantil</option>
                  </select>
                </div>
              </div>
            </div>

            <!-- Campo de dirección para delivery -->
            <div class="row" id="campo_direccion" style="display: none;">
              <div class="col-md-12">
                <div class="mb-3">
                  <label for="direccion" class="form-label">Dirección de Entrega</label>
                  <textarea class="form-control" name="direccion" id="direccion" 
                           placeholder="Ingrese la dirección de entrega completa" rows="3"></textarea>
                </div>
              </div>
            </div>
          </div>

          <!-- Sección: Productos -->
          <div class="mb-4">
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

<!-- Script para confirmar cambios -->
<script>
// Cuando el documento esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Para el formulario de edición
    document.querySelectorAll('[id^="formEditarVenta"]').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Confirmar cambios?',
                text: "¿Está seguro de guardar los cambios en esta venta?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, guardar cambios',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Asegurarse de que el formulario tenga todos los campos necesarios
                    if (!form.querySelector('[name="id_pedido"]').value || 
                        !form.querySelector('[name="estado_pedido"]').value) {
                        Swal.fire({
                            title: 'Error',
                            text: 'Faltan datos requeridos',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                        return;
                    }
                    // Enviar el formulario
                    form.submit();
                }
            });
        });
    });
});
</script>
</main>
</body>
</html>