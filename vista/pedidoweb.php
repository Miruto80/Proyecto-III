<!DOCTYPE html>
<html lang="es">

<head>
  <!-- php barra de navegacion-->
  <?php include 'complementos/head.php' ?> 
  
  <title> Pedido Web | LoveMakeup  </title> 
</head>


<style>
  .driver-popover.driverjs-theme {
  color: #000;
}

.driver-popover.driverjs-theme .driver-popover-title {
  font-size: 20px;
}

.driver-popover.driverjs-theme .driver-popover-title,
.driver-popover.driverjs-theme .driver-popover-description,
.driver-popover.driverjs-theme .driver-popover-progress-text {
  color: #000;
}

.driver-popover.driverjs-theme button {
  flex: 1;
  text-align: center;
  background-color: #000;
  color: #ffffff;
  border: 2px solid #000;
  text-shadow: none;
  font-size: 14px;
  padding: 5px 8px;
  border-radius: 6px;
}

.driver-popover.driverjs-theme button:hover {
  background-color: #000;
  color: #ffffff;
}

.driver-popover.driverjs-theme .driver-popover-navigation-btns {
  justify-content: space-between;
  gap: 3px;
}

.driver-popover.driverjs-theme .driver-popover-close-btn {
  color: #fff;
  width: 20px; /* Reducir el tamaño del botón */
  height: 20px;
  font-size: 16px;
  transition: all 0.5 ease-in-out;
}

.driver-popover.driverjs-theme .driver-popover-close-btn:hover {
 background-color: #fff;
 color: #000;
 border: #000;
}

.driver-popover.driverjs-theme .driver-popover-arrow-side-left.driver-popover-arrow {
  border-left-color: #fde047;
}

.driver-popover.driverjs-theme .driver-popover-arrow-side-right.driver-popover-arrow {
  border-right-color: #fde047;
}

.driver-popover.driverjs-theme .driver-popover-arrow-side-top.driver-popover-arrow {
  border-top-color: #fde047;
}

.driver-popover.driverjs-theme .driver-popover-arrow-side-bottom.driver-popover-arrow {
  border-bottom-color: #fde047;
}

</style>

<body class="g-sidenav-show bg-gray-100">


  
<!-- php barra de navegacion-->
<?php include 'complementos/sidebar.php' ?>

<main class="main-content position-relative border-radius-lg ">
<!-- ||| Navbar ||-->
<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur" data-scroll="false">
  <div class="container-fluid py-1 px-3">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="#">Web</a></li>
        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Pedido web</li>
      </ol>
      <h6 class="font-weight-bolder text-white mb-0">Gestionar Pedido Web</h6>
    </nav>
<!-- php barra de navegacion-->    
<?php include 'complementos/nav.php' ?>


<div class="container-fluid py-4"> <!-- DIV CONTENIDO -->

    <div class="row"> <!-- CARD PRINCIPAL-->  
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0">  <!-- CARD N-1 -->  
    
    <!--Titulo de página -->
     <div class="d-sm-flex align-items-center justify-content-between mb-5">
     <div class="d-flex gap-2">
     <h4 class="mb-0"><i class="fa-solid fa-desktop mr-2" style="color: #f6c5b4;"></i>
        Pedido Web</h5>
      </div>
 

 <div class="d-flex gap-2">

          <button type="button" class="btn btn-primary" id="btnayuda">
    <span class="icon text-white">
      <i class="fas fa-info-circle"></i>
    </span>
    <span class="text-white">Ayuda</span>
  </button>

  
