
<!DOCTYPE html>
<html lang="en">

<head>
<!-- php CSS, Meta y titulo--> 
<?php include 'vista/complementos/head_catalogo.php' ?>

</head>

<body>
<?php
$carrito = $_SESSION['carrito'] ?? [];
$total = 0;
?>

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
            <li class="breadcrumb-item"><a href="?pagina=catalogo_producto">Todos los productos</a></li>
             <li class="breadcrumb-item active" aria-current="page">Carrito</li>
        </ol>
      </nav>

    <div class="mb-4">

    <style>
         .cart-step {
      display: flex;
      align-items: center;
      margin-bottom: 30px;
    }
    .cart-step div {
      font-weight: bold;
      margin-right: 15px;
    }
    .cart-step .current-step {
      color: black;
    }
    .cart-step .step-number {
      width: 30px;
      height: 30px;
      background-color: #000;
      color: white;
      border-radius: 50%;
      text-align: center;
      line-height: 30px;
      margin-right: 10px;
    }
    </style>
        <!-- Step Indicator -->
        <div class="cart-step">
    <div class="step-number">1</div>
    <div class="current-step"> Carrito de Compras</div>
    <div>→</div>
    <div><a href="?pagina=verpedidoweb"> 2  Detalles de Compra</a></div>
    <div>→</div>
    <div>3 Pedido Completo</div>
  </div>
    </div>

    <?php if (empty($carrito)): ?>
      
        <div class="container" id="Vcard" style=" border: 1px solid #ccc;border-top-left-radius:5px;border-top-right-radius:5px; padding:10px; display:flex;flex-direction: column;
 justify-content:center;align-items:center;">
        <svg class="codevz-cart-empty-svg" style="width: 70px;" fill="#676767" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 231.523 231.523" xml:space="preserve">
        <g>
          <path d="M107.415,145.798c0.399,3.858,3.656,6.73,7.451,6.73c0.258,0,0.518-0.013,0.78-0.04c4.12-0.426,7.115-4.111,6.689-8.231
            l-3.459-33.468c-0.426-4.12-4.113-7.111-8.231-6.689c-4.12,0.426-7.115,4.111-6.689,8.231L107.415,145.798z"></path>
          <path d="M154.351,152.488c0.262,0.027,0.522,0.04,0.78,0.04c3.796,0,7.052-2.872,7.451-6.73l3.458-33.468
            c0.426-4.121-2.569-7.806-6.689-8.231c-4.123-0.421-7.806,2.57-8.232,6.689l-3.458,33.468
            C147.235,148.377,150.23,152.062,154.351,152.488z"></path>
          <path d="M96.278,185.088c-12.801,0-23.215,10.414-23.215,23.215c0,12.804,10.414,23.221,23.215,23.221
            c12.801,0,23.216-10.417,23.216-23.221C119.494,195.502,109.079,185.088,96.278,185.088z M96.278,216.523
            c-4.53,0-8.215-3.688-8.215-8.221c0-4.53,3.685-8.215,8.215-8.215c4.53,0,8.216,3.685,8.216,8.215
            C104.494,212.835,100.808,216.523,96.278,216.523z"></path>
          <path d="M173.719,185.088c-12.801,0-23.216,10.414-23.216,23.215c0,12.804,10.414,23.221,23.216,23.221
            c12.802,0,23.218-10.417,23.218-23.221C196.937,195.502,186.521,185.088,173.719,185.088z M173.719,216.523
            c-4.53,0-8.216-3.688-8.216-8.221c0-4.53,3.686-8.215,8.216-8.215c4.531,0,8.218,3.685,8.218,8.215
            C181.937,212.835,178.251,216.523,173.719,216.523z"></path>
          <path d="M218.58,79.08c-1.42-1.837-3.611-2.913-5.933-2.913H63.152l-6.278-24.141c-0.86-3.305-3.844-5.612-7.259-5.612H18.876
            c-4.142,0-7.5,3.358-7.5,7.5s3.358,7.5,7.5,7.5h24.94l6.227,23.946c0.031,0.134,0.066,0.267,0.104,0.398l23.157,89.046
            c0.86,3.305,3.844,5.612,7.259,5.612h108.874c3.415,0,6.399-2.307,7.259-5.612l23.21-89.25C220.49,83.309,220,80.918,218.58,79.08z
             M183.638,165.418H86.362l-19.309-74.25h135.895L183.638,165.418z"></path>
          <path d="M105.556,52.851c1.464,1.463,3.383,2.195,5.302,2.195c1.92,0,3.84-0.733,5.305-2.198c2.928-2.93,2.927-7.679-0.003-10.607
            L92.573,18.665c-2.93-2.928-7.678-2.927-10.607,0.002c-2.928,2.93-2.927,7.679,0.002,10.607L105.556,52.851z"></path>
          <path d="M159.174,55.045c1.92,0,3.841-0.733,5.306-2.199l23.552-23.573c2.928-2.93,2.925-7.679-0.005-10.606
            c-2.93-2.928-7.679-2.925-10.606,0.005l-23.552,23.573c-2.928,2.93-2.925,7.679,0.005,10.607
            C155.338,54.314,157.256,55.045,159.174,55.045z"></path>
          <path d="M135.006,48.311c0.001,0,0.001,0,0.002,0c4.141,0,7.499-3.357,7.5-7.498l0.008-33.311c0.001-4.142-3.356-7.501-7.498-7.502
            c-0.001,0-0.001,0-0.001,0c-4.142,0-7.5,3.357-7.501,7.498l-0.008,33.311C127.507,44.951,130.864,48.31,135.006,48.311z"></path>
        </g></svg>
                 <p style="font-weight: bold;
