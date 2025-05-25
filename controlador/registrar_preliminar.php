<?php
require_once '../modelo/verpedidoweb.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $venta = new VentaWeb();

        $datos = [
            'id_detalle' => $_POST['id_detalle'] ?? null,
            'condicion' => $_POST['condicion'] ?? 'pedido'
        ];

        if (!$datos['id_detalle']) {
            throw new Exception("Falta el id_detalle");
        }

        $venta->set_Datos($datos);
        $id_preliminar = $venta->registrarPreliminar();

        echo json_encode([
            'success' => true,
            'id_preliminar' => $id_preliminar
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido'
    ]);
}
