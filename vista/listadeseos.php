<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'complementos/head.php'; ?> 
  <title>Lista de Deseos | LoveMakeup</title> 
</head>

<body class="g-sidenav-show bg-gray-100">

  <?php include 'complementos/sidebar.php'; ?>
  <main class="main-content position-relative border-radius-lg ">

    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur" data-scroll="false">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="#">Administrar</a></li>
            <li class="breadcrumb-item text-sm text-white active" aria-current="page">Lista de Deseos</li>
          </ol>
          <h6 class="font-weight-bolder text-white mb-0">Gestionar Lista de Deseos</h6>
        </nav>

        <?php include 'complementos/nav.php'; ?>

        <div class="container-fluid py-4">
          <div class="row">  
            <div class="col-12">
              <div class="card mb-4">
                <div class="card-header pb-0">  
                  <div class="d-sm-flex align-items-center justify-content-between mb-5">
                    <h4 class="mb-0"><i class="fa-solid fa-heart mr-2" style="color: #f6c5b4;"></i> Lista de Deseos</h4>
                  </div>
                  
                  <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="myTable" width="100%" cellspacing="0">
                      <thead class="table-color">
                        <tr>
                          <th class="text-white">Producto</th>
                          <th class="text-white">NOMBRE</th>
                          <th class="text-white">DETALLE</th>
                          <th class="text-white">ACCIONES</th>
                        </tr>
                      </thead>
                      <tbody id="listaDeseosTableBody">
                        <?php if (isset($registro) && is_array($registro) && count($registro) > 0): ?>
                            <?php foreach ($registro as $dato): ?>
                            <tr>
                              <td><?php echo $dato['id_lista']; ?></td>
                              <td><?php echo $dato['nombre']; ?></td>
                              <td><?php echo $dato['detalle']; ?></td>
                              <td>
                                <!-- Botón Añadir a Pedido -->
                                <button type="button" class="btn btn-warning btn-sm btnAñadirPedido" 
                                        data-id-lista="<?php echo $dato['id_lista']; ?>" 
                                        data-id-persona="<?php echo $dato['id_persona']; ?>">
                                  <i class="fas fa-shopping-cart" title="Añadir a Pedidos"> </i> 
                                </button>
                                
                                <!-- Botón Eliminar de la lista de deseos -->
                                <button type="button" class="btn btn-danger btn-sm btnEliminarListaDeseo" 
                                        data-id-lista="<?php echo $dato['id_lista']; ?>">
                                  <i class="fas fa-trash-alt" title="Eliminar"> </i>
                                </button>
                              </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center">No hay elementos en la lista de deseos</td>
                            </tr>
                        <?php endif; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <?php include 'complementos/footer.php'; ?>

        <script src="assets/js/listadeseos.js"></script>
      </body>
    </html>
