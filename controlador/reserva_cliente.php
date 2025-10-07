<?php
session_start();
require_once __DIR__ . '/../modelo/reserva_cliente.php';

$nombre = isset($_SESSION["nombre"]) && !empty($_SESSION["nombre"]) ? $_SESSION["nombre"] : "Estimado Cliente";
$apellido = isset($_SESSION["apellido"]) && !empty($_SESSION["apellido"]) ? $_SESSION["apellido"] : ""; 
$nombreCompleto = trim("$nombre $apellido");

$sesion_activa = isset($_SESSION["id"]) && !empty($_SESSION["id"]);

if (!$sesion_activa) {
    header("Location:?pagina=login");
    exit;
}

$reserva = new ReservaCliente();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['continuar_pago'])) {
    header('Content-Type: application/json');

    // Validar carrito
    if (empty($_SESSION['carrito'])) {
        echo json_encode(['success'=>false,'message'=>'El carrito está vacío.']);
        exit;
    }

    // Construir datos de reserva/pedido
    $datos = [
        'operacion' => 'registrar_reserva',
        'datos' => [
            'id_persona'          => $_SESSION['id'],
            'tipo'                => '3',
            'estado'              => '1',
            'precio_total_usd'    => $_POST['precio_total_usd'] ?? '0',
            'precio_total_bs'     => $_POST['precio_total_bs'] ?? '0',
            'id_metodopago'       => $_POST['id_metodopago'] ?? '',
            'referencia_bancaria' => $_POST['referencia_bancaria'] ?? '',
            'telefono_emisor'     => $_POST['telefono_emisor'] ?? '',
            'banco'               => $_POST['banco'] ?? '',
            'banco_destino'       => $_POST['banco_destino'] ?? '',
            'monto'               => $_POST['precio_total_bs'] ?? null,
            'monto_usd'           => $_POST['precio_total_usd'] ?? null,
            'imagen'              => '', // se setea abajo
            'carrito'             => $_SESSION['carrito'],
        ]
    ];

    // Manejo de imagen
    if (!empty($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
        $name = uniqid('img_') . ".$ext";
        $dest = __DIR__ . '/../assets/img/captures/' . $name;
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $dest)) {
            $datos['datos']['imagen'] = 'assets/img/captures/' . $name;
        }
    }

    // Procesar reserva
    $res = $reserva->procesarReserva(json_encode($datos));

    if ($res['success'] && isset($res['id_pedido'])) {
        unset($_SESSION['carrito']); // limpiar carrito
        echo json_encode([
            'success'  => true,
            'message'  => 'Pago realizado en espera de verificación.',
            'redirect' => '?pagina=confirmacion&id=' . $res['id_pedido']
        ]);
    } else {
        echo json_encode(['success'=>false,'message'=>$res['message'] ?? 'Error al procesar reserva.']);
    }
    exit;
}

// Si no es POST, mostrar vista de reserva
$metodos_pago = $reserva->obtenerMetodosPago();
$carrito = $_SESSION['carrito'] ?? [];
$total = 0;
foreach ($carrito as $item) {
    $cantidad = $item['cantidad'];
    $precioUnitario = $cantidad >= $item['cantidad_mayor'] ? $item['precio_mayor'] : $item['precio_detal'];
    $total += $cantidad * $precioUnitario;
}

require_once __DIR__ . '/../vista/tienda/reserva_cliente.php';
