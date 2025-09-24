<?php  
session_start();

if (empty($_SESSION['id'])) {
    header('Location:?pagina=login');
    exit;
}
 if (!empty($_SESSION['id'])) {
    require_once 'verificarsession.php';
} 

if ($_SESSION["nivel_rol"] == 1) {
        header("Location: ?pagina=catalogo");
        exit();
    }/*  Validacion cliente  */

    
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
  $pagina_actual = isset($_GET['pagina']) ? $_GET['pagina'] : 'home';
  require_once 'vista/home.php';

?>