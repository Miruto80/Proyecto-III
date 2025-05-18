<?php
session_start();
if (empty($_SESSION["id"])) {
    header("location:?pagina=login");
    exit;
} /* Validacion URL */

require_once 'modelo/salida.php';

$salida = new Salida();

// Detectar si la solicitud es AJAX
function esAjax() {
    return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
}

// Función para sanitizar datos de entrada
function sanitizar($dato) {
    return htmlspecialchars(trim($dato), ENT_QUOTES, 'UTF-8');
}

// Procesar el registro de un nuevo pedido
if (isset($_POST['registrar'])) {
    // Recopilar datos del pedido
    $datosPedido = array(
        'tipo' => isset($_POST['tipo']) ? sanitizar($_POST['tipo']) : 'salida',
        'estado' => isset($_POST['estado']) ? sanitizar($_POST['estado']) : 'pendiente',
        'precio_total' => isset($_POST['precio_total_general']) ? floatval($_POST['precio_total_general']) : 0,
        'referencia_bancaria' => isset($_POST['referencia_bancaria']) ? sanitizar($_POST['referencia_bancaria']) : null,
        'telefono_emisor' => isset($_POST['telefono_emisor']) ? sanitizar($_POST['telefono_emisor']) : null,
        'banco' => isset($_POST['banco']) ? sanitizar($_POST['banco']) : null,
        'id_entrega' => isset($_POST['id_entrega']) ? intval($_POST['id_entrega']) : null,
        'id_metodopago' => isset($_POST['id_metodopago']) ? intval($_POST['id_metodopago']) : null
    );
    
    $salida->set_DatosPedido($datosPedido);
    
    // Recopilar detalles del pedido
    $detalles = array();
    if (isset($_POST['id_producto']) && is_array($_POST['id_producto'])) {
        for ($i = 0; $i < count($_POST['id_producto']); $i++) {
            if (!empty($_POST['id_producto'][$i]) && isset($_POST['cantidad'][$i]) && $_POST['cantidad'][$i] > 0) {
                $detalle = array(
                    'id_producto' => intval($_POST['id_producto'][$i]),
                    'cantidad' => intval($_POST['cantidad'][$i]),
                    'precio_unitario' => floatval($_POST['precio_unitario'][$i])
                );
                $detalles[] = $detalle;
            }
        }
    }
    
    $salida->set_DetallesPedido($detalles);
    
    // Validar datos
    if (empty($detalles) || 
        empty($datosPedido['id_entrega']) || 
        empty($datosPedido['id_metodopago'])) {
        
        $_SESSION['mensaje'] = "Error: Datos incompletos para el registro del pedido.";
        $_SESSION['tipo_mensaje'] = "danger";
        header("Location: ?pagina=salida");
        exit;
    }
    
    // Registrar pedido
    $respuesta = $salida->registrarPedido();
    
    if (esAjax()) {
        // Devolver respuesta JSON para peticiones AJAX
        header('Content-Type: application/json');
        echo json_encode($respuesta);
        exit;
    } else {
        // Respuesta normal para peticiones no-AJAX
        if ($respuesta['respuesta'] == 1) {
            $_SESSION['mensaje'] = "Pedido registrado correctamente con ID: {$respuesta['id_pedido']}";
            $_SESSION['tipo_mensaje'] = "success";
        } else {
            $_SESSION['mensaje'] = "Error al registrar el pedido: " . (isset($respuesta['error']) ? $respuesta['error'] : "");
            $_SESSION['tipo_mensaje'] = "danger";
        }
        
        header("Location: ?pagina=salida");
        exit;
    }
}

// Procesar la actualización de un pedido existente
if (isset($_POST['actualizar'])) {
    if (isset($_POST['id_pedido'])) {
        $id_pedido = intval($_POST['id_pedido']);
        
        // Recopilar datos del pedido
        $datosPedido = array(
            'estado' => isset($_POST['estado']) ? sanitizar($_POST['estado']) : 'pendiente',
            'referencia_bancaria' => isset($_POST['referencia_bancaria']) ? sanitizar($_POST['referencia_bancaria']) : null,
            'telefono_emisor' => isset($_POST['telefono_emisor']) ? sanitizar($_POST['telefono_emisor']) : null,
            'banco' => isset($_POST['banco']) ? sanitizar($_POST['banco']) : null,
            'id_entrega' => isset($_POST['id_entrega']) ? intval($_POST['id_entrega']) : null,
            'id_metodopago' => isset($_POST['id_metodopago']) ? intval($_POST['id_metodopago']) : null
        );
        
        $salida->set_Id_pedido($id_pedido);
        $salida->set_DatosPedido($datosPedido);
        
        // Actualizar pedido
        $respuesta = $salida->actualizarPedido();
        
        if (esAjax()) {
            // Devolver respuesta JSON para peticiones AJAX
            header('Content-Type: application/json');
            echo json_encode($respuesta);
            exit;
        } else {
            // Respuesta normal para peticiones no-AJAX
            if ($respuesta['respuesta'] == 1) {
                $_SESSION['mensaje'] = "Pedido actualizado correctamente";
                $_SESSION['tipo_mensaje'] = "success";
            } else {
                $_SESSION['mensaje'] = "Error al actualizar el pedido: " . (isset($respuesta['error']) ? $respuesta['error'] : "");
                $_SESSION['tipo_mensaje'] = "danger";
            }
            
            header("Location: ?pagina=salida");
            exit;
        }
    }
}

