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
               <button class="btn btn-primary btn-vaciar" type="button"> 
               <i class="fa-solid fa-trash-can mr-4"></i> Vaciar
              </button>


              </div>
            <table class="table">
                   
            <tbody>
  <?php if (!empty($notificaciones)): ?>
    <?php foreach ($notificaciones as $n): ?>
      <tr id="notificacion-<?= $n['id_notificaciones'] ?>">
        <td><?= htmlspecialchars($n['titulo']) ?></td>
        <td><?= htmlspecialchars($n['mensaje']) ?></td>
        <td class="estado"><?= $n['estado'] == 1 ? 'No leída' : 'Leída' ?></td>
        <td><?= date('d-m-Y h:i a', strtotime($n['fecha'])) ?></td>
        <td>
  <button class="btn btn-info btn-sm btn-leer" data-id="<?= $n['id_notificaciones'] ?>" title="Marcar como leída">
    <i class="fa-solid fa-envelope-open"></i>
  </button>
  <button class="btn btn-danger btn-sm btn-eliminar" data-id="<?= $n['id_notificaciones'] ?>" title="Eliminar">
    <i class="fa-solid fa-trash-can"></i>
  </button>
</td>
      </tr>
    <?php endforeach; ?>
  <?php else: ?>
    <tr><td colspan="5" class="text-center">No hay notificaciones registradas.</td></tr>
  <?php endif; ?>
</tbody>
                </table>
            </div><!-- FIN CARD N-1 -->  
          
        
    </div>
    </div>  
    </div><!-- FIN CARD PRINCIPAL-->  



<!-- php barra de navegacion-->
<?php include 'complementos/footer.php' ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="assets/js/notificacion.js"></script>
<script src="assets/js/notificacion.js"></script>

</body>

</html>