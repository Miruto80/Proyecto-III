<footer class="footer pt-3 text-center">
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-12">
        <div class="copyright text-center text-muted texto-secundario">
          © 2025, ESTUDIANTE UPTAEB T3-3113 | LOVEMAKEUP C.A | Todos los derechos Reservados.
        </div>
      </div>
    </div>
  </div>
</footer>

  <br><br>
     <!--|||||||||||||||||||||||||||||||||| End Navbar||||||||||||||||||||||||||||||||| --></div>
  </main>

<!-- Modal -->
<div class="modal fade" id="cerrar" tabindex="1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      
      <div class="modal-body bg-s"> <!-- Modal contenido -->
      
      <div class="d-flex justify-content-center mp-3 align-items-center">
          <img src="assets/img/integoracion.png" width="35%">
      </div>
      <div class="d-flex justify-content-center align-items-center">
        <h3 class="texto-secundario">¿Desea Cerrar la sesión?</h3>
      </div>

      <br>    

      <div class="d-flex justify-content-center align-items-center">
          <form action="?pagina=login" method="POST" autocomplete="off">  <!-- Cerrar sesión-->
              <button type="submit" class="btn btn-success btn-lg me-4" name="cerrar"> SI </button>
          </form> 
         <button type="button" class="btn btn-danger  btn-lg ms-4" data-bs-dismiss="modal">NO</button>
      </div>



      </div> <!-- FIN Modal contenido -->
      
    </div>
  </div>
</div>
<button id="scrollToTopBtn" title="Ir al inicio">
  <i class="fa fa-arrow-up"></i>
</button>

  
  <!--   Core JS Files   -->
  <script src="assets/js/core/popper.min.js"></script>
  <script src="assets/js/core/bootstrap.min.js"></script>
  <script src="assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="assets/js/plugins/smooth-scrollbar.min.js"></script>

  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="assets/js/argon-dashboard.min.js?v=2.1.0"></script>

      <script>
        
      const dropdownIcon = document.getElementById('dropdownIcon');
      const dropdownMenu = document.getElementById('dropdownOptions');

      dropdownIcon.addEventListener('click', () => {
        const isExpanded = dropdownIcon.getAttribute('aria-expanded') === 'true';
        dropdownIcon.setAttribute('aria-expanded', !isExpanded); 
        dropdownMenu.classList.toggle('show'); 
      });

      document.addEventListener('click', (event) => {
        if (!dropdownIcon.contains(event.target) && !dropdownMenu.contains(event.target)) {
          dropdownMenu.classList.remove('show');
          dropdownIcon.setAttribute('aria-expanded', 'false');
        }
      });

      // Mostrar u ocultar el botón según el scroll
      window.addEventListener("scroll", function () {
        const btn = document.getElementById("scrollToTopBtn");
        if (window.scrollY > 150) {
          btn.style.display = "block";
        } else {
          btn.style.display = "none";
        }
      });

      // Volver al inicio al hacer clic
      document.getElementById("scrollToTopBtn").addEventListener("click", function () {
        window.scrollTo({ top: 0, behavior: "smooth" });
      });
      </script>
