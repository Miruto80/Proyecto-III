<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'complementos/head.php'; ?> 
  <title> Reservas | LoveMakeup </title> 
  
  <style>
    .driver-popover.driverjs-theme {
      color: #000;
    }

    .driver-popover.driverjs-theme .driver-popover-title {
      font-size: 20px;
    }

    .driver-popover.driverjs-theme .driver-popover-title,
    .driver-popover.driverjs-theme .driver-popover-description,
    .driver-popover.driverjs-theme .driver-popover-progress-text {
      color: #000;
    }

    .driver-popover.driverjs-theme button {
      flex: 1;
      text-align: center;
      background-color: #000;
      color: #ffffff;
      border: 2px solid #000;
      text-shadow: none;
      font-size: 14px;
      padding: 5px 8px;
      border-radius: 6px;
    }

    .driver-popover.driverjs-theme button:hover {
      background-color: #000;
      color: #ffffff;
    }

    .driver-popover.driverjs-theme .driver-popover-navigation-btns {
      justify-content: space-between;
      gap: 3px;
    }

    .driver-popover.driverjs-theme .driver-popover-close-btn {
      color: #fff;
      width: 20px; /* Reducir el tamaño del botón */
      height: 20px;
      font-size: 16px;
      transition: all 0.5 ease-in-out;
    }

    .driver-popover.driverjs-theme .driver-popover-close-btn:hover {
     background-color: #fff;
     color: #000;
     border: #000;
    }

    .driver-popover.driverjs-theme .driver-popover-arrow-side-left.driver-popover-arrow {
      border-left-color: #fde047;
    }

    .driver-popover.driverjs-theme .driver-popover-arrow-side-right.driver-popover-arrow {
      border-right-color: #fde047;
    }

    .driver-popover.driverjs-theme .driver-popover-arrow-side-top.driver-popover-arrow {
      border-top-color: #fde047;
    }

    .driver-popover.driverjs-theme .driver-popover-arrow-side-bottom.driver-popover-arrow {
      border-bottom-color: #fde047;
    }
  </style>
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
                <h4 class="mb-0">
                  <i class="fa-solid fa-calendar-check mr-2" style="color: #f6c5b4;"></i> Reservas
                </h4>
                <div class="d-flex gap-2">
                  <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#registro">
                    <span class="icon text-white"><i class="fas fa-file-medical"></i></span>
                    <span class="text-white">Registrar</span>
                  </button>
                  <button type="button" class="btn btn-primary" id="btnAyuda">
                    <span class="icon text-white">
                      <i class="fas fa-info-circle"></i>
                    </span>
                    <span class="text-white">Ayuda</span>
                  </button>
                </div>
              </div>

              <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                  <thead class="table-color">
                    <tr>
                      <th class="text-white">ID</th>
                      <th class="text-white">FECHA RESERVA</th>
                      <th class="text-white">USUARIO</th>
                      <th class="text-white">ESTADO</th>
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
                        <?php 
                        $estado_texto = '';
                        $estado_class = '';
                        switch($dato['estatus']) {
                            case 1:
                                $estado_texto = 'Activo';
                                $estado_class = 'badge bg-success';
                                break;
                            case 0:
                                $estado_texto = 'Inactivo';
                                $estado_class = 'badge bg-secondary';
                                break;
                            case 2:
                                $estado_texto = 'Entregado';
                                $estado_class = 'badge bg-info';
                                break;
                            default:
                                $estado_texto = 'Desconocido';
                                $estado_class = 'badge bg-warning';
                        }
                        ?>
                        <span class="<?php echo $estado_class; ?>"><?php echo $estado_texto; ?></span>
                      </td>
                      <td>
                        <button type="button" class="btn btn-info btn-sm ver-detalle" 
                                onclick="verDetalles(<?php echo $dato['id_reserva']; ?>)"> 
                          <i class="fas fa-eye" title="Ver detalles"> </i> 
                        </button>
                        <?php if($dato['estatus'] == 1): ?>
                        <button type="button" class="btn btn-primary btn-sm editar-estado" 
                                onclick="abrirModalEditarEstado(<?php echo $dato['id_reserva']; ?>)" title="Editar estado">
                          <i class="fas fa-edit"></i> Editar
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

    <!-- Modal para editar estado -->
    <div class="modal fade" id="editarEstado" tabindex="-1" aria-labelledby="editarEstadoLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header header-color">
            <h5 class="modal-title">Editar Estado de Reserva</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
                    <div class="modal-body">
            <div class="row mb-3">
              <div class="col-12">
                <strong>ID Reserva:</strong> <span id="info_id_reserva"></span>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-12">
                <strong>Fecha de reserva:</strong> <span id="info_fecha_reserva"></span>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-12">
                <strong>Usuario:</strong> <span id="info_cliente_reserva"></span>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-12">
                <strong>Estado Actual:</strong> <span id="info_estado_actual"></span>
              </div>
            </div>
            <hr>
            <div class="row mb-3">
              <div class="col-12">
                <label for="nuevo_estado" class="form-label"><strong>Actualizar Estado:</strong></label>
                <select class="form-control" name="nuevo_estado" id="nuevo_estado" required>
                  <option value="">Seleccione un estado</option>
                  <option value="1">Activo</option>
                  <option value="0">Inactivo</option>
                  <option value="2">Entregado</option>
                </select>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="alert alert-warning" style="font-size: 0.85em; padding: 0.4rem;">
                  <i class="fas fa-exclamation-triangle"></i>
                  <strong>Nota:</strong> Una vez que una reserva se marque como "Inactiva" o "Entregada", no podrá volver a ser modificada.
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" id="btnGuardarEstado">Guardar Cambios</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          </div>
        </div>
      </div>
    </div>

    <?php include 'complementos/footer.php'; ?>
    <script src="assets/js/reserva.js"></script>
  </main>
</body>
</html>