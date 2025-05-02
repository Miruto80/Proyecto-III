<footer class="footer pt-3  ">
        <div class="container-fluid">
          <div class="row align-items-center justify-content-lg-between">
            <div class="col-lg-8 mb-lg-0 mb-4">
              <div class="copyright text-center text-sm text-muted text-lg-start">
                © 2025,
                ESTUDIANTE UPTAEB T3 - LOVEMAKEUP | Todos los derechos Revervados.
              </div>
            </div>
            
          </div>
        </div>
  </footer>
     <!--|||||||||||||||||||||||||||||||||| End Navbar||||||||||||||||||||||||||||||||| --></div>
  </main>

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
          <form action="?pagina=login" method="POST" autocomplete="off">  <!-- Cerrar sesión-->
              <button type="submit" class="btn btn-success btn-lg me-4" name="cerrar"> SI </button>
          </form> 
         <button type="button" class="btn btn-danger  btn-lg ms-4" data-bs-dismiss="modal">NO</button>
      </div>



      </div> <!-- FIN Modal contenido -->
      
    </div>
  </div>
</div>

  
  <!--   Core JS Files   -->
  <script src="assets/js/core/popper.min.js"></script>
  <script src="assets/js/core/bootstrap.min.js"></script>
  <script src="assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="assets/js/plugins/smooth-scrollbar.min.js"></script>

  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="assets/js/argon-dashboard.min.js?v=2.1.0"></script>


<script>
  // Obtener el ícono y el menú desplegable
const dropdownIcon = document.getElementById('dropdownIcon');
const dropdownMenu = document.getElementById('dropdownOptions');

// Escuchar el evento de clic en el ícono
dropdownIcon.addEventListener('click', () => {
  const isExpanded = dropdownIcon.getAttribute('aria-expanded') === 'true';
  dropdownIcon.setAttribute('aria-expanded', !isExpanded); // Cambiar el estado
  dropdownMenu.classList.toggle('show'); // Mostrar/ocultar el menú
});

// Cerrar el menú si se hace clic fuera de él
document.addEventListener('click', (event) => {
  if (!dropdownIcon.contains(event.target) && !dropdownMenu.contains(event.target)) {
    dropdownMenu.classList.remove('show');
    dropdownIcon.setAttribute('aria-expanded', 'false');
  }
});
</script>
