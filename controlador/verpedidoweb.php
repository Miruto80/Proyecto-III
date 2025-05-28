<?php
session_start();
require_once __DIR__ . '/../modelo/verpedidoweb.php';
$sesion_activa = isset($_SESSION["id"]) && !empty($_SESSION["id"]);
$carritoEmpty = empty($_SESSION['carrito']);

if (empty($_SESSION['id'])) {
    header("Location: ?pagina=login");
    exit;
    
}

if ($carritoEmpty) {
        require_once 'vista/complementos/carritovacio.php';
        exit;
    }
    
$venta = new VentaWeb();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    try {
        // 1. Registrar pedido
        $datosPedido = [
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
        ];
         $carrito = $_SESSION['carrito'] ?? [];
         
        $venta->validarStockCarrito($carrito);

        $venta->set_Datos($datosPedido);
        $id_pedido = $venta->registrarPedido();

        if (!$id_pedido) {
            throw new Exception("Error al registrar el pedido");
        }

        // 2. Registrar detalles y preliminar
       

        foreach ($carrito as $item) {
            $detalle = [
                'id_pedido' => $id_pedido,
                'id_producto' => $item['id'],
                'cantidad' => $item['cantidad'],
                'precio_unitario' => $item['cantidad'] >= $item['cantidad_mayor'] ? $item['precio_mayor'] : $item['precio_detal'],
            ];

            $venta->set_Detalles($detalle);
            $id_detalle = $venta->registrarDetalle();

            if (!$id_detalle) {
                throw new Exception("Error al registrar detalle del producto: {$item['nombre']}");
            }

            // Registrar preliminar
            $venta->set_Datos([
                'id_detalle' => $id_detalle,
                'condicion' => 'pedido'
            ]);

            $venta->registrarPreliminar();
        }

        // Vaciar carrito y responder
        $venta->vaciarCarrito();
        echo json_encode(['success' => true, 'id_pedido' => $id_pedido]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
}

// Vista
$nombre = $_SESSION['nombre'] ?? 'Estimado Cliente';
$apellido = $_SESSION['apellido'] ?? '';
$nombreCompleto = trim("$nombre $apellido");
$metodos_pago = $venta->obtenerMetodosPago();
$metodos_entrega = $venta->obtenerMetodosEntrega();
$carrito = $_SESSION['carrito'] ?? [];
$total = 0;

foreach ($carrito as $item) {
    $cantidad = $item['cantidad'];
    $precioUnitario = $cantidad >= $item['cantidad_mayor'] ? $item['precio_mayor'] : $item['precio_detal'];
    $total += $cantidad * $precioUnitario;
}

require_once 'vista/tienda/verpedidoweb.php';
