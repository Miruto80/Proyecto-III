<!DOCTYPE html>
<html lang="en">

<head>
<!-- php CSS, Meta y titulo--> 
<?php include 'vista/complementos/head_catalogo.php' ?>

</head>

<body>


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


<!-- |||||||||||||||| LOADER ||||||||||||||||||||-->
  <div class="preloader-wrapper">
    <div class="preloader">
    </div>
  </div>
<!-- |||||||||||||||| LOADER ||||||||||||||||||||-->

<!-- php CARRITO--> 
<?php include 'vista/complementos/carrito.php' ?>

<!-- php ENCABEZADO LOGO, ICONO CARRITO Y LOGIN--> 
<?php include 'vista/complementos/nav_catalogo.php' ?>

<section id="latest-blog" class="section-padding pt-0">
    <div class="container-lg">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="?pagina=catalogo">Inicio</a></li>
            <li class="breadcrumb-item" aria-current="page">Ver</li>
             <li class="breadcrumb-item active" aria-current="page">Mis Pedidos</li>
        </ol>
      </nav>
      <div class="row">
        <div class="section-header d-flex align-items-center justify-content-between mb-lg-2">
          <h2 class="section-title">Pedidos</h2>
        </div>
      </div>
      <div class="table-responsive text-center"> <!-- comienzo div table-->
           <!-- comienzo de tabla-->                      
          <table class="table table-bordered table-hover" id="myTable" width="100%" cellspacing="0">
              <thead class="table-color">
                <tr>
                  <th class="" style="display: none;">ID</th>
                  <th class="" style="display: none;">Tipo</th>
                  <th class="">Fecha</th>
                  <th class=""style="">Estado</th>
                  <th class="">Total</th>
                  <th class="">Referencia</th>
                  <th class="">Banco Destino</th>
                  <th class="" style="display:none;">usuario</th>
                 <th class="">Teléfono</th>
                  <th class="">Método Entrega</th>
                 <th class="">Método Pago</th>
                 <th class="">Acción</th>
                </tr>
              </thead>
              <tbody>
               
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
              <td><?= $pedido['banco_destino'] ?></td>
              <td style="display: none;"><?= $_SESSION['nombre'] ?></td>
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
      </td>
    </tr>
  <?php endforeach; ?>
</tbody>
          </table>
        </div>

      </div>
    </div>
  </section>
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



<!-- php Publicidad Insta, Publicidad calidad, footer y JS--> 
<?php include 'vista/complementos/footer_catalogo.php' ?>
<script src="assets/js/catalogo_pedido.js"></script>
  
</body>

</html>