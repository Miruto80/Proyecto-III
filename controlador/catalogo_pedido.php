<?php  
session_start();
$nombre = isset($_SESSION["nombre"]) && !empty($_SESSION["nombre"]) ? $_SESSION["nombre"] : "Estimado Cliente";
$apellido = isset($_SESSION["apellido"]) && !empty($_SESSION["apellido"]) ? $_SESSION["apellido"] : ""; 

$nombreCompleto = trim($nombre . " " . $apellido);

$sesion_activa = isset($_SESSION["id"]) && !empty($_SESSION["id"]);

require_once 'modelo/catalogo.php';

$catalogo = new Catalogo();

$categorias = $catalogo->obtenerCategorias();


    if (isset($_GET['categoria'])) {
        // Si se pasa una categoría, se filtra por esa categoría
        $registro = $catalogo->obtenerPorCategoria($_GET['categoria']);
    } else {
        // Si no se pasa categoría, se obtienen los productos activos
        $registro = $catalogo->obtenerProductosActivos();
    }

    // Verifica si la consulta está retornando productos
   

if ($sesion_activa) {
    require_once('vista/tienda/catalogo_pedido.php');
} else {
   header('Location: ?pagina=catalogo');
}


?>
