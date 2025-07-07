<?php  
    session_start();
    if (empty($_SESSION["id"])){
      
      header("location:?pagina=login");
    } 


    require_once 'modelo/home.php';
     require_once 'modelo/reporte.php';
 require_once 'permiso.php';

$objhome = new home();

$registro = $objhome->consultarMasVendidos();

$totales = $objhome->consultarTotales();

$pendientes=$objhome->consultarTotalesPendientes();

$graficaHome = Reporte::graficaVentaTop5(); 


if ($_SESSION["nivel_rol"] != 2 && $_SESSION["nivel_rol"] != 3) {
    header("Location: ?pagina=catalogo");
    exit();
}
  require_once 'vista/home.php';

?>