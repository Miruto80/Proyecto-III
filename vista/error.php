<!DOCTYPE html>
<html lang="en">
<head>
  
  <!-- php encabezado-->
  <?php include 'complementos/head.php' ?>
  <title>Error</title>

</head>
<body id="page-top">


<div style=" height: 100vh; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; padding: 20px;">
  
    <img src="assets/img/E2.svg" alt="Imagen de Error" style="margin-bottom: 20px; border-radius: 10px;" width="30%">

   
    <h1 style="font-size: 4rem; color:rgb(0, 0, 0); margin: 0;">ERROR 404</h1>
    <p style="font-size: 1.5rem; color:rgb(0, 0, 0); margin: 10px 0;">(Page Not Found) Página no encontrada</p>


    <a href="?pagina=home" style="padding: 10px 20px; font-size: 1.2rem; color: white; background-color:#d67888; border: none; border-radius: 5px; cursor: pointer; margin-top: 10px;">
        Volver Atrás
    </a>

 
    <div style="flex-grow: 1;"></div>


    <footer style="color:rgb(0, 0, 0); font-size: 0.9rem; margin-top: auto;">
        © 2025, ESTUDIANTE UPTAEB T3 - LOVEMAKEUP | Todos los derechos Reservados.
    </footer>
</div>


</body>
</html>






<div class="input-group">
          <span class="input-group-text t text-body dropdown-toggle card-m1" id="dropdownIcon" aria-expanded="false" style="cursor: pointer; padding: 12px;">
             <i class="fa-solid fa-user-gear" aria-hidden="true"></i>
         </span>
        
         <ul class="dropdown-menu div-oscuro-1" id="dropdownOptions" aria-labelledby="dropdownIcon">
            <li class="d-block d-md-none">
                <a class="dropdown-item text-dark texto-secundario">
                    <?php echo $_SESSION['nombre'] . " " . $_SESSION['apellido']; ?>
                </a>
            </li>  
            <li> 
                <a class="dropdown-item text-primary texto-secundario"><b><?php 
                echo "Rol:"." ".$_SESSION['nombre_usuario'];
                ?></b></a>
            </li>
            <li>
                <a class="dropdown-item texto-secundario" href="?pagina=datos">
                    <i class="fa-solid fa-user-pen me-2"></i> 
                    Modificar Datos
                </a>
            </li>
            <li>
                <button type="button" id="toggleModo" class="texto-secondario dropdown-item lk">
                <i class="fa-solid fa-moon me-2"></i> Modo Oscuro
                </button> 
            </li>
        </ul>