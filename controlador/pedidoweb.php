<?php  
     session_start();
     if (empty($_SESSION["id"])){
       header("location:?pagina=login");
       exit;
     } 
     require_once __DIR__ . '/../modelo/pedidoweb.php';

       $pedido = new pedidoWeb();


       if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['accion']) && isset($_POST['id_pedido'])) {
            $id_pedido = $_POST['id_pedido'];
    
            if ($_POST['accion'] === 'confirmar') {
                $pedido->confirmarPedido($id_pedido);
                echo json_encode(['status' => 'ok', 'msg' => 'Pedido confirmado']);
                exit;
            }
    
            if ($_POST['accion'] === 'eliminar') {
                $pedido->eliminarPedido($id_pedido);
                echo json_encode(['status' => 'ok', 'msg' => 'Pedido eliminado']);
                exit;
            }
        }
    }
    
    $pedidos = $pedido->consultarPedidosCompletos();
       
     
       foreach ($pedidos as &$p) {
           $p['detalles'] = $pedido->consultarDetallesPedido($p['id_pedido']);
       }
       

      require_once 'vista/pedidoweb.php';

  

?>