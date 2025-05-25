<?php  
     session_start();
     if (empty($_SESSION["iduser"])){
       header("location:?pagina=login");
     } /*  Validacion URL  */
     
    require_once 'vista/seguridad/olvidoclave.php';

?>