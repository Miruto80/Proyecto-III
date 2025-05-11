<!DOCTYPE html>
<html lang="es">

<head>
  <!-- php barra de navegacion-->
  <?php include 'complementos/head.php' ?> 
  <title> Compra | LoveMakeup  </title> 
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
        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Compra</li>
      </ol>
      <h6 class="font-weight-bolder text-white mb-0">Gestionar Compra</h6>
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
       <h4 class="mb-0"><i class="fa-solid fa-cart-plus mr-2" style="color: #f6c5b4;"></i>
        Compra</h4>
           
       <!-- Button que abre el Modal N1 Registro -->
          <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#registroModal">
            <span class="icon text-white">
            <i class="fas fa-file-medical"></i>
            </span>
            <span class="text-white">Registrar</span>
          </button>
      </div>
          

      <div class="table-responsive"> <!-- comienzo div table-->
           <!-- comienzo de tabla-->                      
          <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
              <thead class="table-color">
                <tr>
                  <th class="text-white">ID</th>
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
                    $detalles_producto = $entrada->consultarDetalles($compra['id_compra']);
                    $primer_producto = !empty($detalles_producto) ? $detalles_producto[0]['producto_nombre'] : 'Sin productos';
                    ?>
                    <tr>
                      <td><?php echo $compra['id_compra']; ?></td>
                      <td><?php echo $primer_producto; ?></td>
                      <td><?php echo date('d/m/Y', strtotime($compra['fecha_entrada'])); ?></td>
                      <td><?php echo $compra['proveedor_nombre']; ?></td>
                      <td class="text-center">
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editarModal<?php echo $compra['id_compra']; ?>">
                          <i class="fas fa-pencil-alt" title="Editar"></i>
                        </button>
                        <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#verDetallesModal<?php echo $compra['id_compra']; ?>">
                          <i class="fas fa-eye" title="Ver detalles"></i>
                        </button>
                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#eliminarModal<?php echo $compra['id_compra']; ?>">
                          <i class="fas fa-trash-alt" title="Eliminar"></i>
                        </button>
                      </td>
                    </tr>

                    <!-- Modal para Ver Detalles -->
                    <div class="modal fade" id="verDetallesModal<?php echo $compra['id_compra']; ?>" tabindex="-1" aria-labelledby="verDetallesModalLabel<?php echo $compra['id_compra']; ?>" aria-hidden="true">
                      <div class="modal-dialog modal-lg">
                          <div class="modal-content">
                              <div class="modal-header header-color">
                                  <h5 class="modal-title" id="verDetallesModalLabel<?php echo $compra['id_compra']; ?>">Detalles de la compra</h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <div class="modal-body">
                                  <div class="row mb-3">
                                      <div class="col-md-6">
                                          <strong>Fecha:</strong> <?php echo date('d/m/Y', strtotime($compra['fecha_entrada'])); ?>
                                      </div>
                                      <div class="col-md-6">
                                          <strong>Proveedor:</strong> <?php echo $compra['proveedor_nombre']; ?>
                                      </div>
                                  </div>
                                  
                                  <?php
                                  $detalles_compra = $entrada->consultarDetalles($compra['id_compra']);
                                  foreach($detalles_compra as $detalle):
                                  ?>
                                  
                                  <hr style="border-top: 2px solid #ccc;">
                                  <div class="row mb-3">
                                      <div class="col-md-6">
                                          <strong>Producto:</strong> <?php echo $detalle['producto_nombre']; ?>
                                      </div>
                                      <div class="col-md-6">
                                          <strong>Marca:</strong> <?php echo $detalle['marca']; ?>
                                      </div>
                                      <div class="col-md-6">
                                          <strong>Cantidad:</strong> <?php echo $detalle['cantidad']; ?>
                                      </div>
                                      <div class="col-md-6">
                                          <strong>Precio Unitario:</strong> <?php echo number_format($detalle['precio_unitario'], 2); ?>
                                      </div>
                                      <div class="col-md-6">
                                          <strong>Precio Total:</strong> <?php echo number_format($detalle['precio_total'], 2); ?>
                                      </div>

                                  </div>
                                  <?php endforeach; ?>
                              </div>
                          </div>
                      </div>
                  </div>

                    <!-- Modal para Editar -->
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
                                  $detalles = $entrada->consultarDetalles($compra['id_compra']);
                                  foreach($detalles as $index => $detalle): 
                                  ?>
                                    <div class="row mb-2 producto-fila">
                                      <div class="col-md-4">
                                        <label class="form-label">Producto</label>
                                        <select class="form-select producto-select" name="id_producto[]" required>
                                          <option value="">Seleccione un producto</option>
                                          <?php foreach($productos_lista as $producto): ?>
                                            <option value="<?php echo $producto['id_producto']; ?>" <?php echo ($producto['id_producto'] == $detalle['id_producto']) ? 'selected' : ''; ?>>
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

                    <!-- Modal para Eliminar -->
                    <div class="modal fade" id="eliminarModal<?php echo $compra['id_compra']; ?>" tabindex="-1" aria-labelledby="eliminarModalLabel<?php echo $compra['id_compra']; ?>" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header header-color">
                            <h5 class="modal-title" id="eliminarModalLabel<?php echo $compra['id_compra']; ?>">Confirmar Eliminación</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            <p>¿Está seguro que desea eliminar la compra? Esta acción no se puede deshacer y afectará al inventario.</p>
                            <form method="POST" action="">
                              <input type="hidden" name="id_compra" value="<?php echo $compra['id_compra']; ?>">
                              <div class="text-center mt-4">
                                <button type="submit" name="eliminar_compra" class="btn btn-danger">Eliminar</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="5" class="text-center">No hay compras registradas</td>
                  </tr>
                <?php endif; ?>
              </tbody>
          </table> <!-- Fin tabla--> 
      </div>  <!-- Fin div table-->


            </div><!-- FIN CARD N-1 -->  
    </div>
    </div>  
    </div><!-- FIN CARD PRINCIPAL-->  


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
                      <option value="<?php echo $producto['id_producto']; ?>">
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

</body>

</html>