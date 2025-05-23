<?php
session_start();

require_once __DIR__ . '/../modelo/vercarrito.php';

$nombre = isset($_SESSION["nombre"]) && !empty($_SESSION["nombre"]) ? $_SESSION["nombre"] : "Estimado Cliente";
$apellido = isset($_SESSION["apellido"]) && !empty($_SESSION["apellido"]) ? $_SESSION["apellido"] : ""; 
$nombreCompleto = trim($nombre . " " . $apellido);

$sesion_activa = isset($_SESSION["id"]) && !empty($_SESSION["id"]);

$carrito = $_SESSION['carrito'] ?? [];
$total = 0;

$vercarrito = 1;

$accion = $_POST['accion'] ?? '';

// Si es una solicitud AJAX (POST con acción), respondemos JSON y salimos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $accion) {
    header('Content-Type: application/json');

    switch ($accion) {
        case 'eliminar':
            $id = $_POST['id'] ?? '';
            if ($id && Carrito::eliminarProducto($id)) {
                $carrito = Carrito::obtenerCarrito();
    $total = 0;

    foreach ($carrito as $p) {
        $cant = $p['cantidad'];
        $precio = ($cant >= $p['cantidad_mayor']) ? $p['precio_mayor'] : $p['precio_detal'];
        $total += $cant * $precio;
    }

    echo json_encode([
        'success' => true,
        'total' => number_format($total, 2),
    ]);
} else {
                echo json_encode(['success' => false, 'error' => 'No se pudo eliminar']);
            }
            exit;

        case 'actualizar':
            $id = $_POST['id'] ?? '';
            $cantidad = intval($_POST['cantidad'] ?? 0);

            if ($id && isset($carrito[$id])) {
                $producto = $carrito[$id];
                $stockDisponible = $producto['stockDisponible'];

                if ($cantidad < 1 || $cantidad > $stockDisponible) {
                    echo json_encode([
                        'success' => false,
                        'error' => 'Cantidad inválida o superior al stock disponible',
                        'stockDisponible' => $stockDisponible
                    ]);
                    exit;
                }

                if (Carrito::actualizarCantidad($id, $cantidad)) {
                    $carrito = Carrito::obtenerCarrito();
                    $item = $carrito[$id];

                    $precioUnitario = ($cantidad >= $item['cantidad_mayor']) ? $item['precio_mayor'] : $item['precio_detal'];
                    $subtotal = $precioUnitario * $cantidad;

                    $total = 0;
                    foreach ($carrito as $p) {
                        $cant = $p['cantidad'];
                        $precio = ($cant >= $p['cantidad_mayor']) ? $p['precio_mayor'] : $p['precio_detal'];
                        $total += $cant * $precio;
                    }

                    echo json_encode([
                        'success' => true,
                        'id' => $id,
                        'cantidad' => $cantidad,
                        'precio' => number_format($precioUnitario, 2),
                        'subtotal' => number_format($subtotal, 2),
                        'total' => number_format($total, 2),
                    ]);
                } else {
                    echo json_encode(['success' => false, 'error' => 'No se pudo actualizar la cantidad']);
                }
            } else {
                echo json_encode(['success' => false, 'error' => 'Producto no encontrado en el carrito']);
            }
            exit;

        default:
            echo json_encode(['success' => false, 'error' => 'Acción no válida']);
            exit;
    }
}

// Si NO es AJAX, entonces mostramos la vista
if ($sesion_activa) {
     if($_SESSION["nivel_rol"] == 1) { 
      require_once 'vista/tienda/vercarrito.php';
    } else{
        header('Location: ?pagina=catalogo');
    } 
} else {
    header('Location: ?pagina=catalogo');
    exit;
}


?>