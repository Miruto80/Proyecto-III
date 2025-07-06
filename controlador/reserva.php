<?php  
// Iniciar la sesión solo si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Asegurarse de que todas las respuestas AJAX sean en formato JSON
$is_ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
          strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ||
          isset($_POST['registrar']) || isset($_POST['modificar']) || 
          isset($_POST['eliminar']) || isset($_POST['consultar_reserva']) ||
          isset($_POST['consultar_detalle']) || isset($_POST['consultar_precio']) ||
          isset($_POST['eliminar_detalle']) || isset($_POST['cambiar_estado']) ||
          isset($_POST['registrar_json']) || isset($_POST['modificar_json']) || isset($_POST['eliminar_json']) || isset($_POST['cambiar_estado_json']) || isset($_POST['consultar_persona']) || isset($_POST['consultar_productos']);

if ($is_ajax) {
    // Para solicitudes AJAX, configurar las cabeceras para JSON
    header('Content-Type: application/json');
}

// Verificar la sesión para todas las solicitudes
if (empty($_SESSION["id"])) {
    if ($is_ajax) {
        // Si es una solicitud AJAX, devolver un error JSON
        echo json_encode(['respuesta' => 0, 'accion' => 'error', 'mensaje' => 'Sesión expirada']);
        exit;
    } else {
        // Redireccionar a la página de login
        header("location:?pagina=login");
        exit;
    }
}

require_once 'modelo/reserva.php';
require_once 'modelo/producto.php';
 require_once 'permiso.php';

$objreserva = new Reserva();

