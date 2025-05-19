<?php
session_start();
$sesion_activa = isset($_SESSION["id"]) && !empty($_SESSION["id"]);
// Función para calcular subtotal de un producto
function calcularSubtotal($cantidad, $precio_unitario) {
    return number_format($cantidad * $precio_unitario, 2, '.', '');
}

// Función para calcular el total general del carrito
function calcularTotalGeneral() {
    $total = 0;
    foreach ($_SESSION['carrito'] as $producto) {
        $total += $producto['subtotal'];
    }
    return number_format($total, 2, '.', '');
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Acción de eliminar producto
    if (isset($_POST['accion']) && $_POST['accion'] === 'eliminar') {
        $idProducto = $_POST['id'];

        // Recorre el carrito y elimina el producto si coincide el ID
        foreach ($_SESSION['carrito'] as $index => $producto) {
            if ($producto['id'] == $idProducto) {
                unset($_SESSION['carrito'][$index]);
                $_SESSION['carrito'] = array_values($_SESSION['carrito']); // Reindexa el carrito
                echo json_encode([ // Responde con el carrito actualizado
                    'success' => true,
                    'id' => $idProducto,
                    'eliminado' => true,
                    'total' => calcularTotalGeneral() // Total general actualizado
                ]);
                exit;
            }
        }

        // Si el producto no se encuentra en el carrito
        echo json_encode(['success' => false, 'mensaje' => 'Producto no encontrado']);
        exit;
    }

    // Acción para actualizar cantidad (incrementar o decrementar)
    if (isset($_POST['accion']) && isset($_POST['id'])) {
        $id = $_POST['id'];
        $accion = $_POST['accion'];

        if (isset($_SESSION['carrito'][$id])) {
            if ($accion === 'incrementar') {
                $_SESSION['carrito'][$id]['cantidad'] += 1;
            } elseif ($accion === 'decrementar') {
                $_SESSION['carrito'][$id]['cantidad'] -= 1;
            }

            // Si la cantidad es menor a 1, eliminamos el producto
            if ($_SESSION['carrito'][$id]['cantidad'] < 1) {
                unset($_SESSION['carrito'][$id]);
                $_SESSION['carrito'] = array_values($_SESSION['carrito']); // Reindexar el carrito
                echo json_encode([
                    'success' => true,
                    'id' => $id,
                    'eliminado' => true,
                    'total' => calcularTotalGeneral()
                ]);
                exit;
            }

            // Calcular precio y subtotal actualizado
            $producto = $_SESSION['carrito'][$id];
            $cantidad = $producto['cantidad'];
            $precio_unitario = $cantidad >= $producto['cantidad_mayor'] ? $producto['precio_mayor'] : $producto['precio_detal'];
            $_SESSION['carrito'][$id]['precio_unitario'] = $precio_unitario;
            $_SESSION['carrito'][$id]['subtotal'] = calcularSubtotal($cantidad, $precio_unitario);

            echo json_encode([
                'success' => true,
                'id' => $id,
                'cantidad' => $cantidad,
                'precio' => number_format($precio_unitario, 2),
                'subtotal' => number_format($_SESSION['carrito'][$id]['subtotal'], 2),
                'total' => calcularTotalGeneral()
            ]);
            exit;
        }
    }

    if($sesion_activa){

    // Agregar nuevo producto al carrito
    if (isset($_POST['id'], $_POST['nombre'], $_POST['precio_detal'], $_POST['precio_mayor'], $_POST['cantidad_mayor'], $_POST['imagen'])) {
        $id = $_POST['id'];


        // Convertimos a float e int lo necesario
        $precio_detal = floatval($_POST['precio_detal']);
        $precio_mayor = floatval($_POST['precio_mayor']);
        $cantidad_mayor = intval($_POST['cantidad_mayor']);
        $stockDisponible = isset($_POST['stockDisponible']) ? intval($_POST['stockDisponible']) : 0 ;

        if (isset($_SESSION['carrito'][$id])) {
            $nuevaCantidad = $_SESSION['carrito'][$id]['cantidad'] + 1;
        
            if ($nuevaCantidad > $stockDisponible) {
                echo json_encode(['success' => false, 'mensaje' => 'No puedes agregar más del stock disponible.']);
                exit;
            }
        
            $_SESSION['carrito'][$id]['cantidad'] = $nuevaCantidad;
        } else {
            if ($stockDisponible < 1) {
                echo json_encode(['success' => false, 'mensaje' => 'Producto sin stock.']);
                exit;
            }
        
            $_SESSION['carrito'][$id] = [
                'id' => $id,
                'nombre' => $_POST['nombre'],
                'imagen' => $_POST['imagen'],
                'precio_detal' => $precio_detal,
                'precio_mayor' => $precio_mayor,
                'cantidad_mayor' => $cantidad_mayor,
                'cantidad' => 1,
                'stockDisponible' => $stockDisponible
            ];
        }

        // Determinar precio unitario
        $cantidad = $_SESSION['carrito'][$id]['cantidad'];
        $precio_unitario = ($cantidad >= $cantidad_mayor) ? $precio_mayor : $precio_detal;

        $_SESSION['carrito'][$id]['precio_unitario'] = $precio_unitario;
        $_SESSION['carrito'][$id]['subtotal'] = calcularSubtotal($cantidad, $precio_unitario);

        echo json_encode([
            'success' => true,
            'producto' => [
                'id' => $id,
                'nombre' => $_SESSION['carrito'][$id]['nombre'],
                'imagen' => $_SESSION['carrito'][$id]['imagen'],
                'cantidad' => $cantidad,
                'precio_unitario' => number_format($precio_unitario, 2),
                'subtotal' => number_format($_SESSION['carrito'][$id]['subtotal'], 2)
            ],
            'total_general' => calcularTotalGeneral()
        ]);
        exit;
    }};

   
}





