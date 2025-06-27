
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
        <div class="ms-md-auto pe-md-3 d-flex align-items-center">

          <a class="notification me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarAyuda" aria-controls="sidebarAyuda" style="background-color: white; padding: 8px; border-radius: 12px; text-decoration: none;">
            <i class="fa-solid fa-circle-question" style="color: #004adf;"></i>
         </a>

         <!-- Cuadro de notificaciones -->
         <a href="?pagina=notificacion" class="notification-icon me-2" style="background-color: white; padding: 8px; border-radius: 12px; text-decoration: none;">
            <i class="fa-solid fa-bell" style="color: black;"></i>
      
        </a>
        

        
        <div class="input-group">
          <span class="input-group-text text-body dropdown-toggle" id="dropdownIcon" aria-expanded="false" style="cursor: pointer;">
             <i class="fa-solid fa-user-gear" aria-hidden="true"></i>
         </span>
        <ul class="dropdown-menu" id="dropdownOptions" aria-labelledby="dropdownIcon">
         <li class="d-block d-md-none">
      <a class="dropdown-item text-dark">
        <?php echo $_SESSION['nombre'] . " " . $_SESSION['apellido']; ?>
      </a>
    </li>  
        <li> 
             <a class="dropdown-item text-primary"><b><?php 
               echo "Rol:"." ".$_SESSION['nombre_usuario'];
             ?></b></a>
           </li>
           <li><a class="dropdown-item" href="?pagina=datos"><i class="fa-solid fa-user-pen"></i> Modificar Datos</a></li>
        </ul>
                    <!-- Solo el nombre en móviles 
            <div class="nombre-usuario d-block d-md-none">
              <?php /* echo $_SESSION['nombre'];*/ ?>
            </div>
                -->
           
            <div class="nombre-usuario d-none d-md-block">
                <?php echo $_SESSION['nombre'] . " " . $_SESSION['apellido']; ?>
            </div>

        </div>

      </div>
          <ul class="navbar-nav  justify-content-end">
            <li class="nav-item d-flex align-items-center">
              <a href="#" class="nav-link text-white font-weight-bold px-0" data-bs-toggle="modal" data-bs-target="#cerrar">
          
              <i class="fa-solid fa-right-to-bracket me-sm-1"></i>  
                <span class="d-sm-inline d-none">Cerrar Session</span>
              </a>
            </li>
          </ul>
          <ul class="navbar-nav  justify-content-end">
        <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-white p-0" id="iconNavbarSidenav">
                <div class="sidenav-toggler-inner">
                  <i class="sidenav-toggler-line bg-white"></i>
                  <i class="sidenav-toggler-line bg-white"></i>
                  <i class="sidenav-toggler-line bg-white"></i>
                </div>
              </a>
        </li>
        </ul>
        </div>
      </div>
    </nav>


    
    <div class="offcanvas offcanvas-end" tabindex="-1" id="sidebarAyuda" aria-labelledby="sidebarAyudaLabel">
    <div class="offcanvas-header">
        <h5 id="sidebarAyudaLabel">Ayuda Lovemakeup</h5>
        <button type="button" class="btn-close bg-danger p-2 rounded" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <!-- Buscador -->
        <div class="mb-3">
        <input type="text" class="form-control" id="searchInput" placeholder="Buscar en ayuda...">
        </div>

        <!-- Acordeón con preguntas frecuentes -->
        <div class="accordion" id="faqAccordion">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                        ¿Cómo restablecer mi contraseña?
                    </button>
                </h2>
                <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Dirijase al inicio de sesion y oprima en "Restablecer contraseña" y siga los pasos. 
                        Ingrese su numero de cedula y oprima el boton de "Validar" luego
                        coloque el correo con el que se ingreso y dele a "Continuar" a partir de alli
                        espere a que le llegue un codigo a su correo ya ingresado,
                        por ultimo ingrese el codigo de verificacion que se le envio al correo y dele a "Contiuar".
                        
                        Felicidades! Ha completado los pasos, ahora puede iniciar sesion con su nueva contraseña.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                        ¿Cómo contactar soporte?
                    </button>
                </h2>
                <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Puedes enviarnos un mensaje al siguiente contacto: miguelfernando8012@gmail.com.
                    </div>
                </div>
            </div>
            <div class="accordion-item">
    <h2 class="accordion-header">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq6">
            ¿Cómo gestionar un pedido pendiente?
        </button>
    </h2>
    <div id="faq6" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
        <div class="accordion-body">
            Accede al modulo de Pedido Web, busca el pedido que se encuentre en estado
            "Pendiente" y oprima el boton de color verde para validar el pedido.
        </div>
    </div>
</div>
<div class="accordion-item">
    <h2 class="accordion-header">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq7">
            ¿Cómo gestionar una venta pendiente?
        </button>
    </h2>
    <div id="faq7" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
        <div class="accordion-body">
            Accede al modulo de Venta, busque la compra que se encuentre en estado
            "Pendiente". Oprima el boton de modificar y modifique el estado de la venta.
        </div>
    </div>
</div>