</div>
      </div>
 

          
          
      </div>
    

      <div class="table-responsive m-3"> <!-- comienzo div table-->
           <!-- comienzo de tabla-->                      
          <table class="table table-bordered table-hover" id="myTable" width="100%" cellspacing="0">
              <thead  class="table-color">
                <tr>
                  <th class="text-white" style="display: none;">ID</th>
                  <th class="text-white" style="display: none;">Tipo</th>
                  <th class="text-white d-none">Fecha</th>
                  <th class="text-white"style="">Estado</th>
                  <th class="text-white">Total</th>
                  <th class="text-white">Referencia</th>
                  <th class="text-white">usuario</th>
                 <th class="text-white">Teléfono</th>
                  <th class="text-white">Método Entrega</th>
                 <th style="" class="text-white">Método Pago</th>
                 <th class="text-white">Acción</th>
                </tr>
              </thead>
              <tbody id="pedidowebTableBody">
    
              <?php foreach ($pedidos as $pedido): 
    // Define clases y deshabilitar botones si estado es 0 o 2
    if ($pedido['estado'] == 2) {
      $claseFila = "pedido-confirmado";
        $botonesDeshabilitados = "disabled";
    } elseif ($pedido['estado'] == 0) {
        $botonesDeshabilitados = "disabled";
    } else {
        $claseFila = "";
        $botonesDeshabilitados = "enabled";
    }

    $estatus_texto = array(
     '0' => 'Anulado',
  '1' => 'Verificar pago',
  '2' => 'Pago Verificado',
  '3' => 'Pendiente envío',
  '4' => 'En camino',
  '5' => 'Enviado',

    );

    $badgeClass = '';
    switch (strtolower($pedido['estado'])) {
      case '0': $badgeClass = 'bg-danger'; break;
      case '1': $badgeClass = 'bg-warning'; break;
      case '2': $badgeClass = 'bg-primary'; break;
      case '3': $badgeClass = 'bg-success'; break;
      case '4': $badgeClass = 'bg-info'; break;
      default:  $badgeClass = 'bg-secondary';
    }

  
?>
    <tr style="text-align:center;">
    <td style="display: none;"><?= $pedido['id_pedido'] ?></td>
    <td style="display: none;"><?= $pedido['tipo'] ?></td>
    <td class="d-none"><?= $pedido['fecha'] ?></td>
    <td class=" m-3 text-white badge <?php echo $badgeClass; ?>"><?php echo $estatus_texto[$pedido['estado']] ?></td>
    <td><?= $pedido['precio_total_bs'] ?>$</td>
    <td><?= $pedido['referencia_bancaria'] ?></td>
    <td><?= $pedido['nombre'] ?></td>
    <td><?= $pedido['telefono_emisor'] ?></td>
    <td><?= $pedido['metodo_entrega'] ?></td>
    <td style=""><?= $pedido['metodo_pago'] ?></td>
    <td>
    <button class="btn btn-info " data-bs-toggle="modal" 
    data-bs-target="#verDetallesModal<?= $pedido['id_pedido']; ?>">
 <i class="fa fa-eye"></i> 
</button>
<?php if ($_SESSION["nivel_rol"] >= 2 && tieneAcceso(9, 'especial')): ?>
<button type="button" class="btn  btn-primary" data-bs-toggle="modal" data-bs-target="#modalTracking<?php echo $pedido['id_pedido']; ?>"><i class="fa-regular fa-envelope"></i>

</button>

<!-- <?php if (!in_array($pedido['estado'], [0,1])): ?>
  <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deliveryModal<?php echo $pedido['id_pedido']; ?>">
  <i class="fa-solid fa-box"></i>
  </button>
<?php endif; ?> -->

<?php if (!in_array($pedido['estado'], [0,2,3,4,5])): ?>
<button type="button" class="btn btn-secundary btn-validar btn-success"  data-id="<?= $pedido['id_pedido'] ?>">
<i class="fa-solid fa-check"></i>
  </button>

  <?php endif; ?>

 <?php endif; ?>
      
    </td>
</tr>
<?php endforeach; ?>

</tbody>
          </table>
        </div>

      </div>
    </div>
  </div>
</div>

