<?php 
require_once('modelo/conexion.php');
$objconex = new Conexion();

if (!empty($objconex->Conex()))
	{ echo
"Exito";
}else{
	echo $e->getMessage();
}
?>