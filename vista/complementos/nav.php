
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
        <div class="ms-md-auto pe-md-3 d-flex align-items-center">

         <!-- Cuadro de notificaciones -->
         <a href="?pagina=notificacion" class="notification-icon me-2" style="background-color: white; padding: 8px; border-radius: 12px; text-decoration: none;">
            <i class="fa-solid fa-bell" style="color: black;"></i>
        </a>
        
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
    <!--|||||||||||||||||||||||||||||||||| End Navbar||||||||||||||||||||||||||||||||| -->
