<?php  
session_start();
$nombre = isset($_SESSION["nombre"]) && !empty($_SESSION["nombre"]) ? $_SESSION["nombre"] : "Estimado Cliente";
$apellido = isset($_SESSION["apellido"]) && !empty($_SESSION["apellido"]) ? $_SESSION["apellido"] : ""; 

$nombreCompleto = trim($nombre . " " . $apellido);

$sesion_activa = isset($_SESSION["id"]) && !empty($_SESSION["id"]);

if (!empty($_SESSION['id'])) {
    require_once 'verificarsession.php';
}

if (empty($_SESSION["id"])){
    header("location:?pagina=login");
    exit;
  } else{

    require_once 'modelo/catalogo_pedido.php';

    $pedido = new Catalogopedido();
    $id_persona = $_SESSION["id"]; 
    $pedidos = $pedido->consultarPedidosCompletosCatalogo($id_persona);

    foreach ($pedidos as &$p) {
        $p['detalles'] = $pedido->consultarDetallesPedidoCatalogo($p['id_pedido']);
    }

    require_once 'vista/tienda/catalogo_pedido.php';
}


?>
