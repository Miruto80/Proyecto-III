




<!-- ||||||||||||||||||||| PIE DE PAGINA ||||||||||||||||||||||||||||||||-->
  <footer class="section-padding pb-5 bg-dark text-secondary-emphasis" data-bs-theme="dark">
    <div class="container-lg">
      <div class="row my-5 justify-content-center">

        <div class="col-md-3 col-sm-6">
          <div class="footer-menu">
            <img src="assets/img/logo.png" width="130" height="130" alt="logo">
            <div class="social-links mt-3">
              <ul class="d-flex list-unstyled gap-3">
                
                <li>
                  <a href="https://www.instagram.com/lovemakeupyk/" target="_blank" class="text-secondary-emphasis">
                    <i class="fa-brands fa-instagram" style="font-size: 30px; color: #fa48c9;"></i>
                  </a>
                </li>
                <li>
                  <a href="https://wa.link/0e2clu" target="_blank" class="text-secondary-emphasis">
                  <i class="fa-brands fa-whatsapp" style="font-size: 30px; color: #fa48c9;"></i>
                  </a>
                </li>
                 <li>
                  <a href="https://www.facebook.com/lovemakeupyk/" target="_blank" class="text-secondary-emphasis">
                  <i class="fa-brands fa-facebook" style="font-size: 30px; color: #fa48c9;"></i>
                  </a>
                </li>
               
              </ul>
            </div>
          </div>
        </div>

           <div class="col-md-3 col-sm-6">
          <div class="footer-menu">
            <h5 class="fs-5 fw-normal text-white">Información de<br> contacto</h5>
            <ul class="menu-list list-unstyled">
              <li class="menu-item">
                <a href="https://www.instagram.com/lovemakeupyk/" target="_blank" class="nav-link text-secondary-emphasis">
                  <i class="fa-brands fa-instagram"></i>   Instagram</a>
              </li>
                <li class="menu-item">
                <a href="https://www.facebook.com/lovemakeupyk/" target="_blank" class="nav-link text-secondary-emphasis">
                  <i class="fa-brands fa-facebook"></i>  Facebook</a>
              </li>
              <li class="menu-item">
                <a href="https://www.instagram.com/lovemakeupyk/" target="_blank" class="nav-link text-secondary-emphasis">
                  <i class="fa-solid fa-phone"></i> 04245115414
                </a>
              </li>
              <li class="menu-item">
                <a href="#" class="nav-link text-secondary-emphasis">
                  <i class="fa-solid fa-location-dot"></i> Av 20 entre calles 29 y 30 <br> CC 
                  Barquisimeto Plaza</a>
              </li>
            </ul>
          </div>
        </div>
        


        <style>
.footer-menu .menu-list .menu-item .nav-link {
  color: inherit;
  transition: color 0.3s ease;
}

.footer-menu .menu-list .menu-item .nav-link:hover {
  color: #fa48c9 !important;
}
.footer-menu .menu-list .menu-item .nav-link i {
  transition: color 0.3s ease;
}
.footer-menu .menu-list .menu-item .nav-link:hover i {
  color: #fa48c9;
}
        </style>
        <div class="col-md-3 col-sm-6">
          <div class="footer-menu">
            <h5 class="fs-5 fw-normal text-white">Tienda</h5>
            <ul class="menu-list list-unstyled">
              <li class="menu-item">
                <a href="?pagina=catalogo" class="nav-link text-secondary-emphasis">Inicio</a>
              </li>
              <li class="menu-item">
                <a href="?pagina=catalogo_producto" class="nav-link text-secondary-emphasis">Todos los productos</a>
              </li>
              <li class="menu-item">
                <a href="?pagina=catalogo_consejo" class="nav-link text-secondary-emphasis">Nuestros Consejos</a>
              </li>
              
             <?php if ($sesion_activa): ?>
              <?php if($_SESSION["nivel_rol"] == 1) { ?>

              <li class="menu-item">
                <a href="?pagina=listadeseo" class="nav-link text-secondary-emphasis">Lista de deseos</a>
              </li>
               <?php } ?>
               <?php endif; ?>
            </ul>
          </div>
        </div>
        
        <div class="col-md-3 col-sm-6">
          <div class="footer-menu">
            <h5 class="fs-5 fw-normal text-white">Información</h5>
            <ul class="menu-list list-unstyled">
               <li class="menu-item">
                <a href="#" class="nav-link text-secondary-emphasis">FAQ</a>
              </li>
              <li class="menu-item">
                <a href="?pagina=aviso_legal" class="nav-link text-secondary-emphasis">Aviso Legal</a>
              </li>
           
              <li class="menu-item">
                <a href="#" class="nav-link text-secondary-emphasis">Politica de Privacidad</a>
              </li>
              <li class="menu-item">
                <a href="#" class="nav-link text-secondary-emphasis">Politica de cookies</a>
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
            saludo = "Buenos días <i class='fa-solid fa-cloud-sun'></i>, " + nombreCompleto;
        } else if (hora >= 12 && hora < 18) {
            saludo = "Buenas tardes <i class='fa-solid fa-sun'></i>, " + nombreCompleto;
        } else {
            saludo = "Buenas noches <i class='fa-solid fa-moon'></i>, " + nombreCompleto;
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
        <h3>¿Desea Cerrar la sesión?</h3>
      </div>

      <br>    

      <div class="d-flex justify-content-center align-items-center">
          <form action="?pagina=catalogo" method="POST" autocomplete="off">  <!-- Cerrar sesión-->
              <button type="submit" class="btn btn-primary btn-lg me-4" name="cerrar"> SI </button>
          </form> 
         <button type="button" class="btn btn-dark btn-lg me-4" data-bs-dismiss="modal">NO</button>
      </div>



      </div> <!-- FIN Modal contenido -->
      
    </div>
  </div>
</div>
