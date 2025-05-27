<?php  
    session_start();
    if (empty($_SESSION["id"])){
      header("location:?pagina=login");
    } /*  Validacion URL  */
    require_once 'modelo/home.php';

$objhome = new home();

$registro = $objhome->consultarMasVendidos();
   require_once 'vista/home.php';

?>