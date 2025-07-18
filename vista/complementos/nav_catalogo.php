<!--ENCABEZADO LOGO CARRITO Y LOGIN-->
<style>
  header {
  position: sticky;
  top: 0;
  width: 100%;
  transition: top 0.6s ease-in-out ;
  background: white;
  z-index: 1000;
}

.slideb{
text-align: end;
font-weight: bold;
color: #212529ff;
}
nav {
  transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
}

.nav-hidden {
  opacity: 0;
  transform: translateY(-15px);
}

.nav-hidden-display {
  display: none;
}

.contadorL{
  margin-left: 20px;
  margin-bottom: 2px;
  display:flex;
  width: 22px;
  color: #fff;
  background-color: #ff71d8;
}
</style>
  <header>
    <div class="container-lg">
      <div class="row py-4">
      <p id="bcv" class=" slideb m-0 p-0"></p>   
        <div class="col-sm-6 col-md-5 col-lg-3 justify-content-center justify-content-lg-between text-center text-sm-start d-flex gap-3">
          <div class="d-flex align-items-center">
            <a href="?pagina=catalogo">
              <img src="assets/img/logo2.png" alt="logo" class="img-fluid" height="50px" width="110px">
            </a>
            <button class="navbar navbar-toggler ms-3 d-block d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
              aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
          </div>
        </div>

        <style>
          .sombra-profunda {
            box-shadow: 0 0 12px rgba(0, 0, 0, 0.2);
                border-radius: 8px; 
                background-color: #fff;
                padding: 20px;
            }
        </style>

        <div class="col-sm-12 col-md-4 col-lg-7 d-none d-md-block">
          <div class="search-bar row justify-content-between p-2 rounded-4 bg-ligth sombra-profunda">
            <div class="col-11">
            <form id="search-form" class="text-center" action="index.php" method="get">
              <input type="hidden" name="pagina" value="catalogo_producto">
              <input type="text" name="busqueda" class="form-control border-0 bg-transparent "
                placeholder="Búsqueda de más de 1.000 productos">
            </form>
            </div>
            <div class="col-1">
            <i class="fa-solid fa-magnifying-glass" style="font-size: 30px; cursor:pointer;" onclick="document.getElementById('search-form').submit();"></i>
            </div>

          </div>
         
             <div id="saludo" class="text-center mt-1 text-dark"></div>

        </div>

        <div class="col-sm-6 col-md-3 col-lg-2 d-flex justify-content-end gap-5 align-items-center mt-4 mt-sm-0 justify-content-center justify-content-sm-end">
          <ul class="d-flex justify-content-end list-unstyled m-0">
         
          <?php if ($sesion_activa && $_SESSION["nivel_rol"] == 1): ?>
            <?php 
              $pagina = $_GET['pagina'] ?? '';
              $paginasPermitidas = ['catalogo', 'catalogo_producto', 'vercarrito', 'verpedidoweb'];
              $paginasOcultas = ['vercarrito', 'verpedidoweb','Pedidoentrega','Pedidopago','Pedidoconfirmar'];
            ?>
          <?php if (in_array($pagina,$paginasPermitidas)): ?>
            <li>
              <a class="p-2 mx-1" id="btnAyuda" title="Ayuda">
                <span class="icon text-dark">
                  <i class="fa-solid fa-circle-question"  style="font-size: 25px; color:#004adf; cursor: pointer;"></i>
                </span>
                
              </a>
            </li>
            <?php endif; ?>

              <?php if (!in_array($pagina, $paginasOcultas)): ?>
                <li id="carrito">
                  <a href="#" class="p-0 m-0" data-bs-toggle="offcanvas" data-bs-target="#offcanvasCart" title="Ver Carrito"
                    aria-controls="offcanvasCart">
                    <span class="badge rounded-pill contador contadorL">
                      <?php echo count($carrito); ?>
                    </span>
                    <i class="fa-solid fa-cart-shopping" style="font-size: 25px;"></i>
                  </a>
                </li>
              <?php endif; ?>
              

            <?php endif; ?>


            <li class="d-md-none">
              <a href="#" class="p-2 mx-1" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSearch"
                aria-controls="offcanvasSearch">
               <i class="fa-solid fa-magnifying-glass" style="font-size: 25px;"></i>
              </a>
            </li>
           

            <li>
             <?php if ($sesion_activa): ?>
               <!-- Si hay sesión activa, muestra el botón de cerrar sesión con otro ícono -->
               <a href="#" class="p-2 mx-1"  data-bs-toggle="modal" data-bs-target="#cerrar" role="button">
                <span id="logoutPopover"  data-bs-toggle="popover"  data-bs-placement="bottom" data-bs-trigger="hover focus" 
                      data-bs-content="Cerrar sesión"  data-bs-container="body">
                  <i class="fa-solid fa-right-from-bracket" style="font-size: 25px; color:red;"></i>
                </span>
              </a>


            <?php else: ?>
              <!-- Si no hay sesión activa, muestra el ícono de usuario para iniciar sesión -->
              
              <a href="?pagina=login" class="p-2 mx-1" id="userPopover" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-placement="bottom" data-bs-content="Inicia sesión aquí">
                <i class="fa-solid fa-circle-user" style="font-size: 25px;"></i>
              </a>

         
           <?php endif; ?>
            </li>
             
          </ul>
          
        </div>

      </div>
      
      <nav class="p-0 navbar navbar-expand-lg">
        
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
          <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasNavbarLabel">LoveMakeup C.A</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
          </div>
          <div class="offcanvas-body justify-content-center">

            <ul class="navbar-nav mb-0">
              <li class="nav-item border-end-0 border-lg-end-0 border-lg-end active">
                <a href="?pagina=catalogo" class="nav-link fw-bold px-4 py-3">Inicio</a>
              </li>
            
              <li class="nav-item border-end-0 border-lg-end-0 border-lg-end">
                <a href="?pagina=catalogo_producto" class="nav-link fw-bold px-4 py-3">Todos los productos</a>
              </li>
              <li class="nav-item border-end-0 border-lg-end-0 border-lg-end">
                <a href="?pagina=catalogo_consejo" class="nav-link fw-bold px-4 py-3">Consejos</a>
              </li>
              <li class="nav-item border-end-0 border-lg-end-0 border-lg-end">
                <a href="?pagina=catalogo_contacto" class="nav-link fw-bold px-4 py-3">Contactos</a>
              </li>
             <?php if ($sesion_activa): ?>
              <?php if($_SESSION["nivel_rol"] == 1) { ?>
              <li class="nav-item border-end-0 border-lg-end-0 border-lg-end">
                <a href="?pagina=listadeseo" class="nav-link fw-bold px-4 py-3" style="color:#ff71d8;">
                <i class="fa-solid fa-heart"></i> Mi Lista de Deseos </a>
              </li>

              <li class="nav-item border-end-0 border-lg-end-0 border-lg-end dropdown">
                <a class="nav-link fw-bold px-4 py-3 dropdown-toggle" role="button" id="pages"
                  data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="fa-solid fa-laptop-file"></i> Ver </a>
                <ul class="dropdown-menu px-3 px-lg-0 pb-2 mt-0 border-0 rounded-0 animate slide shadow" aria-labelledby="pages">
                  <li> <a href="?pagina=catalogo_datos"  class="dropdown-item text-dark" >
                        <i class="fa-solid fa-user-gear"></i> Mis Datos </a>
                    </li>
                  <li><a href="?pagina=catalogo_pedido" class="dropdown-item text-dark">
                  <i class="fa-solid fa-bag-shopping"></i> Mis Pedidos </a></li>
                </ul>
              </li>
              <?php } ?>
               <?php if($_SESSION["nivel_rol"] == 3) { ?>
              <li class="nav-item border-end-0 border-lg-end-0 border-lg-end">
                <a href="?pagina=home" class="nav-link fw-bold px-4 py-3" style="color:#ff0000; font-size: 20px;">
                <i class="fa-solid fa-share"></i> Volver</a>
              </li>
                <?php } ?>
             <?php endif; ?>
            </ul>

          </div>
        </div>
      </nav>
    </div>

  </header>

  <script>
 let ultimaPosicionScroll = 0;
const nav = document.querySelector("nav");

window.addEventListener("scroll", () => {
  let posicionScroll = window.scrollY || document.documentElement.scrollTop;

  if (window.innerWidth > 768) {
    if (posicionScroll > ultimaPosicionScroll) {
      nav.classList.add("nav-hidden");  
      setTimeout(() => {
        nav.classList.add("nav-hidden-display");
      }, 300);
    } else if (posicionScroll === 0) {
      nav.classList.remove("nav-hidden-display"); 
      setTimeout(() => {
        nav.classList.remove("nav-hidden");
      }, 10);
    }
  }

  ultimaPosicionScroll = posicionScroll;
});

document.addEventListener('DOMContentLoaded', function () {
  const popoverElements = document.querySelectorAll('[data-bs-toggle="popover"]');
  popoverElements.forEach(el => new bootstrap.Popover(el));
});


  </script>
 <script src="assets/js/Tasa.js"></script>

