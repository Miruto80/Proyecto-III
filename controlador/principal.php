<?php
// Se incluye el archivo del modelo principal
require_once('modelo/principal.php');

// Verifica si el archivo de vista correspondiente a la página existe
if(is_file("vista/".$pagina.".php")){
    // Se crea una instancia de la clase principal
    $conteos = new principal();
    // Se obtienen los datos de conteos
    $datos = $conteos->obtenerConteos();
    // Se incluye el archivo de vista
    require_once("vista/".$pagina.".php");
} else {
    // Si el archivo de vista no existe, se muestra un error 404
    echo "ERROR 404";
}
?>