<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'complementos/head.php'; ?>
  <title> Tipo de Usuario | LoveMakeup </title>
  <link rel="stylesheet" href="assets/css/formulario.css">
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
            <div class="card-header pb-0 div-oscuro-2">
              <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h4 class="mb-0 texto-quinto">
                  <i class="fa-solid fa-user-group mr-2" style="color: #f6c5b4;"></i> Tipo Usuario
                </h4>
                <div class="d-flex align-items-center gap-2">
                  <?php if ($_SESSION["nivel_rol"] == 3 && tieneAcceso(14, 'registrar')): ?>
                  <button type="button"
                          class="btn btn-success"
                          data-bs-toggle="modal"
                          data-bs-target="#registro"
                          title="Registrar nuevo tipo de usuario">
                    <i class="fas fa-file-medical me-1"></i>
                    Registrar
                  </button>
                  <?php endif; ?>
                  
                  <button type="button" class="btn btn-primary" id="btnAyuda" title="Ver ayuda del módulo">
                    <i class="fas fa-info-circle me-1"></i>
                    Ayuda
                  </button>
                </div>
              </div>

              <div class="table-responsive">
                <table class="table table-m table-hover" id="myTable" width="100%" cellspacing="0">
                  <thead class="table-color">
                    <tr>
                      <th class="text-white text-center">Tipo de Usuario</th>
                      <th class="text-white text-center">Nivel</th>
                      <th class="text-white text-center">Estatus</th>
                      <th class="text-white text-center">Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                    $estatus_texto = array(
                      1 => "Activo",
                      2 => "Inactivo"
                    );
                    
                    $estatus_classes = array(
                      1 => 'badge bg-success text-dark',
                      2 => 'badge bg-danger'
                    );
                    
                    foreach ($registro as $dato): ?>
                      <tr>
                        <td>
                          <div class="d-flex align-items-center">
                            <div class="me-3">
                              <i class="fa-solid fa-user-tag fa-2x" style="color: #f6c5b4;"></i>
                            </div>
                            <div>
                              <div class="text-dark texto-secundario">
                                <b><?= htmlspecialchars($dato['nombre']) ?></b>
                              </div>
                              <div style="font-size: 12px; color: #6c757d;" class="texto-tercero">
                                ID: <?= $dato['id_rol'] ?>
                              </div>
                            </div>
                          </div>
                        </td>
                        <td class="text-center text-dark">
                          <div>
                            <span class="badge bg-primary">Nivel <?= htmlspecialchars($dato['nivel']) ?></span>
                          </div>
                        </td>
                        <td class="text-center">
                          <span class="<?= $estatus_classes[$dato['estatus']] ?>">
                            <?= $estatus_texto[$dato['estatus']] ?>
                          </span>
                        </td>
                        <td class="text-center">
                          <button type="button" 
                                  class="btn btn-info btn-sm me-1" 
                                  title="Ver información del tipo de usuario"
                                  data-bs-toggle="modal"
                                  data-bs-target="#infoModal"
                                  data-nombre="<?= htmlspecialchars($dato['nombre']) ?>"
                                  data-nivel="<?= $dato['nivel'] ?>"
                                  data-estatus="<?= $dato['estatus'] ?>"
                                  data-id="<?= $dato['id_rol'] ?>">
                            <i class="fas fa-eye" title="Ver Detalles"></i>
                          </button>
                          
                          <?php if ($_SESSION["nivel_rol"] == 3 && tieneAcceso(14, 'editar')): ?>
                          <button type="button"
                                  class="btn btn-primary btn-sm me-1 modificar"
                                  title="Editar tipo de usuario"
                                  data-id="<?= $dato['id_rol'] ?>"
                                  data-nombre="<?= htmlspecialchars($dato['nombre']) ?>"
                                  data-nivel="<?= $dato['nivel'] ?>"
                                  data-estatus="<?= $dato['estatus'] ?>"
                                  data-bs-toggle="modal"
                                  data-bs-target="#modificar">
                            <i class="fas fa-pencil-alt"></i>
                          </button>
                          <?php endif; ?>
                          
                          <?php if ($_SESSION["nivel_rol"] == 3 && tieneAcceso(14, 'eliminar')): ?>
                          <button type="button"
                                  class="btn btn-danger btn-sm eliminar"
                                  title="Eliminar tipo de usuario"
                                  value="<?= $dato['id_rol'] ?>"
                                  data-nombre="<?= htmlspecialchars($dato['nombre']) ?>">
                            <i class="fas fa-trash-alt"></i>
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

    <!-- Modal Registrar -->
    <div class="modal fade" id="registro" tabindex="-1" aria-labelledby="registroModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content modal-producto">
          <div class="modal-header">
            <h5 class="modal-title fs-5" id="registroModalLabel">
              <i class="fa-solid fa-user-plus"></i>
              Registrar Tipo Usuario
            </h5>
            <button type="button" class="btn-close" title="(CONTROL + ALT + X) Cerrar" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <div class="modal-body bg-s">
            <form id="u" autocomplete="off">
              <div class="seccion-formulario">
                <h6 class="texto-quinto"><i class="fas fa-user-tag"></i> Datos del Tipo de Usuario</h6>
                <div class="row g-3">
                  <!-- Nombre del Tipo -->
                  <div class="col-md-12">
                    <label for="nombre">NOMBRE DEL TIPO</label>
                    <div class="input-group">
                      <span class="input-group-text"><i class="fa-solid fa-user-tag"></i></span>
                      <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Ejemplo: Administrador, Vendedor, Supervisor" required>
                    </div>
                    <span id="snombre" class="error-message"></span>
                  </div>

                  <!-- Nivel -->
                  <div class="col-md-6">
                    <label for="nivel">NIVEL DE ACCESO</label>
                    <div class="input-group">
                      <span class="input-group-text"><i class="fa-solid fa-layer-group"></i></span>
                      <select class="form-select" name="nivel" id="nivel" required>
                        <option value="">Seleccione nivel</option>
                        <option value="2">Nivel 2 - Acceso Limitado</option>
                        <option value="3">Nivel 3 - Acceso Completo</option>
                      </select>
                    </div>
                    <span id="snivel" class="error-message"></span>
                  </div>

                  <!-- Estatus -->
                  <div class="col-md-6">
                    <label for="estatus">ESTADO</label>
                    <div class="input-group">
                      <span class="input-group-text"><i class="fa-solid fa-toggle-on"></i></span>
                      <select class="form-select" name="estatus" id="estatus" required>
                        <option value="1">Activo</option>
                        <option value="2">Inactivo</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>

              <hr class="bg-primary">
              
              <div class="col">
                <div class="info-box">
                  <div class="info-icon">
                    <i class="fa-solid fa-circle-info"></i>
                  </div>
                  <div class="info-content">
                    <strong>Información sobre Niveles de Acceso:</strong>
                    <p><b>Nivel 2 - Acceso Limitado:</b><br>
                      <span class="text-muted">Reporte, Producto, Venta, Reserva, Pedido Web.</span>
                    </p>
                    <p><b>Nivel 3 - Acceso Completo:</b><br>
                      <span class="text-muted">Reporte, Compra, Producto, Venta, Reserva, Proveedor, Categoría, Cliente, Pedido Web, Método de Pago, Método de Entrega, Usuario, Tipo de Usuario.</span>
                    </p>
                    <p>Los permisos se asignan automáticamente según el nivel seleccionado.</p>
                  </div>
                </div>
              </div>

              <!-- Botones -->
              <div class="col-12 text-center">
                <button type="button" class="btn btn-modern btn-guardar me-3" id="registrar">
                  <i class="fa-solid fa-floppy-disk me-2"></i> Registrar
                </button>
                <button type="reset" class="btn btn-modern btn-limpiar">
                  <i class="fa-solid fa-eraser me-2"></i> Limpiar
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Modificar -->
    <div class="modal fade" id="modificar" tabindex="-1" aria-labelledby="modificarModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content modal-producto">
          <div class="modal-header">
            <h5 class="modal-title fs-5" id="modificarModalLabel">
              <i class="fas fa-pencil-alt"></i>
              Modificar Tipo Usuario
            </h5>
            <button type="button" class="btn-close" title="(CONTROL + ALT + X) Cerrar" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body bg-s">
            <form id="formModificar" autocomplete="off">
              <input type="hidden" name="id_tipo" id="id_tipo_modificar">
              
              <div class="seccion-formulario">
                <h6 class="texto-quinto"><i class="fas fa-edit"></i> Modificar Datos del Tipo de Usuario</h6>
                <div class="row g-3">
                  <!-- Nombre del Tipo -->
                  <div class="col-md-12">
                    <label for="nombre_modificar">NOMBRE DEL TIPO</label>
                    <div class="input-group">
                      <span class="input-group-text"><i class="fa-solid fa-user-tag"></i></span>
                      <input type="text" class="form-control" name="nombre" id="nombre_modificar" placeholder="Ejemplo: Administrador, Vendedor, Supervisor" required>
                    </div>
                    <span id="snombre_modificar" class="error-message"></span>
                  </div>

                  <!-- Nivel -->
                  <div class="col-md-6">
                    <label for="nivel_modificar">NIVEL DE ACCESO</label>
                    <div class="input-group">
                      <span class="input-group-text"><i class="fa-solid fa-layer-group"></i></span>
                      <select class="form-select" name="nivel" id="nivel_modificar" required>
                        <option value="">Seleccione nivel</option>
                        <option value="2">Nivel 2 - Acceso Limitado</option>
                        <option value="3">Nivel 3 - Acceso Completo</option>
                      </select>
                    </div>
                    <span id="snivel_modificar" class="error-message"></span>
                  </div>

                  <!-- Estatus -->
                  <div class="col-md-6">
                    <label for="estatus_modificar">ESTADO</label>
                    <div class="input-group">
                      <span class="input-group-text"><i class="fa-solid fa-toggle-on"></i></span>
                      <select class="form-select" name="estatus" id="estatus_modificar" required>
                        <option value="1">Activo</option>
                        <option value="2">Inactivo</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>

              <hr class="bg-primary">
              
              <div class="col">
                <div class="info-box">
                  <div class="info-icon">
                    <i class="fa-solid fa-circle-info"></i>
                  </div>
                  <div class="info-content">
                    <strong>Información sobre Niveles de Acceso:</strong>
                    <p><b>Nivel 2 - Acceso Limitado:</b><br>
                      <span class="text-muted">Reporte, Producto, Venta, Reserva, Pedido Web.</span>
                    </p>
                    <p><b>Nivel 3 - Acceso Completo:</b><br>
                      <span class="text-muted">Reporte, Compra, Producto, Venta, Reserva, Proveedor, Categoría, Cliente, Pedido Web, Método de Pago, Método de Entrega, Usuario, Tipo de Usuario.</span>
                    </p>
                    <p>Los permisos se asignan automáticamente según el nivel seleccionado.</p>
                  </div>
                </div>
              </div>

              <!-- Botones -->
              <div class="col-12 text-center">
                <button type="button" class="btn btn-modern btn-guardar me-3" id="btnModificar">
                  <i class="fa-solid fa-check me-2"></i> ACTUALIZAR
                </button>
                <button type="button" class="btn btn-modern btn-limpiar" data-bs-dismiss="modal">
                  <i class="fa-solid fa-times me-2"></i> Cancelar
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de Información -->
    <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-producto">
          <div class="modal-header header-color text-white">
            <h5 class="modal-title" id="infoModalLabel">
              <i class="fas fa-user-tag me-2"></i>Información del Tipo de Usuario
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body bg-s">
            <div class="seccion-formulario table-responsive">
              <table class="table">
                <tr>
                  <th class="texto-secundario">ID del Tipo</th>
                  <td id="modalId" class="texto-secundario"></td>
                </tr>
                <tr>
                  <th class="texto-secundario">Nombre</th>
                  <td id="modalNombre" class="texto-secundario"></td>
                </tr>
                <tr>
                  <th class="texto-secundario">Nivel</th>
                  <td id="modalNivel" class="texto-secundario"></td>
                </tr>
                <tr>
                  <th class="texto-secundario">Estatus</th>
                  <td id="modalEstatus" class="texto-secundario"></td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <?php include 'complementos/footer.php'; ?>
    <!-- para el datatable-->
    <script src="assets/js/demo/datatables-demo.js"></script>
    <script src="assets/js/tipousuario.js"></script>

    <script>
      // Script para el modal de información
      const infoModal = document.getElementById('infoModal');
      infoModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;

        const id = button.getAttribute('data-id');
        const nombre = button.getAttribute('data-nombre');
        const nivel = button.getAttribute('data-nivel');
        const estatus = button.getAttribute('data-estatus');

        const estatusTexto = {
          1: 'Activo',
          2: 'Inactivo'
        };

        const estatusClase = {
          1: 'badge bg-success text-dark',
          2: 'badge bg-danger text-white'
        };

        document.getElementById('modalId').textContent = id;
        document.getElementById('modalNombre').textContent = nombre;
        document.getElementById('modalNivel').innerHTML = `<span class="badge bg-primary">Nivel ${nivel}</span>`;
        document.getElementById('modalEstatus').innerHTML = `<span class="${estatusClase[estatus]}">${estatusTexto[estatus]}</span>`;
      });
    </script>
  </main>
</body>
</html>
