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
    // Si se realiza una búsqueda, ignora categoría
    $registro = $catalogo->buscarProductos($_GET['busqueda']);
} elseif (isset($_GET['categoria'])) {
    // Si no hay búsqueda, pero sí categoría
    $registro = $catalogo->obtenerPorCategoria($_GET['categoria']);
} else {
    // Si no hay ni búsqueda ni categoría, muestra todo
    $registro = $catalogo->obtenerProductosActivos();
}

    // Verifica si la consulta está retornando productos
   



    // Si todo es correcto, carga la vista de catálogo
     require_once('vista/tienda/catalogo_producto.php');
    exit;

   


// Aquí se puede cargar otras vistas si no es 'catalogo'

?>
