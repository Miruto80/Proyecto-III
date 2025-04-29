<!DOCTYPE html>
<html lang="es">

<head>
  <!-- php barra de navegacion-->
  <?php include 'complementos/head.php' ?> 
  <title> Notificaciones | LoveMakeup  </title> 
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
        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="#">Inicio</a></li>
        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Notificaciones</li>
      </ol>
      <h6 class="font-weight-bolder text-white mb-0">Lista de notificaciones</h6>
    </nav>
<!-- php barra de navegacion-->    
<?php include 'complementos/nav.php' ?>


<div class="container-fluid py-4"> <!-- DIV CONTENIDO -->

    <div class="row"> <!-- CARD PRINCIPAL-->  
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0">  <!-- CARD N-1 -->  
              <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button class="btn btn-primary" type="button"> <i class="fa-solid fa-trash-can mr-4"></i> Vaciar</button>
              </div>
            <table class="table">
                   
                    <tbody>
                        <tr>
                            <td>Notificación de confirmapago</td>
                            <td>compra #444564 </td>
                            <td>17-04-2025	08:50 am</td>
                            <td>
                              <button class="btn btn-info btn-sm" type="button" ><i class="fa-solid fa-envelope-open"></i></button>
                              <button class="btn btn-danger btn-sm" type="button" ><i class="fa-solid fa-trash-can"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>Notificación de confirmapago</td>
                            <td>compra #666664 </td>
                            <td>20-04-2025	10:50 am</td>
                            <td>
                              <button class="btn btn-info btn-sm" type="button" ><i class="fa-solid fa-envelope-open"></i></button>
                              <button class="btn btn-danger btn-sm" type="button" ><i class="fa-solid fa-trash-can"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>Notificación de confirmapago</td>
                            <td>compra #45554 </td>
                            <td>19-04-2025	05:50 am</td>
                            <td>
                              <button class="btn btn-info btn-sm" type="button" ><i class="fa-solid fa-envelope-open"></i></button>
                              <button class="btn btn-danger btn-sm" type="button" ><i class="fa-solid fa-trash-can"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>Notificación de confirmapago</td>
                            <td>compra #2222224564 </td>
                            <td>18-04-2025	07:50 am</td>
                            <td>
                              <button class="btn btn-info btn-sm" type="button" ><i class="fa-solid fa-envelope-open"></i></button>
                              <button class="btn btn-danger btn-sm" type="button" ><i class="fa-solid fa-trash-can"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div><!-- FIN CARD N-1 -->  
          
        
    </div>
    </div>  
    </div><!-- FIN CARD PRINCIPAL-->  



<!-- php barra de navegacion-->
<?php include 'complementos/footer.php' ?>

</body>

</html>