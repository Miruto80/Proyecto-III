<!DOCTYPE html>
<html lang="en">

<head>
<!-- php CSS, Meta y titulo--> 
<?php include 'vista/complementos/head_catalogo.php' ?>


</head>

<body>


  <style>

.tableh:hover{
  scale: 1.02;
  cursor: pointer;
  transition: ease 0.2s;
}

/* Asegura que el thead pueda redondearse */
thead.thead-rounded th:first-child {
  border-top-left-radius: 10px;
}

thead.thead-rounded th:last-child {
  border-top-right-radius: 10px;
}

/* Opcional: quitar bordes de colapso que impidan ver el efecto */
table {
  border-collapse: separate;
  border-spacing: 0;
  overflow: hidden;
}




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
         <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb" class="custom-breadcrumb mt-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="?pagina=catalogo">Inicio</a></li>
            <li class="breadcrumb-item" aria-current="page">Ver</li>
             <li class="breadcrumb-item active" aria-current="page">Mis Pedidos</li>
        </ol>
      </nav>
      <br>
      <div class="row">
        <div class="section-header d-flex align-items-center justify-content-between mb-lg-2">
          <h2 class="section-title">Mis Pedidos</h2>
        </div>
      </div>
      <div class="table-responsive text-center"> <!-- comienzo div table-->
           <!-- comienzo de tabla-->                      
          <table class="table  table-hover tablefix" id="myTable" width="100%" cellspacing="0">
              <thead class="table-color thead-rounded" style="border-top: none;">
                <tr class="">
                  
                  <th class="text-white ">Tipo</th>
                  <th class="text-white">Fecha</th>
                  <th class="text-white">Estado</th>
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
                  $botonesDeshabilitados = "disabled";
              } else {
                  $claseFila = "";
                  $botonesDeshabilitados = "enabled";
              }
          
              $estatus_texto = array(
                '0' => 'Anulado',
             '1' => 'Verificar pago',
             '2' => 'Entregado',
             '3' => 'Pendiente envío',
             '4' => 'En camino',
             '5' => 'Enviado',
           
               );

               $tipo_texto = array(
          
             '1' => 'Tienda',
             '2' => 'Web',
             '3' => 'Reserva',
           
               );
          
             
    $badgeClass = '';
    switch (strtolower($pedido['estado'])) {
      case '0': $badgeClass = 'bg-danger'; break;
      case '1': $badgeClass = 'bg-warning'; break;
      case '2': $badgeClass = 'bg-primary'; break;
      case '3': $badgeClass = 'bg-success'; break;
      case '4': $badgeClass = 'bg-info'; break;
      default:  $badgeClass = 'bg-secondary';
    }

    
          
            
          ?>
              <tr class="tableh" style="text-align:center;" data-bs-toggle="modal" 
              data-bs-target="#verDetallesModal<?= $pedido['id_pedido']; ?>">
              <td class=""><?php echo $tipo_texto[$pedido['tipo']] ?></td>
              <td><?= $pedido['fecha'] ?></td>
              <td class=" m-3 text-white badge <?php echo $badgeClass; ?>"><?php echo $estatus_texto[$pedido['estado']] ?></td>
              <td><?= $pedido['telefono_emisor'] ?></td>
              <td><?= $pedido['metodo_entrega'] ?></td>
              <td><?= $pedido['metodo_pago'] ?></td>
              <td>
              <button class="btn btn-info " data-bs-toggle="modal" 
    data-bs-target="#verDetallesModal<?= $pedido['id_pedido']; ?>">
 <i class="fa fa-eye"></i> </button>
      </td>
    </tr>
  <?php endforeach; ?>
