<?php  
     session_start();
     if (empty($_SESSION["id"])){
       header("location:?pagina=login");
     } /*  Validacion URL  */
     
    require_once 'vista/metodoenvio.php';

?>