<?php  
     session_start();
     if (empty($_SESSION["id"])){
       header("location:?pagina=login");
     } /*  Validacion URL  */
    
    
    if (isset($_POST['entrar'])){
     require 'vista/seguridad/bitacora.php';
    }
    else{
        require_once 'vista/bitacora.php';
    }

?>