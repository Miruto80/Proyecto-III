<?php  
session_start();
$sesion_activa = isset($_SESSION["id"]) && !empty($_SESSION["id"]);

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
