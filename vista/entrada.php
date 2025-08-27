<!DOCTYPE html>
<html lang="es">

<head>
  <!-- php barra de navegacion-->
  <?php include 'complementos/head.php' ?> 
  <title> Compra | LoveMakeup  </title> 
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <style>
    @media (forced-colors: active) {
      .modal-header .btn-close {
        border: 2px solid currentColor;
      }
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

    /* ====== ESTILOS MODAL PRODUCTO ====== */
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
      background: linear-gradient(135deg, #495057 0%, #343a40 100%);
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(108, 117, 125, 0.3);
    }
    /* Forzar el color rojo del botón de eliminar producto y quitar transición */
    .btn-danger.remover-producto, .btn-danger.remover-producto:active, .btn-danger.remover-producto:focus, .btn-danger.remover-producto:visited {
      background-color: #dc3545 !important;
      border-color: #dc3545 !important;
      color: #fff !important;
      box-shadow: none !important;
      transition: none !important;
    }

    /* Estilos para el buscador de productos */
    .producto-search-container {
      position: relative;
    }
    
    .producto-search {
      border: 2px solid #e9ecef;
      border-radius: 8px;
      padding: 8px 12px;
      font-size: 0.9rem;
      background-color: #f8f9fa;
      transition: all 0.3s ease;
      margin-bottom: 8px;
    }
    
    .producto-search:focus {
      border-color: #f6c5b4;
      box-shadow: 0 0 0 0.2rem rgba(246, 197, 180, 0.25);
      background-color: white;
    }
    
    .producto-search::placeholder {
      color: #6c757d;
      font-style: italic;
    }
    
    /* Mostrar todas las opciones por defecto */
    .producto-select option {
      display: block;
    }
    
    /* Estilo para opciones filtradas (opcional - para resaltar) */
    .producto-select option.filtered {
      background-color: #f8f9fa;
      color: #6c757d;
    }
  </style>
</head>

<body class="g-sidenav-show bg-gray-100">
  
<!-- php barra de navegacion--> 
<?php include 'complementos/sidebar.php' ?>

<main class="main-content position-relative border-radius-lg ">
<?php if (isset($_SESSION['message'])): ?>
  <div class="alert alert-<?php echo $_SESSION['message']['icon'] === 'success' ? 'success' : ($_SESSION['message']['icon'] === 'error' ? 'danger' : ($_SESSION['message']['icon'] === 'warning' ? 'warning' : 'info')); ?> text-center" role="alert" style="display:none;">
    <?php echo htmlspecialchars($_SESSION['message']['text']); ?>
  </div>
  <?php unset($_SESSION['message']); ?>
<?php endif; ?>
<!-- ||| Navbar ||-->
<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur" data-scroll="false">
  <div class="container-fluid py-1 px-3">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="#">Administrar</a></li>
        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Compra</li>
      </ol>
      <h6 class="font-weight-bolder text-white mb-0">Gestionar Compra</h6>
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
       <h4 class="mb-0"><i class="fa-solid fa-cart-plus mr-2" style="color: #f6c5b4;"></i>
        Compra</h4>
           
       <!-- Button que abre el Modal N1 Registro -->
       <div class="d-flex gap-2">
          <?php if ($_SESSION["nivel_rol"] == 3 && tieneAcceso(2, 'registrar')): ?>
          <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#registroModal">
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
          

      <div class="table-responsive"> <!-- comienzo div table-->
           <!-- comienzo de tabla-->                      
          <table class="table table-bordered table-hover" id="myTable" width="100%" cellspacing="0">
              <thead class="table-color">
                <tr>
                  <th class="text-white">Producto</th>
                  <th class="text-white">Fecha Entrada</th>
                  <th class="text-white">Proveedor</th>
                  <th class="text-white">Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php if(isset($compras) && !empty($compras)): ?>
                  <?php foreach($compras as $compra): ?>
                    <?php 
                    // Obtener el primer producto de la compra para mostrar en la tabla principal
                    $resultadoDetalles = $entrada->procesarCompra(json_encode([
                        'operacion' => 'consultarDetalles',
                        'datos' => ['id_compra' => $compra['id_compra']]
                    ]));
                    $detalles_producto = $resultadoDetalles['datos'];
                    $primer_producto = !empty($detalles_producto) ? $detalles_producto[0]['producto_nombre'] : 'Sin productos';
                    ?>
                    <tr>
                      <td><?php echo $primer_producto; ?></td>
                      <td><?php echo date('d/m/Y', strtotime($compra['fecha_entrada'])); ?></td>
                      <td><?php echo $compra['proveedor_nombre']; ?></td>
                      <td class="text-center">
                        
                         <?php if ($_SESSION["nivel_rol"] == 3 && tieneAcceso(2, 'editar')): ?>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editarModal<?php echo $compra['id_compra']; ?>">
                          <i class="fas fa-pencil-alt" title="Editar"></i>
                        </button>
                          <?php endif; ?>

                       
                        <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#verDetallesModal<?php echo $compra['id_compra']; ?>">
                          <i class="fas fa-eye" title="Ver detalles"></i>
                        </button>
                          
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="4" class="text-center">No hay compras registradas</td>
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
<?php if(isset($compras) && !empty($compras)): ?>
  <?php foreach($compras as $compra): ?>
    <!-- Modal para Ver Detalles -->
    <div class="modal fade" id="verDetallesModal<?php echo $compra['id_compra']; ?>" tabindex="-1" aria-labelledby="verDetallesModalLabel<?php echo $compra['id_compra']; ?>" aria-hidden="true">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header header-color">
            <h5 class="modal-title text-white" id="verDetallesModalLabel<?php echo $compra['id_compra']; ?>">
              Detalles de la compra
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <!-- Información de Fecha y Hora -->
            <div class="row mb-4">
              <div class="col-12">
                <div class="card">
                  <div class="card-header bg-light" data-bs-toggle="collapse" data-bs-target="#collapse-fecha-<?php echo $compra['id_compra']; ?>" aria-expanded="false" aria-controls="collapse-fecha-<?php echo $compra['id_compra']; ?>" style="cursor: pointer;">
                    <h6 class="mb-0">
                      <i class="fas fa-calendar-alt" style="color: #f6c5b4;"></i> Fecha y Hora de Registro
                      <i class="fas fa-chevron-down float-end" style="font-size: 0.8em;"></i>
                    </h6>
                  </div>
                  <div class="collapse" id="collapse-fecha-<?php echo $compra['id_compra']; ?>">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-6">
                          <strong>Fecha de Entrada:</strong> <?php echo date('d/m/Y', strtotime($compra['fecha_entrada'])); ?>
                        </div>
                        <div class="col-md-6">
                          <strong>Hora de Entrada:</strong> <?php echo date('H:i:s', strtotime($compra['fecha_entrada'])); ?>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Información Detallada del Proveedor -->
            <div class="row mb-4">
              <div class="col-12">
                <div class="card">
                  <div class="card-header bg-light" data-bs-toggle="collapse" data-bs-target="#collapse-proveedor-detalle-<?php echo $compra['id_compra']; ?>" aria-expanded="false" aria-controls="collapse-proveedor-detalle-<?php echo $compra['id_compra']; ?>" style="cursor: pointer;">
                    <h6 class="mb-0">
                      <i class="fas fa-truck" style="color: #f6c5b4;"></i> Información del Proveedor
                      <i class="fas fa-chevron-down float-end" style="font-size: 0.8em;"></i>
                    </h6>
                  </div>
                  <div class="collapse" id="collapse-proveedor-detalle-<?php echo $compra['id_compra']; ?>">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-6">
                          <strong>Nombre del Proveedor:</strong><br>
                          <?php echo htmlspecialchars($compra['proveedor_nombre']); ?>
                        </div>
                        <div class="col-md-6">
                          <strong>Teléfono del Proveedor:</strong><br>
                          <?php echo htmlspecialchars($compra['proveedor_telefono']); ?>
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
                  <div class="card-header bg-light" data-bs-toggle="collapse" data-bs-target="#collapse-productos-detalle-<?php echo $compra['id_compra']; ?>" aria-expanded="false" aria-controls="collapse-productos-detalle-<?php echo $compra['id_compra']; ?>" style="cursor: pointer;">
                    <h6 class="mb-0">
                      <i class="fas fa-shopping-cart" style="color: #f6c5b4;"></i> Productos de la Compra
                      <i class="fas fa-chevron-down float-end" style="font-size: 0.8em;"></i>
                    </h6>
                  </div>
                  <div class="collapse" id="collapse-productos-detalle-<?php echo $compra['id_compra']; ?>">
                    <div class="card-body">
                      <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                          <thead class="table-color">
                            <tr>
                              <th class="text-white">#</th>
                              <th class="text-white">Producto</th>
                              <th class="text-white">Marca</th>
                              <th class="text-center text-white">Cantidad</th>
                              <th class="text-center text-white">Precio Unitario</th>
                              <th class="text-center text-white">Precio Total</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                            $resultadoDetalles = $entrada->procesarCompra(json_encode([
                                'operacion' => 'consultarDetalles',
                                'datos' => ['id_compra' => $compra['id_compra']]
                            ]));
                            $detalles_compra = $resultadoDetalles['datos'];
                            $total_compra = 0;
                            $contador = 1;
                            foreach($detalles_compra as $detalle): 
                              $total_compra += $detalle['precio_total'];
                            ?>
                            <tr>
                              <td class="text-center"><?php echo $contador++; ?></td>
                              <td><?php echo htmlspecialchars($detalle['producto_nombre']); ?></td>
                              <td><?php echo htmlspecialchars($detalle['marca']); ?></td>
                              <td class="text-center"><?php echo $detalle['cantidad']; ?></td>
                              <td class="text-center">$<?php echo number_format($detalle['precio_unitario'], 2); ?></td>
                              <td class="text-center">$<?php echo number_format($detalle['precio_total'], 2); ?></td>
                            </tr>
                            <?php endforeach; ?>
                          </tbody>
                          <tfoot class="table-light">
                            <tr>
                              <th colspan="5" class="text-end">Total USD:</th>
                              <th class="text-center">$<?php echo number_format($total_compra, 2); ?></th>
                            </tr>
                          </tfoot>
                        </table>
                      </div>
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

<!-- Modal para Editar -->
<?php if(isset($compras) && !empty($compras)): ?>
  <?php foreach($compras as $compra): ?>
    <div class="modal fade" id="editarModal<?php echo $compra['id_compra']; ?>" tabindex="-1" aria-labelledby="editarModalLabel<?php echo $compra['id_compra']; ?>" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header header-color">
            <h5 class="modal-title" id="editarModalLabel<?php echo $compra['id_compra']; ?>">Editar Entrada</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form method="POST" action="" name="editar_compra">
              <input type="hidden" name="id_compra" value="<?php echo $compra['id_compra']; ?>">
              
              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="fecha_entrada" class="form-label">Fecha de entrada</label>
                  <input type="date" class="form-control" id="fecha_entrada" name="fecha_entrada" value="<?php echo $compra['fecha_entrada']; ?>" required>
                </div>
                <div class="col-md-6">
                  <label for="id_proveedor" class="form-label">Proveedor</label>
                  <select class="form-select" id="id_proveedor" name="id_proveedor" required>
                    <option value="">Seleccione un proveedor</option>
                    <?php foreach($proveedores as $proveedor): ?>
                      <option value="<?php echo $proveedor['id_proveedor']; ?>" <?php echo ($proveedor['id_proveedor'] == $compra['id_proveedor']) ? 'selected' : ''; ?>>
                        <?php echo $proveedor['nombre']; ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              
              <div class="mb-3">
                <h6>Productos</h6>
                <div id="productos-container-edit<?php echo $compra['id_compra']; ?>">
                  <?php 
                  $resultadoDetalles = $entrada->procesarCompra(json_encode([
                      'operacion' => 'consultarDetalles',
                      'datos' => ['id_compra' => $compra['id_compra']]
                  ]));
                  $detalles_compra = $resultadoDetalles['datos'];
                  foreach($detalles_compra as $index => $detalle): 
                  ?>
                    <div class="row mb-2 producto-fila">
                      <div class="col-md-4">
                        <label class="form-label">Producto</label>
                        <select class="form-select producto-select" name="id_producto[]" disabled>
                          <option value="" selected>Seleccione un producto</option>
                          <?php foreach($productos_lista as $producto): ?>
                            <option value="<?php echo $producto['id_producto']; ?>" 
                                    <?php echo ($producto['id_producto'] == $detalle['id_producto']) ? 'selected' : ''; ?>
                                    data-stock-actual="<?php echo $producto['stock_disponible']; ?>"
                                    data-search-text="<?php echo strtolower($producto['nombre'] . ' ' . $producto['marca']); ?>">
                              <?php echo $producto['nombre'] . ' - ' . $producto['marca']; ?>
                            </option>
                          <?php endforeach; ?>
                        </select>
                        <input type="hidden" name="id_producto[]" value="<?php echo $detalle['id_producto']; ?>">
                      </div>
                      <div class="col-md-2">
                        <label class="form-label">Cantidad</label>
                        <input type="number" class="form-control cantidad-input" name="cantidad[]" placeholder="Cantidad" value="<?php echo $detalle['cantidad']; ?>" min="1" required>
                      </div>
                      <div class="col-md-2">
                        <label class="form-label">Precio Unit.</label>
                        <input type="number" step="0.01" class="form-control precio-input" name="precio_unitario[]" placeholder="Precio Unitario" value="<?php echo $detalle['precio_unitario']; ?>" min="0.01" required readonly>
                      </div>
                      <div class="col-md-2">
                        <label class="form-label">Precio Total</label>
                        <input type="number" step="0.01" class="form-control precio-total" name="precio_total[]" placeholder="Precio Total" value="<?php echo $detalle['precio_total']; ?>" readonly>
                      </div>
                      <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button type="button" class="btn btn-danger remover-producto form-control">
                          <i class="fas fa-trash-alt"></i>
                        </button>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
                <div class="mt-2">
                  <button type="button" class="btn btn-success agregar-producto-edit" data-container="productos-container-edit<?php echo $compra['id_compra']; ?>">
                    <i class="fas fa-plus"></i> Agregar Producto
                  </button>
                </div>
              </div>
              
              <div class="text-center mt-4">
                <button type="submit" name="modificar_compra" class="btn btn-primary">Guardar Cambios</button>
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
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content modal-producto">
      <div class="modal-header">
        <div class="d-flex justify-content-between align-items-center w-100">
          <h5 class="modal-title d-flex align-items-center gap-2" id="registroModalLabel">
           Registrar compra
          </h5>
          <button type="button" class="btn-close ms-2" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
      </div>
      <div class="modal-body">
        <form method="POST" action="" name="registrar_compra">
          <!-- Sección: Datos Básicos de la Compra -->
          <div class="seccion-formulario">
            <h6><i class="fas fa-info-circle"></i> Datos de la Compra</h6>
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="fecha_entrada_reg" class="form-label">Fecha de Entrada</label>
                <input type="date" class="form-control" id="fecha_entrada_reg" name="fecha_entrada" value="<?php echo date('Y-m-d'); ?>" required>
              </div>
              <div class="col-md-6">
                <label for="id_proveedor_reg" class="form-label">Proveedor</label>
                <select class="form-select" id="id_proveedor_reg" name="id_proveedor" required>
                  <option value="">Seleccione un proveedor</option>
                  <?php foreach($proveedores as $proveedor): ?>
                    <option value="<?php echo $proveedor['id_proveedor']; ?>"><?php echo $proveedor['nombre']; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>
          <!-- Sección: Productos -->
          <div class="seccion-formulario">
            <h6><i class="fas fa-boxes"></i> Productos</h6>
            <div id="productos-container">
              <div class="row mb-2 producto-fila">
                <div class="col-md-4">
                  <label class="form-label">Producto</label>
                  <!-- Buscador de productos -->
                  <div class="producto-search-container">
                    <input type="text" class="form-control producto-search" placeholder="Buscar producto..." style="margin-bottom: 5px;">
                    <select class="form-select producto-select" name="id_producto[]" required>
                      <option value="" selected>Seleccione un producto</option>
                      <?php foreach($productos_lista as $producto): ?>
                        <option value="<?php echo $producto['id_producto']; ?>" 
                                data-stock-actual="<?php echo $producto['stock_disponible']; ?>"
                                data-search-text="<?php echo strtolower($producto['nombre'] . ' ' . $producto['marca']); ?>">
                          <?php echo $producto['nombre'] . ' - ' . $producto['marca']; ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-2">
                  <label class="form-label">Cantidad</label>
                  <input type="number" class="form-control cantidad-input" name="cantidad[]" placeholder="Cantidad" value="1" min="1" required>
                </div>
                <div class="col-md-2">
                  <label class="form-label">Precio Unit.</label>
                  <input type="number" step="0.01" class="form-control precio-input" name="precio_unitario[]" placeholder="Precio Unitario" value="0.00" min="0.01" required>
                </div>
                <div class="col-md-2">
                  <label class="form-label">Precio Total</label>
                  <input type="number" step="0.01" class="form-control precio-total" name="precio_total[]" placeholder="Precio Total" value="0.00" readonly>
                </div>
                <div class="col-md-2">
                  <label class="form-label">&nbsp;</label>
                  <button type="button" class="btn btn-danger remover-producto form-control">
                    <i class="fas fa-trash-alt"></i>
                  </button>
                </div>
              </div>
            </div>
            <div class="mt-2">
              <button type="button" class="btn btn-success" id="agregar-producto">
                <i class="fas fa-plus"></i> Agregar Producto
              </button>
            </div>
          </div>
          <div class="text-center mt-4">
            <button type="submit" name="registrar_compra" class="btn btn-modern btn-guardar me-3"><i class="fas fa-save me-2"></i>Registrar</button>
            <button type="reset" class="btn btn-modern btn-limpiar"><i class="fas fa-eraser me-2"></i>Limpiar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

</div>  
</div>  
<!-- php barra de navegacion-->
<?php include 'complementos/footer.php' ?>

<!-- jQuery -->
<script src="assets/js/libreria/jquery.min.js"></script>

<!-- Script para el cálculo de precios -->
<script src="assets/js/entrada.js"></script>

<!-- Script para inicializar DataTable -->
<script src="assets/js/demo/datatables-demo.js"></script>

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

    // Funcionalidad de búsqueda de productos
    function configurarBuscadorProductos() {
        document.querySelectorAll('.producto-search').forEach(buscador => {
            buscador.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                const select = this.nextElementSibling;
                const options = select.querySelectorAll('option[data-search-text]');
                
                // Si no hay término de búsqueda, mostrar todas las opciones
                if (searchTerm === '') {
                    options.forEach(option => {
                        option.style.display = 'block';
                        option.classList.remove('filtered');
                    });
                    return;
                }
                
                // Filtrar opciones y mostrar solo las que coinciden
                options.forEach(option => {
                    const searchText = option.getAttribute('data-search-text');
                    if (searchText.includes(searchTerm)) {
                        option.style.display = 'block';
                        option.classList.remove('filtered');
                    } else {
                        option.style.display = 'none';
                        option.classList.add('filtered');
                    }
                });
                
                // Mostrar la opción por defecto
                const defaultOption = select.querySelector('option[value=""]');
                if (defaultOption) {
                    defaultOption.style.display = 'block';
                }
            });
            
            // Limpiar búsqueda cuando se selecciona un producto
            buscador.addEventListener('change', function() {
                const select = this.nextElementSibling;
                if (select.value !== '') {
                    this.value = select.options[select.selectedIndex].text;
                }
            });
            
            // Limpiar búsqueda cuando se hace clic en el select
            select.addEventListener('click', function() {
                if (buscador.value !== '') {
                    // Mostrar todas las opciones cuando se hace clic en el select
                    const options = this.querySelectorAll('option[data-search-text]');
                    options.forEach(option => {
                        option.style.display = 'block';
                        option.classList.remove('filtered');
                    });
                }
            });
            
            // Restaurar filtro cuando se cierra el select
            select.addEventListener('blur', function() {
                setTimeout(() => {
                    if (buscador.value !== '') {
                        const searchTerm = buscador.value.toLowerCase().trim();
                        const options = this.querySelectorAll('option[data-search-text]');
                        
                        options.forEach(option => {
                            const searchText = option.getAttribute('data-search-text');
                            if (searchText.includes(searchTerm)) {
                                option.style.display = 'block';
                                option.classList.remove('filtered');
                            } else {
                                option.style.display = 'none';
                                option.classList.add('filtered');
                            }
                        });
                    }
                }, 200); // Pequeño delay para permitir la selección
            });
        });
    }

    // Configurar buscadores cuando se abre el modal de registro
    const registroModal = document.getElementById('registroModal');
    if (registroModal) {
        registroModal.addEventListener('shown.bs.modal', function() {
            configurarBuscadorProductos();
        });
    }

    // Configurar buscadores cuando se abre cualquier modal de edición
    document.querySelectorAll('[id^="editarModal"]').forEach(modal => {
        modal.addEventListener('shown.bs.modal', function() {
            configurarBuscadorProductos();
        });
    });

    // Configurar buscadores inicialmente si ya están en el DOM
    configurarBuscadorProductos();

    // Función para agregar nueva fila de producto con buscador
    function agregarFilaProducto() {
        const container = document.getElementById('productos-container');
        const nuevaFila = document.createElement('div');
        nuevaFila.className = 'row mb-2 producto-fila';
        
        nuevaFila.innerHTML = `
            <div class="col-md-4">
                <label class="form-label">Producto</label>
                <!-- Buscador de productos -->
                <div class="producto-search-container">
                    <input type="text" class="form-control producto-search" placeholder="Buscar producto..." style="margin-bottom: 5px;">
                    <select class="form-select producto-select" name="id_producto[]" required>
                        <option value="" selected>Seleccione un producto</option>
                        ${Array.from(document.querySelector('.producto-select').options).map(option => 
                            option.outerHTML
                        ).join('')}
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <label class="form-label">Cantidad</label>
                <input type="number" class="form-control cantidad-input" name="cantidad[]" placeholder="Cantidad" value="1" min="1" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">Precio Unit.</label>
                <input type="number" step="0.01" class="form-control precio-input" name="precio_unitario[]" placeholder="Precio Unitario" value="0.00" min="0.01" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">Precio Total</label>
                <input type="number" step="0.01" class="form-control precio-total" name="precio_total[]" placeholder="Precio Total" value="0.00" readonly>
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <button type="button" class="btn btn-danger remover-producto form-control">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
        `;
        
        container.appendChild(nuevaFila);
        
        // Configurar el buscador para la nueva fila
        configurarBuscadorProductos();
        
        // Configurar eventos para la nueva fila
        configurarEventosProducto(nuevaFila);
    }

    // Configurar eventos para una fila de producto
    function configurarEventosProducto(fila) {
        const select = fila.querySelector('.producto-select');
        const cantidadInput = fila.querySelector('.cantidad-input');
        const precioInput = fila.querySelector('.precio-input');
        const precioTotalInput = fila.querySelector('.precio-total');
        
        // Evento para cambio de cantidad o precio
        [cantidadInput, precioInput].forEach(input => {
            input.addEventListener('input', calcularPrecioTotal);
        });
        
        function calcularPrecioTotal() {
            const cantidad = parseInt(cantidadInput.value) || 0;
            const precio = parseFloat(precioInput.value) || 0;
            const total = cantidad * precio;
            precioTotalInput.value = total.toFixed(2);
        }
    }

    // Configurar eventos para botones de agregar/remover productos
    document.addEventListener('click', function(e) {
        if (e.target.id === 'agregar-producto') {
            agregarFilaProducto();
        } else if (e.target.classList.contains('remover-producto')) {
            const fila = e.target.closest('.producto-fila');
            if (document.querySelectorAll('.producto-fila').length > 1) {
                fila.remove();
            }
        }
    });

    // Configurar eventos para la primera fila de producto
    const primeraFila = document.querySelector('.producto-fila');
    if (primeraFila) {
        configurarEventosProducto(primeraFila);
    }
});
</script>
</body>

</html>