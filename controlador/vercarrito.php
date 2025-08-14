<?php
session_start();

require_once __DIR__ . '/../modelo/vercarrito.php';

$nombre = isset($_SESSION["nombre"]) && !empty($_SESSION["nombre"]) ? $_SESSION["nombre"] : "Estimado Cliente";
$apellido = isset($_SESSION["apellido"]) && !empty($_SESSION["apellido"]) ? $_SESSION["apellido"] : ""; 
$nombreCompleto = trim($nombre . " " . $apellido);

$sesion_activa = isset($_SESSION["id"]) && !empty($_SESSION["id"]);

if (!empty($_SESSION['id'])) {
        require_once 'verificarsession.php';
} 

$carrito = $_SESSION['carrito'] ?? [];
$carritoEmpty = empty($carrito);
$total = 0;
$accion = $_POST['accion'] ?? '';

// Si es una solicitud AJAX (POST con acción), respondemos JSON y salimos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $accion) {
    header('Content-Type: application/json');

    $carritoObj = new VerCarrito();

    switch ($accion) {
        case 'eliminar':
            $id = $_POST['id'] ?? '';
        
            if ($id === '') {
                echo json_encode(['success' => false, 'error' => 'ID no recibido']);
                exit;
            }
        
            if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
                echo json_encode(['success' => false, 'error' => 'El carrito está vacío']);
                exit;
            }
        
            $productoEncontrado = false;
        
            foreach ($_SESSION['carrito'] as $index => $producto) {
                if ($producto['id'] == $id) {
                    unset($_SESSION['carrito'][$index]);
                    $_SESSION['carrito'] = array_values($_SESSION['carrito']); // Reindexar
        
                    // Función para calcular total general
                    function calcularTotalGeneral() {
                        $total = 0;
                        foreach ($_SESSION['carrito'] as $p) {
                            $cant = $p['cantidad'];
                            $precio = ($cant >= $p['cantidad_mayor']) ? $p['precio_mayor'] : $p['precio_detal'];
                            $total += $cant * $precio;
                        }
                        return number_format($total, 2);
                    }
        
                    echo json_encode([
                        'success' => true,
                        'id' => $id,
                        'eliminado' => true,
                        'total' => calcularTotalGeneral()
                    ]);
                    exit;
                }
            }
        
            echo json_encode(['success' => false, 'error' => 'Producto no encontrado en el carrito']);
            exit;

            $producto = $carrito[$id];
            $stockDisponible = $producto['stockDisponible'];

            if ($cantidad < 1 || $cantidad > $stockDisponible) {
                echo json_encode([
                    'success' => false,
                    'error' => 'Cantidad inválida o superior al stock disponible',
                    'stockDisponible' => $stockDisponible
                ]);
                exit;}
        
                case 'actualizar':
                    $id = $_POST['id'] ?? '';
                    $cantidad = intval($_POST['cantidad'] ?? 0);
                
                    $carrito = $carritoObj->procesarCarrito(json_encode([
                        'operacion' => 'obtener'
                    ]))['carrito'] ?? [];
                
                    if ($id && isset($carrito[$id])) {
                        $producto = $carrito[$id];
                        $stockDisponible = (int) $producto['stockDisponible'];
                
                        if ($cantidad < 1 || $cantidad > $stockDisponible) {
                            echo json_encode([
                                'success' => false,
                                'error' => 'Cantidad inválida o superior al stock disponible',
                                'stockDisponible' => $stockDisponible
                            ]);
                            exit;
                        }
                
                        // Actualizar cantidad
                        $respuesta = $carritoObj->procesarCarrito(json_encode([
                            'operacion' => 'actualizar',
                            'datos' => ['id' => $id, 'cantidad' => $cantidad]
                        ]));
                
                        if ($respuesta['respuesta'] == 1) {
                            // Obtener carrito actualizado
                            $carrito = $carritoObj->procesarCarrito(json_encode([
                                'operacion' => 'obtener'
                            ]))['carrito'] ?? [];
                
                            if (!isset($carrito[$id])) {
                                echo json_encode(['success' => false, 'error' => 'Producto no encontrado tras actualizar']);
                                exit;
                            }
                
                            $item = $carrito[$id];
                
                            // Forzar tipos para comparación segura
                            $cantidadMayor = (int)($item['cantidad_mayor'] ?? 0);
                            $precioMayor = floatval($item['precio_mayor'] ?? 0);
                            $precioDetal = floatval($item['precio_detal'] ?? 0);
                            $cantidad = (int) $item['cantidad'];
                
                            // Lógica de precios por cantidad
                            $precioUnitario = ($cantidad >= $cantidadMayor) ? $precioMayor : $precioDetal;
                            $subtotal = $precioUnitario * $cantidad;
                
                            // Calcular total del carrito
                            $total = 0;
                            foreach ($carrito as $p) {
                                $cant = (int)$p['cantidad'];
                                $mayor = (int)$p['cantidad_mayor'];
                                $precio = ($cant >= $mayor) ? floatval($p['precio_mayor']) : floatval($p['precio_detal']);
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
                            echo json_encode(['success' => false, 'error' => $respuesta['mensaje'] ?? 'Error al actualizar']);
                        }
                    } else {
                        echo json_encode(['success' => false, 'error' => 'Producto no encontrado en el carrito']);
                    }
                    exit;
                
    }
}

// Si NO es AJAX, entonces mostramos la vista
if ($sesion_activa) {
    if ($_SESSION["nivel_rol"] == 1) {
        if ($carritoEmpty) {
            require_once __DIR__ . '/../vista/complementos/carritovacio.php';
            exit;
        } else {
            require_once __DIR__ . '/../vista/tienda/vercarrito.php';
            exit;
        }
    } else {
        header('Location: ?pagina=catalogo');
        exit;
    }
} else {
    header('Location: ?pagina=catalogo');
    exit;
}
?>