<?php if (isset($pedidos) && !empty($pedidos)): ?>
  <?php foreach ($pedidos as $pedido): ?>
    <div class="modal fade" id="verDetallesModal<?php echo $pedido['id_pedido']; ?>" tabindex="-1" aria-labelledby="verDetallesModalLabel<?php echo $pedido['id_pedido']; ?>" aria-hidden="true">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header header-color">
            <h5 class="modal-title text-white" id="verDetallesModalLabel<?php echo $pedido['id_pedido']; ?>">Detalles del Pedido</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body">

            <!-- Fecha -->
            <div class="row mb-3">
              <div class="col-12">
                <div class="card">
                  <div class="card-header bg-light" data-bs-toggle="collapse" data-bs-target="#collapseFecha<?php echo $pedido['id_pedido']; ?>" style="cursor:pointer;">
                    <h6 class="mb-0">
                      <i class="fas fa-calendar-alt text-secondary"></i> Fecha y Hora
                      <i class="fas fa-chevron-down float-end"></i>
                    </h6>
                  </div>
                  <div class="collapse" id="collapseFecha<?php echo $pedido['id_pedido']; ?>">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-6">
                          <strong>Fecha:</strong> <?php echo date('d/m/Y', strtotime($pedido['fecha'])); ?>
                        </div>
                        <div class="col-md-6">
                          <strong>Hora:</strong> <?php echo date('H:i:s', strtotime($pedido['fecha'])); ?>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Cliente -->
            <div class="row mb-3">
              <div class="col-12">
                <div class="card">
                  <div class="card-header bg-light" data-bs-toggle="collapse" data-bs-target="#collapseCliente<?php echo $pedido['id_pedido']; ?>" style="cursor:pointer;">
                    <h6 class="mb-0">
                      <i class="fas fa-user text-primary"></i> Información del Cliente
                      <i class="fas fa-chevron-down float-end"></i>
                    </h6>
                  </div>
                  <div class="collapse" id="collapseCliente<?php echo $pedido['id_pedido']; ?>">
                    <div class="card-body">
                      <p><strong>Nombre:</strong> <?php echo htmlspecialchars($pedido['nombre']); ?> <?php echo htmlspecialchars($pedido['apellido']); ?></p>
                      
                      <p><strong>Estado del pedido:</strong>
                        <span class="badge 
                          <?php
                            $badge = [
                              '0' => 'bg-danger', '1' => 'bg-warning', '2' => 'bg-primary',
                              '3' => 'bg-success', '4' => 'bg-info', '5' => 'bg-secondary'
                            ];
                            echo $badge[$pedido['estado']] ?? 'bg-dark';
                          ?>">
                          <?php
                            $estados_texto = [
                              '0' => 'Rechazado',
                              '1' => 'Verificar pago',
                              '2' => 'Pago Verificado',
                              '3' => 'Pendiente envío',
                              '4' => 'En camino',
                              '5' => 'Enviado',
                            ];
                            echo htmlspecialchars($estados_texto[$pedido['estado']] ?? 'Desconocido');
                          ?>
                        </span>
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Información de Pago y Entrega -->
            <div class="row mb-3">
              <div class="col-12">
                <div class="card">
                  <div class="card-header bg-light" data-bs-toggle="collapse" data-bs-target="#collapsePagoEntrega<?php echo $pedido['id_pedido']; ?>" style="cursor:pointer;">
                    <h6 class="mb-0">
                      <i class="fas fa-credit-card text-success"></i> Pago y Entrega
                      <i class="fas fa-chevron-down float-end"></i>
                    </h6>
                  </div>
                  <div class="collapse" id="collapsePagoEntrega<?php echo $pedido['id_pedido']; ?>">
                    <div class="card-body">
                      <p><strong>Método de Pago:</strong> <?php echo htmlspecialchars($pedido['metodo_pago'] ?? 'N/A'); ?></p>
                      <?php if (!empty($pedido['banco']) || !empty($pedido['banco_destino'])): ?>
                        <p><strong>Banco Emisor:</strong> <?php echo htmlspecialchars($pedido['banco'] ?? 'N/A'); ?></p>
                        <p><strong>Banco Receptor:</strong> <?php echo htmlspecialchars($pedido['banco_destino'] ?? 'N/A'); ?></p>
                      <?php endif; ?>
                      <?php if (!empty($pedido['referencia_bancaria'])): ?>
                        <p><strong>Referencia Bancaria:</strong> <?php echo htmlspecialchars($pedido['referencia_bancaria']); ?></p>
                      <?php endif; ?>

                      <?php if (!empty($pedido['imagen'])): ?>
  <p><strong>Comprobante de Pago:</strong></p>
  <img src="<?php echo htmlspecialchars($pedido['imagen']); ?>" alt="Comprobante de Pago" class="img-fluid rounded border" style="max-width: 300px;">
