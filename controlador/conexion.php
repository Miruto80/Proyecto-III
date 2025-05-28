<?php 
require_once('modelo/conexion.php');
$objconex = new Conexion();

// Verificar la conexión a la primera base de datos
if (!empty($objconex->getConex1())) {
    echo "Éxito: Conectado a la primera base de datos <br>";
} else {
    echo "Error al conectar la primera base de datos <br>";
}

// Verificar la conexión a la segunda base de datos
if (!empty($objconex->getConex2())) {
    echo "Éxito: Conectado a la segunda base de datos <br>";
} else {
    echo "Error al conectar la segunda base de datos <br>";
}
?>

