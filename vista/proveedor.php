<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'complementos/head.php'; ?> 
  <title> Proveedor | LoveMakeup </title> 
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
              <i class="fa-solid fa-truck-moving mr-2" style="color: #f6c5b4;"></i> Proveedores
            </h4>

            <div class="d-flex align-items-center gap-2"> 
                <?php if ($_SESSION["nivel_rol"] == 3 && tieneAcceso(6, 'registrar')): ?>
              <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#registro" id="btnAbrirRegistrar">
                <i class="fas fa-file-medical"></i> Registrar
              </button>
              <?php endif; ?>
              
              <button type="button" class="btn btn-primary" id="btnAyuda">
                <i class="fas fa-info-circle"></i> Ayuda
              </button>
              
            </div>
          </div>
    

              <div class="table-responsive">
                <table class="table table-bordered table-hover" id="myTable" width="100%" cellspacing="0">
                  <thead class="table-color">
                    <tr>
                      <th class="text-white">DOCUMENTO</th>
                      <th class="text-white">NOMBRE</th>
                      <th class="text-white">CORREO</th>
                      <th class="text-white">TELÉFONO</th>
                      <th class="text-white">DIRECCIÓN</th>
                      <th class="text-white">ACCIONES</th>
                    </tr>
                  </thead>
                  <tbody id="proveedorTableBody">
                    <?php foreach ($registro as $dato): ?>
                    <tr>
                      <td><?php echo $dato['tipo_documento']; ?> <?php echo $dato['numero_documento']; ?></td>
                      <td><?php echo $dato['nombre']; ?></td>
                      <td><?php echo $dato['correo']; ?></td>
                      <td><?php echo $dato['telefono']; ?></td>
                      <td><?php echo $dato['direccion']; ?></td>
                      <td>
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
                                  onclick="abrirModalModificar(<?php echo $dato['id_proveedor']; ?>)"> 
                            <i class="fas fa-pencil-alt" title="Editar"> </i> 
                          </button>
                        <?php endif; ?>

                        <?php if ($_SESSION["nivel_rol"] == 3 && tieneAcceso(6, 'eliminar')): ?>
                          <button type="button" class="btn btn-danger btn-sm eliminar" 
                                  onclick="eliminarProveedor(<?php echo $dato['id_proveedor']; ?>)">
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
    <div class="modal-content">
      <div class="modal-header header-color">
        <h5 class="modal-title" id="modalTitle">Registrar Proveedor</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="formProveedor" enctype="multipart/form-data" autocomplete="off">
          <input type="hidden" name="id_proveedor" id="id_proveedor" value="">
          <input type="hidden" name="accion" id="accion" value="registrar">
          
          <div class="row">
            <div class="col-md-6">
              <label>TIPO DE DOCUMENTO</label>
              <select class="form-control" name="tipo_documento" id="tipo_documento" required>
                <option value="">Seleccione...</option>
                <option value="V">V</option>
                <option value="J">J</option>
                <option value="E">E</option>
                <option value="G">G</option>
              </select>
            </div>
            <div class="col-md-6">
              <label>NÚMERO DE DOCUMENTO</label>
              <input type="text" class="form-control" name="numero_documento" id="numero_documento" placeholder="Ejemplo: 12345678" maxlength="9" required>
              <span id="snumero_documento" class="text-danger"></span>              
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-12">
              <label>NOMBRE</label>
              <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Ejemplo: Proveedor XYZ" maxlength="30" required>
              <span id="snombre" class="text-danger"></span>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-6">
              <label>CORREO</label>
              <input type="email" class="form-control" name="correo" id="correo" placeholder="Ejemplo: proveedor@ejemplo.com" maxlength="60" required>
              <span id="scorreo" class="text-danger"></span>
            </div>
            <div class="col-md-6">
              <label>TELÉFONO</label>
              <input type="text" class="form-control" name="telefono" id="telefono" placeholder="Ejemplo: 04121234567" maxlength="11" required>
              <span id="stelefono" class="text-danger"></span>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-12">
              <label>DIRECCIÓN</label>
              <textarea class="form-control" name="direccion" id="direccion" rows="3" placeholder="Ejemplo: Av. Principal, Local #123" maxlength="70" required></textarea>
              <span id="sdireccion" class="text-danger"></span>
            </div>
          </div>
          <br>

          <div class="text-center">
            <button type="button" class="btn btn-primary" id="btnEnviar">Guardar</button>
            <button type="reset" class="btn btn-secondary">Limpiar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php foreach($registro as $prov): ?>
  <div class="modal fade" id="verDetallesModal<?= $prov['id_proveedor'] ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header header-color">
          <h5 class="modal-title text-white">Detalles del Proveedor</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <?php 
            // Trae todos los campos de este proveedor
            $det = $obj->consultarPorId($prov['id_proveedor']);
          ?>

          <!-- Datos Generales -->
          <div class="card mb-3">
            <div class="card-header bg-light" data-bs-toggle="collapse"
                data-bs-target="#collapse-gen-<?= $prov['id_proveedor'] ?>"
                style="cursor:pointer">
              <i class="fas fa-info-circle"></i> Datos Generales
              <i class="fas fa-chevron-down float-end"></i>
            </div>
            <div class="collapse" id="collapse-gen-<?= $prov['id_proveedor'] ?>">
              <div class="card-body">
                <p><strong>Documento:</strong> 
                   <?= htmlspecialchars($det['tipo_documento'].' '.$det['numero_documento']) ?></p>
                <p><strong>Nombre:</strong> <?= htmlspecialchars($det['nombre']) ?></p>
                <p><strong>Correo:</strong> <?= htmlspecialchars($det['correo']) ?></p>
                <p><strong>Teléfono:</strong> <?= htmlspecialchars($det['telefono']) ?></p>
              </div>
            </div>
          </div>

          <!-- Dirección -->
          <div class="card mb-3">
            <div class="card-header bg-light" data-bs-toggle="collapse"
                data-bs-target="#collapse-dir-<?= $prov['id_proveedor'] ?>"
                style="cursor:pointer">
              <i class="fas fa-map-marker-alt"></i> Dirección
              <i class="fas fa-chevron-down float-end"></i>
            </div>
            <div class="collapse" id="collapse-dir-<?= $prov['id_proveedor'] ?>">
              <div class="card-body">
                <p><?= nl2br(htmlspecialchars($det['direccion'])) ?></p>
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