<div class="accordion-item">
    <h2 class="accordion-header">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq12">
            ¿Cómo manejar pedidos con problemas de pago?
        </button>
    </h2>
    <div id="faq12" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
        <div class="accordion-body">
            Verifique el estado del pago y contacte al cliente para resolver cualquier inconveniente.
            En caso de haber ocurrido un problema puede dirijirse al modulo de Clientes y actualizar su estatus en "Acccion" para mas precaucion. 
        </div>
    </div>
</div>
<div class="accordion-item">
    <h2 class="accordion-header">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq13">
            ¿Cómo actualizar información de productos?
        </button>
    </h2>
    <div id="faq13" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
        <div class="accordion-body">
            Acceda al módulo de gestión de productos y edite detalles como precio, descripción y disponibilidad.
        </div>
    </div>
</div>
<div class="accordion-item">
    <h2 class="accordion-header">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq17">
            ¿Cómo gestionar un reporte?
        </button>
    </h2>
    <div id="faq17" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
        <div class="accordion-body">
            Oprima el boton de "Generar Reporte" del modulo en el que se encuentre y lo llevara a una nueva pestaña donde se generara un reporte estadistico que podra descargar como PDF.
        </div>
    </div>
</div>
<!-- Nuevos acordeones para la ayuda -->
<div class="accordion-item">
    <h2 class="accordion-header">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq18">
            ¿Cómo registrar una nueva compra?
        </button>
    </h2>
    <div id="faq18" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
        <div class="accordion-body">
            Ve al módulo "Compra", haz clic en "Registrar Compra", ingresa los datos del proveedor y los productos adquiridos.
        </div>
    </div>
</div>

<div class="accordion-item">
    <h2 class="accordion-header">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq19">
            ¿Cómo agregar un nuevo producto?
        </button>
    </h2>
    <div id="faq19" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
        <div class="accordion-body">
            Ingresa al módulo "Producto", haz clic en "Agregar producto", completa los campos requeridos y guarda los cambios.
        </div>
    </div>
</div>

<div class="accordion-item">
    <h2 class="accordion-header">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq20">
            ¿Cómo registrar una venta?
        </button>
    </h2>
    <div id="faq20" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
        <div class="accordion-body">
            Acceda al módulo "Venta", seleccione al cliente y verifique su cedula, tambien puede registrarlo y 
            cambiar el estatus a aprovado o rechazado para confirmar la venta, rellene los campos requeridos 
            y luego guarde el registro.
        </div>
    </div>
</div>

<div class="accordion-item">
    <h2 class="accordion-header">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq21">
            ¿Cómo hacer una reserva?
        </button>
    </h2>
    <div id="faq21" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
        <div class="accordion-body">
            Dirígete a "Reserva", selecciona al cliente y los productos a reservar, y confirma la reserva con la decha del dia fecha.
        </div>
    </div>
</div>

<div class="accordion-item">
    <h2 class="accordion-header">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq22">
            ¿Cómo registrar un proveedor?
        </button>
    </h2>
    <div id="faq22" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
        <div class="accordion-body">
            Dirijase al módulo "Proveedor", haga clic en "Registrar" y complete los datos de contacto.
        </div>
    </div>
</div>

<div class="accordion-item">
    <h2 class="accordion-header">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq23">
            ¿Cómo asignar una categoría a un producto?
        </button>
    </h2>
    <div id="faq23" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
        <div class="accordion-body">
            Dentro de "Producto", a la hora de registrar un producto tiene un campo para asignarle una categoria a dicho producto, tambien puede seleccionar alguno ya registrado y edita su informacion cambiandolo de categoria si es necesario.
        </div>
    </div>
</div>

<div class="accordion-item">
    <h2 class="accordion-header">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq24">
            ¿Cómo se registran nuevos clientes?
        </button>
    </h2>
    <div id="faq24" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
        <div class="accordion-body">
            Los clientes se registran en la parte del iniciar sesion en el boton de "Registrase".
            Tambien se pueden registrar desde el modulo de Venta si se necesita.
        </div>
    </div>
</div>


<div class="accordion-item">
    <h2 class="accordion-header">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq25">
            ¿Cómo agregar un producto al catalogo?
        </button>
    </h2>
    <div id="faq25" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
        <div class="accordion-body">
            Al registrar un producto en el modulo "Producto" se muestra automaticamente en el catalogo y lo puede visualizar junto a los demas productos en el boton "Ver todos los Productos".
        </div>
    </div>
</div>

        </div>
    </div>
</div>
<script>
document.getElementById('searchInput').addEventListener('keyup', function() {
    let filter = this.value.toLowerCase();
    let items = document.querySelectorAll('.accordion-item');

    items.forEach(function(item) {
        let buttonText = item.querySelector('.accordion-button').textContent.toLowerCase();
        if (buttonText.includes(filter)) {
            item.style.display = 'block'; // Mostrar si coincide
        } else {
            item.style.display = 'none'; // Ocultar si no coincide
        }
    });
});
</script>


    <!--|||||||||||||||||||||||||||||||||| End Navbar||||||||||||||||||||||||||||||||| -->