<!DOCTYPE html>
<html lang="es">

<head>
  <!-- php barra de navegacion-->
  <?php include 'complementos/head.php' ?>
  <title> Inicio | LoveMakeup  </title> 
</head>

<body class="g-sidenav-show bg-gray-100">
  
<!-- php barra de navegacion-->
<?php include 'complementos/sidebar.php' ?>

<main class="main-content position-relative border-radius-lg ">
<!-- ||| Navbar ||-->
<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur" data-scroll="false">
  <div class="container-fluid py-1 px-3">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="#">Bienvenid@</a></li>
        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Inicio</li>
      </ol>
      <h6 class="font-weight-bolder text-white mb-0">Dashboard</h6>
    </nav>
<!-- php barra de navegacion-->    
<?php include 'complementos/nav.php' ?>

<!-- |||||||||||||||| LOADER ||||||||||||||||||||-->
  <div class="preloader-wrapper">
    <div class="preloader">
    </div>
  </div> 
<!-- |||||||||||||||| LOADER ||||||||||||||||||||-->

<style>
    .cardhome:hover {
    transform: scale(1.03);
    box-shadow: 0 0 15px rgba(255, 255, 255, 0.3);
    transition: all 0.3s ease-in-out;
}

.cardhome .icon-sha {
    transition: transform 0.3s ease;
}

.cardhome:hover .icon-sha {
    transform: rotate(5deg) scale(1.1);
}

</style>

<div class="container-fluid py-4"> <!-- DIV CONTENIDO-->

    <div class="row"> <!-- CARD PRINCIPAL-->  
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0">  <!-- CARD N-1 -->  

          <div class="row">
  <!-- Ventas Totales -->
  <div class="col-lg-3 col-md-6 col-12 mt-4 mt-md-0">
    <div class="card cardhome text-center p-3 d-flex align-items-center justify-content-center position-relative" style="min-height: 160px; background-color: #ED73B1;">
      <div class="position-relative w-100">
        <div class="d-flex align-items-center justify-content-center mb-2">
          <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background-color: #e6f0ff;">
            <i class="fa-solid fa-comments-dollar"></i>
          </div>
        </div>
        <h3 class="fw-bold mb-0 text-white">
          $ <?php echo number_format($totales['total_ventas'], 2); ?>
        </h3>
        <span class="text-white">Ventas Totales</span>
      </div>
    </div>
  </div>

  <!-- Venta por Web -->
  <div class="col-lg-3 col-md-6 col-12 mt-4 mt-md-0">
    <div class="card cardhome text-center p-3 d-flex align-items-center justify-content-center position-relative" style="min-height: 160px; background-color: #D67888">
      <div class="position-relative w-100">
        <div class="d-flex align-items-center justify-content-center mb-2">
          <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background-color: #e6f0ff;">
            <i class="fa-solid fa-circle-dollar-to-slot"></i>
          </div>
        </div>
        <h3 class="fw-bold mb-0 text-white">
          $ <?php echo number_format($totales['total_web'], 2); ?>
        </h3>
        <span class="text-white">Venta por Web</span>
      </div>
    </div>
  </div>

  <!-- Pedidos Web -->
  <div class="col-lg-3 col-md-6 col-12 mt-4 mt-md-0">
    <div class="card cardhome text-center p-3 d-flex align-items-center justify-content-center position-relative" style="min-height: 160px; background-color: #FC91A3;">
      <div class="position-relative w-100">
        <div class="d-flex align-items-center justify-content-center mb-2">
          <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background-color: #e6f0ff;">
            <i class="fa-solid fa-laptop-file"></i>
          </div>
        </div>
        <h3 class="fw-bold mb-0 text-white">
          <?php echo $totales['cantidad_pedidos_web']; ?>
        </h3>
        <span class="text-white">Pedidos por Web</span>
      </div>
    </div>
  </div>

  <!-- Por Confirmar -->
  <div class="col-lg-3 col-md-6 col-12 mt-4 mt-md-0">
    <div class="card cardhome text-center p-3 d-flex align-items-center justify-content-center position-relative" style="min-height: 160px; background-color: #7F7F7F;">
      <div class="position-relative w-100">
        <div class="d-flex align-items-center justify-content-center mb-2">
          <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background-color: #e6f0ff;">
            <i class="fa-solid fa-file-invoice-dollar"></i>
          </div>
        </div>
        <h3 class="fw-bold mb-0 text-white">
          <?php echo $pendientes['cantidad_pedidos_pendientes']; ?>
        </h3>
        <span class="text-white">Pagos por confirmar</span>
      </div>
    </div>
  </div>
</div> <!-- cierre -->

          
            <div class="row mt-4">
    <div class="col-lg-7 col-md-7 col-12"> <!-- Tabla (60%) -->
        <div class="card">
            <div class="card-body">
                <h5>Los 5 Producto más vendidos</h5>
    <table class="table">
    <thead>
        <tr>
            <th style="color:#d67888;" class="text-center"><b>Producto</b></th>
            <th style="color:#d67888;" class="text-center"><b>Cantidad</b></th>
            <th style="color:#d67888;" class="text-center"><b>Total</b></th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (!empty($registro)) {
            foreach ($registro as $fila) {
                echo "<tr>";
                echo "<td class='text-center'>" . htmlspecialchars($fila['nombre_producto']) . "</td>";
                echo "<td class='text-center'>" . htmlspecialchars($fila['cantidad_vendida']) . "</td>";
                echo "<td class='text-center'> $" . htmlspecialchars(number_format($fila['total_vendido'], 2)) . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No hay datos disponibles</td></tr>";
        }
        ?>
    </tbody>
</table>

            </div>
        </div>
    </div>

<div class="col-lg-5 col-md-5 col-12">
  <div class="card">
    <div class="card-body text-center">
      
<?php if ($graficaHome): ?>
  <img
    src="<?php echo htmlspecialchars($graficaHome, ENT_QUOTES); ?>"
    alt="Top 5 productos"
    class="img-fluid border-radius-lg"
  >
<?php else: ?>
  <p class="text-muted">No hay datos para la gráfica.</p>
<?php endif; ?>


    </div>
  </div>
</div>




</div>




           <br> <br>
    </div>
    </div>  
    </div><!-- FIN CARD PRINCIPAL-->  
<!-- php barra de navegacion-->
<?php include 'complementos/footer.php' ?>

</body>

</html>