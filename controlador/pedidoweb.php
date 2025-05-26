<?php  
     session_start();
     if (empty($_SESSION["id"])){
       header("location:?pagina=login");
       exit;
     } 
       require_once 'modelo/pedidoweb.php';

       $pedido = new PedidoWeb();
       $pedidos = $pedido->consultarPedidosCompletos();
       
     
       foreach ($pedidos as &$p) {
           $p['detalles'] = $pedido->consultarDetallesPedido($p['id_pedido']);
       }
       

      require_once 'vista/pedidoweb.php';

  

?>