try {
    // Chequeo de sesión para AJAX
    if ($is_ajax && empty($_SESSION["id"])) {
        echo json_encode(['respuesta' => 0, 'accion' => 'error', 'mensaje' => 'Sesión expirada (AJAX)']);
        exit;
    }
    
    // Manejar consultas de precio de producto para la función consultarPrecioProducto() en reserva.js
    if (isset($_POST['consultar_precio']) && !empty($_POST['id_producto'])) {
        $id_producto = $_POST['id_producto'];
        $objproductos->set_Id_producto($id_producto);
        $result = $objproductos->consultarPorId();
        if ($result && isset($result['precio_detal'])) {
            echo json_encode($result);
        } else {
            echo json_encode(['respuesta' => 0, 'mensaje' => 'Producto no encontrado']);
        }
        exit;
    }
    
    // Manejar cambio de estado de reserva
    if (isset($_POST['cambiar_estado']) && !empty($_POST['id_reserva']) && isset($_POST['nuevo_estatus'])) {
        $objreserva->set_Id_reserva($_POST['id_reserva']);
        $result = $objreserva->cambiarEstado($_POST['nuevo_estatus']);
        echo json_encode($result);
        exit;
    }
    
    // Manejar operaciones de reserva
    if(isset($_POST['registrar_json'])) {
        $productos = [];
        if (isset($_POST['productos']) && is_array($_POST['productos'])) {
            for ($i = 0; $i < count($_POST['productos']); $i++) {
                $productos[] = [
                    'id_producto' => $_POST['productos'][$i],
                    'cantidad' => $_POST['cantidades'][$i],
                    'precio' => $_POST['precios_unit'][$i]
                ];
            }
        }
        $datosReserva = [
            'operacion' => 'registrar',
            'datos' => [
                'fecha_apartado' => $_POST['fecha_apartado'],
                'id_persona' => $_POST['id_persona'],
                'productos' => $productos,
                'id_persona_bitacora' => $_SESSION['id']
            ]
        ];
        $resultadoRegistro = $objreserva->procesarReserva(json_encode($datosReserva));
        echo json_encode($resultadoRegistro);
        exit;
    } elseif (isset($_POST['modificar_json'])) {
        $datosReserva = [
            'operacion' => 'modificar',
            'datos' => [
                'id_reserva' => $_POST['id_reserva'],
                'fecha_apartado' => $_POST['fecha_apartado'],
                'id_persona' => $_POST['id_persona'],
                'id_persona_bitacora' => $_SESSION['id']
            ]
        ];
        $resultado = $objreserva->procesarReserva(json_encode($datosReserva));
        echo json_encode($resultado);
        exit;
    } elseif (isset($_POST['eliminar_json'])) {
        $datosReserva = [
            'operacion' => 'eliminar',
            'datos' => [
                'id_reserva' => $_POST['id_reserva'],
                'id_persona' => $_SESSION['id']
            ]
        ];
        $resultado = $objreserva->procesarReserva(json_encode($datosReserva));
        echo json_encode($resultado);
        exit;
    } elseif (isset($_POST['cambiar_estado_json'])) {
        $datosReserva = [
            'operacion' => 'cambiar_estado',
            'datos' => [
                'id_reserva' => $_POST['id_reserva'],
                'nuevo_estatus' => $_POST['nuevo_estatus'],
                'id_persona' => $_SESSION['id']
            ]
        ];
        $resultado = $objreserva->procesarReserva(json_encode($datosReserva));
        echo json_encode($resultado);
        exit;
    } elseif (isset($_POST['operacion']) && $_POST['operacion'] === 'consultar_reserva') {
        if (empty($_SESSION["id"])) {
            echo json_encode(['respuesta' => 0, 'accion' => 'error', 'mensaje' => 'Sesión expirada (AJAX consultar_reserva)']);
            exit;
        }
        $datos = [
            'operacion' => 'consultar_reserva',
            'datos' => [
                'id_reserva' => $_POST['datos']['id_reserva']
            ]
        ];
        $resultado = $objreserva->procesarReserva(json_encode($datos));
        echo json_encode($resultado);
        exit;
    } elseif (isset($_POST['operacion']) && $_POST['operacion'] === 'consultar_detalle') {
        if (empty($_SESSION["id"])) {
            echo json_encode(['respuesta' => 0, 'accion' => 'error', 'mensaje' => 'Sesión expirada (AJAX consultar_detalle)']);
            exit;
        }
        $datos = [
            'operacion' => 'consultar_detalle',
            'datos' => [
                'id_reserva' => $_POST['datos']['id_reserva']
            ]
        ];
        $resultado = $objreserva->procesarReserva(json_encode($datos));
        echo json_encode($resultado);
        exit;
    } elseif (isset($_POST['consultar_persona'])) {
        echo json_encode(['respuesta' => 0, 'mensaje' => 'Consulta de personas no implementada en el nuevo flujo.']);
        exit;
    } elseif (isset($_POST['consultar_productos'])) {
        echo json_encode(['respuesta' => 0, 'mensaje' => 'Consulta de productos no implementada en el nuevo flujo.']);
        exit;
    } else {
       if ($_SESSION["nivel_rol"] == 1) {
            header("Location: ?pagina=catalogo");
            exit();
        }           
        // Consultar reservas para la vista
        $resultado = $objreserva->procesarReserva(json_encode(['operacion' => 'consultar']));
        $reservas = ($resultado['respuesta'] == 1) ? $resultado['datos'] : [];
        // Consultar personas para el select
        $resultadoPersonas = $objreserva->procesarReserva(json_encode(['operacion' => 'consultar_personas']));
        $personas = ($resultadoPersonas['respuesta'] == 1) ? $resultadoPersonas['datos'] : [];
        // Consultar productos para el select
        $resultadoProductos = $objreserva->procesarReserva(json_encode(['operacion' => 'consultar_productos']));
        $productos = ($resultadoProductos['respuesta'] == 1) ? $resultadoProductos['datos'] : [];
       
       if ($_SESSION["nivel_rol"] >= 2 && tieneAcceso(5, 'ver')) {
 
                 require_once 'vista/reserva.php';
        } else {
                require_once 'vista/seguridad/privilegio.php';

        }

    }
} catch (Exception $e) {
    if ($is_ajax) {
        echo json_encode(['respuesta' => 0, 'accion' => 'error', 'mensaje' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        exit;
    } else {
        
        // Mostrar error en la interfaz
        $error_message = $e->getMessage();
         $id_persona = $_SESSION["id"];
        $accion = 'Acceso a Módulo';
        $descripcion = 'módulo de Reserva';
        $objreserva->registrarBitacora(json_encode([
            'id_persona' => $id_persona,
            'accion' => $accion,
            'descripcion' => $descripcion
        ]));
        
        if ($_SESSION["nivel_rol"] == 1) {
            header("Location: ?pagina=catalogo");
            exit();
        }
        if ($_SESSION["nivel_rol"] >= 2 && tieneAcceso(5, 'ver')) {
 
                 require_once 'vista/reserva.php';
        } else {
                require_once 'vista/seguridad/privilegio.php';

        }
                
     }
}



