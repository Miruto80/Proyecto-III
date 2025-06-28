<?php  
     session_start();
     if (empty($_SESSION["id"])){
       header("location:?pagina=login");
       exit;
     } 
     require_once __DIR__ . '/../modelo/pedidoweb.php';

       $objpedido = new pedidoWeb();

      

       if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['accion']) && isset($_POST['id_pedido'])) {
                $datosPeticion = [
                    'operacion' => $_POST['accion'],
                    'datos' => $_POST['id_pedido']
                ];

                $resultado = $objpedido->procesarPedidoweb(json_encode($datosPeticion));
                echo json_encode($resultado);
        }
        exit;
    }
    
    $pedidos = $objpedido->consultarPedidosCompletos();
       foreach ($pedidos as &$p) {
           $p['detalles'] = $objpedido->consultarDetallesPedido($p['id_pedido']);
       }

      require_once 'vista/pedidoweb.php';

  

?>