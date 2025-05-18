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

    <div class="container-fluid py-4">
      <div class="row">  
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0">  
              <div class="d-sm-flex align-items-center justify-content-between mb-5">
                <h4 class="mb-0">
                  <i class="fa-solid fa-truck-moving mr-2" style="color: #f6c5b4;"></i> Proveedores
                </h4>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#registro">
                  <span class="icon text-white"><i class="fas fa-file-medical"></i></span>
                  <span class="text-white">Registrar</span>
                </button>
              </div>

              <div class="table-responsive">
                <table class="table table-bordered table-hover" id="myTable" width="100%" cellspacing="0">
                  <thead class="table-color">
                    <tr>
                      <th class="text-white">ID</th>
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
                      <td><?php echo $dato['id_proveedor']; ?></td>
                      <td><?php echo $dato['tipo_documento']; ?> <?php echo $dato['numero_documento']; ?></td>
                      <td><?php echo $dato['nombre']; ?></td>
                      <td><?php echo $dato['correo']; ?></td>
                      <td><?php echo $dato['telefono']; ?></td>
                      <td><?php echo $dato['direccion']; ?></td>
                      <td>
                        <button type="button" class="btn btn-primary btn-sm modificar" 
                                onclick="abrirModalModificar(<?php echo $dato['id_proveedor']; ?>)"> 
                          <i class="fas fa-pencil-alt" title="Editar"> </i> 
                        </button>
                        <button type="button" class="btn btn-danger btn-sm eliminar" 
                                onclick="eliminarProveedor(<?php echo $dato['id_proveedor']; ?>)">
                          <i class="fas fa-trash-alt" title="Eliminar"> </i>
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

    <!-- Modal para registrar -->
    <div class="modal fade" id="registro" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header header-color">
            <h1 class="modal-title fs-5">Registrar Proveedor</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="formRegistrar" autocomplete="off">
              <div class="row">
                <div class="col-md-6">
                  <label>TIPO DOCUMENTO</label>
                  <select class="form-control" name="tipo_documento" id="tipo_documento" required>
                    <option value="">Seleccione...</option>
                    <option value="V">V</option>
                    <option value="J">J</option>
                    <option value="E">E</option>
                    <option value="G">G</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label>NÚMERO DOCUMENTO</label>
                  <input type="text" class="form-control" name="numero_documento" id="numero_documento" placeholder="Ejemplo: 12345678" required>
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-12">
                  <label>NOMBRE</label>
                  <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Ejemplo: Proveedor XYZ" required>
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-6">
                  <label>CORREO</label>
                  <input type="email" class="form-control" name="correo" id="correo" placeholder="Ejemplo: proveedor@ejemplo.com">
                </div>
                <div class="col-md-6">
                  <label>TELÉFONO</label>
                  <input type="text" class="form-control" name="telefono" id="telefono" placeholder="Ejemplo: 04121234567">
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-12">
                  <label>DIRECCIÓN</label>
                  <textarea class="form-control" name="direccion" id="direccion" rows="3" placeholder="Ejemplo: Av. Principal, Local #123"></textarea>
                </div>
              </div>
              <br>
              <div class="text-center">
                <button type="button" class="btn btn-primary" id="registrar">Registrar</button>
                <button type="reset" class="btn btn-secondary">Limpiar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal para modificar -->
    <div class="modal fade" id="modificar" tabindex="-1" aria-labelledby="modificarLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header header-color">
            <h5 class="modal-title">Modificar Proveedor</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="formModificar" autocomplete="off">
              <input type="hidden" name="id_proveedor" id="id_proveedor_modificar">
              <div class="row">
                <div class="col-md-6">
                  <label>TIPO DOCUMENTO</label>
                  <select class="form-control" name="tipo_documento" id="tipo_documento_modificar" required>
                    <option value="">Seleccione...</option>
                    <option value="V">V</option>
                    <option value="J">J</option>
                    <option value="E">E</option>
                    <option value="G">G</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label>NÚMERO DOCUMENTO</label>
                  <input type="text" class="form-control" name="numero_documento" id="numero_documento_modificar" required>
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-12">
                  <label>NOMBRE</label>
                  <input type="text" class="form-control" name="nombre" id="nombre_modificar" required>
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-6">
                  <label>CORREO</label>
                  <input type="email" class="form-control" name="correo" id="correo_modificar">
                </div>
                <div class="col-md-6">
                  <label>TELÉFONO</label>
                  <input type="text" class="form-control" name="telefono" id="telefono_modificar">
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-12">
                  <label>DIRECCIÓN</label>
                  <textarea class="form-control" name="direccion" id="direccion_modificar" rows="3"></textarea>
                </div>
              </div>
              <br>
              <div class="text-center">
                <button type="button" class="btn btn-primary" id="btnModificar">Modificar</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <?php include 'complementos/footer.php'; ?>
    <script src="assets/js/proveedor.js"></script>
  </main>
</body>
</html>