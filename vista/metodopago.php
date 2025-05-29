<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'complementos/head.php'; ?> 
  <title> Método de Pago | LoveMakeup </title>
</head>

<body class="g-sidenav-show bg-gray-100">

<?php include 'complementos/sidebar.php'; ?>

<main class="main-content position-relative border-radius-lg">
  <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="false">
    <div class="container-fluid py-1 px-3">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
          <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="#">Administrar</a></li>
          <li class="breadcrumb-item text-sm text-white active" aria-current="page">Método de Pago</li>
        </ol>
        <h6 class="font-weight-bolder text-white mb-0">Gestionar Método de Pago</h6>
      </nav>

      <?php include 'complementos/nav.php'; ?>

      <div class="container-fluid py-4">
        <div class="row">
          <div class="col-12">
            <div class="card mb-4">
              <div class="card-header pb-0">
                <div class="d-sm-flex align-items-center justify-content-between mb-5">
                  <h4 class="mb-0"><i class="fa-solid fa-credit-card mr-2" style="color: #f6c5b4;"></i> Método de Pago</h4>
                  <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#registro">
                    <i class="fas fa-file-medical"></i> Registrar
                  </button>
                </div>

                <div class="table-responsive">
                  <table class="table table-bordered table-hover" id="myTable" width="100%" cellspacing="0">
                    <thead class="table-color">
                      <tr>
                        <th class="text-white">ID</th>
                        <th class="text-white">Nombre</th>
                        <th class="text-white">Descripción</th>
                        <th class="text-white">Acciones</th>
                      </tr>
                    </thead>
                    <tbody id="metodopagoTableBody">
                      <?php foreach ($registro as $dato): ?>
                      <tr>
                        <td><?= $dato['id_metodopago']; ?></td>
                        <td><?= htmlspecialchars($dato['nombre']); ?></td>
                        <td><?= htmlspecialchars($dato['descripcion']); ?></td>
                        <td>
                          <button class="btn btn-primary btn-sm"
                                  onclick="abrirModalModificar(<?= $dato['id_metodopago']; ?>, '<?= htmlspecialchars($dato['nombre'], ENT_QUOTES); ?>', '<?= htmlspecialchars($dato['descripcion'], ENT_QUOTES); ?>')">
                            <i class="fas fa-pencil-alt"></i>
                          </button>
                          <button class="btn btn-danger btn-sm" onclick="eliminarMetodoPago(<?= $dato['id_metodopago']; ?>)">
                            <i class="fas fa-trash-alt"></i>
                          </button>
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

      <!-- Modal registrar -->
      <div class="modal fade" id="registro" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header header-color">
              <h1 class="modal-title fs-5">Registrar Método de Pago</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <form id="formRegistrar" autocomplete="off">
    <label>Nombre</label>
    <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Ej: Tarjeta de crédito" required>
    <small id="snombre" class="text-danger"></small> <!-- Mensaje de error aquí -->

    <label class="mt-2">Descripción</label>
    <input type="text" class="form-control" name="descripcion" id="descripcion" placeholder="Ej: Visa, MasterCard, etc." required>
    <small id="sdescripcion" class="text-danger"></small> <!-- Mensaje de error aquí -->

    <div class="text-center mt-4">
        <button type="button" class="btn btn-primary" id="registrar">Registrar</button>
        <button type="reset" class="btn btn-secondary">Limpiar</button>
    </div>
</form>

            </div>
          </div>
        </div>
      </div>

      <!-- Modal modificar -->
      <div class="modal fade" id="modificar" tabindex="-1" aria-labelledby="modificarLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Modificar Método de Pago</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
            <form id="formModificar" autocomplete="off">
    <input type="hidden" name="id_metodopago" id="id_metodopago_modificar">
    
    <label>Nombre</label>
    <input type="text" class="form-control" name="nombre" id="nombre_modificar" required>
    <small id="snombre_modificar" class="text-danger"></small> <!-- Mensaje de error aquí -->

    <label class="mt-2">Descripción</label>
    <input type="text" class="form-control" name="descripcion" id="descripcion_modificar" required>
    <small id="sdescripcion_modificar" class="text-danger"></small> <!-- Mensaje de error aquí -->

    <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="btnModificar">Modificar</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
    </div>
</form>
          </div>
        </div>
      </div>
 </div>
<?php include 'complementos/footer.php'; ?>
<script src="assets/js/metodopago.js"></script>

<script src="assets/js/demo/datatables-demo.js"></script>
</body>
</html>
