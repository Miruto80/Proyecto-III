<?php  
session_start();
if (empty($_SESSION["id"])) {
    header("location:?pagina=login");
    exit;
}

require_once __DIR__ . '/../modelo/pedidoweb.php';

$objPedidoWeb = new pedidoWeb();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Confirmar pedido
    if (isset($_POST['confirmar'])) {
        if (!empty($_POST['id_pedido'])) {
            $datosPeticion = [
                'operacion' => 'confirmar',
                'datos' => $_POST['id_pedido']
            ];

            $respuesta = $objPedidoWeb->procesarPedidoweb(json_encode($datosPeticion));
            echo json_encode($respuesta);
        } else {
            echo json_encode(['respuesta' => 0, 'mensaje' => 'Falta el ID del pedido para confirmar']);
        }

    // Eliminar pedido
    } else if (isset($_POST['eliminar'])) {
        if (!empty($_POST['id_pedido'])) {
            $datosPeticion = [
                'operacion' => 'eliminar',
                'datos' => $_POST['id_pedido']
            ];

            $respuesta = $objPedidoWeb->procesarPedidoweb(json_encode($datosPeticion));
            echo json_encode($respuesta);
        } else {
            echo json_encode(['respuesta' => 0, 'mensaje' => 'Falta el ID del pedido para eliminar']);
        }
    } else if  (!empty($_POST['id_pedido']) && isset($_POST['estado_delivery']) && isset($_POST['direccion'])) {
        $datosPeticion = [
            'operacion' => 'delivery',
            'datos' => [
                'id_pedido' => $_POST['id_pedido'],
                'estado_delivery' => $_POST['estado_delivery'],
                'direccion' => $_POST['direccion']
            ]
        
        ];
        $respuesta = $objPedidoWeb->procesarPedidoweb(json_encode($datosPeticion));
        echo json_encode($respuesta);
    } else {
        echo json_encode(['respuesta' => 0, 'mensaje' => 'Faltan datos para actualizar delivery']);
    }
    

    exit;
}



// Cargar vista con pedidos y detalles (GET)
$pedidos = $objPedidoWeb->consultarPedidosCompletos();
foreach ($pedidos as &$p) {
    $p['detalles'] = $objPedidoWeb->consultarDetallesPedido($p['id_pedido']);
}


if ($_SESSION["nivel_rol"] != 2 && $_SESSION["nivel_rol"] != 3) {
    header("Location: ?pagina=catalogo");
    exit();

}
/* $bitacora = [
        'id_persona' => $_SESSION["id"],
        'accion' => 'Acceso a Módulo',
        'descripcion' => 'módulo de PedidoWeb'
    ];
    $objPedidoWeb->registrarBitacora(json_encode($bitacora));*/
require_once 'vista/pedidoweb.php';
