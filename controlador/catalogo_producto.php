<?php  
session_start();
$nombre = isset($_SESSION["nombre"]) && !empty($_SESSION["nombre"]) ? $_SESSION["nombre"] : "Estimado Cliente";
$apellido = isset($_SESSION["apellido"]) && !empty($_SESSION["apellido"]) ? $_SESSION["apellido"] : ""; 

$nombreCompleto = trim($nombre . " " . $apellido);

$sesion_activa = isset($_SESSION["id"]) && !empty($_SESSION["id"]);

require_once 'modelo/catalogo.php';
require_once 'modelo/ListaDeseo.php';

$catalogo = new Catalogo();

$categorias = $catalogo->obtenerCategorias();


if (isset($_GET['busqueda']) && !empty(trim($_GET['busqueda']))) {
    
    $registro = $catalogo->buscarProductos($_GET['busqueda']);
} elseif (isset($_GET['categoria'])) {

    $registro = $catalogo->obtenerPorCategoria($_GET['categoria']);
} else {
   
    $registro = $catalogo->obtenerProductosActivos();
}

// Obtener lista de deseos del usuario para pasar a la vista
$idsProductosFavoritos = [];

if ($sesion_activa) {
    $objListaDeseo = new ListaDeseo();
    $lista = $objListaDeseo->obtenerListaDeseo($_SESSION['id']);
    $idsProductosFavoritos = array_column($lista, 'id_producto');
}

     require_once('vista/tienda/catalogo_producto.php');
    exit;
?>
