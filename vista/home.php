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


<div class="container-fluid py-4"> <!-- DIV CONTENIDO-->

    <div class="row"> <!-- CARD PRINCIPAL-->  
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0">  <!-- CARD N-1 -->  

            <div class="row">
    <div class="col-lg-3 col-md-6 col-12">
        <div class="card">
            <span class="mask bg-card01 opacity-10 border-radius-lg"></span>
            <div class="card-body p-3 position-relative">
                <div class="row">
                    <div class="col-8 text-start">
                        <div class="icon icon-shape bg-white shadow text-center border-radius-2xl">
                            <i class="fa-solid fa-comments-dollar" style="color:black;"> </i>
                        </div>
                        <h5 class="font-weight-bolder mb-0 mt-3" style="color:black;">
                        $<?php echo number_format($totales['total_ventas'], 2); ?>
                    </h5>
                        <span class="text-sm"  style="color:black;"><b>Venta</b></span>
                    </div>
                    <div class="col-4">
                        <p class="text-sm text-end font-weight-bolder mt-auto mb-0" style="color:black;">+55%</p>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="col-lg-3 col-md-6 col-12 mt-4 mt-md-0">
        <div class="card">
            <span class="mask bg-card02 opacity-10 border-radius-lg"></span>
            <div class="card-body p-3 position-relative">
                <div class="row">
                    <div class="col-8 text-start">
                        <div class="icon icon-shape bg-white shadow text-center border-radius-2xl">
                                <i class="fa-solid fa-credit-card" style="color:black;"></i>
                        </div>
                        <h5 class="text-white font-weight-bolder mb-0 mt-3">
                        $<?php echo number_format($totales['total_web'], 2); ?>
                    </h5>
                        <span class="text-white text-sm"><b>Venta Por Web</b></span>
                    </div>
                    <div class="col-4">
                        <p class="text-white text-sm text-end font-weight-bolder mt-auto mb-0">+124%</p>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="col-lg-3 col-md-6 col-12 mt-4 mt-md-0">
        <div class="card">
            <span class="mask bg-card03 opacity-10 border-radius-lg"></span>
            <div class="card-body p-3 position-relative">
                <div class="row">
                    <div class="col-8 text-start">
                        <div class="icon icon-shape bg-white shadow text-center border-radius-2xl">
                             <i class="fa-solid fa-laptop-file" style="color:black;"></i>
                        </div>
                        <h5 class="text-white font-weight-bolder mb-0 mt-3">
                        <?php echo $totales['cantidad_pedidos_web']; ?>
                    </h5>
                        <span class="text-white text-sm"><b>Pedidos por Web</b></span>
                    </div>
                    <div class="col-4">
                        <p class="text-white text-sm text-end font-weight-bolder mt-auto mb-0">+30%</p>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="col-lg-3 col-md-6 col-12 mt-4 mt-md-0">
        <div class="card">
            <span class="mask bg-card04 opacity-10 border-radius-lg"></span>
            <div class="card-body p-3 position-relative">
                <div class="row">
                    <div class="col-8 text-start">
                        <div class="icon icon-shape bg-white shadow text-center border-radius-2xl">
                        <i class="fa-solid fa-file-invoice-dollar" style="color:black;"></i>
                        </div>
                        <h5 class="text-white font-weight-bolder mb-0 mt-3">120</h5>
                        <span class="text-white text-sm"><b>Pago Pendientes</b></span>
                    </div>
                    <div class="col-4">
                        <p class="text-white text-sm text-end font-weight-bolder mt-auto mb-0">+15%</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>

            </div><!-- FIN CARD N-1 -->  
          
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

    <div class="col-lg-5 col-md-5 col-12"> <!-- Imagen (40%) -->
        <div class="card">
            <div class="card-body text-center">
                <img src="assets/img/D3.png" alt="Ejemplo Imagen" class="img-fluid border-radius-lg">
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