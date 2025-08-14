<?php  
session_start();
$nombre = isset($_SESSION["nombre"]) && !empty($_SESSION["nombre"]) ? $_SESSION["nombre"] : "Estimado Cliente";
$apellido = isset($_SESSION["apellido"]) && !empty($_SESSION["apellido"]) ? $_SESSION["apellido"] : ""; 

$nombreCompleto = trim($nombre . " " . $apellido);

$sesion_activa = isset($_SESSION["id"]) && !empty($_SESSION["id"]);

if (!empty($_SESSION['id'])) {
    require_once 'verificarsession.php';
}

require_once 'modelo/catalogo_favorito.php';

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
     if($_SESSION["nivel_rol"] == 1) { 
       require_once('vista/tienda/catalogo_favorito.php');
    } else{
        header('Location: ?pagina=catalogo');
    }   
} else {
   header('Location: ?pagina=catalogo');
}

   


// Aquí se puede cargar otras vistas si no es 'catalogo'

?>
