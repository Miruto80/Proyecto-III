<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'complementos/head.php'; ?>
  <title> Tipo de Usuario | LoveMakeup </title>
  <style>
    .text-danger {
      min-height: 2.2em;
      display: block;
      margin-top: 0.1em;
      color: #dc3545;
      font-size: 1rem;
    }
  </style>
</head>
<body class="g-sidenav-show bg-gray-100">
  <?php include 'complementos/sidebar.php'; ?>

  <main class="main-content position-relative border-radius-lg">
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="false">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="#">Administrar</a></li>
            <li class="breadcrumb-item text-sm text-white active" aria-current="page">Tipo Usuario</li>
          </ol>
          <h6 class="font-weight-bolder text-white mb-0">Gestionar Tipo Usuario</h6>
        </nav>
        <?php include 'complementos/nav.php'; ?>
      </div>
    </nav>

    <!-- LOADER -->
    <div class="preloader-wrapper">
      <div class="preloader"></div>
    </div>

    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0">
              <div class="d-sm-flex align-items-center justify-content-between mb-5">
                <h4 class="mb-0">
                  <i class="fa-solid fa-user-group mr-2" style="color: #f6c5b4;"></i> Tipo Usuario
                </h4>
                <div class="d-flex gap-2">
                  <!-- botón abrir modal registrar -->
                  <button type="button"
                          class="btn btn-success registrar"
                          data-bs-toggle="modal"
                          data-bs-target="#registro">
                    <i class="fas fa-file-medical me-1"></i>
                    Registrar
                  </button>
                  <!-- botón ayuda -->
<!-- botón ayuda -->
<button type="button" class="btn btn-primary" id="btnAyuda">
  <i class="fas fa-info-circle me-1"></i>
  Ayuda
</button>

                </div>
              </div>

              <div class="table-responsive">
                <table class="table table-bordered table-hover" id="myTable" width="100%" cellspacing="0">
                  <thead class="table-color">
                    <tr>
                      <th class="text-white text-center">Nombre</th>
                      <th class="text-white text-center">Nivel</th>
                      <th class="text-white text-center">Accion</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($registro as $dato):
                     ?>
                      <tr>
                        <td><?= htmlspecialchars($dato['nombre']) ?></td>
                        <td><?= htmlspecialchars($dato['nivel']) ?></td>
                        <td class="text-center">
                          <button type="button"
                                  class="btn btn-primary btn-sm modificar"
                                  data-id="<?= $dato['id_rol'] ?>"
                                  data-nombre="<?= htmlspecialchars($dato['nombre']) ?>"
                                  data-nivel="<?= $dato['nivel'] ?>"
                                  data-estatus="<?= $dato['estatus'] ?>"
                                  data-bs-toggle="modal"
                                  data-bs-target="#modificar">
                            <i class="fas fa-pencil-alt"></i>
                          </button>
                          <button type="button"
                                  class="btn btn-danger btn-sm eliminar"
                                  value="<?= $dato['id_rol'] ?>">
                            <i class="fas fa-trash-alt"></i>
                          </button>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>

            </div><!-- .card-header -->
          </div><!-- .card -->
        </div><!-- .col-12 -->
      </div><!-- .row -->
    </div><!-- .container-fluid -->

    <!-- Modal Registrar -->
    <div class="modal fade" id="registro" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header header-color">
            <h5 class="modal-title text-white"><i class="fas fa-user-plus me-1"></i> Registrar Tipo Usuario</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <form id="u" autocomplete="off">
              <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" class="form-control" name="nombre" id="nombre" required>
                <span id="snombre" class="text-danger"></span>
              </div>
              <div class="mb-3">
                <label class="form-label">Nivel</label>
                <select class="form-select" name="nivel" id="nivel" required>
                  <option value="">Seleccione nivel</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                </select>
                <span id="snivel" class="text-danger"></span>
              </div>
              <div class="mb-3">
                <label class="form-label">Estatus</label>
                <select class="form-select" name="estatus" id="estatus" required>
                  <option value="1">Activo</option>
                  <option value="2">Inactivo</option>
                </select>
              </div>
              <div class="modal-footer justify-content-end gap-2">
                <button type="button" class="btn btn-primary" id="registrar">
                  <i class="fas fa-floppy-disk me-1"></i> Registrar
                </button>
                <button type="reset" class="btn btn-secondary">
                  <i class="fas fa-eraser me-1"></i> Limpiar
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Modificar -->
    <div class="modal fade" id="modificar" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header header-color">
            <h5 class="modal-title text-white"><i class="fas fa-pencil-alt me-1"></i> Modificar Tipo Usuario</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <form id="formModificar" autocomplete="off">
              <input type="hidden" name="id_tipo" id="id_tipo_modificar">
              <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" class="form-control" name="nombre" id="nombre_modificar" required>
                <span id="snombre_modificar" class="text-danger"></span>
              </div>
              <div class="mb-3">
                <label class="form-label">Nivel</label>
                <select class="form-select" name="nivel" id="nivel_modificar" required>
                  <option value="">Seleccione nivel</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                </select>
                <span id="snivel_modificar" class="text-danger"></span>
              </div>
              <div class="mb-3">
                <label class="form-label">Estatus</label>
                <select class="form-select" name="estatus" id="estatus_modificar" required>
                  <option value="1">Activo</option>
                  <option value="2">Inactivo</option>
                </select>
              </div>
              <div class="modal-footer justify-content-end gap-2">
                <button type="button" class="btn btn-primary" id="btnModificar">
                  <i class="fas fa-check me-1"></i> Modificar
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

<?php include 'complementos/footer.php'; ?>
<script src="assets/js/tipousuario.js"></script>

  </main>
</body>
</html>
