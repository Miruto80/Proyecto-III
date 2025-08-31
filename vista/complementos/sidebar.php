<div class="min-height-300 sidedar-color position-absolute w-100">  </div>
  <aside class="sidebar sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 " id="sidenav-main">
  <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href="?pagina=home">
        <img src="assets/img/icono.png" width="30px" height="30px" class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold texto-secundario">Love Makeup C.A</span>
      </a>
      <p class="text-center text-black m-0" id="bcv" style="font-size: 14px;"></p>


    </div>
    
    <hr class="horizontal dark mt-0 texto-hr">

   <div class="collapse navbar-collapse sidebar" id="sidenav-collapse-main">
      

    <ul class="navbar-nav texto-secundario">
        
        <li class="nav-item ">
           <a class="nav-link <?= $pagina_actual == 'home' ? 'bg-activo' : '' ?>" href="?pagina=home">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa-solid fa-house text-sm texto-secundario <?= $pagina_actual == 'home' ? 'text-white' : 'text-dark' ?>"></i>
            </div>
            <span class="nav-link-text ms-1 texto-secundario">Inicio</span>
          </a>
        </li>
        
          <?php if($_SESSION["nivel_rol"] == 3) { ?>
        <li class="nav-item">
          <a class="nav-link " href="?pagina=catalogo">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa-solid fa-bag-shopping text-dark text-sm texto-secundario"></i>
            </div>
            <span class="nav-link-text ms-1 texto-secundario">Ver Tienda</span>
          </a>
        </li>
          <?php } ?>

        <?php if ($_SESSION["nivel_rol"] >= 2 && tieneAcceso(1, 'ver')): ?>
        <li class="nav-item">
          <a class="nav-link <?= $pagina_actual == 'reporte' ? 'bg-activo' : '' ?>" href="?pagina=reporte">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa-solid fa-file-pdf text-sm texto-secundario <?= $pagina_actual == 'reporte' ? 'text-white' : 'text-dark' ?>"></i>
            </div>
            <span class="nav-link-text ms-1 texto-secundario">Reporte</span>
          </a>
        </li> 
        <?php endif; ?>

        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6 texto-cuarto">Administrar</h6>
        </li>
        
        <?php if ($_SESSION["nivel_rol"] == 3 && tieneAcceso(2, 'ver')): ?>
        <li class="nav-item">
            <a class="nav-link <?= $pagina_actual == 'entrada' ? 'bg-activo' : '' ?>" href="?pagina=entrada">
              <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                <i class="fa-solid fa-cart-plus text-sm opacity-10 texto-secundario <?= $pagina_actual == 'entrada' ? 'text-white' : 'text-dark' ?>"></i>
              </div>
              <span class="nav-link-text ms-1 texto-secundario">Compra</span>
            </a>
        </li>
         <?php endif; ?>
        
         <?php if ($_SESSION["nivel_rol"] >= 2 && tieneAcceso(3, 'ver')): ?>
        <li class="nav-item">
          <a class="nav-link <?= $pagina_actual == 'producto' ? 'bg-activo' : '' ?> " href="?pagina=producto">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa-solid fa-pump-soap text-sm opacity-10 texto-secundario <?= $pagina_actual == 'producto' ? 'text-white' : 'text-dark' ?>"></i>
            </div>
            <span class="nav-link-text ms-1 texto-secundario">Producto</span>
          </a>
        </li>
        <?php endif; ?>
      
        <?php if ($_SESSION["nivel_rol"] >= 2 && tieneAcceso(4, 'ver')): ?>
        <li class="nav-item">
          <a class="nav-link <?= $pagina_actual == 'salida' ? 'bg-activo' : '' ?> " href="?pagina=salida">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa-solid fa-cash-register text-sm opacity-10 texto-secundario <?= $pagina_actual == 'salida' ? 'text-white' : 'text-dark' ?>"></i>
              
            </div>
            <span class="nav-link-text ms-1 texto-secundario">Venta</span>
          </a>
        </li>
        <?php endif; ?>
        
      <?php if ($_SESSION["nivel_rol"] >= 2 && tieneAcceso(5, 'ver')):?>
        <li class="nav-item">
          <a class="nav-link <?= $pagina_actual == 'reserva' ? 'bg-activo' : '' ?> " href="?pagina=reserva">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa-solid fa-chalkboard-user texto-secundario text-sm opacity-10 <?= $pagina_actual == 'reserva' ? 'text-white' : 'text-dark' ?>"></i>
              
            </div>
            <span class="nav-link-text ms-1 texto-secundario">Reserva</span>
          </a>
        </li>
      <?php endif; ?>
       
         <?php if ($_SESSION["nivel_rol"] == 3 && tieneAcceso(6, 'ver')): ?>
        <li class="nav-item">
          <a class="nav-link <?= $pagina_actual == 'proveedor' ? 'bg-activo' : '' ?> " href="?pagina=proveedor">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa-solid fa-truck-moving text-sm opacity-10 texto-secundario <?= $pagina_actual == 'proveedor' ? 'text-white' : 'text-dark' ?>"></i>
            </div>
            <span class="nav-link-text ms-1 texto-secundario">Proveedor</span>
          </a>
        </li>
        <?php endif; ?>
        
        <?php if ($_SESSION["nivel_rol"] == 3 && tieneAcceso(7, 'ver')): ?>
        <li class="nav-item">
          <a class="nav-link <?= $pagina_actual == 'categoria' ? 'bg-activo' : '' ?>" href="?pagina=categoria">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-world-2  "></i>
              <i class="fa-solid fa-tag text-sm opacity-10 texto-secundario <?= $pagina_actual == 'categoria' ? 'text-white' : 'text-dark' ?>"></i>
            </div>
            <span class="nav-link-text ms-1 texto-secundario">Categoria</span>
          </a>
        </li>
          <?php endif; ?>
         
        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6 texto-cuarto">WEB</h6>
        </li>

        <?php if ($_SESSION["nivel_rol"] == 3 && tieneAcceso(8, 'ver')): ?>
        <li class="nav-item">
          <a class="nav-link <?= $pagina_actual == 'cliente' ? 'bg-activo' : '' ?> " href="?pagina=cliente">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa-solid fa-user text-sm opacity-10 texto-secundario <?= $pagina_actual == 'cliente' ? 'text-white' : 'text-dark' ?>"></i>
            </div>
            <span class="nav-link-text ms-1 texto-secundario">Cliente</span>
          </a>
        </li>
      <?php endif; ?>

         <?php if ($_SESSION["nivel_rol"] >= 2 && tieneAcceso(9, 'ver')):?>
        <li class="nav-item">
          <a class="nav-link <?= $pagina_actual == 'pedidoweb' ? 'bg-activo' : '' ?> " href="?pagina=pedidoweb">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa-solid fa-desktop text-sm texto-secundario opacity-10 <?= $pagina_actual == 'pedidoweb' ? 'text-white' : 'text-dark' ?>"></i>
            </div>
            <span class="nav-link-text ms-1 texto-secundario">PedidoWeb</span>
          </a>
        </li>
       <?php endif; ?>

        <?php if ($_SESSION["nivel_rol"] == 3 && tieneAcceso(10, 'ver')): ?>
        <li class="nav-item">
          <a class="nav-link <?= $pagina_actual == 'metodopago' ? 'bg-activo' : '' ?>" href="?pagina=metodopago">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa-solid fa-wallet text-sm texto-secundario opacity-10 <?= $pagina_actual == 'metodopago' ? 'text-white' : 'text-dark' ?>"></i>
            </div>
            <span class="nav-link-text ms-1 texto-secundario">Metodo Pago</span>
          </a>
        </li>
        <?php endif; ?>

        <?php if ($_SESSION["nivel_rol"] == 3 && tieneAcceso(11, 'ver')): ?>
         <li class="nav-item">
          <a class="nav-link <?= $pagina_actual == 'metodoentrega' ? 'bg-activo' : '' ?>" href="?pagina=metodoentrega">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa-solid fa-boxes-stacked text-sm texto-secundario  opacity-10 <?= $pagina_actual == 'metodoentrega' ? 'text-white' : 'text-dark' ?>"></i>
            </div>
            <span class="nav-link-text ms-1 texto-secundario">Metodo Entrega</span>
          </a>
        </li>
        <?php endif; ?>

        <?php if($_SESSION["nivel_rol"] == 3) { ?>
          <li class="nav-item mt-3">
            <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6 texto-cuarto">Administrar Usuario</h6>
          </li>
        <?php } ?>

        <?php if ($_SESSION["nivel_rol"] == 3 && tieneAcceso(12, 'ver')): ?>
        <li class="nav-item">
          <a class="nav-link <?= $pagina_actual == 'bitacora' ? 'bg-activo' : '' ?> " href="?pagina=bitacora">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa-solid fa-book text-sm texto-secundario opacity-10 <?= $pagina_actual == 'bitacora' ? 'text-white' : 'text-dark' ?>"></i>
            </div>
            <span class="nav-link-text ms-1 texto-secundario">Bitacora</span>
          </a>
        </li>
       <?php endif; ?>

       <?php if ($_SESSION["nivel_rol"] == 3 && tieneAcceso(13, 'ver')): ?>
        <li class="nav-item">
          <a class="nav-link <?= $pagina_actual == 'usuario' ? 'bg-activo' : '' ?> " href="?pagina=usuario">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa-solid fa-user-gear text-sm texto-secundario opacity-10 <?= $pagina_actual == 'usuario' ? 'text-white' : 'text-dark' ?>"></i>
            </div>
            <span class="nav-link-text ms-1 texto-secundario">Usuario</span>
          </a>
        </li>
         <?php endif; ?>
        
        <?php if ($_SESSION["nivel_rol"] == 3 && tieneAcceso(14, 'ver')): ?>
        <li class="nav-item">
          <a class="nav-link <?= $pagina_actual == 'tipousuario' ? 'bg-activo' : '' ?>" href="?pagina=tipousuario">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa-solid fa-user-group text-sm opacity-10 texto-secundario <?= $pagina_actual == 'tipousuario' ? 'text-white' : 'text-dark' ?>"></i>
            </div>
            <span class="nav-link-text ms-1 texto-secundario">Tipo Usuario</span>
          </a>
        </li>
          <?php endif; ?>
      
    </ul>
    </div> 
    <script src="assets/js/Tasa.js"></script>
  </aside>
