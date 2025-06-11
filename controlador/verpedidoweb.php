<?php
session_start();
require_once __DIR__ . '/../modelo/verpedidoweb.php';

// Verificar sesión y definir variable para la vista
$sesion_activa = isset($_SESSION['id']) && !empty($_SESSION['id']);

if (!$sesion_activa) {
    header("Location: ?pagina=login");
    exit;
}

// Verificar carrito
if (empty($_SESSION['carrito'])) {
    require_once 'vista/complementos/carritovacio.php';
    exit;
}

$venta = new VentaWeb();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Limpiar cualquier salida previa
    if (ob_get_length()) ob_clean();
    
    header('Content-Type: application/json');
    
    try {
        // Preparar datos del pedido
        $datosPedido = [
            'operacion' => 'registrar_pedido',
            'datos' => [
                'referencia_bancaria' => $_POST['referencia_bancaria'] ?? '',
                'telefono_emisor' => $_POST['telefono_emisor'] ?? '',
                'banco' => $_POST['banco'] ?? '',
                'banco_destino' => $_POST['banco_destino'] ?? '',
                'id_metodopago' => $_POST['id_metodopago'] ?? '',
                'id_entrega' => $_POST['id_entrega'] ?? '',
                'direccion' => $_POST['direccion'] ?? '',
                'id_persona' => $_SESSION['id'],
                'estado' => $_POST['estado'] ?? '1',
                'precio_total' => $_POST['precio_total'] ?? '0',
                'tipo' => $_POST['tipo'] ?? '2',
                'carrito' => $_SESSION['carrito'] ?? []
            ]
        ];

        // Procesar el pedido
        $resultado = $venta->procesarPedido(json_encode($datosPedido));
        
        // Si el pedido se registró correctamente, vaciar el carrito.
        if ($resultado['success'] && $resultado['id_pedido']) {
            unset($_SESSION['carrito']);
        }
        
        // Asegurarse de que no haya nada antes del JSON
        die(json_encode($resultado));
    } catch (Exception $e) {
        // Asegurarse de que no haya nada antes del JSON
        die(json_encode(['success' => false, 'message' => $e->getMessage()]));
    }
}

// Datos para la vista
$nombre = $_SESSION['nombre'] ?? 'Estimado Cliente';
$apellido = $_SESSION['apellido'] ?? '';
$nombreCompleto = trim("$nombre $apellido");
$metodos_pago = $venta->obtenerMetodosPago();
$metodos_entrega = $venta->obtenerMetodosEntrega();
$carrito = $_SESSION['carrito'] ?? [];
$total = 0;

// Calcular total
foreach ($carrito as $item) {
    $cantidad = $item['cantidad'];
    $precioUnitario = $cantidad >= $item['cantidad_mayor'] ? $item['precio_mayor'] : $item['precio_detal'];
    $total += $cantidad * $precioUnitario;
}

require_once 'vista/tienda/verpedidoweb.php';
