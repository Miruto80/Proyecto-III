<?php
require '../assets/phpmailer/src/Exception.php';
require '../assets/phpmailer/src/PHPMailer.php';
require '../assets/phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

require_once __DIR__ . '/../modelo/pedidoWeb.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pedido = $_POST['id_pedido'] ?? null;
    $tracking = $_POST['tracking'] ?? null;

    // Aquí podrías consultar los datos del cliente asociados al pedido
    // Simulamos los valores por ahora, pero idealmente esto vendría de la DB
    // Ejemplo: consultarPedidoPorId($id_pedido)
    $correo_cliente = $_POST['correo_cliente'] ?? 'cliente@correo.com';  // Idealmente deberías obtener esto en tiempo real desde la base de datos
    $nombre_cliente = $_POST['nombre_cliente'] ?? 'Cliente';             // Lo mismo aquí

    if ($id_pedido && $tracking) {
        $modelo = new pedidoWeb();

     $respuesta = $modelo->procesarPedidoweb(json_encode([
    'operacion' => 'tracking',
    'datos' => [
        'id_pedido' => $id_pedido,
        'tracking' => $tracking,
        'correo_cliente' => $correo_cliente,
        'nombre_cliente' => $nombre_cliente
    ]
]));

        echo json_encode(['success' => $respuesta['respuesta'] == 1, 'message' => $respuesta['msg']]);
        exit;
    } else {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
        exit;
    }
}