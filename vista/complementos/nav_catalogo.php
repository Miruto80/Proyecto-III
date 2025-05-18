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

</style>

  <header>
    <div class="container-lg">
      <div class="row py-4">

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

   <div class="col-sm-12 col-md-4 col-lg-7 d-none d-md-block">
          <div class="search-bar row justify-content-between bg-light p-2 rounded-4">
            <div class="col-11">
            <form id="search-form" class="text-center" action="index.php" method="get">
  <input type="hidden" name="pagina" value="catalogo_producto">
  <input type="text" name="busqueda" class="form-control border-0 bg-transparent"
    placeholder="Búsqueda de más de 1.000 productos">
</form>
            </div>
            <div class="col-1">
            <i class="fa-solid fa-magnifying-glass" style="font-size: 25px; cursor:pointer;" onclick="document.getElementById('search-form').submit();"></i>
            </div>

          </div>
         
             <div id="saludo" class="text-center mt-1 text-dark"></div>

        </div>

        <div class="col-sm-6 col-md-3 col-lg-2 d-flex justify-content-end gap-5 align-items-center mt-4 mt-sm-0 justify-content-center justify-content-sm-end">
          <ul class="d-flex justify-content-end list-unstyled m-0">
            <li>
             <?php if ($sesion_activa): ?>
               <!-- Si hay sesión activa, muestra el botón de cerrar sesión con otro ícono -->
             <a href="#" class="p-2 mx-1" data-bs-toggle="modal" data-bs-target="#cerrar">
                  <i class="fa-solid fa-right-from-bracket" style="font-size: 25px; color:red;"></i> <!-- Ícono de salida -->
               </a>
            <?php else: ?>
              <!-- Si no hay sesión activa, muestra el ícono de usuario para iniciar sesión -->
              <a href="?pagina=login" class="p-2 mx-1">
               <i class="fa-solid fa-circle-user" style="font-size: 25px;"></i>
              </a>
           <?php endif; ?>
            </li>

            <?php if ($sesion_activa): ?>
            <li>
              <a href="#" class="p-2 mx-1" data-bs-toggle="offcanvas" data-bs-target="#offcanvasCart"
                aria-controls="offcanvasCart">
                 <i class="fa-solid fa-cart-shopping" style="font-size: 25px;"></i>
              </a>
            </li>
            <?php endif; ?>

           <li class="d-md-none">
              <a href="#" class="p-2 mx-1" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSearch"
                aria-controls="offcanvasSearch">
               <i class="fa-solid fa-magnifying-glass" style="font-size: 25px;"></i>
              </a>
            </li>
          </ul>
          
        </div>

      </div>
      
      <nav class="p-0 navbar navbar-expand-lg">
        
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
          <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Offcanvas</h5>
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
              <li class="nav-item border-end-0 border-lg-end-0 border-lg-end">
                <a href="?pagina=catalogo_favorito" class="nav-link fw-bold px-4 py-3" style="color:#ff71d8;">
                <i class="fa-solid fa-heart"></i> Mis Favoritos </a>
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
             <?php endif; ?>
            </ul>

          </div>
        </div>
      </nav>
    </div>

  </header>

  <script>
   let ultimaPosicionScroll = 0;
const header = document.querySelector("header");
const nav = document.querySelector("nav"); 

window.addEventListener("scroll", () => {
  let posicionScroll = window.scrollY || document.documentElement.scrollTop;
  
  if (posicionScroll > ultimaPosicionScroll) {
   
    nav.style.display = "none";
  } else {
   
    nav.style.display = "block";
  }
  
  ultimaPosicionScroll = posicionScroll;
});


  </script>


