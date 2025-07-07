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
            <form method="POST" action="">
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
                        <select class="form-select producto-select" name="id_producto[]" required>
                          <option value="">Seleccione un producto</option>
                          <?php foreach($productos_lista as $producto): ?>
                            <option value="<?php echo $producto['id_producto']; ?>" 
                                     
                                    data-stock-actual="<?php echo $producto['stock_disponible']; ?>">
                              <?php echo $producto['nombre'] . ' - ' . $producto['marca']; ?>
                            </option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                      <div class="col-md-2">
                        <label class="form-label">Cantidad</label>
                        <input type="number" class="form-control cantidad-input" name="cantidad[]" placeholder="Cantidad" value="<?php echo $detalle['cantidad']; ?>" min="1" required>
                      </div>
                      <div class="col-md-2">
                        <label class="form-label">Precio Unit.</label>
                        <input type="number" step="0.01" class="form-control precio-input" name="precio_unitario[]" placeholder="Precio Unitario" value="<?php echo $detalle['precio_unitario']; ?>" min="0.01" required>
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
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header header-color">
        <h5 class="modal-title" id="registroModalLabel">Registrar compra</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="POST" action="">
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
          
          <div class="mb-3">
            <h6>Productos</h6>
            <div id="productos-container">
              <div class="row mb-2 producto-fila">
                <div class="col-md-4">
                  <label class="form-label">Producto</label>
                  <select class="form-select producto-select" name="id_producto[]" required>
                    <option value="">Seleccione un producto</option>
                    <?php foreach($productos_lista as $producto): ?>
                      <option value="<?php echo $producto['id_producto']; ?>" 
                              data-stock-actual="<?php echo $producto['stock_disponible']; ?>">
                        <?php echo $producto['nombre'] . ' - ' . $producto['marca']; ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
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
            <button type="submit" name="registrar_compra" class="btn btn-primary">Registrar</button>
            <button type="reset" class="btn btn-secondary">Limpiar</button>
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
});
</script>

</body>

</html>