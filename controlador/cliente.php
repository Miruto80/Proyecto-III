<?php  
     session_start();
     if (empty($_SESSION["id"])){
       header("location:?pagina=login");
     } /*  Validacion URL  */

     
     if($_SESSION["nivel_rol"] == 3) { // Validacion si es administrador entra
      require_once 'vista/cliente.php';
     }else{
      require_once 'vista/seguridad/privilegio.php';
  }

?>