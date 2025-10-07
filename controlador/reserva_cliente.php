<?php
// -----------------------------------------------------------
// INICIO DE SESIÓN (evita duplicados)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// -----------------------------------------------------------
// DESACTIVAR MOSTRAR ERRORES EN PRODUCCIÓN (evita romper JSON)
ini_set('display_errors', 0);
error_reporting(0);

// -----------------------------------------------------------
// CARGAR MODELO
require_once __DIR__ . '/../modelo/reserva_cliente.php';

// -----------------------------------------------------------
// DETECTAR PETICIÓN AJAX POST
$esPost = $_SERVER['REQUEST_METHOD'] === 'POST';
$esAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

if ($esPost && $esAjax) {
    header('Content-Type: application/json; charset=utf-8');

    // Verificar sesión
    if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
        echo json_encode(['success' => false, 'message' => 'Sesión expirada. Inicia sesión nuevamente.']);
        exit;
    }

    // Verificar carrito
    if (empty($_SESSION['carrito'])) {
        echo json_encode(['success' => false, 'message' => 'El carrito está vacío.']);
        exit;
    }

    $reserva = new ReservaCliente();

    try {
        // Obtener datos opcionales
        $monto       = $_POST['monto'] ?? null;
        $monto_usd   = $_POST['monto_usd'] ?? null;
        $imagen      = $_FILES['imagen'] ?? null;

        // Construir datos de la reserva
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

        // Procesar reserva
        $resultado = $reserva->procesarReserva(json_encode($datosReserva));

        // Vaciar carrito si fue exitoso
        if (isset($resultado['success']) && $resultado['success'] && !empty($resultado['id_pedido'])) {
            unset($_SESSION['carrito']);
        }

        // Enviar respuesta JSON
        echo json_encode($resultado);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error interno: ' . $e->getMessage()]);
    }

    exit;
}

// -----------------------------------------------------------
// PETICIÓN NORMAL (GET)
$sesion_activa = isset($_SESSION['id']) && !empty($_SESSION['id']);
if (!$sesion_activa) {
    header("Location: ?pagina=login");
    exit;
}

// Carrito vacío
if (empty($_SESSION['carrito'])) {
    require_once __DIR__ . '/../vista/complementos/carritovacio.php';
    exit;
}

// -----------------------------------------------------------
// CARGAR DATOS DE VISTA
$reserva = new ReservaCliente();

$nombre         = $_SESSION['nombre'] ?? 'Estimado Cliente';
$apellido       = $_SESSION['apellido'] ?? '';
$nombreCompleto = trim("$nombre $apellido");

$metodos_pago = $reserva->obtenerMetodosPago();
$carrito      = $_SESSION['carrito'] ?? [];
$total        = 0;

foreach ($carrito as $item) {
    $cantidad       = $item['cantidad'];
    $precioUnitario = $cantidad >= $item['cantidad_mayor']
        ? $item['precio_mayor']
        : $item['precio_detal'];
    $total += $cantidad * $precioUnitario;
}

// -----------------------------------------------------------
// MOSTRAR VISTA PRINCIPAL
require_once __DIR__ . '/../vista/tienda/reserva_cliente.php';
