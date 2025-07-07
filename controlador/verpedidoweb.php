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

        $rutaImagen = null;
if (!empty($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
    $nuevoNombre = uniqid('img_') . ".$ext";
    $destino = __DIR__ . '/../assets/img/captures/' . $nuevoNombre;
    if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $destino)) {
        die(json_encode(['success'=>false,'message'=>'Error al guardar la imagen.']));
    }
    $rutaImagen = 'assets/img/captures/' . $nuevoNombre;
}

        // Preparar datos del pedido
        $datosPedido = [
            'operacion' => 'registrar_pedido',
            'datos' => [
              
                'tipo'                => $_POST['tipo'] ?? '',
                'fecha'               => $_POST['fecha'] ?? date('Y-m-d H:i:s'),
                'estado'              => $_POST['estado'] ?? 'pendiente',
                'precio_total_usd'    => $_POST['precio_total_usd'] ?? '',
                'precio_total_bs'     => $_POST['precio_total_bs'] ?? '',
                'id_persona'          => $_POST['id_persona'] ?? '',
            
                // **Pago**
                'id_metodopago'       => $_POST['id_metodopago'] ?? '',
                'referencia_bancaria' => $_POST['referencia_bancaria'] ?? '',
                'telefono_emisor'     => $_POST['telefono_emisor'] ?? '',
                'banco_destino'       => $_POST['banco_destino'] ?? '',
                'banco'               => $_POST['banco'] ?? '',
                'monto'               => $_POST['monto'] ?? '',
                'monto_usd'           => $_POST['monto_usd'] ?? '',
                'imagen'            =>$rutaImagen,
            
                // **Dirección**
                'direccion_envio'     => $_POST['direccion_envio'] ?? '',
                'sucursal_envio'      => $_POST['sucursal_envio'] ?? '',
                'id_metodoentrega'    => $_POST['id_metodoentrega'] ?? '',
            
                // Carrito
                'carrito'             => $_SESSION['carrito'] ?? []
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
