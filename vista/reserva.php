<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'complementos/head.php'; ?> 
  <title> Reservas | LoveMakeup </title> 
</head>

<body class="g-sidenav-show bg-gray-100">
  <?php include 'complementos/sidebar.php'; ?>

  <main class="main-content position-relative border-radius-lg ">
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur" data-scroll="false">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="#">Administrar</a></li>
            <li class="breadcrumb-item text-sm text-white active" aria-current="page">Reservas</li>
          </ol>
          <h6 class="font-weight-bolder text-white mb-0">Gestionar Reservas</h6>
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
                  <i class="fa-solid fa-calendar-check mr-2" style="color: #f6c5b4;"></i> Reservas
                </h4>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#registro">
                  <span class="icon text-white"><i class="fas fa-file-medical"></i></span>
                  <span class="text-white">Registrar</span>
                </button>
              </div>

              <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                  <thead class="table-color">
                    <tr>
                      <th class="text-white">ID</th>
                      <th class="text-white">FECHA RESERVA</th>
                      <th class="text-white">USUARIO</th>
                      <th class="text-white">ACCIONES</th>
                    </tr>
                  </thead>
                  <tbody id="reservaTableBody">
                    <?php
                    $reservas = $objreserva->consultarTodos();
                    foreach ($reservas as $dato): ?>
                    <tr>
                      <td><?php echo $dato['id_reserva']; ?></td>
                      <td><?php echo date('d/m/Y', strtotime($dato['fecha_apartado'])); ?></td>
                      <td><?php echo $dato['nombre'] . ' ' . $dato['apellido']; ?></td>
                      <td>
                        <button type="button" class="btn btn-info btn-sm ver-detalle" 
                                onclick="verDetalles(<?php echo $dato['id_reserva']; ?>)"> 
                          <i class="fas fa-eye" title="Ver detalles"> </i> 
                        </button>
                        <button type="button" class="btn btn-primary btn-sm modificar" 
                                onclick="abrirModalModificar(<?php echo $dato['id_reserva']; ?>)"> 
                          <i class="fas fa-pencil-alt" title="Editar"> </i> 
                        </button>
                        <button type="button" class="btn btn-danger btn-sm eliminar" 
                                onclick="eliminarReserva(<?php echo $dato['id_reserva']; ?>)">
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
      <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header header-color">
            <h1 class="modal-title fs-5">Registrar Reserva</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="formRegistrar" autocomplete="off">
              <div class="row">
               <div class="col-md-6">
                  <label>Fecha de reserva</label>
                  <input type="date" class="form-control" name="fecha_apartado" id="fecha_apartado" readonly required>
                </div>
                <div class="col-md-6">
                  <label>Usuario</label>
                  <select class="form-control" name="id_persona" id="id_persona" required>
                    <option value="">Seleccione un usuario</option>
                    <?php
                    $personas = $objreserva->consultarPersonas();
                    foreach ($personas as $persona): ?>
                      <option value="<?php echo $persona['id_persona']; ?>">
                        <?php echo $persona['nombre'] . ' ' . $persona['apellido']; ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <br>
              <h4>Productos</h4>
              <div class="table-responsive">
                <table class="table table-bordered" id="tabla_productos">
                  <thead class="table-color">
                    <tr>
                      <th class="text-white">PRODUCTO</th>
                      <th class="text-white">CANTIDAD</th>
                      <th class="text-white">PRECIO UNIT.</th>
                      <th class="text-white">PRECIO TOTAL</th>
                      <th class="text-white">ACCIÓN</th>
                    </tr>
                  </thead>
                  <tbody id="productos_body">
                    <tr id="fila_producto_0">
                      <td>
                        <select class="form-control producto-select" name="productos[]" required>
                          <option value="">Seleccione un producto</option>
                          <?php
                          $productos = $objreserva->consultarProductos();
                          foreach ($productos as $producto): ?>
                            <option value="<?php echo $producto['id_producto']; ?>" 
                                  data-precio="<?php echo $producto['precio_detal']; ?>">
                              <?php echo $producto['nombre']; ?>
                            </option>
                          <?php endforeach; ?>
                        </select>
                      </td>
                      <td>
                        <input type="number" class="form-control cantidad-input" name="cantidades[]" value="1" min="1" required>
                      </td>
                      <td>
                        <input type="number" step="0.01" class="form-control precio-input" name="precios_unit[]" value="0.00" min="0" required>
                      </td>
                      <td>
                        <input type="text" class="form-control precio-total" value="0.00" readonly>
                      </td>
                      <td>
                        <button type="button" class="btn btn-danger btn-sm eliminar-fila">
                          <i class="fas fa-trash-alt"></i>
                        </button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="row mb-3">
                <div class="col-12">
                  <button type="button" class="btn btn-success " id="agregar_producto">
                    <i class="fas fa-plus"></i> Agregar Producto
                  </button>
                </div>
              </div>
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
            <h5 class="modal-title">Modificar Reserva</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="formModificar" autocomplete="off">
              <input type="hidden" name="id_reserva" id="id_reserva_modificar">
              <div class="row">
                <div class="col-md-6">
                  <label>Fecha de reserva</label>
                  <input type="date" class="form-control" name="fecha_apartado" id="fecha_apartado_modificar" required>
                </div>
                <div class="col-md-6">
                  <label>Usuario</label>
                  <select class="form-control" name="id_persona" id="id_persona_modificar" required>
                    <option value="">Seleccione un usuario</option>
                    <?php foreach ($personas as $persona): ?>
                      <option value="<?php echo $persona['id_persona']; ?>">
                        <?php echo $persona['nombre'] . ' ' . $persona['apellido']; ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
              <div class="row mt-3">
                <div class="col-md-6">
                  <label>Estatus</label>
                  <select class="form-control" name="estatus" id="estatus_modificar" required>
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                  </select>
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

    <!-- Modal para ver detalles -->
    <div class="modal fade" id="verDetalles" tabindex="-1" aria-labelledby="verDetallesLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header header-color">
            <h5 class="modal-title">Detalles de Reserva</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row mb-3">
              <div class="col-md-3">
                <strong>ID Reserva:</strong> <span id="detalle_id_reserva"></span>
              </div>
              <div class="col-md-3">
                <strong>Fecha de reserva:</strong> <span id="detalle_fecha_apartado"></span>
              </div>
              <div class="col-md-3">
                <strong>Usuario:</strong> <span id="detalle_persona"></span>
              </div>
              <div class="col-md-3">
                <strong>Estatus:</strong> <span id="detalle_estatus"></span>
              </div>
            </div>
            <hr>
            <h5>Productos</h5>
            <div class="table-responsive">
              <table class="table table-bordered" id="tabla_detalles">
                <thead class="table-color">
                  <tr>
                    <th class="text-white">PRODUCTO</th>
                    <th class="text-white">CANTIDAD</th>
                    <th class="text-white">PRECIO UNIT.</th>
                    <th class="text-white">PRECIO TOTAL</th>
                  </tr>
                </thead>
                <tbody id="detalles_body">
                  <!-- Los detalles se cargarán mediante AJAX -->
                </tbody>
                <tfoot>
                  <tr>
                    <td colspan="3" class="text-end"><strong>TOTAL:</strong></td>
                    <td id="total_reserva">0.00</td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>

    <?php include 'complementos/footer.php'; ?>
    <script src="assets/js/reserva.js"></script>
  </main>
</body>
</html>