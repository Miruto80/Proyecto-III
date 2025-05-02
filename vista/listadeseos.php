<!DOCTYPE html>
<html lang="es">
<head>
  <?php include 'complementos/head.php'; ?> 
  <title> Lista de Deseos | LoveMakeup </title> 
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
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#agregarProducto">
                      <span class="icon text-white">
                        <i class="fas fa-heart"></i>
                      </span>
                      <span class="text-white">Añadir al Carrito</span>
                    </button>
                  </div>
                  <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="wishlistTable" width="100%" cellspacing="0">
                      <thead class="table-color">
                        <tr>
                          <th class="text-white">ID</th>
                          <th class="text-white">NOMBRE DEL PRODUCTO</th>
                          <th class="text-white">PRECIO</th>
                          <th class="text-white">ACCIONES</th>
                        </tr>
                      </thead>
                      <tbody id="wishlistTableBody">
                        <?php foreach ($producto as $producto): ?>
                        <tr>
                          <td><?php echo $producto['id_producto']; ?></td>
                          <td><?php echo $producto['nombre']; ?></td>
                          <td><?php echo "$" . number_format($producto['precio'], 2); ?></td>
                          <td>
                            <button type="button" class="btn btn-primary btn-sm" 
                                    onclick="añadirAlCarrito(<?php echo $producto['id_producto']; ?>)">
                              <i class="fas fa-cart-plus" title="Añadir al carrito"></i> 
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" 
                                    onclick="eliminarDeWishlist(<?php echo $producto['id_producto']; ?>)">
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

        <!-- Modal para agregar producto al carrito -->
        <div class="modal fade" id="agregarProducto" tabindex="-1" aria-labelledby="agregarProductoLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header header-color">
                <h1 class="modal-title fs-5" id="agregarProductoLabel">Añadir Producto al Carrito</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form id="formAgregarProducto" autocomplete="off">
                  <label>Producto</label>
                  <input type="text" class="form-control" name="nombre" id="nombre_producto" placeholder="Ejemplo: Paleta de Sombras" required> <br>
                  <label>Precio</label>
                  <input type="number" class="form-control" name="precio" id="precio_producto" placeholder="Ejemplo: 50.00" required> <br>
                  <div class="text-center">
                    <button type="button" class="btn btn-primary" id="añadirAlCarritoBtn">Añadir al Carrito</button>
                    <button type="reset" class="btn btn-primary">Limpiar</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>

        <?php include 'complementos/footer.php'; ?>
        <script src="assets/js/listadeseos.js"></script>
      </body>
    </html>
