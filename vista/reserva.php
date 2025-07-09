<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'complementos/head.php'; ?>
  <title>Reservas | LoveMakeup</title>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
  <style>
    .driver-popover.driverjs-theme { color: #000; }
    .driver-popover.driverjs-theme .driver-popover-title { font-size: 20px; }
    .driver-popover.driverjs-theme .driver-popover-title,
    .driver-popover.driverjs-theme .driver-popover-description,
    .driver-popover.driverjs-theme .driver-popover-progress-text { color: #000; }
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
    .driver-popover.driverjs-theme button:hover {
      background-color: #000;
      color: #fff;
    }
    .driver-popover.driverjs-theme .driver-popover-navigation-btns { justify-content: space-between; gap: 3px; }
    .driver-popover.driverjs-theme .driver-popover-close-btn { color: #fff; width: 20px; height: 20px; font-size: 16px; }
    .driver-popover.driverjs-theme .driver-popover-close-btn:hover {
      background-color: #fff; color: #000; border: #000;
    }
    .driver-popover.driverjs-theme .driver-popover-arrow-side-left.driver-popover-arrow { border-left-color: #fde047; }
    .driver-popover.driverjs-theme .driver-popover-arrow-side-right.driver-popover-arrow { border-right-color: #fde047; }
    .driver-popover.driverjs-theme .driver-popover-arrow-side-top.driver-popover-arrow { border-top-color: #fde047; }
    .driver-popover.driverjs-theme .driver-popover-arrow-side-bottom.driver-popover-arrow { border-bottom-color: #fde047; }
  </style>
</head>
<body class="g-sidenav-show bg-gray-100">
<?php include 'complementos/sidebar.php'; ?>
<main class="main-content position-relative border-radius-lg ">
  <?php include 'complementos/nav.php'; ?>
  <div class="container-fluid py-4">
    <div class="row">
      <div class="col-12">
        <div class="card mb-4">
          <div class="card-header pb-0">
            <div class="d-sm-flex align-items-center justify-content-between mb-5">
              <h4 class="mb-0">
                <i class="fa-solid fa-calendar-check mr-2" style="color: #f6c5b4;"></i> Reservas
              </h4>
              <button type="button" class="btn btn-primary" id="btnAyuda">
                <i class="fas fa-info-circle"></i> Ayuda
              </button>
            </div>
            <div class="table-responsive">
              <table class="table table-bordered table-hover" id="tablaReservas" width="100%">
                <thead class="table-color">
                  <tr>
                    <th class="text-white">ID</th>
                    <th class="text-white">FECHA RESERVA</th>
                    <th class="text-white">USUARIO</th>
                    <th class="text-white">ESTADO</th>
                    <th class="text-white">ACCIONES</th>
                  </tr>
                </thead>
                <tbody>
               <?php foreach ($reservas as $dato): ?>
  <?php 
    $jsonDetalles = htmlspecialchars(json_encode([
      'id' => $dato['id_pedido'] ?? '',
      'fecha' => $dato['fecha'] ?? '',
      'nombre' => $dato['nombre'] ?? '',
      'apellido' => $dato['apellido'] ?? '',
      'estado' => $dato['estado'] ?? '',
      'banco' => $dato['banco'] ?? '',
      'banco_destino' => $dato['banco_destino'] ?? '',             // Aquí el cambio
      'referencia_bancaria' => $dato['referencia_bancaria'] ?? '', // Aquí el cambio
      'imagen' => $dato['imagen'] ?? '',
      'precio_total_bs' => $dato['precio_total_bs'] ?? 0,
      'detalles' => $dato['detalles'] ?? []
    ]), ENT_QUOTES, 'UTF-8');
  ?>
  <tr>

                    <td><?= $dato['id_pedido']; ?></td>
                    <td><?= isset($dato['fecha']) ? date('d/m/Y', strtotime($dato['fecha'])) : 'Sin fecha'; ?></td>
                    <td><?= $dato['nombre'] . ' ' . $dato['apellido']; ?></td>
                    <td>
                      <?php
                        $estado_texto = ['0' => 'Inactivo', '1' => 'Activo', '2' => 'Entregado'];
                        $estado_class = ['0' => 'badge bg-secondary', '1' => 'badge bg-success', '2' => 'badge bg-info'];
                        echo '<span class="' . $estado_class[$dato['estado']] . '">' . $estado_texto[$dato['estado']] . '</span>';
                      ?>
                    </td>
                    <td>
                      <button class="btn btn-info ver-detalles" 
                              data-id="<?= $dato['id_pedido']; ?>" 
                              data-detalles='<?= $jsonDetalles; ?>'>
                        <i class="fas fa-eye"></i>
                      </button>
                      <?php if ($dato['estado'] == 1): ?>
                        <button class="btn btn-success btn-validar btn-sm" data-id="<?= $dato['id_pedido']; ?>">
                          <i class="fas fa-check"></i>
                        </button>
                        <button class="btn btn-danger btn-eliminar btn-sm" data-id="<?= $dato['id_pedido']; ?>">
                          <i class="fas fa-trash"></i>
                        </button>
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
  </div>

  <!-- Modal para mostrar detalles -->
  <div class="modal fade" id="modalDetallesReserva" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header bg-secondary text-white">
          <h5 class="modal-title">Detalles de la Reserva</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" id="modalContenidoDetalles">
          <!-- Se genera con JS (reserva.js) -->
        </div>
      </div>
    </div>
  </div>

<?php include 'complementos/footer.php'; ?>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function () {
  $('#tablaReservas').DataTable({
    responsive: true,
    language: {
      url: "https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
    }
  });
});
</script>
<script src="assets/js/reserva.js"></script>
</main>
</body>
</html>
