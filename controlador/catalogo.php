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


 if (isset($_POST['cerrar'])) {
    
    session_destroy(); // Se cierra la sesión
    header('Location: ?pagina=catalogo');
    exit;
} else {
    // Si todo es correcto, carga la vista de catálogo
     require_once('vista/tienda/'.$pagina.'.php');
    exit;
}   
   


// Aquí se puede cargar otras vistas si no es 'catalogo'

?>