">¡Parece que tu carrito está vacío!</p>
<p>Es hora de empezar a comprar</p>
            </div>

    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th></th>
                        <th>Imagen</th>
                        <th>Producto</th>
                        <th>Precio Unitario</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                      
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($carrito as $item):
                        $id = $item['id'];
                        $cantidad = $item['cantidad'];
                        $precioUnitario = $cantidad >= $item['cantidad_mayor'] ? $item['precio_mayor'] : $item['precio_detal'];
                        $subtotal = $cantidad * $precioUnitario;
                        
                    ?>
                    <tr data-id="<?= $id ?>">
                    <td>
                    <button class="btn-eliminar" data-id="<?php echo $id; ?>"><i class="fa-solid fa-x"></i></button>
                            </td>
                            <td><img src="<?= htmlspecialchars($item['imagen']) ?>" class="img-thumbnail" width="60"></td>
                            <td><strong><?= htmlspecialchars($item['nombre']) ?></strong></td>
                            <td class="precio-unitario">$<?= number_format($precioUnitario, 2) ?></td>
                            <td class="quantity-control">
                                <div class="d-flex align-items-center">
                                    <button class="btn btn-outline-secondary btn-sm btn-menos" data-id="<?= $id ?>">−</button>
                                    <span class="mx-2 cantidad"><?= $cantidad ?></span>
                                    <button class="btn btn-outline-secondary btn-sm btn-mas" data-id="<?= $id ?>" data-stock="<?= $item['stockDisponible'] ?>">+</button>
                                </div>
                            </td>
                            <td class="subtotal">$<?= number_format($subtotal, 2) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

        </div>

        <div class="mt-3 text-end">
            <h4>Total: $<span id="total-carrito" class="total-general"><?= number_format($total, 2) ?></span></h4>
        </div>
    <?php endif; ?>
</div>
 </section>
<!-- php Publicidad Insta, Publicidad calidad, footer y JS--> 
<?php include 'vista/complementos/footer_catalogo.php' ?>
   <script src="assets/js/vercarrito.js"></script>
</body>

</html>
