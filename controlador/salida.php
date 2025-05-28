<?php
session_start();
if (empty($_SESSION["id"])) {
    header("location:?pagina=login");
    exit;
} /* Validacion URL */

require_once 'modelo/salida.php';

$salida = new Salida();

// Generar o verificar el token CSRF
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['mensaje'] = "Error de validación del formulario";
        $_SESSION['tipo_mensaje'] = "danger";
        header("Location: ?pagina=salida");
        exit;
    }
}

// Detectar si la solicitud es AJAX
function esAjax() {
    return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
}

// Función para sanitizar datos de entrada
function sanitizar($dato) {
    return htmlspecialchars(trim($dato), ENT_QUOTES, 'UTF-8');
}

// Procesar la búsqueda de cliente por cédula (AJAX)
if (isset($_POST['buscar_cliente'])) {
    if (isset($_POST['cedula'])) {
        $cedula = sanitizar($_POST['cedula']);
        $cliente = $salida->consultarCliente($cedula);
        
        if ($cliente) {
            $_SESSION['cliente_encontrado'] = true;
            $_SESSION['datos_cliente'] = $cliente;
        } else {
            $_SESSION['cliente_encontrado'] = false;
        }
        
        if (esAjax()) {
            header('Content-Type: application/json');
            echo json_encode([
                'respuesta' => $cliente ? 1 : 0,
                'cliente' => $cliente
            ]);
            exit;
        } else {
            header("Location: ?pagina=salida");
            exit;
        }
    }
}

// Procesar el registro de nuevo cliente
if (isset($_POST['registrar_cliente'])) {
    header('Content-Type: application/json; charset=utf-8');
    
    try {
        // Validar datos del cliente
        $cedula = sanitizar($_POST['cedula']);
        $nombre = sanitizar($_POST['nombre']);
        $apellido = sanitizar($_POST['apellido']);
        $telefono = sanitizar($_POST['telefono']);
        $correo = sanitizar($_POST['correo']);

        // Verificar si la cédula ya existe
        if ($salida->existeCedula($cedula)) {
            echo json_encode([
                'success' => false,
                'message' => 'La cédula ya está registrada'
            ]);
            exit;
        }

        // Registrar el nuevo cliente
        $id_cliente = $salida->registrarCliente([
            'cedula' => $cedula,
            'nombre' => $nombre,
            'apellido' => $apellido,
            'telefono' => $telefono,
            'correo' => $correo
        ]);

        if ($id_cliente) {
            echo json_encode([
                'success' => true,
                'id_cliente' => $id_cliente,
                'message' => 'Cliente registrado exitosamente'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Error al registrar el cliente'
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
    exit;
}

// Procesar el registro de una nueva venta
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registrar_venta'])) {
    // Asegurarnos de que no haya salida previa
    ob_clean();
    
    try {
        // Validar que existan los datos necesarios
        if (!isset($_POST['id_persona']) || !isset($_POST['id_metodopago']) || !isset($_POST['id_entrega'])) {
            throw new Exception('Faltan datos requeridos para la venta');
        }

        // Configurar datos básicos de la venta
        $salida->set_Id_persona(intval($_POST['id_persona']));
        $salida->set_Id_metodopago(intval($_POST['id_metodopago']));
        $salida->set_Id_entrega(intval($_POST['id_entrega']));
        $salida->set_Precio_total(floatval($_POST['precio_total']));
        
        // Configurar datos de pago móvil si el método es pago móvil (id = 1)
        if ($_POST['id_metodopago'] == '1') {
            if (!isset($_POST['referencia_bancaria']) || !isset($_POST['telefono_emisor']) || !isset($_POST['banco'])) {
                throw new Exception('Faltan datos del pago móvil');
            }
            $salida->set_Referencia_bancaria(sanitizar($_POST['referencia_bancaria']));
            $salida->set_Telefono_emisor(sanitizar($_POST['telefono_emisor']));
            $salida->set_Banco(sanitizar($_POST['banco']));
            if (isset($_POST['banco_destino'])) {
                $salida->set_Banco_destino(sanitizar($_POST['banco_destino']));
            }
        }

        // Configurar dirección si existe
        if (isset($_POST['direccion'])) {
            $salida->set_Direccion(sanitizar($_POST['direccion']));
        }
        
        // Procesar detalles de productos
        $detalles = [];
        if (!isset($_POST['id_producto']) || !is_array($_POST['id_producto']) || empty($_POST['id_producto'])) {
            throw new Exception('No hay productos seleccionados');
        }

        for ($i = 0; $i < count($_POST['id_producto']); $i++) {
            if (!empty($_POST['id_producto'][$i]) && isset($_POST['cantidad'][$i]) && $_POST['cantidad'][$i] > 0) {
                $detalle = [
                    'id_producto' => intval($_POST['id_producto'][$i]),
                    'cantidad' => intval($_POST['cantidad'][$i]),
                    'precio_unitario' => floatval($_POST['precio_unitario'][$i])
                ];
                $detalles[] = $detalle;
            }
        }

        if (empty($detalles)) {
            throw new Exception('No hay productos válidos para registrar');
        }
        
        $salida->set_Detalles($detalles);
        
        // Registrar la venta
        $respuesta = $salida->registrar();
        
        header('Content-Type: application/json; charset=utf-8');
        if ($respuesta['respuesta'] == 1) {
            echo json_encode([
                'respuesta' => 1,
                'mensaje' => 'Venta registrada exitosamente',
                'id_pedido' => $respuesta['id_pedido']
            ]);
        } else {
            echo json_encode([
                'respuesta' => 0,
                'error' => isset($respuesta['error']) ? $respuesta['error'] : 'Error desconocido al registrar la venta'
            ]);
        }
        exit;

    } catch (Exception $e) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'respuesta' => 0,
            'error' => $e->getMessage()
        ]);
        exit;
    }
}

