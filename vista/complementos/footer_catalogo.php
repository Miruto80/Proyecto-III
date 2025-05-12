




<!-- ||||||||||||||||||||| PIE DE PAGINA ||||||||||||||||||||||||||||||||-->
  <footer class="section-padding pb-5 bg-dark text-secondary-emphasis" data-bs-theme="dark">
    <div class="container-lg">
      <div class="row my-5 justify-content-center">

        <div class="col-md-3 col-sm-6">
          <div class="footer-menu">
            <img src="assets/img/logo.png" width="120" height="120" alt="logo">
            <div class="social-links mt-3">
              <ul class="d-flex list-unstyled gap-3">
                
                <li>
                  <a href="https://www.instagram.com/lovemakeupyk/" target="_blank" class="text-secondary-emphasis">
                    <i class="fa-brands fa-instagram" style="font-size: 30px; color: #fa48c9;"></i>
                  </a>
                </li>
                <li>
                  <a href="https://www.instagram.com/lovemakeupyk/" target="_blank" class="text-secondary-emphasis">
                  <i class="fa-brands fa-whatsapp" style="font-size: 30px; color: #fa48c9;"></i>
                  </a>
                </li>
               
              </ul>
            </div>
          </div>
        </div>

        <div class="col-md-3 col-sm-6">
          <div class="footer-menu">
            <h5 class="fs-5 fw-normal text-white">Tienda</h5>
            <ul class="menu-list list-unstyled">
              <li class="menu-item">
                <a href="#" class="nav-link text-secondary-emphasis">Sobre nosotros</a>
              </li>
              <li class="menu-item">
                <a href="#" class="nav-link text-secondary-emphasis">Condiciones</a>
              </li>
              <li class="menu-item">
                <a href="#" class="nav-link text-secondary-emphasis">Nuestras Revistas</a>
              </li>
              <li class="menu-item">
                <a href="#" class="nav-link text-secondary-emphasis">Carreras</a>
              </li>
              <li class="menu-item">
                <a href="#" class="nav-link text-secondary-emphasis">Programa de Afiliados</a>
              </li>
              <li class="menu-item">
                <a href="#" class="nav-link text-secondary-emphasis">Prensa Ultras</a>
              </li>
            </ul>
          </div>
        </div>
        <div class="col-md-3 col-sm-6">
          <div class="footer-menu">
            <h5 class="fs-5 fw-normal text-white">Enlaces rápidos</h5>
            <ul class="menu-list list-unstyled">
              <li class="menu-item">
                <a href="#" class="nav-link text-secondary-emphasis">Ofrece</a>
              </li>
              <li class="menu-item">
                <a href="#" class="nav-link text-secondary-emphasis">Cupones de descuento</a>
              </li>
              <li class="menu-item">
                <a href="#" class="nav-link text-secondary-emphasis">Reservas</a>
              </li>
              <li class="menu-item">
                <a href="#" class="nav-link text-secondary-emphasis">Orden de seguimiento</a>
              </li>
              <li class="menu-item">
                <a href="#" class="nav-link text-secondary-emphasis">Tienda</a>
              </li>
              <li class="menu-item">
                <a href="#" class="nav-link text-secondary-emphasis">Información</a>
              </li>
            </ul>
          </div>
        </div>
        <div class="col-md-3 col-sm-6">
          <div class="footer-menu">
            <h5 class="fs-5 fw-normal text-white">Servicio al cliente</h5>
            <ul class="menu-list list-unstyled">
              <li class="menu-item">
                <a href="#" class="nav-link text-secondary-emphasis">FAQ</a>
              </li>
              <li class="menu-item">
                <a href="#" class="nav-link text-secondary-emphasis">Contacto</a>
              </li>
              <li class="menu-item">
                <a href="#" class="nav-link text-secondary-emphasis">Política de privacidad</a>
              </li>
              <li class="menu-item">
                <a href="#" class="nav-link text-secondary-emphasis">Devoluciones y reembolsos</a>
              </li>
              <li class="menu-item">
                <a href="#" class="nav-link text-secondary-emphasis">Directrices sobre cookies</a>
              </li>
              <li class="menu-item">
                <a href="#" class="nav-link text-secondary-emphasis">Información de entrega</a>
              </li>
            </ul>
          </div>
        </div>

      </div>
      <div class="row">
        <div class="col-md-6 copyright">
          <p>© 2025 LoveMakeup C.A | Tienen todos los derechos revervados.</p>
        </div>
        <div class="col-md-6 credit-link text-start text-md-end">
          <p>Estudiantes UPTAEB T3</a></p>
        </div>
      </div>
    </div>
  </footer>


  <script src="assets/js/catalago/jquery-1.11.0.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>

  <script src="assets/js/catalago/js/bootstrap.min.js"></script>
  <script src="assets/js/catalago/plugins.js"></script>
  <script src="assets/js/catalago/script.js"></script>
   
  <script src="assets/js/catalago/catalogo.js"></script>
  <script src="assets/js/bootstrap.bundle.min.js"></script>

<script>

  // Funcion para poner el nombre
 function obtenerSaludo(nombreCompleto) {
        var hora = moment().hour(); // Obtiene la hora actual con Moment.js
        var saludo = "";

        if (hora >= 6 && hora < 12) {
            saludo = "Buenos días, " + nombreCompleto;
        } else if (hora >= 12 && hora < 18) {
            saludo = "Buenas tardes, " + nombreCompleto;
        } else {
            saludo = "Buenas noches, " + nombreCompleto;
        }

        document.getElementById("saludo").innerHTML = saludo;
    }

    var nombreUsuario = "<?php echo $nombreCompleto; ?>"; // Usa el nombre y apellido de sesión o valores por defecto
obtenerSaludo(nombreUsuario)
</script>

<!-- Modal -->
<div class="modal fade" id="cerrar" tabindex="1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      
      <div class="modal-body"> <!-- Modal contenido -->
      
      <div class="d-flex justify-content-center align-items-center">
          <img src="assets/img/integoracion.png" width="35%">
      </div>
      <div class="d-flex justify-content-center align-items-center">
        <h3>¿Desea cerrar la session?</h3>
      </div>

      <br>    

      <div class="d-flex justify-content-center align-items-center">
          <form action="?pagina=catalogo" method="POST" autocomplete="off">  <!-- Cerrar sesión-->
              <button type="submit" class="btn btn-dark btn-lg me-4" name="cerrar"> SI </button>
          </form> 
         <button type="button" class="btn btn-dark btn-lg me-4" data-bs-dismiss="modal">NO</button>
      </div>



      </div> <!-- FIN Modal contenido -->
      
    </div>
  </div>
</div>
