<?php
session_start();
if (empty($_SESSION["id"])) {
    header("location:?pagina=login");
    exit;
} /* Validacion URL */

require_once 'modelo/salida.php';
 require_once 'permiso.php';
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

// Función para obtener el nombre de un método de pago
function obtenerNombreMetodoPago($id_metodopago) {
    global $salida;
    try {
        $sql = "SELECT nombre FROM metodo_pago WHERE id_metodopago = ? AND estatus = 1";
        $stmt = $salida->getConex1()->prepare($sql);
        $stmt->execute([$id_metodopago]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado ? $resultado['nombre'] : null;
    } catch (Exception $e) {
        return null;
    }
}

// Función para procesar detalles específicos de cada método de pago
function procesarDetallesMetodoPago(&$metodo, $nombreMetodo, $postData) {
    switch($nombreMetodo) {
        case 'Divisas $':
            // El monto USD ya viene en el campo principal monto_metodopago[]
            break;
            
        case 'Efectivo Bs':
            // El monto USD ya viene en el campo principal monto_metodopago[]
            if (isset($postData['monto_efectivo_bs']) && $postData['monto_efectivo_bs'] > 0) {
                $metodo['monto_bs'] = floatval($postData['monto_efectivo_bs']);
            }
            break;
            
        case 'Pago Movil':
            if (isset($postData['monto_pm_bs']) && $postData['monto_pm_bs'] > 0) {
                $metodo['monto_bs'] = floatval($postData['monto_pm_bs']);
            }
            if (isset($postData['banco_emisor_pm'])) {
                $metodo['banco_emisor'] = sanitizar($postData['banco_emisor_pm']);
            }
            if (isset($postData['banco_receptor_pm'])) {
                $metodo['banco_receptor'] = sanitizar($postData['banco_receptor_pm']);
            }
            if (isset($postData['referencia_pm'])) {
                $metodo['referencia'] = sanitizar($postData['referencia_pm']);
            }
            if (isset($postData['telefono_emisor_pm'])) {
                $metodo['telefono_emisor'] = sanitizar($postData['telefono_emisor_pm']);
            }
            break;
            
        case 'Punto de Venta':
            if (isset($postData['monto_pv_bs']) && $postData['monto_pv_bs'] > 0) {
                $metodo['monto_bs'] = floatval($postData['monto_pv_bs']);
            }
            if (isset($postData['referencia_pv'])) {
                $metodo['referencia'] = sanitizar($postData['referencia_pv']);
            }
            break;
            
        case 'Transferencia Bancaria':
            if (isset($postData['monto_tb_bs']) && $postData['monto_tb_bs'] > 0) {
                $metodo['monto_bs'] = floatval($postData['monto_tb_bs']);
            }
            if (isset($postData['referencia_tb'])) {
                $metodo['referencia'] = sanitizar($postData['referencia_tb']);
            }
            break;
    }
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
            // Validar datos requeridos
            if (empty($_POST['precio_total'])) {
                throw new Exception('Datos de venta incompletos');
            }

            // Validar que el precio total sea un número válido
            if (!is_numeric($_POST['precio_total']) || $_POST['precio_total'] <= 0) {
                throw new Exception('Precio total inválido');
            }

            // Validar que haya productos
            if (!isset($_POST['id_producto']) || !is_array($_POST['id_producto']) || empty($_POST['id_producto'])) {
                throw new Exception('Debe seleccionar al menos un producto');
            }

            // Si se va a registrar un cliente nuevo junto con la venta
            if (isset($_POST['registrar_cliente_con_venta'])) {
                // Validar datos del cliente
                $datosCliente = [
                    'cedula' => sanitizar($_POST['cedula_cliente']),
                    'nombre' => sanitizar($_POST['nombre_cliente']),
                    'apellido' => sanitizar($_POST['apellido_cliente']),
                    'telefono' => sanitizar($_POST['telefono_cliente']),
                    'correo' => sanitizar($_POST['correo_cliente'])
                ];

                // Validar que todos los campos del cliente estén completos
                foreach ($datosCliente as $campo => $valor) {
                    if (empty($valor)) {
                        throw new Exception("Campo {$campo} del cliente es obligatorio");
                    }
                }

                // Validar formato de cédula
                if (!preg_match('/^[0-9]{7,8}$/', $datosCliente['cedula'])) {
                    throw new Exception('Formato de cédula inválido');
                }

                // Validar formato de teléfono
                if (!preg_match('/^0[0-9]{10}$/', $datosCliente['telefono'])) {
                    throw new Exception('Formato de teléfono inválido');
                }

                // Validar formato de correo
                if (!filter_var($datosCliente['correo'], FILTER_VALIDATE_EMAIL)) {
                    throw new Exception('Formato de correo inválido');
                }

                // Registrar cliente y obtener su ID
                $respuestaCliente = $salida->registrarClientePublico($datosCliente);
                if (!$respuestaCliente['success']) {
                    throw new Exception('Error al registrar cliente: ' . $respuestaCliente['message']);
                }

                $id_persona = $respuestaCliente['id_cliente'];
            } else {
                // Usar el cliente existente
                if (empty($_POST['id_persona'])) {
                    throw new Exception('ID de cliente no proporcionado');
                }
                $id_persona = intval($_POST['id_persona']);
            }

            $datosVenta = [
                'id_persona' => $id_persona,
                'precio_total' => floatval($_POST['precio_total']),
                'detalles' => []
            ];

            // Validar y procesar detalles de productos
            $productosValidos = 0;
            for ($i = 0; $i < count($_POST['id_producto']); $i++) {
                if (!empty($_POST['id_producto'][$i]) && isset($_POST['cantidad'][$i]) && $_POST['cantidad'][$i] > 0) {
                    // Validar que los datos sean numéricos
                    $id_producto = intval($_POST['id_producto'][$i]);
                    $cantidad = intval($_POST['cantidad'][$i]);
                    $precio_unitario = floatval($_POST['precio_unitario'][$i]);

                    if ($id_producto <= 0 || $cantidad <= 0 || $precio_unitario <= 0) {
                        throw new Exception('Datos de producto inválidos en la fila ' . ($i + 1));
                    }

                        $datosVenta['detalles'][] = [
                        'id_producto' => $id_producto,
                        'cantidad' => $cantidad,
                        'precio_unitario' => $precio_unitario
                    ];
                    $productosValidos++;
                }
            }

            if ($productosValidos === 0) {
                throw new Exception('Debe seleccionar al menos un producto válido');
            }

            // Procesar métodos de pago si existen
            $metodosPago = [];
            if (isset($_POST['id_metodopago']) && is_array($_POST['id_metodopago'])) {
                $totalMetodosPago = 0;
                $metodosPagoUnicos = [];
                for ($i = 0; $i < count($_POST['id_metodopago']); $i++) {
                    $idMetodo = intval($_POST['id_metodopago'][$i]);
                    $montoMetodo = floatval($_POST['monto_metodopago'][$i]);
                    if ($idMetodo > 0 && $montoMetodo > 0) {
                        $key = $idMetodo . '-' . $montoMetodo;
                        if (!isset($metodosPagoUnicos[$key])) {
                            $metodo = [
                                'id_metodopago' => $idMetodo,
                                'monto_usd' => $montoMetodo,
                                'monto_bs' => 0.00,
                                'referencia' => null,
                                'banco_emisor' => null,
                                'banco_receptor' => null,
                                'telefono_emisor' => null
                            ];
                            $nombreMetodo = obtenerNombreMetodoPago($metodo['id_metodopago']);
                            procesarDetallesMetodoPago($metodo, $nombreMetodo, $_POST);
                            $metodosPago[] = $metodo;
                            $totalMetodosPago += $metodo['monto_usd'];
                            $metodosPagoUnicos[$key] = true;
                        }
                    }
                }
                // Validar que la suma de métodos de pago coincida con el total
                if (abs($totalMetodosPago - $datosVenta['precio_total']) > 0.01) {
                    throw new Exception('La suma de los métodos de pago ($' . number_format($totalMetodosPago, 2) . ') no coincide con el total de la venta ($' . number_format($datosVenta['precio_total'], 2) . ')');
                }
                if (empty($metodosPago)) {
                    throw new Exception('Debe seleccionar al menos un método de pago válido');
                }
            } else {
                throw new Exception('Debe seleccionar al menos un método de pago');
            }

            // Agregar métodos de pago a los datos de la venta
            $datosVenta['metodos_pago'] = $metodosPago;

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
                'estado' => $_POST['estado_pedido']
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
                'estado' => $_POST['estado_delivery']
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
} else if ($_SESSION["nivel_rol"] >=2 && tieneAcceso(4, 'ver')) {
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
  $pagina_actual = isset($_GET['pagina']) ? $_GET['pagina'] : 'salida';
    require_once 'vista/salida.php';
} else {
      require_once 'vista/seguridad/privilegio.php';

}  if ($_SESSION["nivel_rol"] == 1) {
    header("Location: ?pagina=catalogo");
    exit();
}

// Si es una solicitud GET normal, mostrar la vista
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Consultar datos actualizados para la vista
    $ventas = $salida->consultarVentas();
    $productos_lista = $salida->consultarProductos();
    $metodos_pago = $salida->consultarMetodosPago();

    // Cargar la vista
    if ($_SESSION["nivel_rol"] == 1) {
    header("Location: ?pagina=catalogo");
    exit();
    } else if ($_SESSION["nivel_rol"] >= 2 && tieneAcceso(4, 'ver')) {
             $pagina_actual = isset($_GET['pagina']) ? $_GET['pagina'] : 'salida';
            require_once 'vista/salida.php';
    } else {
            require_once 'vista/seguridad/privilegio.php';

    }
}


?>