<?php  
session_start();
$nombre = isset($_SESSION["nombre"]) && !empty($_SESSION["nombre"]) ? $_SESSION["nombre"] : "Estimado Cliente";
$apellido = isset($_SESSION["apellido"]) && !empty($_SESSION["apellido"]) ? $_SESSION["apellido"] : ""; 

$nombreCompleto = trim($nombre . " " . $apellido);

$sesion_activa = isset($_SESSION["id"]) && !empty($_SESSION["id"]);

require_once 'modelo/catalogo_producto.php';

$catalogo = new Catalogo();

$categorias = $catalogo->obtenerCategorias();


if (isset($_GET['busqueda']) && !empty(trim($_GET['busqueda']))) {
    
    $registro = $catalogo->buscarProductos($_GET['busqueda']);
} elseif (isset($_GET['categoria'])) {

    $registro = $catalogo->obtenerPorCategoria($_GET['categoria']);
} else {
   
    $registro = $catalogo->obtenerProductosActivos();
}

     require_once('vista/tienda/catalogo_producto.php');
    exit;
?>