// Procesar la eliminación de un pedido
if (isset($_POST['eliminar'])) {
    if (isset($_POST['id_pedido'])) {
        $id_pedido = intval($_POST['id_pedido']);
        $salida->set_Id_pedido($id_pedido);
        
        // Eliminar pedido
        $respuesta = $salida->eliminarPedido();
        
        if (esAjax()) {
            // Devolver respuesta JSON para peticiones AJAX
            header('Content-Type: application/json');
            echo json_encode($respuesta);
            exit;
        } else {
            // Respuesta normal para peticiones no-AJAX
            if ($respuesta['respuesta'] == 1) {
                $_SESSION['mensaje'] = "Pedido eliminado correctamente";
                $_SESSION['tipo_mensaje'] = "success";
            } else {
                $_SESSION['mensaje'] = "Error al eliminar el pedido: " . (isset($respuesta['error']) ? $respuesta['error'] : "");
                $_SESSION['tipo_mensaje'] = "danger";
            }
            
            header("Location: ?pagina=salida");
            exit;
        }
    }
}

// Actualizar el estado de un pedido
if (isset($_POST['actualizar_estado'])) {
    if (isset($_POST['id_pedido']) && isset($_POST['estado'])) {
        $id_pedido = intval($_POST['id_pedido']);
        $estado = sanitizar($_POST['estado']);
        
        $salida->set_Id_pedido($id_pedido);
        $salida->set_Estado($estado);
        
        // Actualizar estado
        $respuesta = $salida->actualizarEstadoPedido();
        
        if (esAjax()) {
            // Devolver respuesta JSON para peticiones AJAX
            header('Content-Type: application/json');
            echo json_encode($respuesta);
            exit;
        } else {
            // Respuesta normal para peticiones no-AJAX
            if ($respuesta['respuesta'] == 1) {
                $_SESSION['mensaje'] = "Estado del pedido actualizado correctamente";
                $_SESSION['tipo_mensaje'] = "success";
            } else {
                $_SESSION['mensaje'] = "Error al actualizar el estado del pedido: " . (isset($respuesta['error']) ? $respuesta['error'] : "");
                $_SESSION['tipo_mensaje'] = "danger";
            }
            
            header("Location: ?pagina=salida");
            exit;
        }
    }
}

// Carga los detalles de un pedido específico para mostrarlos en modal
if (isset($_POST['cargar_detalles']) && isset($_POST['id_pedido'])) {
    $id_pedido = intval($_POST['id_pedido']);
    
    // Obtener pedido y sus detalles
    $pedido = $salida->obtenerPedido($id_pedido);
    $detalles = $salida->obtenerDetallesPedido($id_pedido);
    
    // Devolver los datos en formato JSON
    header('Content-Type: application/json');
    echo json_encode([
        'pedido' => $pedido,
        'detalles' => $detalles
    ]);
    exit;
}

// Consultar datos para la vista
$pedidos = $salida->listarPedidos();

// Si hay un ID en la URL, consultamos los detalles de ese pedido
$detalles_pedido = [];
if (isset($_GET['id'])) {
    $detalles_pedido = $salida->obtenerDetallesPedido(intval($_GET['id']));
}

// Obtener datos necesarios para formularios
$metodosPago = $salida->obtenerMetodosPago();
$metodosEntrega = $salida->obtenerMetodosEntrega();
$productos = $salida->obtenerProductos();

// Cargamos la vista
if($_SESSION["nivel_rol"] == 3) { // Validacion si es administrador entra
    require_once 'vista/salida.php';
}else{
    require_once 'vista/seguridad/privilegio.php';
}
?>