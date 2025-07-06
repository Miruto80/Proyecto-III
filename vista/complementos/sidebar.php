<div class="min-height-300 sidedar-color position-absolute w-100">  </div>
  <aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 " id="sidenav-main">
    
  <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href="?pagina=home">
        <img src="assets/img/icono.png" width="30px" height="30px" class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold">Love Makeup C.A</span>
      </a>
      <p class="text-center text-black m-0" id="bcv" style="font-size: 14px;"></p>


    </div>
    
    <hr class="horizontal dark mt-0">

   <div class="collapse navbar-collapse" id="sidenav-collapse-main">
      

    <ul class="navbar-nav">
        
         <li class="nav-item">
          <a class="nav-link active" href="?pagina=home">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa-solid fa-house text-dark text-sm"></i>
            </div>
            <span class="nav-link-text ms-1">Inicio</span>
          </a>
        </li>
        
          <?php if($_SESSION["nivel_rol"] == 3) { ?>
        <li class="nav-item">
          <a class="nav-link" href="?pagina=catalogo">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa-solid fa-bag-shopping text-dark text-sm"></i>
            </div>
            <span class="nav-link-text ms-1">Ver Tienda</span>
          </a>
        </li>
          <?php } ?>

          <?php
          $mostrar_reporte = false;
         if ( ($_SESSION["nivel_rol"] == 2 || $_SESSION["nivel_rol"] == 3) && !empty($_SESSION['permisos'])) {
              foreach ($_SESSION['permisos'] as $permiso) {
                  if (
                      $permiso['id_modulo'] == 1 &&
                      $permiso['accion'] === 'ver' &&
                      $permiso['estado'] == 1
                  ) {
                      $mostrar_reporte = true;
                      break;
                  }
              }
          }
          if ($mostrar_reporte) {
          ?>
        <li class="nav-item">
          <a class="nav-link" href="?pagina=reporte">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa-solid fa-file-pdf text-sm text-dark "></i>
            </div>
            <span class="nav-link-text ms-1">Reporte</span>
          </a>
        </li> 
        <?php } ?>

        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Administrar</h6>
        </li>

         <?php
          $mostrar_compra = false;
          if ($_SESSION["nivel_rol"] == 3 && !empty($_SESSION['permisos'])) {
              foreach ($_SESSION['permisos'] as $permiso) {
                  if (
                      $permiso['id_modulo'] == 2 &&
                      $permiso['accion'] === 'ver' &&
                      $permiso['estado'] == 1
                  ) {
                      $mostrar_compra = true;
                      break;
                  }
              }
          }

          if ($mostrar_compra) {
          ?>
          
          <li class="nav-item">
            <a class="nav-link" href="?pagina=entrada">
              <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                <i class="fa-solid fa-cart-plus text-dark text-sm opacity-10"></i>
              </div>
              <span class="nav-link-text ms-1">Compra</span>
            </a>
          </li>
          <?php } ?>


          <?php
          $mostrar_producto = false;
         if ( ($_SESSION["nivel_rol"] == 2 || $_SESSION["nivel_rol"] == 3) && !empty($_SESSION['permisos'])) {
              foreach ($_SESSION['permisos'] as $permiso) {
                  if (
                      $permiso['id_modulo'] == 3 &&
                      $permiso['accion'] === 'ver' &&
                      $permiso['estado'] == 1
                  ) {
                      $mostrar_producto = true;
                      break;
                  }
              }
          }
          if ($mostrar_producto) {
          ?>   
        <li class="nav-item">
          <a class="nav-link " href="?pagina=producto">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa-solid fa-pump-soap text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Producto</span>
          </a>
        </li>
        <?php } ?> 
        
        <?php
          $mostrar_venta = false;
         if ( ($_SESSION["nivel_rol"] == 2 || $_SESSION["nivel_rol"] == 3) && !empty($_SESSION['permisos'])) {
              foreach ($_SESSION['permisos'] as $permiso) {
                  if (
                      $permiso['id_modulo'] == 4 &&
                      $permiso['accion'] === 'ver' &&
                      $permiso['estado'] == 1
                  ) {
                      $mostrar_venta = true;
                      break;
                  }
              }
          }
          if ($mostrar_venta) {
          ?>  

        <li class="nav-item">
          <a class="nav-link " href="?pagina=salida">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa-solid fa-cash-register text-dark text-sm opacity-10"></i>
              
            </div>
            <span class="nav-link-text ms-1">Venta</span>
          </a>
        </li>
        <?php } ?>
        
       <?php
          $mostrar_reserva = false;
         if ( ($_SESSION["nivel_rol"] == 2 || $_SESSION["nivel_rol"] == 3) && !empty($_SESSION['permisos'])) {
              foreach ($_SESSION['permisos'] as $permiso) {
                  if (
                      $permiso['id_modulo'] == 5 &&
                      $permiso['accion'] === 'ver' &&
                      $permiso['estado'] == 1
                  ) {
                      $mostrar_reserva = true;
                      break;
                  }
              }
          }
          if ($mostrar_reserva) {
          ?>
        <li class="nav-item">
          <a class="nav-link " href="?pagina=reserva">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa-solid fa-chalkboard-user text-dark text-sm opacity-10"></i>
              
            </div>
            <span class="nav-link-text ms-1">Reserva</span>
          </a>
        </li>
        <?php } ?>
       
        <?php
          $mostrar_proveedor = false;
         if ($_SESSION["nivel_rol"] == 3 && !empty($_SESSION['permisos']))  {
              foreach ($_SESSION['permisos'] as $permiso) {
                  if (
                      $permiso['id_modulo'] == 6 &&
                      $permiso['accion'] === 'ver' &&
                      $permiso['estado'] == 1
                  ) {
                      $mostrar_proveedor = true;
                      break;
                  }
              }
          }
          if ($mostrar_proveedor) {
          ?>
        <li class="nav-item">
          <a class="nav-link " href="?pagina=proveedor">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa-solid fa-truck-moving text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Proveedor</span>
          </a>
        </li>
        <?php } ?>
        
          <?php
          $mostrar_categoria = false;
         if ($_SESSION["nivel_rol"] == 3 && !empty($_SESSION['permisos']))  {
              foreach ($_SESSION['permisos'] as $permiso) {
                  if (
                      $permiso['id_modulo'] == 7 &&
                      $permiso['accion'] === 'ver' &&
                      $permiso['estado'] == 1
                  ) {
                      $mostrar_categoria = true;
                      break;
                  }
              }
          }
          if ($mostrar_categoria) {
          ?>
        <li class="nav-item">
          <a class="nav-link " href="?pagina=categoria">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-world-2  "></i>
              <i class="fa-solid fa-tag text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Categoria</span>
          </a>
        </li>
         <?php } ?>
         
         
        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">WEB</h6>
        </li>

          <?php
          $mostrar_cliente = false;
          if ( ($_SESSION["nivel_rol"] == 2 || $_SESSION["nivel_rol"] == 3) && !empty($_SESSION['permisos'])) {
              foreach ($_SESSION['permisos'] as $permiso) {
                  if (
                      $permiso['id_modulo'] == 8 &&
                      $permiso['accion'] === 'ver' &&
                      $permiso['estado'] == 1
                  ) {
                      $mostrar_cliente = true;
                      break;
                  }
              }
          }
          if ($mostrar_cliente) {
          ?>
        <li class="nav-item">
          <a class="nav-link " href="?pagina=cliente">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa-solid fa-user text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Cliente</span>
          </a>
        </li>
          <?php } ?>

             <?php
          $mostrar_web = false;
         if ($_SESSION["nivel_rol"] == 3 && !empty($_SESSION['permisos']))  {
              foreach ($_SESSION['permisos'] as $permiso) {
                  if (
                      $permiso['id_modulo'] == 9 &&
                      $permiso['accion'] === 'ver' &&
                      $permiso['estado'] == 1
                  ) {
                      $mostrar_web = true;
                      break;
                  }
              }
          }
          if ($mostrar_web) {
          ?>
        <li class="nav-item">
          <a class="nav-link " href="?pagina=pedidoweb">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa-solid fa-desktop text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">PedidoWeb</span>
          </a>
        </li>
        <?php } ?>

          <?php
          $mostrar_pago = false;
         if($_SESSION["nivel_rol"] == 3 && !empty($_SESSION['permisos'])) {
              foreach ($_SESSION['permisos'] as $permiso) {
                  if (
                      $permiso['id_modulo'] == 10 &&
                      $permiso['accion'] === 'ver' &&
                      $permiso['estado'] == 1
                  ) {
                      $mostrar_pago = true;
                      break;
                  }
              }
          }
          if ($mostrar_pago) {
          ?>
        <li class="nav-item">
          <a class="nav-link " href="?pagina=metodopago">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa-solid fa-wallet text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Metodo Pago</span>
          </a>
        </li>
        <?php } ?>

        <?php
          $mostrar_entrega = false;
         if ($_SESSION["nivel_rol"] == 3 && !empty($_SESSION['permisos']))  {
              foreach ($_SESSION['permisos'] as $permiso) {
                  if (
                      $permiso['id_modulo'] == 11 &&
                      $permiso['accion'] === 'ver' &&
                      $permiso['estado'] == 1
                  ) {
                      $mostrar_entrega = true;
                      break;
                  }
              }
          }
          if ($mostrar_entrega) {
          ?>
         <li class="nav-item">
          <a class="nav-link " href="?pagina=metodoentrega">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa-solid fa-boxes-stacked text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Metodo Entrega</span>
          </a>
        </li>
        <?php } ?>

         <?php if($_SESSION["nivel_rol"] == 3) { ?>
        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Administrar Usuario</h6>
        </li>
          <?php } ?>

          <?php
          $mostrar_bitacora = false;
         if ($_SESSION["nivel_rol"] == 3 && !empty($_SESSION['permisos']))  {
              foreach ($_SESSION['permisos'] as $permiso) {
                  if (
                      $permiso['id_modulo'] == 12 &&
                      $permiso['accion'] === 'ver' &&
                      $permiso['estado'] == 1
                  ) {
                      $mostrar_bitacora = true;
                      break;
                  }
              }
          }
          if ($mostrar_bitacora) {
          ?>
        <li class="nav-item">
          <a class="nav-link " href="?pagina=bitacora">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa-solid fa-book text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Bitacora</span>
          </a>
        </li>
        <?php } ?>

        <?php
          $mostrar_usuario = false;
         if ($_SESSION["nivel_rol"] == 3 && !empty($_SESSION['permisos']))  {
              foreach ($_SESSION['permisos'] as $permiso) {
                  if (
                      $permiso['id_modulo'] == 13 &&
                      $permiso['accion'] === 'ver' &&
                      $permiso['estado'] == 1
                  ) {
                      $mostrar_usuario = true;
                      break;
                  }
              }
          }
          if ($mostrar_usuario) {
          ?>
        <li class="nav-item">
          <a class="nav-link " href="?pagina=usuario">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa-solid fa-user-gear text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Usuario</span>
          </a>
        </li>
        <?php } ?>

        <?php
          $mostrar_tipo = false;
         if ($_SESSION["nivel_rol"] == 3 && !empty($_SESSION['permisos']))  {
              foreach ($_SESSION['permisos'] as $permiso) {
                  if (
                      $permiso['id_modulo'] == 14 &&
                      $permiso['accion'] === 'ver' &&
                      $permiso['estado'] == 1
                  ) {
                      $mostrar_tipo = true;
                      break;
                  }
              }
          }
          if ($mostrar_tipo) {
          ?>
        <li class="nav-item">
          <a class="nav-link " href="?pagina=tipousuario">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa-solid fa-user-group text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Tipo Usuario</span>
          </a>
        </li>
        <?php } ?>


     
      
    </ul>

    </div> 
    <script src="assets/js/Tasa.js"></script>
  </aside>
