<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../assets/phpmailer/src/Exception.php';
require_once __DIR__ . '/../assets/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/../assets/phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

require_once '/home/u417714339/domains/lovemakeuptienda.com/public_html/modelo/pedidoWeb.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pedido = $_POST['id_pedido'] ?? null;
    $tracking = $_POST['tracking'] ?? null;

    $correo_cliente = $_POST['correo_cliente'] ?? 'cliente@correo.com';
    $nombre_cliente = $_POST['nombre_cliente'] ?? 'Cliente';

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
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}
