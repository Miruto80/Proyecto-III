<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'complementos/head.php'; ?> 
  <title> Proveedor | LoveMakeup </title> 
  <link rel="stylesheet" href="assets/css/formulario.css">
</head>

<body class="g-sidenav-show bg-gray-100">
  <?php include 'complementos/sidebar.php'; ?>

  <main class="main-content position-relative border-radius-lg ">
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur" data-scroll="false">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="#">Administrar</a></li>
            <li class="breadcrumb-item text-sm text-white active" aria-current="page">Proveedor</li>
          </ol>
          <h6 class="font-weight-bolder text-white mb-0">Gestionar Proveedor</h6>
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
  <div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header pb-0">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h4 class="mb-0">
              <i class="fa-solid fa-truck-moving me-2" style="color: #f6c5b4;"></i> Proveedores
            </h4>

            <div class="d-flex align-items-center gap-2"> 
                <?php if ($_SESSION["nivel_rol"] == 3 && tieneAcceso(6, 'registrar')): ?>
              <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#registro" id="btnAbrirRegistrar" title="(CONTROL + ALT + N) Registrar proveedor">
                <span class="icon text-white">
                  <i class="fas fa-file-medical me-2"></i>
                </span>
                <span class="text-white">Registrar</span>
              </button>
              <?php endif; ?>
              
              <button type="button" class="btn btn-primary" id="btnAyuda" title="(CONTROL + ALT + A) click para ver la ayuda">
                <span class="icon text-white">
                  <i class="fas fa-info-circle me-2"></i>
                </span>
                <span class="text-white">Ayuda</span>
              </button>
              
            </div>
          </div>
    

              <div class="table-responsive">
                <table class="table table-hover" id="myTable" width="100%" cellspacing="0">
                  <thead class="table-color">
                    <tr>
                      <th class="text-white text-center">Documento y Nombre</th>
                      <th class="text-white text-center">Teléfono</th>
                      <th class="text-white text-center">Acción</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($registro as $dato): ?>
                    <tr>
                      <td>
                        <div class="d-flex align-items-center">
                          <div class="me-3">
                            <i class="fa-solid fa-truck fa-2x" style="color: #f6c5b4;"></i>
                          </div>
                          <div>
                            <div class="text-dark">
                              <b><?php echo $dato['nombre']; ?></b>
                            </div>
                            <div>N° Documento: <?php echo $dato['tipo_documento']; ?> <?php echo $dato['numero_documento']; ?></div>
                          </div>
                        </div>
                      </td>
                      <td class="text-center text-dark">
                        <div><?php echo $dato['telefono']; ?></div>
                      </td>
                      <td class="text-center">
                        <?php if ($_SESSION["nivel_rol"] == 3 && tieneAcceso(6, 'ver')): ?>
                          <button type="button"
                                  class="btn btn-info btn-sm me-1"
                                  data-bs-toggle="modal"
                                  data-bs-target="#verDetallesModal<?= $dato['id_proveedor'] ?>">
                            <i class="fas fa-eye" title="Ver Detalles"></i>
                          </button>
                        <?php endif; ?>

                        <?php if ($_SESSION["nivel_rol"] == 3 && tieneAcceso(6, 'editar')): ?>
                          <button type="button" class="btn btn-primary btn-sm modificar" 
                                  onclick="abrirModalModificar(<?php echo $dato['id_proveedor']; ?>)" title="Editar datos del proveedor"> 
                            <i class="fas fa-pencil-alt" title="Editar"> </i> 
                          </button>
                        <?php endif; ?>

                        <?php if ($_SESSION["nivel_rol"] == 3 && tieneAcceso(6, 'eliminar')): ?>
                          <button type="button" class="btn btn-danger btn-sm eliminar" 
                                  onclick="eliminarProveedor(<?php echo $dato['id_proveedor']; ?>)" title="Eliminar proveedor">
                            <i class="fas fa-trash-alt" title="Eliminar"> </i>
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

    