// Procesar la modificación del estado de una venta
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modificar_venta'])) {
    if (isset($_POST['id_pedido']) && isset($_POST['estado_pedido'])) {
        $salida->set_Id_pedido(intval($_POST['id_pedido']));
        $salida->set_Estado(sanitizar($_POST['estado_pedido']));
        
        // Modificar el estado de la venta
        $respuesta = $salida->modificar();
        
        if (esAjax()) {
            header('Content-Type: application/json');
            echo json_encode($respuesta);
            exit;
        } else {
            // Regenerar token CSRF
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            header("Location: ?pagina=salida");
            exit;
        }
    }
}

// Procesar la eliminación de una venta
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_venta'])) {
    if (!empty($_POST['eliminar_venta'])) {
        $salida->set_Id_pedido(intval($_POST['eliminar_venta']));
        
        // Eliminar la venta
        $respuesta = $salida->eliminar();
        
        if (esAjax()) {
            header('Content-Type: application/json');
            echo json_encode($respuesta);
            exit;
        } else {
            if ($respuesta['respuesta'] == 1) {
                $_SESSION['mensaje'] = "Venta eliminada exitosamente";
                $_SESSION['tipo_mensaje'] = "success";
            } else {
                $_SESSION['mensaje'] = "Error al eliminar la venta: " . (isset($respuesta['error']) ? $respuesta['error'] : "");
                $_SESSION['tipo_mensaje'] = "danger";
            }
            
            // Regenerar token CSRF
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            header("Location: ?pagina=salida");
            exit;
        }
    }
}

// Si es una solicitud GET normal, mostrar la vista
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Consultar datos para la vista
    $ventas = $salida->consultar();

    // Obtener la lista de productos y métodos de pago/entrega para los formularios
    $productos_lista = $salida->consultarProductos();
    $metodos_pago = $salida->consultarMetodosPago();
    $metodos_entrega = $salida->consultarMetodosEntrega();

    // Cargamos la vista
    if ($_SESSION["nivel_rol"] >= 2) { // Validación si es administrador o vendedor
        require_once 'vista/salida.php';
    } else {
        require_once 'vista/seguridad/privilegio.php';
    }
}
?>