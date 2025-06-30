<!DOCTYPE html>
<html lang="es">
<head>

  <?php include 'complementos/head.php'; ?> 
  <title> Categoria | LoveMakeup  </title> 
</head>

<body class="g-sidenav-show bg-gray-100">

  <?php include 'complementos/sidebar.php'; ?>
  <main class="main-content position-relative border-radius-lg ">
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur" data-scroll="false">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="#">Administrar</a></li>
            <li class="breadcrumb-item text-sm text-white active" aria-current="page">Categoria</li>
          </ol>
          <h6 class="font-weight-bolder text-white mb-0">Gestionar Categoria</h6>
        </nav>

        <?php include 'complementos/nav.php'; ?>
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
                  <div class="d-sm-flex align-items-center justify-content-between mb-5">
                    <h4 class="mb-0"><i class="fa-solid fa-tag mr-2" style="color: #f6c5b4;"></i> Categoria</h4>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#registro">
                      <span class="icon text-white">
                        <i class="fas fa-file-medical"></i>
                      </span>
                      <span class="text-white">Registrar</span>
                    </button>
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
                      <tbody id="categoriaTableBody">
                        <?php foreach ($registro as $dato): ?>
                        <tr>
                          <td><?php echo $dato['id_categoria']; ?></td>
                          <td><?php echo $dato['nombre']; ?></td>
                          <td>
                            <button type="button" class="btn btn-primary btn-sm modificar" 
                                    onclick="abrirModalModificar(<?php echo $dato['id_categoria']; ?>, '<?php echo $dato['nombre']; ?>')"> 
                              <i class="fas fa-pencil-alt" title="Editar"> </i> 
                            </button>
                            <button type="button" class="btn btn-danger btn-sm eliminar" 
                                    onclick="eliminarCategoria(<?php echo $dato['id_categoria']; ?>)">
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
              <!-- Modal Registro -->
              <div class="modal fade" id="registro" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header header-color">
                      <h5 class="modal-title">Registrar Categoría</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                      <form id="formRegistrar" autocomplete="off">
                        <label for="nombre">Nombre</label>
                        <input type="text" id="nombre" name="nombre" class="form-control" maxlength="30" placeholder="Ejemplo: Polvo">
                        <span id="snombre" class="text-danger"></span><br>
                        <div class="text-center">
                          <button type="button" id="registrar" class="btn btn-primary">Registrar</button>
                          <button type="reset" class="btn btn-secondary">Limpiar</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Modal Modificar -->
              <div class="modal fade" id="modificar" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Modificar Categoría</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                      <form id="formModificar" autocomplete="off">
                        <input type="hidden" id="id_categoria_modificar" name="id_categoria">
                        <label for="nombre_modificar">Nombre</label>
                        <input type="text" id="nombre_modificar" name="nombre" class="form-control" maxlength="30">
                        <span id="snombre_modificar" class="text-danger"></span><br>
                        <div class="text-center">
                          <button type="button" id="btnModificar" class="btn btn-primary">Modificar</button>
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
    <?php include 'complementos/footer.php'; ?>
    <script src="assets/js/categoria.js"></script>
  </main>
</body>
</html>
