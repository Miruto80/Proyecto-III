<?php  
     session_start();
     if (empty($_SESSION["iduser"])){
       header("location:?pagina=login");
     } /*  Validacion URL  */
     
     
  if (isset($_POST['cerrarolvido'])) {    
      session_destroy(); // Se cierra la sesión
      header('Location: ?pagina=login');
      exit;

} else  if (isset($_POST['validar'])) {    
      

} else {
    require_once 'vista/seguridad/olvidoclave.php';
}

?>