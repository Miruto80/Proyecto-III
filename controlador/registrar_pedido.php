<?php
session_start();
require_once '../modelo/verpedidoweb.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $venta = new VentaWeb();

    $datos = [
        'referencia_bancaria' => $_POST['referencia_bancaria'],
        'telefono_emisor' => $_POST['telefono_emisor'],
        'banco' => $_POST['banco'],
        'id_metodopago' => $_POST['id_metodopago'],
        'id_entrega' => $_POST['id_entrega'],
        'id_persona' => $_SESSION['id'],
        'estado' => $_POST['estado'],
        'precio_total' => $_POST['precio_total'],
        'tipo' => $_POST['tipo'],
    ];

    $venta = new VentaWeb();
    $venta->set_Datos($_POST);
    $id_pedido = $venta->registrarPedido();

   

    if ($id_pedido) {
        echo json_encode(['success' => true, 'id_pedido' => $id_pedido]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al registrar pedido']);
    }

   
}
