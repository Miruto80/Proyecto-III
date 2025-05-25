<?php
require_once '../modelo/verpedidoweb.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $detalle = [
        'id_pedido' => $_POST['id_pedido'],
        'id_producto' => $_POST['id_producto'],
        'cantidad' => $_POST['cantidad'],
        'precio_unitario' => $_POST['precio_unitario'],
       
    ];

    try {
        $venta = new VentaWeb();
        $venta->set_Detalles($detalle);
        $idDetalle = $venta->registrarDetalle();
       $venta->vaciarCarrito();

        echo json_encode([
            'success' => true,
            'id_detalle' => $idDetalle
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
}