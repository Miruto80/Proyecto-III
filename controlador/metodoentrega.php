<?php  
session_start();
if (empty($_SESSION["id"])) {
    header("location:?pagina=login");
} // Validación de sesión

require_once __DIR__ . '/../modelo/metodoentrega.php';

$objEntrega = new metodoentrega();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion']) && isset($_POST['datos'])) {
        $datosPeticion = [
            'operacion' => $_POST['accion'],
            'datos' => $_POST['datos'] ?? null
        ];

        $respuesta = $objEntrega->procesarMetodoEntrega(json_encode($datosPeticion));
        echo json_encode($respuesta);
       
    } 
    exit;
}
    $metodos = $objEntrega->consultar();
require_once __DIR__ . '/../vista/metodoentrega.php';



?>