</tbody>
          </table>
        </div>

      </div>
    </div>
  </section>

  <?php if (isset($pedidos) && !empty($pedidos)): ?>
  <?php foreach ($pedidos as $pedido): ?>
    <div class="modal fade" id="verDetallesModal<?php echo $pedido['id_pedido']; ?>" tabindex="-1" aria-labelledby="verDetallesModalLabel<?php echo $pedido['id_pedido']; ?>" aria-hidden="true">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header header-color">
            <h5 class="modal-title text-white" id="verDetallesModalLabel<?php echo $pedido['id_pedido']; ?>">Detalles del Pedido</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body">

            <!-- Fecha -->
            <div class="row mb-3">
              <div class="col-12">
                <div class="card">
                  <div class="card-header bg-light" data-bs-toggle="collapse" data-bs-target="#collapseFecha<?php echo $pedido['id_pedido']; ?>" style="cursor:pointer;">
                    <h6 class="mb-0">
                      <i class="fas fa-calendar-alt text-secondary"></i> Fecha y Hora
                      <i class="fas fa-chevron-down float-end"></i>
                    </h6>
                  </div>
                  <div class="collapse" id="collapseFecha<?php echo $pedido['id_pedido']; ?>">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-6">
                          <strong>Fecha:</strong> <?php echo date('d/m/Y', strtotime($pedido['fecha'])); ?>
                        </div>
                        <div class="col-md-6">
                          <strong>Hora:</strong> <?php echo date('H:i:s', strtotime($pedido['fecha'])); ?>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Cliente -->
            <div class="row mb-3">
              <div class="col-12">
                <div class="card">
                  <div class="card-header bg-light" data-bs-toggle="collapse" data-bs-target="#collapseCliente<?php echo $pedido['id_pedido']; ?>" style="cursor:pointer;">
                    <h6 class="mb-0">
                      <i class="fas fa-user text-primary"></i> Información del Cliente
                      <i class="fas fa-chevron-down float-end"></i>
                    </h6>
                  </div>
                  <div class="collapse" id="collapseCliente<?php echo $pedido['id_pedido']; ?>">
                    <div class="card-body">
                      <p><strong>Nombre:</strong> <?php echo htmlspecialchars($pedido['nombre']); ?> <?php echo htmlspecialchars($pedido['apellido']); ?></p>
                      
                      <p><strong>Estado del pedido:</strong>
                        <span class="badge 
                          <?php
                            $badge = [
                              '0' => 'bg-danger', '1' => 'bg-warning', '2' => 'bg-primary',
                              '3' => 'bg-success', '4' => 'bg-info', '5' => 'bg-secondary'
                            ];
                            echo $badge[$pedido['estado']] ?? 'bg-dark';
                          ?>">
                          <?php
                            $estados_texto = [
                              '0' => 'Rechazado',
                              '1' => 'Verificar pago',
                              '2' => 'Pago Verificado',
                              '3' => 'Pendiente envio',
                              '4' => 'En camino',
                              '5' => 'Entregado',
                            ];
                            echo htmlspecialchars($estados_texto[$pedido['estado']] ?? 'Desconocido');
                          ?>
                        </span>
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Información de Pago y Entrega -->
            <div class="row mb-3">
              <div class="col-12">
                <div class="card">
                  <div class="card-header bg-light" data-bs-toggle="collapse" data-bs-target="#collapsePagoEntrega<?php echo $pedido['id_pedido']; ?>" style="cursor:pointer;">
                    <h6 class="mb-0">
                      <i class="fas fa-credit-card text-success"></i> Pago y Entrega
                      <i class="fas fa-chevron-down float-end"></i>
                    </h6>
                  </div>
                  <div class="collapse" id="collapsePagoEntrega<?php echo $pedido['id_pedido']; ?>">
                    <div class="card-body">
                      <p><strong>Método de Pago:</strong> <?php echo htmlspecialchars($pedido['metodo_pago'] ?? 'N/A'); ?></p>
                      <?php if (!empty($pedido['banco']) || !empty($pedido['banco_destino'])): ?>
                        <p><strong>Banco Emisor:</strong> <?php echo htmlspecialchars($pedido['banco'] ?? 'N/A'); ?></p>
                        <p><strong>Banco Receptor:</strong> <?php echo htmlspecialchars($pedido['banco_destino'] ?? 'N/A'); ?></p>
                      <?php endif; ?>
                      <?php if (!empty($pedido['referencia_bancaria'])): ?>
                        <p><strong>Referencia Bancaria:</strong> <?php echo htmlspecialchars($pedido['referencia_bancaria']); ?></p>
                      <?php endif; ?>

                      <?php if (!empty($pedido['imagen'])): ?>
  <p><strong>Comprobante de Pago:</strong></p>
  <img src="<?php echo htmlspecialchars($pedido['imagen']); ?>" alt="Comprobante de Pago" class="img-fluid rounded border" style="max-width: 300px;">
