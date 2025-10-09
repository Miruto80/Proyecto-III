<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'complementos/head.php'; ?>
  <title>Reservas | LoveMakeup</title>
</head>

<style>
  .driver-popover.driverjs-theme {
    color: #000;
  }
  .driver-popover.driverjs-theme .driver-popover-title {
    font-size: 20px;
  }
  .driver-popover.driverjs-theme button {
    flex: 1;
    text-align: center;
    background-color: #000;
    color: #fff;
    border: 2px solid #000;
    font-size: 14px;
    padding: 5px 8px;
    border-radius: 6px;
  }
  .driver-popover.driverjs-theme .driver-popover-close-btn:hover {
    background-color: #fff;
    color: #000;
  }
</style>

<body class="g-sidenav-show bg-gray-100">
<?php include 'complementos/sidebar.php'; ?>

<main class="main-content position-relative border-radius-lg">
  <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur" data-scroll="false">
    <div class="container-fluid py-1 px-3">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
          <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="#">Administrar</a></li>
          <li class="breadcrumb-item text-sm text-white active" aria-current="page">Reservas</li>
        </ol>
        <h6 class="font-weight-bolder text-white mb-0">Gestionar Reservas</h6>
      </nav>
      <?php include 'complementos/nav.php'; ?>
    </div>
  </nav>

  <div class="preloader-wrapper"><div class="preloader"></div></div>

  <div class="container-fluid py-4">
    <div class="row">
      <div class="col-12">
        <div class="card mb-4 div-oscuro-2">
          <div class="card-header pb-0 div-oscuro-2">
            <div class="d-sm-flex align-items-center justify-content-between mb-3">
              <div class="d-flex gap-2">
                <h4 class="mb-0 texto-quinto"><i class="fa-solid fa-calendar-check me-2" style="color:#f6c5b4;"></i> Reservas</h4>
              </div>
              <div class="d-flex gap-2 div-oscuro-2">
                <button type="button" class="btn btn-primary" id="btnAyuda">
                  <i class="fas fa-info-circle"></i> Ayuda
                </button>
              </div>
            </div>
          </div>

          <div class="table-responsive m-3 div-oscuro-2">
            <table class="table table-bordered table-hover" id="tablaReservas" width="100%">
              <thead class="table-color">
                <tr>
                  <th class="text-white">ID</th>
                  <th class="text-white">Fecha</th>
                  <th class="text-white">Estado</th>
                  <th class="text-white">Total Bs</th>
                  <th class="text-white">Usuario</th>
                  <th class="text-white">Acción</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($reservas as $r): 
                  $estadoTexto = [
                    '0' => 'Rechazado',
                    '1' => 'Verificar pago',
                    '2' => 'Pago Verificado'
                  ];
                  $estadoClass = [
                    '0' => 'bg-danger',
                    '1' => 'bg-warning',
                    '2' => 'bg-success'
                  ];
                ?>
                  <tr style="text-align:center;">
                    <td><?= $r['id_pedido']; ?></td>
                    <td><?= isset($r['fecha']) ? date('d/m/Y', strtotime($r['fecha'])) : 'Sin fecha'; ?></td>
                    <td class="badge <?= $estadoClass[$r['estado']] ?? 'bg-dark'; ?>">
                      <?= $estadoTexto[$r['estado']] ?? 'Desconocido'; ?>
                    </td>
                    <td><?= number_format($r['precio_total_bs'], 2); ?> Bs</td>
                    <td><?= $r['nombre'] . ' ' . $r['apellido']; ?></td>
                    <td>
                      <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modalReserva<?= $r['id_pedido']; ?>">
                        <i class="fa fa-eye"></i>
                      </button>
                      <?php if ($r['estado'] == 1): ?>
                        <button class="btn btn-success btn-validar" data-id="<?= $r['id_pedido']; ?>"><i class="fa-solid fa-check"></i></button>
                        <button class="btn btn-danger btn-eliminar" data-id="<?= $r['id_pedido']; ?>"><i class="fa-solid fa-x"></i></button>
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

          <!-- Modales -->
          <?php foreach ($reservas as $r): ?>
            <div class="modal fade" id="modalReserva<?= $r['id_pedido']; ?>" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-xl">
                <div class="modal-content">
                  <div class="modal-header header-color">
                    <h5 class="modal-title text-white">Detalles de la Reserva</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body bg-s">

                    <!-- Fecha -->
                    <div class="card mb-3">
                      <div class="card-header bg-light" data-bs-toggle="collapse" data-bs-target="#collapseFecha<?= $r['id_pedido']; ?>">
                        <h6 class="mb-0"><i class="fas fa-calendar-alt text-secondary"></i> Fecha y Hora <i class="fas fa-chevron-down float-end"></i></h6>
                      </div>
                      <div class="collapse show" id="collapseFecha<?= $r['id_pedido']; ?>">
                        <div class="card-body">
                          <p><strong>Fecha:</strong> <?= date('d/m/Y', strtotime($r['fecha'])); ?></p>
                          <p><strong>Hora:</strong> <?= date('H:i:s', strtotime($r['fecha'])); ?></p>
                        </div>
                      </div>
                    </div>

                    <!-- Cliente -->
                    <div class="card mb-3">
                      <div class="card-header bg-light" data-bs-toggle="collapse" data-bs-target="#collapseCliente<?= $r['id_pedido']; ?>">
                        <h6 class="mb-0"><i class="fas fa-user text-primary"></i> Información del Cliente <i class="fas fa-chevron-down float-end"></i></h6>
                      </div>
                      <div class="collapse" id="collapseCliente<?= $r['id_pedido']; ?>">
                        <div class="card-body">
                          <p><strong>Nombre:</strong> <?= htmlspecialchars($r['nombre'] . ' ' . $r['apellido']); ?></p>
                          <p><strong>Estado:</strong>
                            <span class="badge <?= $estadoClass[$r['estado']] ?? 'bg-dark'; ?>">
                              <?= $estadoTexto[$r['estado']] ?? 'Desconocido'; ?>
                            </span>
                          </p>
                        </div>
                      </div>
                    </div>

                    <!-- Pago y Entrega -->
                    <div class="card mb-3">
                      <div class="card-header bg-light" data-bs-toggle="collapse" data-bs-target="#collapsePago<?= $r['id_pedido']; ?>">
                        <h6 class="mb-0"><i class="fas fa-credit-card text-success"></i> Pago y Entrega <i class="fas fa-chevron-down float-end"></i></h6>
                      </div>
                      <div class="collapse" id="collapsePago<?= $r['id_pedido']; ?>">
                        <div class="card-body">
                          <p><strong>Método de Pago:</strong> <?= htmlspecialchars($r['metodo_pago'] ?? 'N/A'); ?></p>
                          <p><strong>Banco Emisor:</strong> <?= htmlspecialchars($r['banco'] ?? 'N/A'); ?></p>
                          <p><strong>Banco Receptor:</strong> <?= htmlspecialchars($r['banco_destino'] ?? 'N/A'); ?></p>
                          <p><strong>Referencia Bancaria:</strong> <?= htmlspecialchars($r['referencia_bancaria'] ?? 'N/A'); ?></p>
                          <p><strong>Teléfono Emisor:</strong> <?= htmlspecialchars($r['telefono_emisor'] ?? 'N/A'); ?></p>

                          <?php if (!empty($r['imagen'])): ?>
                            <p><strong>Comprobante:</strong></p>
                            <img src="assets/img/captures/<?= htmlspecialchars($r['imagen']); ?>" class="img-fluid rounded border" style="max-width:300px;">
                          <?php endif; ?>

                          <p><strong>Método de Entrega:</strong> <?= htmlspecialchars($r['metodo_entrega'] ?? 'N/A'); ?></p>

                          <?php if (!empty($r['direccion'])): ?>
                            <p><strong>Dirección:</strong><br><?= nl2br(htmlspecialchars($r['direccion'])); ?></p>
                          <?php endif; ?>

                          <?php if (!empty($r['sucursal'])): ?>
                            <p><strong>Sucursal:</strong> <?= htmlspecialchars($r['sucursal']); ?></p>
                          <?php endif; ?>

                          <p><strong>Total Bs:</strong> <?= number_format($r['precio_total_bs'], 2); ?> Bs</p>
                        </div>
                      </div>
                    </div>

                    <!-- Productos -->
                    <div class="card mb-3">
                      <div class="card-header bg-light" data-bs-toggle="collapse" data-bs-target="#collapseProductos<?= $r['id_pedido']; ?>">
                        <h6 class="mb-0"><i class="fas fa-box-open text-dark"></i> Detalles de Productos <i class="fas fa-chevron-down float-end"></i></h6>
                      </div>
                      <div class="collapse" id="collapseProductos<?= $r['id_pedido']; ?>">
                        <div class="card-body table-responsive">
                          <table class="table table-bordered table-striped">
                            <thead class="table-color">
                              <tr>
                                <th>#</th>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio Unitario</th>
                                <th>Subtotal</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php 
                                $total = 0; $i = 1;
                                foreach ($r['detalles'] as $d):
                                  $subtotal = $d['cantidad'] * $d['precio_unitario'];
                                  $total += $subtotal;
                              ?>
                                <tr>
                                  <td><?= $i++; ?></td>
                                  <td><?= htmlspecialchars($d['nombre']); ?></td>
                                  <td><?= $d['cantidad']; ?></td>
                                  <td>$<?= number_format($d['precio_unitario'], 2); ?></td>
                                  <td>$<?= number_format($subtotal, 2); ?></td>
                                </tr>
                              <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                              <tr>
                                <th colspan="4" class="text-end">Total USD:</th>
                                <th>$<?= number_format($total, 2); ?></th>
                              </tr>
                            </tfoot>
                          </table>
                        </div>
                      </div>
                    </div>

                  </div><!-- /.modal-body -->
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>

  <?php include 'complementos/footer.php'; ?>
  <script src="assets/js/reserva.js"></script>
</main>
</body>
</html>
