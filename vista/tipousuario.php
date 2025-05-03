<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'complementos/head.php'; ?>
  <title> Tipo de Usuario | LoveMakeup </title>
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

    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0">
              <div class="d-sm-flex align-items-center justify-content-between mb-5">
                <h4 class="mb-0">
                  <i class="fa-solid fa-user-group mr-2" style="color: #f6c5b4;"></i> Tipo Usuario
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
                      <th class="text-white">NOMBRE</th>
                      <th class="text-white">NIVEL</th>
                      <th class="text-white">ESTATUS</th>
                      <th class="text-white">ACCION</th>
                    </tr>
                  </thead>
                  <tbody id="tipousuarioTableBody">
                    <?php foreach ($registro as $dato): ?>
                    <tr>
                      <td><?php echo $dato['id_tipo']; ?></td>
                      <td><?php echo $dato['nombre']; ?></td>
                      <td><?php echo $dato['nivel']; ?></td>
                      <td><?php echo $dato['estatus']; ?></td>
                      <td>
                        <button type="button" class="btn btn-primary btn-sm modificar" 
                                onclick="abrirModalModificar(<?php echo $dato['id_tipo']; ?>, '<?php echo $dato['nombre']; ?>', <?php echo $dato['nivel']; ?>, <?php echo $dato['estatus']; ?>)"> 
                          <i class="fas fa-pencil-alt" title="Editar"> </i> 
                        </button>
                        <button type="button" class="btn btn-danger btn-sm eliminar" 
                                onclick="eliminarTipoUsuario(<?php echo $dato['id_tipo']; ?>)">
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
            <h1 class="modal-title fs-5">Registrar Tipo Usuario</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="formRegistrar" autocomplete="off">
              <label>NOMBRE</label>
              <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Ejemplo: Admin" required> <br>
              <label>NIVEL</label>
              <input type="number" class="form-control" name="nivel" id="nivel" placeholder="Ejemplo: 1" required> <br>
              <label>ESTATUS</label>
              <select class="form-control" name="estatus" id="estatus" required>
                <option value="1">Activo</option>
                <option value="0">Inactivo</option>
              </select> <br>
              <div class="text-center">
                <button type="button" class="btn btn-primary" id="registrar">Registrar</button>
                <button type="reset" class="btn btn-primary">Limpiar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal para modificar -->
    <div class="modal fade" id="modificar" tabindex="-1" aria-labelledby="modificarLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Modificar Tipo Usuario</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="formModificar" autocomplete="off">
              <input type="hidden" name="id_tipo" id="id_tipo_modificar">
              <label>NOMBRE</label>
              <input type="text" class="form-control" name="nombre" id="nombre_modificar" required>
              <br>
              <label>NIVEL</label>
              <input type="number" class="form-control" name="nivel" id="nivel_modificar" required>
              <br>
              <label>ESTATUS</label>
              <select class="form-control" name="estatus" id="estatus_modificar" required>
                <option value="1">Activo</option>
                <option value="0">Inactivo</option>
              </select> <br>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" id="btnModificar">Modificar</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          </div>
          </form>
        </div>
      </div>
    </div>

    <?php include 'complementos/footer.php'; ?>
    <script src="assets/js/tipousuario.js"></script>
  </main>
</body>
</html>
