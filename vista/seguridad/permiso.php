<!DOCTYPE html>
<html lang="es">

<head> 
  <!-- php barra de navegacion-->
  <?php include 'vista/complementos/head.php' ?> 
  <title> Cambiar Permisos | LoveMakeup  </title> 
</head>
 
<body class="g-sidenav-show bg-gray-100">
  
<!-- php barra de navegacion-->
<?php include 'vista/complementos/sidebar.php' ?>

<main class="main-content position-relative border-radius-lg ">
<!-- ||| Navbar ||-->
<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur" data-scroll="false">
  <div class="container-fluid py-1 px-3">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="#">Administrar Usuario</a></li>
        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Usuario</li>
      </ol>
      <h6 class="font-weight-bolder text-white mb-0">Cambiar Permisos del Usuario</h6>
    </nav>
<!-- php barra de navegacion-->    
<?php include 'vista/complementos/nav.php' ?>

<style>
  .text-g{
    font-size: 15px;
  }
</style>
<!-- |||||||||||||||| LOADER ||||||||||||||||||||-->
  <div class="preloader-wrapper">
    <div class="preloader">
    </div>
  </div> 
<!-- |||||||||||||||| LOADER ||||||||||||||||||||-->
<div class="container-fluid py-4"> <!-- DIV CONTENIDO -->

    <div class="row"> <!-- CARD PRINCIPAL-->  
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0">  <!-- CARD N-1 --> 
              
            <div class="d-sm-flex align-items-center justify-content-between mb-3">
              <h4 class="mb-0"><i class="fa-solid fa-users-gear me-2" style="color: #f6c5b4;"></i>
                Permiso del Usuario</h4>
           
       <!-- Button que abre el Modal N1 Registro -->
        <a href="?pagina=usuario" class="btn btn-primary"><i class="fa-solid fa-reply"></i> Regresar</a>
      </div>
       <form action="?pagina=usuario" method="POST" autocomplete="off" id="forpermiso">
          
          <div class="table-responsive">
         <table class="table table-bordered text-center align-middle table-hover">
  <thead class="table-color">
    <tr>
      <th class="text-white">#</th>
      <th class="text-white">Módulo</th>
      <th class="text-white">Ver</th>
      <th class="text-white">Registrar</th>
      <th class="text-white">Editar</th>
      <th class="text-white">Eliminar</th>
      <th class="text-white">Especial</th>
    </tr>
  </thead>
  <tbody>
    <?php
      $permisos_por_modulo = [];

      foreach ($modificar as $permiso) {
        $modulo_id = $permiso['id_modulo'];
        $modulo_nombre = $permiso['nombre'];
        $accion = $permiso['accion'];
        $estado = $permiso['estado'];
        $permiso_id = $permiso['id_permiso'];

        if (!isset($permisos_por_modulo[$modulo_id])) {
          $permisos_por_modulo[$modulo_id] = [
            'nombre' => $modulo_nombre,
            'acciones' => [],
            'ids' => []
          ];
        }

        $permisos_por_modulo[$modulo_id]['acciones'][$accion] = $estado;
        $permisos_por_modulo[$modulo_id]['ids'][$accion] = $permiso_id;
      }

      $acciones_por_modulo = [
        1 => ['ver'],
        2 => ['ver', 'registrar', 'editar'],
        3 => ['ver', 'registrar', 'editar', 'eliminar', 'especial'],
        4 => ['ver', 'registrar', 'especial'],
        5 => ['ver', 'registrar', 'editar'],
        6 => ['ver', 'registrar', 'editar', 'eliminar'],
        7 => ['ver', 'registrar', 'editar', 'eliminar'],
        8 => ['ver', 'editar'],
        9 => ['ver', 'especial'],
        10 => ['ver', 'registrar', 'editar', 'eliminar'],
        11 => ['ver', 'registrar', 'editar', 'eliminar'],
        12 => ['ver'],
        13 => ['ver', 'registrar', 'editar', 'eliminar', 'especial'],
        14 => ['ver', 'registrar', 'editar', 'eliminar']
      ];

      $contador = 1;
      foreach ($permisos_por_modulo as $modulo_id => $info):
        $acciones_validas = $acciones_por_modulo[$modulo_id] ?? [];
    ?>
      <tr>
        <td><?= $contador++ ?></td>
        <td><?= htmlspecialchars($info['nombre']) ?></td>
        <?php foreach (['ver', 'registrar', 'editar', 'eliminar', 'especial'] as $accion): ?>
          <td>
            <?php if (in_array($accion, $acciones_validas)): ?>
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox"
                       name="permiso[<?= $modulo_id ?>][<?= $accion ?>]"
                       <?= !empty($info['acciones'][$accion]) ? 'checked' : '' ?>>

                <input type="hidden"
                       name="permiso_id[<?= $modulo_id ?>][<?= $accion ?>]"
                       value="<?= $info['ids'][$accion] ?? '' ?>">
              </div>
            <?php endif; ?>
          </td>
        <?php endforeach; ?>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

          <hr class="bg-primary">
            <div class="text-center">
                        <button type="button" class="btn btn-success btn-lg" name="actualizar_permisos" id="actualizar_permisos"> <i class="fa-solid fa-floppy-disk me-2"></i> Guardar</button>
              </div>
        </div>
   </form>
        </div>
            </div>

 </div>
            </div><!-- FIN CARD N-1 -->  
    </div>
    </div>  
    </div><!-- FIN CARD PRINCIPAL-->  

<!-- php barra de navegacion-->
<?php include 'vista/complementos/footer.php' ?>
<script src="assets/js/usuario.js"></script>
</body>

</html>