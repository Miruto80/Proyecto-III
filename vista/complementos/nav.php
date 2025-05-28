
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
        <div class="ms-md-auto pe-md-3 d-flex align-items-center">
         <!-- Cuadro de notificaciones -->
         <a href="?pagina=notificacion" class="notification-icon me-2" style="background-color: white; padding: 8px; border-radius: 12px; text-decoration: none;">
            <i class="fa-solid fa-bell" style="color: black;"></i>
        </a>
        <button class="btn btn-info" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarAyuda" aria-controls="sidebarAyuda">
    <i class="fa-solid fa-circle-question"></i>
</button>

        
        <div class="input-group">
          <span class="input-group-text text-body dropdown-toggle" id="dropdownIcon" aria-expanded="false" style="cursor: pointer;">
             <i class="fa-solid fa-user-gear" aria-hidden="true"></i>
         </span>
        <ul class="dropdown-menu" id="dropdownOptions" aria-labelledby="dropdownIcon">
           <li> 
             <a class="dropdown-item text-primary"><b><?php 
               echo "Rol:"." ".$_SESSION['nombre_usuario'];
             ?></b></a>
           </li>
           <li><a class="dropdown-item" href="?pagina=datos"><i class="fa-solid fa-user-pen"></i> Modificar Datos</a></li>
        </ul>
      <div class="nombre-usuario">
         <?php 
           echo $_SESSION['nombre']." ".$_SESSION['apellido'];
         ?>
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
                        Ve a la sección de configuración y selecciona "Restablecer contraseña".
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
                        Puedes enviarnos un mensaje desde la página de contacto.
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
            Accede al panel de administración, busca el pedido en "Órdenes pendientes" y sigue el proceso de confirmación o actualización del estado.
        </div>
    </div>
</div>
<div class="accordion-item">
    <h2 class="accordion-header">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq7">
            ¿Cómo actualizar el inventario?
        </button>
    </h2>
    <div id="faq7" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
        <div class="accordion-body">
            En el sistema de inventario, selecciona el producto y actualiza la cantidad disponible según los últimos ingresos o ventas.
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
            Verifica el estado del pago en la pasarela de pagos y contacta al cliente para resolver cualquier inconveniente.
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
            Accede al módulo de gestión de productos y edita detalles como precio, descripción y disponibilidad.
        </div>
    </div>
</div>
<div class="accordion-item">
    <h2 class="accordion-header">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq17">
            ¿Cómo gestionar reportes de clientes?
        </button>
    </h2>
    <div id="faq17" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
        <div class="accordion-body">
            Registra los reportes en el sistema y escala los casos según su nivel de prioridad para una resolución rápida.
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
            Ve al módulo "Compra", haz clic en "Nueva compra", ingresa los datos del proveedor y los productos adquiridos.
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
            Accede al módulo "Venta", selecciona al cliente, los productos vendidos y guarda el registro de la transacción.
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
            Dirígete a "Reserva", selecciona al cliente y los productos a reservar, y confirma la reserva con fecha y duración.
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
            En el módulo "Proveedor", haz clic en "Nuevo proveedor", completa los datos de contacto y guarda.
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
            Dentro de "Producto", selecciona uno y edita su información. En la sección de categoría, elige la más adecuada y guarda.
        </div>
    </div>
</div>

<div class="accordion-item">
    <h2 class="accordion-header">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq24">
            ¿Cómo se registran nuevos cliente?
        </button>
    </h2>
    <div id="faq24" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
        <div class="accordion-body">
            Los clientes se registran en la parte del iniciar sesion y registrase
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