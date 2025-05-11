<?php
session_start();
if (empty($_SESSION["id"])) {
    header("location:?pagina=login");
    exit;
} /* Validacion URL */

require_once 'modelo/entrada.php';

$entrada = new Entrada();

// Detectar si la solicitud es AJAX
function esAjax() {
    return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
}

// Función para sanitizar datos de entrada
function sanitizar($dato) {
    return htmlspecialchars(trim($dato), ENT_QUOTES, 'UTF-8');
}

// Procesar el registro de una nueva compra
if (isset($_POST['registrar_compra'])) {
    // Recibimos los datos de la compra
    if (isset($_POST['id_proveedor']) && isset($_POST['fecha_entrada'])) {
        $entrada->set_Fecha_entrada(sanitizar($_POST['fecha_entrada']));
        $entrada->set_Id_proveedor(intval($_POST['id_proveedor']));
        
        // Procesamos los detalles de los productos
        $detalles = [];
        if (isset($_POST['id_producto']) && is_array($_POST['id_producto'])) {
            for ($i = 0; $i < count($_POST['id_producto']); $i++) {
                if (!empty($_POST['id_producto'][$i]) && isset($_POST['cantidad'][$i]) && $_POST['cantidad'][$i] > 0) {
                    $detalle = [
                        'id_producto' => intval($_POST['id_producto'][$i]),
                        'cantidad' => intval($_POST['cantidad'][$i]),
                        'precio_unitario' => floatval($_POST['precio_unitario'][$i]),
                        'precio_total' => floatval($_POST['precio_total'][$i])
                    ];
                    $detalles[] = $detalle;
                }
            }
        }
        
        $entrada->set_Detalles($detalles);
        
        // Registramos la compra
        $respuesta = $entrada->registrar();
        
        
        if (esAjax()) {
            // Devolver respuesta JSON para peticiones AJAX
            header('Content-Type: application/json');
            echo json_encode($respuesta);
            exit;
        } else {
            // Respuesta normal para peticiones no-AJAX
            if ($respuesta['respuesta'] == 1) {
                $_SESSION['mensaje'] = "Compra registrada exitosamente";
                $_SESSION['tipo_mensaje'] = "success";
            } else {
                $_SESSION['mensaje'] = "Error al registrar la compra: " . (isset($respuesta['error']) ? $respuesta['error'] : "");
                $_SESSION['tipo_mensaje'] = "danger";
            }
            
            header("Location: ?pagina=entrada");
            exit;
        }
    }
}

// Procesar la modificación de una compra
if (isset($_POST['modificar_compra'])) {
    if (isset($_POST['id_compra']) && isset($_POST['id_proveedor']) && isset($_POST['fecha_entrada'])) {
        $entrada->set_Id_compra(intval($_POST['id_compra']));
        $entrada->set_Fecha_entrada(sanitizar($_POST['fecha_entrada']));
        $entrada->set_Id_proveedor(intval($_POST['id_proveedor']));
        
        // Procesamos los detalles de los productos
        $detalles = [];
        if (isset($_POST['id_producto']) && is_array($_POST['id_producto'])) {
            for ($i = 0; $i < count($_POST['id_producto']); $i++) {
                if (!empty($_POST['id_producto'][$i]) && isset($_POST['cantidad'][$i]) && $_POST['cantidad'][$i] > 0) {
                    $detalle = [
                        'id_producto' => intval($_POST['id_producto'][$i]),
                        'cantidad' => intval($_POST['cantidad'][$i]),
                        'precio_unitario' => floatval($_POST['precio_unitario'][$i]),
                        'precio_total' => floatval($_POST['precio_total'][$i])
                    ];
                    $detalles[] = $detalle;
                }
            }
        }
        
        $entrada->set_Detalles($detalles);
        
        // Modificamos la compra
        $respuesta = $entrada->modificar();
        
        
        if (esAjax()) {
            // Devolver respuesta JSON para peticiones AJAX
            header('Content-Type: application/json');
            echo json_encode($respuesta);
            exit;
        } else {
            // Respuesta normal para peticiones no-AJAX
            if ($respuesta['respuesta'] == 1) {
                $_SESSION['mensaje'] = "Compra actualizada exitosamente";
                $_SESSION['tipo_mensaje'] = "success";
            } else {
                $_SESSION['mensaje'] = "Error al actualizar la compra: " . (isset($respuesta['error']) ? $respuesta['error'] : "");
                $_SESSION['tipo_mensaje'] = "danger";
            }
            
            header("Location: ?pagina=entrada");
            exit;
        }
    }
}

// Procesar la eliminación de una compra
if (isset($_POST['eliminar_compra'])) {
    if (isset($_POST['id_compra'])) {
        $entrada->set_Id_compra(intval($_POST['id_compra']));
        
        // Eliminamos la compra
        $respuesta = $entrada->eliminar();
        
        
        if (esAjax()) {
            // Devolver respuesta JSON para peticiones AJAX
            header('Content-Type: application/json');
            echo json_encode($respuesta);
            exit;
        } else {
            // Respuesta normal para peticiones no-AJAX
            if ($respuesta['respuesta'] == 1) {
                $_SESSION['mensaje'] = "Compra eliminada exitosamente";
                $_SESSION['tipo_mensaje'] = "success";
            } else {
                $_SESSION['mensaje'] = "Error al eliminar la compra: " . (isset($respuesta['error']) ? $respuesta['error'] : "");
                $_SESSION['tipo_mensaje'] = "danger";
            }
            
            header("Location: ?pagina=entrada");
            exit; 
        }
    }
}

// Consultar datos para la vista
$compras = $entrada->consultar();

// Si hay un ID en la URL, consultamos los detalles de esa compra
$detalles_compra = [];
if (isset($_GET['id'])) {
    $detalles_compra = $entrada->consultarDetalles(intval($_GET['id']));
}

// Obtener la lista de productos y proveedores para los formularios
$productos_lista = $entrada->consultarProductos();
$proveedores = $entrada->consultarProveedores();

// Cargamos la vista
require_once 'vista/entrada.php';
?>