<?php endif; ?>

                      <p><strong>Método de Entrega:</strong> <?php echo htmlspecialchars($pedido['metodo_entrega'] ?? 'N/A'); ?></p>
                      <?php if (!empty($pedido['direccion'])): ?>
                        <p><strong>Dirección:</strong><br><?php echo nl2br(htmlspecialchars($pedido['direccion'])); ?></p>
                      <?php endif; ?>
                      <p><strong>Total Bs:</strong> <?php echo number_format($pedido['precio_total_bs'], 2); ?></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Productos -->
            <div class="row mb-3">
              <div class="col-12">
                <div class="card">
                  <div class="card-header bg-light" data-bs-toggle="collapse" data-bs-target="#collapseProductos<?php echo $pedido['id_pedido']; ?>" style="cursor:pointer;">
                    <h6 class="mb-0">
                      <i class="fas fa-box-open text-dark"></i> Detalles de la Venta
                      <i class="fas fa-chevron-down float-end"></i>
                    </h6>
                  </div>
                  <div class="collapse" id="collapseProductos<?php echo $pedido['id_pedido']; ?>">
                    <div class="card-body table-responsive">
                      <table class="table table-bordered table-striped">
                        <thead class="table-color">
                          <tr>
                            <th class="text-white">#</th>
                            <th class="text-center text-white">Producto</th>
                            <th class="text-center text-white">Cantidad</th>
                            <th class="text-center text-white">Precio Unitario</th>
                            <th class="text-center text-white">Subtotal</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php 
                            $total = 0;
                            $i = 1;
                            foreach ($pedido['detalles'] as $detalle):
                              $subtotal = $detalle['cantidad'] * $detalle['precio_unitario'];
                              $total += $subtotal;
                          ?>
                          <tr>
                            <td class="text-center"><?php echo $i++; ?></td>
                            <td><?php echo htmlspecialchars($detalle['nombre']); ?></td>
                            <td class="text-center"><?php echo $detalle['cantidad']; ?></td>
                            <td class="text-center">$<?php echo number_format($detalle['precio_unitario'], 2); ?></td>
                            <td class="text-center">$<?php echo number_format($subtotal, 2); ?></td>
                          </tr>
                          <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                          <tr>
                            <th colspan="4" class="text-end">Total USD:</th>
                            <th class="text-center">$<?php echo number_format($total, 2); ?></th>
                          </tr>
                        </tfoot>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div> <!-- /.modal-body -->
        </div>
      </div>
    </div>
  <?php endforeach; ?>
<?php endif; ?>


<!-- CSS Files -->
<link id="pagestyle" href="assets/css/argon-dashboard-pedidos.css?v=2.1.0" rel="stylesheet" />
<link id="pagestyle" href="assets/css/sidebar.css" rel="stylesheet" />
<link href="assets/css/datatables.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/driver.js@latest/dist/driver.css"/>

<!-- php Publicidad Insta, Publicidad calidad, footer y JS--> 
<?php include 'vista/complementos/footer_catalogo.php' ?>
<script src="assets/js/demo/datatables-demo.js"></script>


<!-- JS LIBRERIA -->


<script src="assets/js/libreria/datatables.min.js"></script>






</body>

</html>