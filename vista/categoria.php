<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'complementos/head.php'; ?>
  <title> Categoria | LoveMakeup </title>
</head>
<body class="g-sidenav-show bg-gray-100">

  <?php include 'complementos/sidebar.php'; ?>
  <main class="main-content position-relative border-radius-lg ">
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl"
         id="navbarBlur" data-scroll="false">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm">
              <a class="opacity-5 text-white" href="#">Administrar</a>
            </li>
            <li class="breadcrumb-item text-sm text-white active" aria-current="page">
              Categoria
            </li>
          </ol>
          <h6 class="font-weight-bolder text-white mb-0">Gestionar Categoria</h6>
        </nav>
        <?php include 'complementos/nav.php'; ?>
      </div>
    </nav>

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
                  <i class="fa-solid fa-tag mr-2" style="color: #f6c5b4;"></i>
                  Categoria
                </h4>
<!-- Dentro de .d-sm-flex, justo después de btnAbrirRegistrar -->
 
                <div class="d-flex gap-2">
                    <?php
                  $accion_1 = false;
                if ( ($_SESSION["nivel_rol"] == 2 || $_SESSION["nivel_rol"] == 3) && !empty($_SESSION['permisos'])) {
                      foreach ($_SESSION['permisos'] as $permiso) {
                          if (
                              $permiso['id_modulo'] == 7 &&
                              $permiso['accion'] === 'registrar' &&
                              $permiso['estado'] == 1
                          ) {
                              $accion_1 = true;
                              break;
                          }
                      }
                  }
                  if ($accion_1) {
                  ?> 
                  <button id="btnAbrirRegistrar" class="btn btn-success">
                    <i class="fas fa-file-medical"></i> Registrar
                  </button>
                     <?php } ?>
                  <button id="btnAyuda" type="button" class="btn btn-primary">
                    <i class="fas fa-info-circle"></i> Ayuda
                  </button>
              </div>

              </div>

              <div class="table-responsive">
                <table class="table table-bordered table-hover" id="myTable" width="100%" cellspacing="0">
                  <thead class="table-color">
                    <tr>
                      <th class="text-white">ID</th>
                      <th class="text-white">NOMBRE</th>
                      <th class="text-white">ACCION</th>
                    </tr>
                  </thead>

                  <tbody>
                    <?php foreach ($categorias as $dato): ?>
                      <tr data-id="<?= $dato['id_categoria'] ?>">
                        <td><?= $dato['id_categoria'] ?></td>
                        <td><?= htmlspecialchars($dato['nombre']) ?></td>
                        <td class="text-center">
                          <div class="d-flex justify-content-center gap-2">
                             <button class="btn btn-primary btn-sm btnModif">
                              <i class="fas fa-pencil-alt"></i>
                             </button>
                             <button class="btn btn-danger btn-sm btnElim">
                              <i class="fas fa-trash-alt"></i>
                             </button>
                            </div>
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

    <!-- Modal único para Registrar/Modificar -->
    <div class="modal fade" id="registro" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

          <div class="modal-header header-color">
            <h5 class="modal-title" id="modalTitle">Registrar Categoría</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>

          <div class="modal-body">
            <form id="u" autocomplete="off">
              <input type="hidden" id="id_categoria" name="id_categoria" value="">
              <input type="hidden" id="accion" name="accion" value="registrar">

              <label for="nombre">Nombre</label>
              <input type="text"
                     id="nombre"
                     name="nombre"
                     class="form-control"
                     maxlength="30"
                     placeholder="Ejemplo: Polvo"
                     required>
              <span id="snombre" class="text-danger"></span>
              <br>

              <div class="text-center">
                <button type="button" id="btnEnviar" class="btn btn-primary">Guardar</button>
                <button type="reset" id="btnLimpiar" class="btn btn-secondary">Limpiar</button>
              </div>
            </form>
          </div>

        </div>
      </div>
    </div>

    <?php include 'complementos/footer.php'; ?>
    <script src="assets/js/categoria.js"></script>
  </main>
</body>
</html>