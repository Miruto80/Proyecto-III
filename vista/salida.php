<!DOCTYPE html>
<html lang="es">

<head>
  <!-- php barra de navegacion-->
  <?php include 'complementos/head.php' ?> 
  <title> Venta | LoveMakeup  </title> 
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
      <h6 class="font-weight-bolder text-white mb-0">Gestionar Ventas</h6>
    </nav>
<!-- php barra de navegacion-->    
<?php include 'complementos/nav.php' ?>
  </div>
</nav>

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
                <h4 class="mb-0"><i class="fa-solid fa-cash-register mr-2" style="color: #f6c5b4;"></i>
                  Venta</h4>
           
                <!-- Button que abre el Modal N1 Registro -->
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalRegistro">
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
                      <th class="text-white">Fecha</th>
                      <th class="text-white">Estado</th>
                      <th class="text-white">Total</th>
                      <th class="text-white">Método de Pago</th>
                      <th class="text-white">Entrega</th>
                      <th class="text-white">Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($pedidos as $pedido): ?>
                      <tr>
                        <td><?php echo $pedido['id_pedido']; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($pedido['fecha'])); ?></td>
                        <td>
                          <span class="badge <?php 
                            switch($pedido['estado']) {
                              case 'pendiente': echo 'bg-warning'; break;
                              case 'aprobado': echo 'bg-success'; break;
                              case 'rechazado': echo 'bg-danger'; break;
                              case 'enviado': echo 'bg-info'; break;
                              case 'entregado': echo 'bg-primary'; break;
                              default: echo 'bg-secondary';
                            }
                          ?>">
                            <?php echo ucfirst($pedido['estado']); ?>
                          </span>
                        </td>
                        <td><?php echo number_format($pedido['precio_total'], 2); ?></td>
                        <td><?php echo $pedido['metodo_pago']; ?></td>
                        <td><?php echo $pedido['metodo_entrega']; ?></td>
                        <td class="text-center">
                          <button class="btn btn-info btn-sm ver-detalles" 
                                  data-id="<?php echo $pedido['id_pedido']; ?>" 
                                  data-bs-toggle="modal" 
                                  data-bs-target="#modalDetalles">
                            <i class="fas fa-eye" title="Ver detalles"></i>
                          </button>

                          <button class="btn btn-primary btn-sm editar-pedido"
                                  data-id="<?php echo $pedido['id_pedido']; ?>" 
                                  data-bs-toggle="modal" 
                                  data-bs-target="#modalEditar">
                            <i class="fas fa-pencil-alt" title="Editar"></i>
                          </button>

                          <div class="dropdown d-inline">
                            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                              <i class="fas fa-cog"></i>
                            </button>
                            <ul class="dropdown-menu">
                              <?php 
                              $estados = ['pendiente', 'aprobado', 'rechazado', 'enviado', 'entregado'];
                              foreach($estados as $estado): 
                                if($estado != $pedido['estado']):
                              ?>
                                <li>
                                  <a class="dropdown-item cambiar-estado" href="#" 
                                     data-id="<?php echo $pedido['id_pedido']; ?>" 
                                     data-estado="<?php echo $estado; ?>">
                                    Cambiar a <?php echo ucfirst($estado); ?>
                                  </a>
                                </li>
                              <?php 
                                endif;
                              endforeach; 
                              ?>
                            </ul>
                          </div>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table> <!-- Fin tabla--> 
              </div>  <!-- Fin div table-->
            </div><!-- FIN CARD N-1 -->  
          </div>
        </div>  
    </div><!-- FIN CARD PRINCIPAL-->  

    <!-- Modal de Registro -->
    <div class="modal fade" id="modalRegistro" tabindex="-1" aria-labelledby="modalRegistroLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header header-color">
            <h5 class="modal-title" id="modalRegistroLabel">Registrar Nuevo Pedido</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="formRegistroPedido" method="POST" action="">
              <input type="hidden" name="registrar" value="1">
              
              <div class="row mb-3">
                <div class="col-md-6">
                  <label class="form-label">Método de Pago</label>
                  <select class="form-control" name="id_metodopago" required>
                    <option value="">Seleccione un método de pago</option>
                    <?php foreach($metodosPago as $metodo): ?>
                      <option value="<?php echo $metodo['id_metodopago']; ?>"><?php echo $metodo['nombre']; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Método de Entrega</label>
                  <select class="form-control" name="id_entrega" required>
                    <option value="">Seleccione un método de entrega</option>
                    <?php foreach($metodosEntrega as $metodo): ?>
                      <option value="<?php echo $metodo['id_entrega']; ?>"><?php echo $metodo['nombre']; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

              <div id="datos-pago" class="row mb-3" style="display:none;">
                <div class="col-md-4">
                  <label class="form-label">Referencia Bancaria</label>
                  <input type="text" class="form-control" name="referencia_bancaria">
                </div>
                <div class="col-md-4">
                  <label class="form-label">Teléfono Emisor</label>
                  <input type="text" class="form-control" name="telefono_emisor">
                </div>
                <div class="col-md-4">
                  <label class="form-label">Banco</label>
                  <input type="text" class="form-control" name="banco">
                </div>
              </div>

              <hr>
              <h5>Productos</h5>
              <div class="table-responsive">
                <table class="table" id="tablaProductos">
                  <thead>
                    <tr>
                      <th>Producto</th>
                      <th>Cantidad</th>
                      <th>Precio Unitario</th>
                      <th>Subtotal</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr class="fila-producto">
                      <td>
                        <select class="form-control select-producto" name="id_producto[]" required>
                          <option value="">Seleccione un producto</option>
                          <?php foreach($productos as $producto): ?>
                            
                            <option value="<?php echo $producto['id_producto']; ?>" 
                                    data-precio="<?php echo $producto['precio_unitario']; ?>"
                                    data-stock="<?php echo $producto['stock_disponible']; ?>">
                              <?php echo $producto['nombre'] . ' - ' . $producto['marca']; ?>
                             
                            </option>
                          <?php endforeach; ?>
                        </select>
                      </td>
                      <td>
                        <input type="number" class="form-control cantidad" name="cantidad[]" min="1" required>
                      </td>
                      <td>
                        <input type="number" step="0.01" class="form-control precio-unitario" name="precio_unitario[]" readonly>
                      </td>
                      <td>
                        <span class="subtotal">0.00</span>
                      </td>
                    
                    </tr>
                  </tbody>
                  <tfoot>
                    <tr>
                      <td colspan="3" class="text-end"><strong>Total:</strong></td>
                      <td><span id="total_general">0.00</span></td>
                      <td>
                        <input type="hidden" name="precio_total_general" id="precio_total_hidden" value="0">
                        <button type="button" class="btn btn-success btn-sm" id="agregarFilaProducto">
                          <i class="fas fa-plus"></i> Agregar producto
                        
                      </td>
                    </tr>
                  </tfoot>
                </table>
              </div>
              
              <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary">Registrar Pedido</button>
                <button type="reset" class="btn btn-secondary">Limpiar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de Detalles -->
    <div class="modal fade" id="modalDetalles" tabindex="-1" aria-labelledby="modalDetallesLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header header-color">
            <h5 class="modal-title" id="modalDetallesLabel">Detalles del Pedido</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6">
                <h6>Información del Pedido</h6>
                <table class="table table-sm">
                  <tr>
                    <th>ID Pedido:</th>
                    <td id="detalle-id-pedido"></td>
                  </tr>
                  <tr>
                    <th>Fecha:</th>
                    <td id="detalle-fecha"></td>
                  </tr>
                  <tr>
                    <th>Estado:</th>
                    <td id="detalle-estado"></td>
                  </tr>
                  <tr>
                    <th>Método de Pago:</th>
                    <td id="detalle-metodo-pago"></td>
                  </tr>
                  <tr>
                    <th>Método de Entrega:</th>
                    <td id="detalle-metodo-entrega"></td>
                  </tr>
                </table>
              </div>
              <div class="col-md-6" id="info-pago">
                <h6>Información de Pago</h6>
                <table class="table table-sm">
                  <tr>
                    <th>Referencia Bancaria:</th>
                    <td id="detalle-referencia"></td>
                  </tr>
                  <tr>
                    <th>Teléfono:</th>
                    <td id="detalle-telefono"></td>
                  </tr>
                  <tr>
                    <th>Banco:</th>
                    <td id="detalle-banco"></td>
                  </tr>
                </table>
              </div>
            </div>
            
            <hr>
            <h6>Productos</h6>
            <div class="table-responsive">
              <table class="table" id="tabla-detalles-productos">
                <thead>
                  <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Subtotal</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Se llenará mediante JavaScript -->
                </tbody>
                <tfoot>
                  <tr>
                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                    <td id="detalle-total"></td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de Edición -->
    <div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header header-color">
            <h5 class="modal-title" id="modalEditarLabel">Editar Pedido</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="formEditarPedido" method="POST" action="">
              <input type="hidden" name="actualizar" value="1">
              <input type="hidden" name="id_pedido" id="editar-id-pedido">
              
              <div class="row mb-3">
                <div class="col-md-6">
                  <label class="form-label">Estado</label>
                  <select class="form-control" name="estado" id="editar-estado" required>
                    <option value="pendiente">Pendiente</option>
                    <option value="aprobado">Aprobado</option>
                    <option value="rechazado">Rechazado</option>
                    <option value="enviado">Enviado</option>
                    <option value="entregado">Entregado</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Método de Pago</label>
                  <select class="form-control" name="id_metodopago" id="editar-metodopago" required>
                    <option value="">Seleccione un método de pago</option>
                    <?php foreach($metodosPago as $metodo): ?>
                      <option value="<?php echo $metodo['id_metodopago']; ?>"><?php echo $metodo['nombre']; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

              <div class="row mb-3">
                <div class="col-md-6">
                  <label class="form-label">Método de Entrega</label>
                  <select class="form-control" name="id_entrega" id="editar-entrega" required>
                    <option value="">Seleccione un método de entrega</option>
                    <?php foreach($metodosEntrega as $metodo): ?>
                      <option value="<?php echo $metodo['id_entrega']; ?>"><?php echo $metodo['nombre']; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

              <div id="editar-datos-pago" class="row mb-3">
                <div class="col-md-4">
                  <label class="form-label">Referencia Bancaria</label>
                  <input type="text" class="form-control" name="referencia_bancaria" id="editar-referencia">
                </div>
                <div class="col-md-4">
                  <label class="form-label">Teléfono Emisor</label>
                  <input type="text" class="form-control" name="telefono_emisor" id="editar-telefono">
                </div>
                <div class="col-md-4">
                  <label class="form-label">Banco</label>
                  <input type="text" class="form-control" name="banco" id="editar-banco">
                </div>
              </div>
              
              <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary">Actualizar Pedido</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

</div>

<!-- php barra de navegacion-->
<?php include 'complementos/footer.php' ?>

<!-- DataTables JavaScript -->
<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>

<!-- Script para salida.js -->
<script src="assets/js/salida.js"></script>

</body>

</html>