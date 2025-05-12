<?php
session_start();
$nombre = isset($_SESSION["nombre"]) && !empty($_SESSION["nombre"]) ? $_SESSION["nombre"] : "Estimado Cliente";
$apellido = isset($_SESSION["apellido"]) && !empty($_SESSION["apellido"]) ? $_SESSION["apellido"] : ""; 

$nombreCompleto = trim($nombre . " " . $apellido);

$sesion_activa = isset($_SESSION["id"]) && !empty($_SESSION["id"]);

$carrito = $_SESSION['carrito'] ?? [];
$total = 0;

if ($sesion_activa) {
    require_once 'vista/tienda/vercarrito.php';
} else {
   header('Location: ?pagina=catalogo');
}
