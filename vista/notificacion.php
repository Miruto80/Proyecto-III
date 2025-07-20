<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'complementos/head.php'; ?>
  <title>Notificaciones | LoveMakeup</title>
  
  <style>
    /* Compacta padding en celdas */
    .table-compact th,
    .table-compact td {
      padding: .4rem .6rem;
      vertical-align: middle;
    }
    /* Título cabecera */
    .card-header.d-flex h6 {
      margin: 0;
      font-size: 1rem;
      font-weight: 600;
    }
    /* Pegar columnas Estado (3) y Fecha (4) a la izquierda */
    .table-compact th:nth-child(3),
    .table-compact td:nth-child(3),
    .table-compact th:nth-child(4),
    .table-compact td:nth-child(4) {
      padding-left: 0;
    }
  </style>
</head>
<body class="g-sidenav-show bg-gray-100">
  <?php include 'complementos/sidebar.php'; ?>

  <main class="main-content position-relative border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl"
         id="navbarBlur" data-scroll="false">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm">
              <a class="opacity-5 text-white" href="#">Inicio</a>
            </li>
            <li class="breadcrumb-item text-sm text-white active" aria-current="page">
              Notificaciones
            </li>
          </ol>
          <h6 class="font-weight-bolder text-white mb-0">Lista de notificaciones</h6>
        </nav>
        <?php include 'complementos/nav.php'; ?>
      </div>
    </nav>
<!-- |||||||||||||||| LOADER ||||||||||||||||||||-->
  <div class="preloader-wrapper">
    <div class="preloader">
    </div>
  </div> 
<!-- |||||||||||||||| LOADER ||||||||||||||||||||-->
    <div class="container-fluid py-4">
      <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between py-3">
          <h6 class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
            Lista de notificaciones
          </h6>
          <div class="d-flex align-items-center gap-2">
            <!-- Al lado derecho del título, dentro de .card-header -->
            <button type="button" class="btn btn-primary btn-sm" id="btnAyudanoti">
              <i class="fas fa-info-circle me-1"></i> Ayuda
            </button>
          </div>
        </div>
        

        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-sm table-compact mb-0">
              <thead class="bg-light">
                <tr>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Título
                  </th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Mensaje
                  </th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Estado
                  </th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Fecha
                  </th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                    Acciones
                  </th>
                </tr>
              </thead>

<tbody
  id="notif-body"
  data-empty-msg="<?= $nivel === 2
        ? 'Esperando nuevas notificaciones.'
        : 'No hay notificaciones registradas.' ?>">
  
  <?php if (empty($notificaciones)): ?>
    <tr>
      <td colspan="5" class="text-center py-3">
        <?= $nivel === 2
            ? 'Esperando nuevas notificaciones.'
            : 'No hay notificaciones registradas.'; ?>
      </td>
    </tr>
  
  <?php else: foreach ($notificaciones as $n): ?>
    <tr id="notif-<?= $n['id_notificacion'] ?>">
      <td>
        <p class="text-sm font-weight-normal mb-0">
          <?= htmlspecialchars($n['titulo']) ?>
        </p>
      </td>
      <td>
        <p class="text-sm font-weight-normal mb-0">
          <?= htmlspecialchars($n['mensaje']) ?>
        </p>
      </td>
      <td class="text-sm mb-0">
        <?php switch ((int)$n['estado']):
          case 1: ?>
            <span class="text-danger text-sm">No leída</span>
          <?php break;
          case 2: ?>
            <span class="text-secondary text-sm">Leída</span>
          <?php break;
          case 3: ?>
            <span class="text-success text-sm">
              <?= $nivel === 3 ? 'Leída y entregada' : 'Entregada' ?>
            </span>
          <?php break;
        endswitch; ?>
      </td>
      <td class="text-sm mb-0">
        <span class="text-secondary text-sm">
          <?= date('d-m-Y h:i a', strtotime($n['fecha'])) ?>
        </span>
      </td>
      <td class="text-center">
        <?php if ($nivel === 3 && in_array((int)$n['estado'], [1, 4])): ?>
          <button
            type="button"
            class="btn btn-info btn-sm btn-action"
            data-id="<?= $n['id_notificacion'] ?>"
            data-accion="marcarLeida"
            title="Marcar como leída">
            <i class="fa-solid fa-envelope-open"></i>
          </button>
        <?php elseif ($nivel === 2 && (int)$n['estado'] === 1): ?>
          <button
            type="button"
            class="btn btn-secondary btn-sm btn-action"
            data-id="<?= $n['id_notificacion'] ?>"
            data-accion="marcarLeidaAsesora"
            title="Leer (solo para mí)">
            <i class="fa-solid fa-envelope-open"></i>
          </button>
        <?php endif; ?>
      </td>
    </tr>
  <?php endforeach; endif; ?>

  </tbody>
  </table>
  </div>
  </div>

  </div>
 </div>

  <?php include 'complementos/footer.php'; ?>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="assets/js/notificacion.js"></script>
</body>
</html>
