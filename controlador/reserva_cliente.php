<?php
session_start();
require_once __DIR__ . '/../modelo/reserva_cliente.php';

$esPost = $_SERVER['REQUEST_METHOD'] === 'POST';
$esAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';

if ($esPost && $esAjax) {
    header('Content-Type: application/json');

    if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
        echo json_encode(['success' => false, 'message' => 'Sesión expirada. Inicia sesión nuevamente.']);
        exit;
    }

    if (empty($_SESSION['carrito'])) {
        echo json_encode(['success' => false, 'message' => 'El carrito está vacío.']);
        exit;
    }

    $reserva = new ReservaCliente();

    try {
        // Valores opcionales
        $monto       = $_POST['monto'] ?? null;
        $monto_usd   = $_POST['monto_usd'] ?? null;
        $imagen      = $_FILES['imagen'] ?? null;

        $datosReserva = [
            'operacion' => 'registrar_reserva',
            'datos' => [
                'referencia_bancaria' => $_POST['referencia_bancaria'] ?? '',
                'telefono_emisor'     => $_POST['telefono_emisor'] ?? '',
                'banco'               => $_POST['banco'] ?? '',
                'banco_destino'       => $_POST['banco_destino'] ?? '',
                'id_metodopago'       => $_POST['id_metodopago'] ?? '',
                'id_persona'          => $_SESSION['id'],
                'estado'              => $_POST['estado'] ?? '1',
                'precio_total_usd'    => $_POST['precio_total_usd'] ?? '0',
                'precio_total_bs'     => $_POST['precio_total_bs'] ?? '0',
                'tipo'                => '3',
                'carrito'             => $_SESSION['carrito'] ?? [],
                'monto'               => $monto,
                'monto_usd'           => $monto_usd,
                'imagen'              => $imagen,
            ]
        ];

        $resultado = $reserva->procesarReserva(json_encode($datosReserva));

        if ($resultado['success'] && $resultado['id_pedido']) {
            unset($_SESSION['carrito']);
        }

        echo json_encode($resultado);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
}

// Resto del código para vista (GET normal)
$sesion_activa = isset($_SESSION['id']) && !empty($_SESSION['id']);
if (!$sesion_activa) {
    header("Location: ?pagina=login");
    exit;
}

if (empty($_SESSION['carrito'])) {
    require_once 'vista/complementos/carritovacio.php';
    exit;
}

$reserva = new ReservaCliente();

$nombre         = $_SESSION['nombre'] ?? 'Estimado Cliente';
$apellido       = $_SESSION['apellido'] ?? '';
$nombreCompleto = trim("$nombre $apellido");

$metodos_pago = $reserva->obtenerMetodosPago();

$carrito = $_SESSION['carrito'] ?? [];
$total   = 0;

foreach ($carrito as $item) {
    $cantidad       = $item['cantidad'];
    $precioUnitario = $cantidad >= $item['cantidad_mayor'] ? $item['precio_mayor'] : $item['precio_detal'];
    $total         += $cantidad * $precioUnitario;
}

require_once 'vista/tienda/reserva_cliente.php';
