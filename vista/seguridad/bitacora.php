<!DOCTYPE html>
<html lang="es">

<head>
  <!-- php barra de navegacion-->
  <?php include 'vista/complementos/head.php' ?> 
  <title>Bitácora del Sistema | LoveMakeup</title>
  <!-- Asegurarnos que jQuery esté cargado -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="g-sidenav-show bg-gray-100">
  
<!-- php barra de navegacion-->
<?php include 'vista/complementos/sidebar.php' ?>

<main class="main-content position-relative border-radius-lg ">
<!-- ||| Navbar ||-->
<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur" data-scroll="false">
  <div class="container-fluid py-1 px-3">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="#">Seguridad</a></li>
        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Bitácora</li>
      </ol>
      <h6 class="font-weight-bolder text-white mb-0">Bitácora del Sistema</h6>
    </nav>
<!-- php barra de navegacion-->    
<?php include 'vista/complementos/nav.php' ?>


<div class="container-fluid py-4"> <!-- DIV CONTENIDO -->

    <div class="row"> <!-- CARD PRINCIPAL-->  
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0">  <!-- CARD N-1 -->  
    
              <!--Titulo de página -->
              <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h4 class="mb-0">
                  <i class="fas fa-history fa-sm text-primary-50"></i> Registro de Actividades
                </h4>
              </div>

              <div class="table-responsive"> <!-- comienzo div table-->
                <table class="table table-bordered table-hover display responsive nowrap" id="tablaBitacora" width="100%" cellspacing="0">
                  <thead class="table-color">
                    <tr>
                      <th class="text-white">Acción</th>
                      <th class="text-white">Fecha y Hora</th>
                      <th class="text-white">Descripción</th>
                      <th class="text-white">Usuario</th>
                      <th class="text-white">Rol</th>
                      <th class="text-white">Detalles</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php foreach ($registro as $dato) { ?>
                    <tr>
                      <td>
                        <span class="badge bg-<?php 
                          switch($dato['accion']) {
                            case 'CREAR': echo 'success'; break;
                            case 'MODIFICAR': echo 'primary'; break;
                            case 'ELIMINAR': echo 'danger'; break;
                            case 'ACCESO A MÓDULO': echo 'info'; break;
                            case 'CAMBIO_ESTADO': echo 'warning'; break;
                            default: echo 'secondary';
                          }
                        ?>">
                          <?php echo $dato['accion']?>
                        </span>
                      </td>
                      <td><?php echo date('d/m/Y H:i:s', strtotime($dato['fecha_hora']))?></td>
                      <td>
                        <?php 
                          $desc = $dato['descripcion'];
                          if (preg_match('/\[(.*?)\]$/', $desc, $matches)) {
                              echo str_replace($matches[0], '', $desc);
                              echo '<span class="fw-bold text-primary">' . $matches[0] . '</span>';
                          } else {
                              echo $desc;
                          }
                        ?>
                      </td>
                      <td><?php echo $dato['nombre']." ".$dato["apellido"]?></td>
                      <td><?php echo $dato['nombre_usuario']?></td>
                      <td class="text-center">
                        <button class="btn btn-info btn-sm" 
                                onclick="verDetalles(<?php echo $dato['id_bitacora']?>)"
                                title="Ver detalles">
                          <i class="fas fa-info-circle"></i>
                        </button>
                      </td>
                    </tr>
                  <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>  
    </div>

<!-- Modal de Detalles -->
<div class="modal fade" id="detallesModal" tabindex="-1" aria-labelledby="detallesModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class=" modal-header table-color">
        <h5 class="modal-title text-white" id="detallesModalLabel">
          <i class="fas fa-info-circle me-2"></i>Detalles del Registro
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <!-- Columna Izquierda -->
          <div class="col-md-6">
            <div class="card shadow-sm">
              <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-user me-2"></i>Información del Usuario</h6>
              </div>
              <div class="card-body">
                <div class="mb-3">
                  <label class="text-muted small">Nombre Completo:</label>
                  <p class="fw-bold mb-2" id="detalle-usuario"></p>
                </div>
                <div class="mb-3">
                  <label class="text-muted small">Rol del Usuario:</label>
                  <p class="fw-bold mb-2" id="detalle-rol"></p>
                </div>
              </div>
            </div>
          </div>
          <!-- Columna Derecha -->
          <div class="col-md-6">
            <div class="card shadow-sm">
              <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-clock me-2"></i>Información del Evento</h6>
              </div>
              <div class="card-body">
                <div class="mb-3">
                  <label class="text-muted small">Fecha y Hora:</label>
                  <p class="fw-bold mb-2" id="detalle-fecha"></p>
                </div>
                <div class="mb-3">
                  <label class="text-muted small">Tipo de Acción:</label>
                  <div id="detalle-accion"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Descripción Completa -->
        <div class="card shadow-sm mt-3">
          <div class="card-header bg-light">
            <h6 class="mb-0"><i class="fas fa-file-alt me-2"></i>Descripción Detallada</h6>
          </div>
          <div class="card-body">
            <div id="detalle-descripcion"></div>
          </div>
        </div>
      </div>
      <div class="modal-footer bg-light">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="fas fa-times me-2"></i>Cerrar
        </button>
      </div>
    </div>
  </div>
</div>

<!-- php barra de navegacion-->
<?php include 'vista/complementos/footer.php' ?>
<script src="assets/js/demo/datatables-demo.js"></script>

<!-- Script para el manejo de detalles -->
<script>
function verDetalles(id) {
    $.ajax({
        url: '?pagina=bitacora',
        type: 'POST',
        data: {detalles: id},
        dataType: 'json',
        success: function(response) {
            if(response.error) {
                Swal.fire('Error', response.error, 'error');
                return;
            }
            
            // Información del Usuario
            $('#detalle-usuario').text(response.nombre + ' ' + response.apellido);
            $('#detalle-rol').text(response.nombre_usuario);
            
            // Información del Evento
            $('#detalle-fecha').text(response.fecha_hora);
            
            // Tipo de Acción con badge
            let badgeClass = '';
            switch(response.accion) {
                case 'CREAR': badgeClass = 'bg-success'; break;
                case 'MODIFICAR': badgeClass = 'bg-primary'; break;
                case 'ELIMINAR': badgeClass = 'bg-danger'; break;
                case 'ACCESO A MÓDULO': badgeClass = 'bg-info'; break;
                case 'CAMBIO_ESTADO': badgeClass = 'bg-warning'; break;
                default: badgeClass = 'bg-secondary';
            }
            $('#detalle-accion').html(`<span class="badge ${badgeClass}">${response.accion}</span>`);
            
            // Descripción con formato
            let desc = response.descripcion;
            if (desc.match(/\[(.*?)\]$/)) {
                let partes = desc.split(/\[(.*?)\]$/);
                $('#detalle-descripcion').html(`
                    <p class="mb-2">${partes[0]}</p>
                    <span class="badge bg-primary">[${partes[1]}]</span>
                `);
            } else {
                $('#detalle-descripcion').text(desc);
            }
            
            $('#detallesModal').modal('show');
        },
        error: function() {
            Swal.fire('Error', 'No se pudieron cargar los detalles', 'error');
        }
    });
}
</script>

</body>
</html>
