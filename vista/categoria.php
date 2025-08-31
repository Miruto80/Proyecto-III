<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'complementos/head.php'; ?>
  <title> Categoria | LoveMakeup </title>
  <link rel="stylesheet" href="assets/css/formulario.css">
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
            <div class="card-header pb-0 div-oscuro-2">
              <div class="d-sm-flex align-items-center justify-content-between mb-5">
                <h4 class="mb-0 texto-quinto">
                  <i class="fa-solid fa-tag me-2" style="color: #f6c5b4;"></i>
                  Categoria
                </h4>
 
                <div class="d-flex gap-2">
                    <?php if ($_SESSION["nivel_rol"] == 3 && tieneAcceso(7, 'registrar')): ?>
                  <button id="btnAbrirRegistrar" class="btn btn-success" title="(CONTROL + ALT + N) Registrar categoría">
                    <span class="icon text-white">
                      <i class="fas fa-file-medical me-2"></i>
                    </span>
                    <span class="text-white">Registrar</span>
                  </button>
                       <?php endif; ?>
                  <button id="btnAyuda" type="button" class="btn btn-primary" title="(CONTROL + ALT + A) click para ver la ayuda">
                    <span class="icon text-white">
                      <i class="fas fa-info-circle me-2"></i>
                    </span>
                    <span class="text-white">Ayuda</span>
                  </button>
              </div>

              </div>

              <div class="table-responsive">
                <table class="table table-m table-hover" id="myTable" width="100%" cellspacing="0">
                  <thead class="table-color">
                    <tr>
                      <th class="text-white text-center">Nombre de Categoría</th>
                      <th class="text-white text-center">Acción</th>
                    </tr>
                  </thead>

                  <tbody>
                    <?php foreach ($categorias as $dato): ?>
                      <tr data-id="<?= $dato['id_categoria'] ?>">
                        <td>
                          <div class="d-flex align-items-center">
                            <div class="me-3">
                              <i class="fa-solid fa-tag fa-2x" style="color: #f6c5b4;"></i>
                            </div>
                            <div>
                              <div class="text-dark texto-secundario">
                                <b><?= htmlspecialchars($dato['nombre']) ?></b>
                              </div>
                              <div style="font-size: 11px; color: #d67888" class="texto-tercero">
                                <b>ID: <?= $dato['id_categoria'] ?></b>
                              </div>
                            </div>
                          </div>
                        </td>

                        <td class="text-center">
                          <div class="d-flex justify-content-center gap-2">
                              <?php if ($_SESSION["nivel_rol"] == 3 && tieneAcceso(7, 'editar')): ?>
                             <button class="btn btn-primary btn-sm btnModif" title="Editar datos de la categoría">
                              <i class="fas fa-pencil-alt"></i>
                             </button>
                              <?php endif; ?>

                              <?php if ($_SESSION["nivel_rol"] == 3 && tieneAcceso(7, 'eliminar')): ?>
                             <button class="btn btn-danger btn-sm btnElim" title="Eliminar categoría">
                              <i class="fas fa-trash-alt"></i>
                             </button>
                              <?php endif; ?>
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
        <div class="modal-content modal-producto">

          <div class="modal-header">
            <h5 class="modal-title fs-5" id="modalTitle">
              <i class="fa-solid fa-tag"></i>
              <span id="modalTitleText">Registrar Categoría</span>
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" title="(CONTROL + ALT + X) Cerrar" aria-label="Close"></button>
          </div>

          <div class="modal-body bg-s">
            <form id="u" autocomplete="off">
              <input type="hidden" id="id_categoria" name="id_categoria" value="">
              <input type="hidden" id="accion" name="accion" value="registrar">

              <div class="seccion-formulario">
                <h6 class="texto-quinto"><i class="fas fa-tag"></i> Información de la Categoría</h6>
                <div class="row g-3">
                  <div class="col-md-12">
                    <label for="nombre">NOMBRE DE LA CATEGORÍA</label>
                    <div class="input-group">
                      <span class="input-group-text"><i class="fa-solid fa-tag"></i></span>
                      <input type="text"
                             id="nombre"
                             name="nombre"
                             class="form-control"
                             maxlength="30"
                             placeholder="Ejemplo: Polvo, Base, Sombras, etc."
                             required>
                    </div>
                    <span id="snombre" class="error-message"></span>
                  </div>
                </div>
              </div>

              <div class="col">
                <div class="info-box">
                  <div class="info-icon">
                    <i class="fa-solid fa-circle-info"></i>
                  </div>
                  <div class="info-content">
                    <strong>Información Importante:</strong>
                    <p>Las categorías ayudan a organizar los productos del sistema. Una buena categorización facilita la búsqueda y gestión del inventario.</p>
                    <p><b>Recomendaciones:</b></p>
                    <ul class="text-muted">
                      <li>Usa nombres descriptivos y claros</li>
                      <li>Evita nombres muy largos o confusos</li>
                      <li>Piensa en cómo los usuarios buscarán los productos</li>
                    </ul>
                  </div>
                </div>
              </div>

              <!-- Botones -->
              <div class="col-12 text-center">
                <button type="button" id="btnEnviar" class="btn btn-modern btn-guardar me-3">
                  <i class="fa-solid fa-floppy-disk me-2"></i> <span id="btnText">Registrar</span>
                </button>
                <button type="reset" id="btnLimpiar" class="btn btn-modern btn-limpiar">
                  <i class="fa-solid fa-eraser me-2"></i> Limpiar
                </button>
              </div>
            </form>
          </div>

        </div>
      </div>
    </div>

    <?php include 'complementos/footer.php'; ?>
    <!-- para el datatable-->
    <script src="assets/js/demo/datatables-demo.js"></script>
    <script src="assets/js/categoria.js"></script>
  </main>
</body>
</html>