<!-- Modal único para Registrar/Modificar -->
<div class="modal fade" id="registro" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content modal-producto">
      <div class="modal-header">
        <h5 class="modal-title fs-5" id="modalTitle">
          <i class="fa-solid fa-truck-moving"></i>
          <span id="modalTitleText">Registrar Proveedor</span>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" title="(CONTROL + ALT + X) Cerrar" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="formProveedor" enctype="multipart/form-data" autocomplete="off">
          <input type="hidden" name="id_proveedor" id="id_proveedor" value="">
          <input type="hidden" name="accion" id="accion" value="registrar">
          
          <div class="seccion-formulario">
            <h6><i class="fas fa-id-card"></i> Datos de Identificación</h6>
            <div class="row g-3">
              <div class="col-md-12">
                <label for="tipo_documento">TIPO DE DOCUMENTO</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fa-solid fa-id-card"></i></span>
                  <select class="form-select" name="tipo_documento" id="tipo_documento" required>
                    <option value="">Seleccione...</option>
                    <option value="V">V - Venezolano</option>
                    <option value="J">J - Jurídico</option>
                    <option value="E">E - Extranjero</option>
                    <option value="G">G - Gobierno</option>
                  </select>
                </div>
                <span id="stipo_documento" class="error-message"></span>
              </div>
            </div>
            
            <div class="row g-3 mt-2">
              <div class="col-md-12">
                <label for="numero_documento">NÚMERO DE DOCUMENTO</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fa-solid fa-hashtag"></i></span>
                  <input type="text" class="form-control" name="numero_documento" id="numero_documento" placeholder="Ejemplo: 12345678" maxlength="9" required>
                </div>
                <span id="snumero_documento" class="error-message"></span>
              </div>
            </div>
          </div>

          <div class="seccion-formulario">
            <h6><i class="fas fa-user"></i> Información Personal</h6>
            <div class="row g-3">
              <div class="col-md-12">
                <label for="nombre">NOMBRE COMPLETO</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                  <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Ejemplo: Proveedor XYZ" maxlength="30" required>
                </div>
                <span id="snombre" class="error-message"></span>
              </div>
            </div>
          </div>

          <div class="seccion-formulario">
            <h6><i class="fas fa-address-book"></i> Información de Contacto</h6>
            <div class="row g-3">
              <div class="col-md-6">
                <label for="correo">CORREO ELECTRÓNICO</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                  <input type="email" class="form-control" name="correo" id="correo" placeholder="Ejemplo: proveedor@ejemplo.com" maxlength="60" required>
                </div>
                <span id="scorreo" class="error-message"></span>
              </div>
              
              <div class="col-md-6">
                <label for="telefono">TELÉFONO</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fa-solid fa-mobile-screen-button"></i></span>
                  <input type="text" class="form-control" name="telefono" id="telefono" placeholder="Ejemplo: 04121234567" maxlength="11" required>
                </div>
                <span id="stelefono" class="error-message"></span>
              </div>
            </div>
          </div>

          <div class="seccion-formulario">
            <h6><i class="fas fa-map-marker-alt"></i> Información de Ubicación</h6>
            <div class="row g-3">
              <div class="col-md-12">
                <label for="direccion">DIRECCIÓN</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fa-solid fa-location-dot"></i></span>
                  <textarea class="form-control" name="direccion" id="direccion" rows="3" placeholder="Ejemplo: Av. Principal, Local #123, Ciudad, Estado" maxlength="70" required></textarea>
                </div>
                <span id="sdireccion" class="error-message"></span>
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
                <p>Los proveedores registrados podrán ser asignados a productos para gestionar el inventario y las compras del sistema.</p>
                <p><b>Tipos de Documento:</b></p>
                <ul class="text-muted">
                  <li><b>V:</b> Venezolano (Cédula de Identidad)</li>
                  <li><b>J:</b> Jurídico (RIF)</li>
                  <li><b>E:</b> Extranjero (Pasaporte)</li>
                  <li><b>G:</b> Gobierno (Documentos oficiales)</li>
                </ul>
              </div>
            </div>
          </div>

          <!-- Botones -->
          <div class="col-12 text-center">
            <button type="button" class="btn btn-modern btn-guardar me-3" id="btnEnviar">
              <i class="fa-solid fa-floppy-disk me-2"></i> <span id="btnText">Registrar</span>
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

<?php foreach($registro as $prov): ?>
  <div class="modal fade" id="verDetallesModal<?= $prov['id_proveedor'] ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content modal-producto">
        <div class="modal-header">
          <h5 class="modal-title text-white">
            <i class="fa-solid fa-eye"></i> Detalles del Proveedor
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" title="(CONTROL + ALT + X) Cerrar"></button>
        </div>
        <div class="modal-body">
          <?php 
            // Trae todos los campos de este proveedor
            $det = $obj->consultarPorId($prov['id_proveedor']);
          ?>

          <div class="seccion-formulario">
            <h6><i class="fas fa-id-card"></i> Datos de Identificación</h6>
            <div class="row">
              <div class="col-md-6">
                <p><strong>Tipo de Documento:</strong> <?= htmlspecialchars($det['tipo_documento']) ?></p>
              </div>
              <div class="col-md-6">
                <p><strong>Número de Documento:</strong> <?= htmlspecialchars($det['numero_documento']) ?></p>
              </div>
            </div>
          </div>

          <div class="seccion-formulario">
            <h6><i class="fas fa-user"></i> Información Personal</h6>
            <div class="row">
              <div class="col-md-12">
                <p><strong>Nombre:</strong> <?= htmlspecialchars($det['nombre']) ?></p>
              </div>
            </div>
          </div>

          <div class="seccion-formulario">
            <h6><i class="fas fa-address-book"></i> Información de Contacto</h6>
            <div class="row">
              <div class="col-md-6">
                <p><strong>Correo:</strong> <?= htmlspecialchars($det['correo']) ?></p>
              </div>
              <div class="col-md-6">
                <p><strong>Teléfono:</strong> <?= htmlspecialchars($det['telefono']) ?></p>
              </div>
            </div>
          </div>

          <div class="seccion-formulario">
            <h6><i class="fas fa-map-marker-alt"></i> Información de Ubicación</h6>
            <div class="row">
              <div class="col-md-12">
                <p><strong>Dirección:</strong></p>
                <p class="text-muted"><?= nl2br(htmlspecialchars($det['direccion'])) ?></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php endforeach; ?>


<?php include 'complementos/footer.php'; ?>

<script src="assets/js/proveedor.js"></script>
</body>
</html>