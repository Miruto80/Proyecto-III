<!DOCTYPE html>
<html lang="es">

<head>
  <!-- php barra de navegacion-->
  <?php include 'complementos/head.php' ?> 
  
  <title> Pedido Web | LoveMakeup  </title> 
</head>


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

<body class="g-sidenav-show bg-gray-100">


  
<!-- php barra de navegacion-->
<?php include 'complementos/sidebar.php' ?>

<main class="main-content position-relative border-radius-lg ">
<!-- ||| Navbar ||-->
<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur" data-scroll="false">
  <div class="container-fluid py-1 px-3">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="#">Web</a></li>
        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Pedido web</li>
      </ol>
      <h6 class="font-weight-bolder text-white mb-0">Gestionar Pedido Web</h6>
    </nav>
<!-- php barra de navegacion-->    
<?php include 'complementos/nav.php' ?>


<div class="container-fluid py-4"> <!-- DIV CONTENIDO -->

    <div class="row"> <!-- CARD PRINCIPAL-->  
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0">  <!-- CARD N-1 -->  
    
    <!--Titulo de página -->
     <div class="d-sm-flex align-items-center justify-content-between mb-5">
     <div class="d-flex gap-2">
     <h4 class="mb-0"><i class="fa-solid fa-desktop mr-2" style="color: #f6c5b4;"></i>
        Pedido Web</h5>
      </div>
 

 <div class="d-flex gap-2">

          <button type="button" class="btn btn-primary" id="btnayuda">
    <span class="icon text-white">
      <i class="fas fa-info-circle"></i>
    </span>
    <span class="text-white">Ayuda</span>
  </button>

  
</div>
      </div>
 

          
          
      </div>
    

      <div class="table-responsive m-3"> <!-- comienzo div table-->
           <!-- comienzo de tabla-->                      
          <table class="table table-bordered table-hover" id="myTable" width="100%" cellspacing="0">
              <thead  class="table-color">
                <tr>
                  <th class="text-white" style="display: none;">ID</th>
                  <th class="text-white" style="display: none;">Tipo</th>
                  <th class="text-white">Fecha</th>
                  <th class="text-white"style="">Estado</th>
                  <th class="text-white">Total</th>
                  <th class="text-white">Referencia</th>
                  <th class="text-white">usuario</th>
                 <th class="text-white">Teléfono</th>
                  <th class="text-white">Método Entrega</th>
                 <th class="text-white">Método Pago</th>
                 <th class="text-white">Acción</th>
                </tr>
              </thead>
              <tbody id="pedidowebTableBody">
    
              <?php foreach ($pedidos as $pedido): 
    // Define clases y deshabilitar botones si estado es 0 o 2
    if ($pedido['estado'] == 2) {
      $claseFila = "pedido-confirmado";
        $botonesDeshabilitados = "disabled";
    } elseif ($pedido['estado'] == 0) {
        $botonesDeshabilitados = "disabled";
    } else {
        $claseFila = "";
        $botonesDeshabilitados = "enabled";
    }

    $estatus_texto = array(
      0 => "Rechazado",
      1 => "Pendiente",
      2 => "Validado",

    );

    $badgeClass = '';
    switch (strtolower($pedido['estado'])) {
        case '2':
            $badgeClass = 'bg-primary';
            break;
        case '1':
            $badgeClass = 'bg-warning';
            break;
        case '0':
            $badgeClass = 'bg-danger';
            break;
        default:
            $badgeClass = 'bg-secondary';
    }

  
?>
    <tr style="text-align:center;">
    <td style="display: none;"><?= $pedido['id_pedido'] ?></td>
    <td style="display: none;"><?= $pedido['tipo'] ?></td>
    <td><?= $pedido['fecha'] ?></td>
    <td class=" m-3 text-white badge <?php echo $badgeClass; ?>"><?php echo $estatus_texto[$pedido['estado']] ?></td>
    <td><?= $pedido['precio_total'] ?>$</td>
    <td><?= $pedido['referencia_bancaria'] ?></td>
    <td><?= $pedido['nombre'] ?></td>
    <td><?= $pedido['telefono_emisor'] ?></td>
    <td><?= $pedido['metodo_entrega'] ?></td>
    <td><?= $pedido['metodo_pago'] ?></td>
    <td>
        <button
            class="btn btn-primary ver-detalles"
            data-detalles='<?= json_encode($pedido["detalles"]) ?>'
            title="Ver productos del pedido"
            >
            <i class="fa fa-eye"></i> 
        </button>

        <button
            class="btn btn-success btn-validar"
            data-id="<?= $pedido['id_pedido'] ?>"
            title="Validar pedido"
            <?= $botonesDeshabilitados ?>>
            <i class="fa-solid fa-check"></i>
        </button>

        <button
            class="btn btn-danger btn-eliminar"
            data-id="<?= $pedido['id_pedido'] ?>"
            title="Eliminar pedido"
            <?= $botonesDeshabilitados ?>>
            <i class="fas fa-trash-alt"></i>
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

<div class="modal fade" id="modalDetallesProducto" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalLabel">Productos del Pedido</h5>
        <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Nombre</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
              </tr>
            </thead>
            <tbody id="tbody-detalles-producto">
              <!-- Se llena desde JS -->
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
    


<!-- php barra de navegacion-->
<?php include 'complementos/footer.php' ?>
<script src="assets/js/pedidoweb.js"></script>
<script src="assets/js/demo/datatables-demo.js"></script>
</body>

</html>