<?php endif; ?>

                      <p><strong>Método de Entrega:</strong> <?php echo htmlspecialchars($pedido['metodo_entrega'] ?? 'N/A'); ?></p>
                      <?php if (!empty($pedido['direccion'])): ?>
                        <p><strong>Dirección:</strong><br><?php echo nl2br(htmlspecialchars($pedido['direccion'])); ?></p>
                      <?php endif; ?>
                      <p><strong>Total Bs:</strong> $<?php echo number_format($pedido['precio_total_bs'], 2); ?></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Productos -->
            <div class="row mb-3">
              <div class="col-12">
                <div class="card">
                  <div class="card-header bg-light" data-bs-toggle="collapse" data-bs-target="#collapseProductos<?php echo $pedido['id_pedido']; ?>" style="cursor:pointer;">
                    <h6 class="mb-0">
                      <i class="fas fa-box-open text-dark"></i> Detalles de la Venta
                      <i class="fas fa-chevron-down float-end"></i>
                    </h6>
                  </div>
                  <div class="collapse" id="collapseProductos<?php echo $pedido['id_pedido']; ?>">
                    <div class="card-body table-responsive">
                      <table class="table table-bordered table-striped">
                        <thead class="table-color">
                          <tr>
                            <th class="text-white">#</th>
                            <th class="text-center text-white">Producto</th>
                            <th class="text-center text-white">Cantidad</th>
                            <th class="text-center text-white">Precio Unitario</th>
                            <th class="text-center text-white">Subtotal</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php 
                            $total = 0;
                            $i = 1;
                            foreach ($pedido['detalles'] as $detalle):
                              $subtotal = $detalle['cantidad'] * $detalle['precio_unitario'];
                              $total += $subtotal;
                          ?>
                          <tr>
                            <td class="text-center"><?php echo $i++; ?></td>
                            <td><?php echo htmlspecialchars($detalle['nombre']); ?></td>
                            <td class="text-center"><?php echo $detalle['cantidad']; ?></td>
                            <td class="text-center">$<?php echo number_format($detalle['precio_unitario'], 2); ?></td>
                            <td class="text-center">$<?php echo number_format($subtotal, 2); ?></td>
                          </tr>
                          <?php endforeach; ?>
                        </tbody>
                        <tfoot>
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

          </div> <!-- /.modal-body -->
        </div>
      </div>
    </div>
  <?php endforeach; ?>
<?php endif; ?>



<?php if (isset($pedidos) && !empty($pedidos)): ?>
  <?php foreach ($pedidos as $pedido): ?>
    <!-- Modal Tracking para cada pedido -->
    <div class="modal fade" id="modalTracking<?php echo $pedido['id_pedido']; ?>" tabindex="-1" aria-labelledby="modalTrackingLabel<?php echo $pedido['id_pedido']; ?>" aria-hidden="true">
      <div class="modal-dialog">
        <form id="formTracking<?php echo $pedido['id_pedido']; ?>" class="modal-content tracking-form">
          <div class="modal-header">
            <h5 class="modal-title" id="modalTrackingLabel<?php echo $pedido['id_pedido']; ?>">Agregar Número de Tracking</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="id_pedido" value="<?php echo $pedido['id_pedido']; ?>">
            <div class="mb-3">
              <label for="tracking<?php echo $pedido['id_pedido']; ?>" class="form-label">Número de Tracking</label>
              <input type="text" class="form-control" id="tracking<?php echo $pedido['id_pedido']; ?>" name="tracking" required>

              <input type="hidden" name="correo_cliente" value="<?php echo htmlspecialchars($pedido['correo'], ENT_QUOTES); ?>">
              <input type="hidden" name="nombre_cliente" value="<?php echo htmlspecialchars($pedido['nombre'], ENT_QUOTES); ?>">
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success">Guardar y Enviar Email</button>
          </div>
        </form>
      </div>
    </div>
  <?php endforeach; ?>
