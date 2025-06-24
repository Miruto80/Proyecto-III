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

// Función para detectar si la solicitud es AJAX
function esAjax() {
    return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
}

// Función para sanitizar datos de entrada
function sanitizar($dato) {
    return htmlspecialchars(trim($dato), ENT_QUOTES, 'UTF-8');
}

// Función para enviar respuesta JSON
function enviarRespuesta($datos, $codigo = 200) {
    http_response_code($codigo);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($datos);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar token CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        enviarRespuesta([
            'respuesta' => 0,
            'error' => 'Error de validación del formulario'
        ], 403);
    }

    // Procesar búsqueda de cliente
    if (isset($_POST['buscar_cliente'])) {
        try {
            $datos = [
                'cedula' => sanitizar($_POST['cedula'])
            ];
            $respuesta = $salida->consultarClientePublico($datos);
            enviarRespuesta($respuesta);
        } catch (Exception $e) {
            enviarRespuesta([
                'respuesta' => 0,
                'error' => $e->getMessage()
            ]);
        }
    }

    // Procesar registro de cliente
    if (isset($_POST['registrar_cliente'])) {
        try {
            $datos = [
                'cedula' => sanitizar($_POST['cedula']),
                'nombre' => sanitizar($_POST['nombre']),
                'apellido' => sanitizar($_POST['apellido']),
                'telefono' => sanitizar($_POST['telefono']),
                'correo' => sanitizar($_POST['correo'])
            ];
            $respuesta = $salida->registrarClientePublico($datos);
            enviarRespuesta($respuesta);
        } catch (Exception $e) {
            enviarRespuesta([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    // Procesar registro de venta
    if (isset($_POST['registrar'])) {
        try {
            $datosVenta = [
                'id_persona' => $_POST['id_persona'],
                'id_metodopago' => $_POST['id_metodopago'],
                'id_entrega' => $_POST['id_entrega'],
                'precio_total' => $_POST['precio_total'],
                'referencia_bancaria' => $_POST['referencia_bancaria'] ?? null,
                'telefono_emisor' => $_POST['telefono_emisor'] ?? null,
                'banco' => $_POST['banco'] ?? null,
                'banco_destino' => $_POST['banco_destino'] ?? null,
                'direccion' => $_POST['direccion'] ?? null,
                    'detalles' => []
            ];
            if (isset($_POST['id_producto']) && is_array($_POST['id_producto'])) {
            for ($i = 0; $i < count($_POST['id_producto']); $i++) {
                if (!empty($_POST['id_producto'][$i]) && isset($_POST['cantidad'][$i]) && $_POST['cantidad'][$i] > 0) {
                        $datosVenta['detalles'][] = [
                            'id_producto' => $_POST['id_producto'][$i],
                            'cantidad' => $_POST['cantidad'][$i],
                            'precio_unitario' => $_POST['precio_unitario'][$i]
                    ];
                }
            }
            }
            $respuesta = $salida->registrarVentaPublico($datosVenta);
            enviarRespuesta($respuesta);
        } catch (Exception $e) {
            enviarRespuesta([
                'respuesta' => 0,
                'error' => $e->getMessage()
            ]);
        }
    }

    // Procesar actualización de venta
    if (isset($_POST['actualizar'])) {
        try {
            $datosVenta = [
                'id_pedido' => $_POST['id_pedido'],
                'estado' => $_POST['estado_pedido'],
                'direccion' => $_POST['direccion'] ?? null
            ];
            $respuesta = $salida->actualizarVentaPublico($datosVenta);
            enviarRespuesta($respuesta);
        } catch (Exception $e) {
            enviarRespuesta([
                    'respuesta' => 0,
                    'error' => $e->getMessage()
                ]);
    }
}

    // Procesar eliminación de venta
    if (isset($_POST['eliminar'])) {
        try {
            $datosVenta = [
                'id_pedido' => $_POST['eliminar']
            ];
            $respuesta = $salida->eliminarVentaPublico($datosVenta);
            enviarRespuesta($respuesta);
        } catch (Exception $e) {
            enviarRespuesta([
                    'respuesta' => 0,
                    'error' => $e->getMessage()
                ]);
    }
}

    // Procesar actualización de delivery
    if (isset($_POST['actualizar_delivery'])) {
        try {
            $datosVenta = [
                'id_pedido' => $_POST['id_pedido'],
                'estado' => $_POST['estado_delivery'],
                'direccion' => $_POST['direccion'] ?? null
            ];
            $respuesta = $salida->actualizarVentaPublico($datosVenta);
            if ($respuesta['respuesta'] == 1) {
                $_SESSION['mensaje'] = "Estado del delivery actualizado exitosamente";
                $_SESSION['tipo_mensaje'] = "success";
            } else {
                $_SESSION['mensaje'] = "Error al actualizar el estado del delivery";
                $_SESSION['tipo_mensaje'] = "error";
            }
        } catch (Exception $e) {
            $_SESSION['mensaje'] = "Error: " . $e->getMessage();
        $_SESSION['tipo_mensaje'] = "error";
    }
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    header("Location: ?pagina=salida");
    exit;
    }
} else if($_SESSION["nivel_rol"] >= 2) {
    $bitacora = [
        'id_persona' => $_SESSION["id"],
        'accion' => 'Acceso a Módulo',
        'descripcion' => 'módulo de Ventas'
    ];
    $salida->registrarBitacora(json_encode($bitacora));

    // Consultar datos actualizados para la vista
    $ventas = $salida->consultarVentas();
    $productos_lista = $salida->consultarProductos();
    $metodos_pago = $salida->consultarMetodosPago();
    $metodos_entrega = $salida->consultarMetodosEntrega();

    require_once 'vista/salida.php';
} else {
    require_once 'vista/seguridad/privilegio.php';
}

// Si es una solicitud GET normal, mostrar la vista
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Consultar datos actualizados para la vista
    $ventas = $salida->consultarVentas();
    $productos_lista = $salida->consultarProductos();
    $metodos_pago = $salida->consultarMetodosPago();
    $metodos_entrega = $salida->consultarMetodosEntrega();

    // Cargar la vista
    if ($_SESSION["nivel_rol"] >= 2) {
        require_once 'vista/salida.php';
    } else {
        require_once 'vista/seguridad/privilegio.php';
    }
}
?>