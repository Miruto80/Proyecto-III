<?php  
    
    


    if (isset($_POST['entrar'])){
     require 'vista/seguridad/bitacora.php';
    }
    else{
        require_once 'vista/bitacora.php';
    }

?>