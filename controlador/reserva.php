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
          isset($_POST['eliminar_detalle']) || isset($_POST['cambiar_estado']);

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

$objreserva = new Reserva();

try {
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
    if(isset($_POST['registrar'])) {
        if(!empty($_POST['fecha_apartado']) && !empty($_POST['id_persona'])) { 
            
            // Datos de encabezado de la reserva
            $objreserva->set_Fecha_apartado($_POST['fecha_apartado']);
            $objreserva->set_Id_persona($_POST['id_persona']);
            $objreserva->set_Estatus(1); // Activo por defecto
            
            // Datos de detalle de reserva
            $productos = isset($_POST['productos']) ? $_POST['productos'] : [];
            $cantidades = isset($_POST['cantidades']) ? $_POST['cantidades'] : [];
            $precios_unit = isset($_POST['precios_unit']) ? $_POST['precios_unit'] : [];
            
            // Validar que haya al menos un producto
            if(count($productos) == 0) {
                echo json_encode(['respuesta' => 0, 'accion' => 'incluir', 'mensaje' => 'Debe incluir al menos un producto']);
                exit;
            }
            
            $result = $objreserva->registrar($productos, $cantidades, $precios_unit);
            echo json_encode($result);
            exit;
        } else {
            echo json_encode(['respuesta' => 0, 'accion' => 'incluir', 'mensaje' => 'Campos requeridos incompletos']);
            exit;
        }
    } elseif (isset($_POST['modificar'])) {
        if (!empty($_POST['id_reserva']) && !empty($_POST['fecha_apartado']) && !empty($_POST['id_persona'])) {
        $objreserva->set_Id_reserva($_POST['id_reserva']);
        $objreserva->set_Fecha_apartado($_POST['fecha_apartado']);
        $objreserva->set_Id_persona($_POST['id_persona']);
    
            $result = $objreserva->modificar();
            echo json_encode($result);
            exit;
        } else {
            echo json_encode(['respuesta' => 0, 'accion' => 'actualizar', 'mensaje' => 'Datos incompletos para actualizar la reserva']);
            exit;
        }
    } elseif (isset($_POST['eliminar'])) {
        if (!empty($_POST['id_reserva'])) {
            $objreserva->set_Id_reserva($_POST['id_reserva']);
            $result = $objreserva->eliminar();
            echo json_encode($result);
            exit;
        } else {
            echo json_encode(['respuesta' => 0, 'accion' => 'eliminar', 'mensaje' => 'ID no proporcionado']);
            exit;
        }
    } elseif (isset($_POST['consultar_reserva'])) {
        if (!empty($_POST['id_reserva'])) {
            $objreserva->set_Id_reserva($_POST['id_reserva']);
            $result = $objreserva->consultarPorId();
            
            // Si la consulta fue exitosa, agregamos información adicional del cliente
            if ($result) {
                $objreserva->set_Id_persona($result['id_persona']);
                $cliente = $objreserva->obtenerDatosCliente();
                if ($cliente) {
                    // Agregar el nombre completo para mostrarlo en el modal de detalles
                    $result['nombre_completo'] = $cliente['nombre'] . ' ' . $cliente['apellido'];
                }
            }
            
            echo json_encode($result);
            exit;
        } else {
            echo json_encode(['respuesta' => 0, 'accion' => 'consultar', 'mensaje' => 'ID no proporcionado']);
            exit;
        }
    } elseif (isset($_POST['consultar_detalle'])) {
        if (!empty($_POST['id_reserva'])) {
            $objreserva->set_Id_reserva($_POST['id_reserva']);
            $result = $objreserva->consultarDetalle();
            echo json_encode($result);
            exit;
        } else {
            echo json_encode(['respuesta' => 0, 'accion' => 'consultar_detalle', 'mensaje' => 'ID de reserva no proporcionado']);
            exit;
        }
    } elseif (isset($_POST['eliminar_detalle'])) {
        if (!empty($_POST['id_detalle'])) {
            $result = $objreserva->eliminarDetalle($_POST['id_detalle']);
            echo json_encode($result);
            exit;
        } else {
            echo json_encode(['respuesta' => 0, 'accion' => 'eliminar_detalle', 'mensaje' => 'ID de detalle no proporcionado']);
            exit;
        }
    } elseif (isset($_POST['consultar_persona'])) {
        $result = $objreserva->consultarPersonas();
        echo json_encode($result);
        exit;
    } elseif (isset($_POST['consultar_productos'])) {
        $result = $objreserva->consultarProductos();
        echo json_encode($result);
        exit;
    } else {
        // Si no es una solicitud AJAX, cargar la vista
          if ($_SESSION["nivel_rol"] != 2 && $_SESSION["nivel_rol"] != 3) {
                header("Location: ?pagina=catalogo");
                exit();
        }
        require_once 'vista/reserva.php';
    }
} catch (Exception $e) {
    if ($is_ajax) {
        echo json_encode(['respuesta' => 0, 'accion' => 'error', 'mensaje' => $e->getMessage()]);
        exit;
    } else {
        
        // Mostrar error en la interfaz
        $error_message = $e->getMessage();
         $id_persona = $_SESSION["id"];
        $accion = 'Acceso a Módulo';
        $descripcion = 'módulo de Reverva';
        $objreserva->registrarBitacora($id_persona, $accion, $descripcion);
        
        if ($_SESSION["nivel_rol"] != 2 && $_SESSION["nivel_rol"] != 3) {
                header("Location: ?pagina=catalogo");
                exit();
        }
        require_once 'vista/reserva.php';
    }
}