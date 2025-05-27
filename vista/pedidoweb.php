<!DOCTYPE html>
<html lang="es">

<head>
  <!-- php barra de navegacion-->
  <?php include 'complementos/head.php' ?> 
  
  <title> Pedido Web | LoveMakeup  </title> 
</head>

<body class="g-sidenav-show bg-gray-100">

<style>
  .pedido-confirmado {
    background-color: #75d1a6ff;
    color: #fff;
}

.pedido-pendiente {
    background-color: #c76b76ff;
    color: #fff;
}
</style>
  
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
       <h4 class="mb-0"><i class="fa-solid fa-desktop mr-2" style="color: #f6c5b4;"></i>
        Pedido Web</h5>
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#registro">
        <i class="fa-solid fa-dollar-sign"></i> Registrar Tasa
                  </button>
      </div>
          
      </div>
          

      <div class="table-responsive"> <!-- comienzo div table-->
           <!-- comienzo de tabla-->                      
          <table class="table table-bordered table-hover" id="myTable" width="100%" cellspacing="0">
              <thead class="table-color">
                <tr>
                  <th class="text-white" style="display: none;">ID</th>
                  <th class="text-white" style="display: none;">Tipo</th>
                  <th class="text-white">Fecha</th>
                  <th class="text-white"style="display: none;">Estado</th>
                  <th class="text-white">Total</th>
                  <th class="text-white">Referencia</th>
                  <th class="text-white">usuario</th>
                 <th class="text-white">Teléfono</th>
                  <th class="text-white">Método Entrega</th>
                 <th class="text-white">Método Pago</th>
                 <th class="text-white">Acción</th>
                </tr>
              </thead>
              <tbody>
    
              <?php foreach ($pedidos as $pedido): 
    // Define clases y deshabilitar botones si estado es 0 o 2
    if ($pedido['estado'] == 2) {
        $claseFila = "pedido-confirmado";
        $botonesDeshabilitados = "disabled";
    } elseif ($pedido['estado'] == 0) {
        $claseFila = "pedido-pendiente";
        $botonesDeshabilitados = "disabled";
    } else {
        $claseFila = "";
        $botonesDeshabilitados = "";
    }
?>
<tr class="<?= $claseFila ?>" style="text-align: center;">
    <td style="display: none;"><?= $pedido['id_pedido'] ?></td>
    <td style="display: none;"><?= $pedido['tipo'] ?></td>
    <td><?= $pedido['fecha'] ?></td>
    <td style="display: none;"><?= $pedido['estado'] ?></td>
    <td><?= $pedido['precio_total'] ?>$</td>
    <td><?= $pedido['referencia_bancaria'] ?></td>
    <td><?= $_SESSION['nombre'] ?></td>
    <td><?= $pedido['telefono_emisor'] ?></td>
    <td><?= $pedido['metodo_entrega'] ?></td>
    <td><?= $pedido['metodo_pago'] ?></td>
    <td>
        <button
            class="btn btn-primary ver-detalles"
            data-detalles='<?= json_encode($pedido["detalles"]) ?>'
            title="Ver productos del pedido"
            <?= $botonesDeshabilitados ?>>
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
     <!-- modal tasa -->

     <div class="modal fade" id="registro" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header header-color">
                <h1 class="modal-title fs-5" id="1">Registrar Tasa del Dia</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form id="formRegistrar" autocomplete="off">
                  <label>Cantidad</label>
                  <input type="text" class="form-control" name="valor" id="valor" placeholder="Ejemplo: 94,32" required> <br>
                  <div class="text-center">
                    <button type="button" class="btn btn-primary" id="registrar">Registrar</button>
                    <button type="reset" class="btn btn-primary">Limpiar</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>






<!-- php barra de navegacion-->
<?php include 'complementos/footer.php' ?>
<script src="assets/js/tasa.js"></script>
<script src="assets/js/pedidoweb.js"></script>

</body>

</html>