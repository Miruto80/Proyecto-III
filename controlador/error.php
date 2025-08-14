<?php  
     session_start();
     if (empty($_SESSION["id"])){
       header("location:?pagina=login");
     } /*  Validacion URL  */
     if (!empty($_SESSION['id'])) {
        require_once 'verificarsession.php';
    } 
    require_once 'vista/error.php';

?>