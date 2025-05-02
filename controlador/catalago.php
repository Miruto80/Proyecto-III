<?php  
require_once 'modelo/catalogo.php';

$catalogo = new Catalogo();


    if (isset($_GET['categoria'])) {
        // Si se pasa una categoría, se filtra por esa categoría
        $registro = $catalogo->obtenerPorCategoria($_GET['categoria']);
    } else {
        // Si no se pasa categoría, se obtienen los productos activos
        $registro = $catalogo->obtenerProductosActivos();
    }

    // Verifica si la consulta está retornando productos
    
    // Si todo es correcto, carga la vista de catálogo
    require_once('vista/'.$pagina.'.php');
    exit;


// Aquí se puede cargar otras vistas si no es 'catalogo'

?>
