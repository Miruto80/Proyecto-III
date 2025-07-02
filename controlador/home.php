<?php  
    session_start();
    if (empty($_SESSION["id"])){
      
      header("location:?pagina=login");
    } 


    require_once 'modelo/home.php';

$objhome = new home();

$registro = $objhome->consultarMasVendidos();

$totales = $objhome->consultarTotales();

$pendientes=$objhome->consultarTotalesPendientes();


if ($_SESSION["nivel_rol"] != 2 && $_SESSION["nivel_rol"] != 3) {
    header("Location: ?pagina=catalogo");
    exit();
}
  require_once 'vista/home.php';

?>