<?php endif; ?>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.tracking-form').forEach(function(form) {
      form.addEventListener('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(form);
        console.log('Enviando tracking al correo:', formData.get('correo_cliente'));

        fetch('controlador/pedidoweb_tracking.php', {
          method: 'POST',
          body: formData
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            Swal.fire({
              icon: 'success',
              title: 'Tracking enviado',
              text: data.message,
              confirmButtonText: 'OK'
            }).then(() => {
              location.reload();
            });
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error al enviar',
              text: data.message,
              confirmButtonText: 'Cerrar'
            });
          }
        })
        .catch(err => {
          console.error(err);
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Ocurrió un error en la solicitud.',
            confirmButtonText: 'Cerrar'
          });
        });
      });
    });
  });
</script>


<!-- Modal para pedidos web (delivery, MRW, Zoom) -->
<?php if (!empty($pedidos)): ?>
  <?php foreach ($pedidos as $pedido):
    $entrega   = $pedido['metodo_entrega'];
    $estado    = $pedido['estado'];
    $idPedido  = $pedido['id_pedido'];
    $direccion = htmlspecialchars($pedido['direccion'] ?? '');

    // Solo Delivery / MRW / Zoom y no estados finales
    if (!in_array($entrega, ['Delivery','MRW','Zoom'])) continue;
    if (
      ($entrega==='Delivery' && $estado==5) ||
      (in_array($entrega,['MRW','Zoom']) && $estado==4) ||
      $estado==0
    ) continue;
  ?>
    <div class="modal fade" id="deliveryModal<?= $idPedido ?>" tabindex="-1" aria-labelledby="deliveryModalLabel<?= $idPedido ?>" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header header-color">
            <h5 class="modal-title" id="deliveryModalLabel<?= $idPedido ?>">
              Gestionar <?= $entrega ?>
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <form method="POST" action="?pagina=verpedidoweb" class="form-delivery" data-id="<?= $idPedido ?>">
          
              <input type="hidden" name="actualizar_delivery" value="1">
              <input type="hidden" name="id_pedido"        value="<?= $idPedido ?>">

              <div class="mb-3">
                <label for="estado_delivery<?= $idPedido ?>" class="form-label">Estado del Pedido</label>
                <select class="form-select" name="estado_delivery" id="estado_delivery<?= $idPedido ?>" required>
                  <?php
                  // Array de estados válidos
                  $opciones = [
                    '0' => 'Cancelado',
                    '2' => 'Enviado',
                    '3' => 'En camino',
                    '4' => 'Entregado'
                  ];
                  foreach ($opciones as $val => $label): ?>
                    <option value="<?= $val ?>" <?= $estado == $val ? 'selected' : '' ?>>
                      <?= $label ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="mb-3">
                <label for="direccion<?= $idPedido ?>" class="form-label">Dirección de Entrega</label>
                <div class="d-flex gap-2">
                  <input type="text"
                         class="form-control"
                         name="direccion"
                         id="direccion<?= $idPedido ?>"
                         value="<?= $direccion ?>"
                         readonly
                         required>
                  <button type="button" class="btn btn-warning btn-sm btnEditarDireccion">
                    <i class="fas fa-pencil-alt"></i>
                  </button>
                </div>
              </div>

              <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Actualizar Estado</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
<?php endif; ?>

    


<!-- php barra de navegacion-->
<?php include 'complementos/footer.php' ?>
<script src="assets/js/pedidoweb.js"></script>
<script src="assets/js/demo/datatables-demo.js"></script>
</body>

</html>