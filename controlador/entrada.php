<?php
session_start();
if (empty($_SESSION["id"])) {
    header("location:?pagina=login");
    exit;
} /* Validacion URL */

require_once 'modelo/entrada.php';

$entrada = new Entrada();

// Procesar el registro de una nueva entrada
if (isset($_POST['registrar_compra'])) {
    // Recibimos los datos de la entrada
    if (isset($_POST['fecha_entrada']) && isset($_POST['id_proveedor'])) {
        $entrada->set_Fecha_entrada($_POST['fecha_entrada']);
        $entrada->set_Id_proveedor($_POST['id_proveedor']);
        
        // Procesamos los detalles de los productos
        $detalles = [];
        if (isset($_POST['id_producto']) && is_array($_POST['id_producto'])) {
            for ($i = 0; $i < count($_POST['id_producto']); $i++) {
                if (!empty($_POST['id_producto'][$i]) && $_POST['cantidad'][$i] > 0) {
                    $detalle = [
                        'id_producto' => $_POST['id_producto'][$i],
                        'cantidad' => $_POST['cantidad'][$i],
                        'precio_unitario' => $_POST['precio_unitario'][$i],
                        'precio_total' => $_POST['precio_total'][$i]
                    ];
                    $detalles[] = $detalle;
                }
            }
        }
        
        $entrada->set_Detalles($detalles);
        
        // Registramos la entrada
        $respuesta = $entrada->registrar();
        
        if ($respuesta['respuesta'] == 1) {
            $_SESSION['mensaje'] = "Entrada registrada correctamente.";
            $_SESSION['tipo_mensaje'] = "success";
        } else {
            $_SESSION['mensaje'] = "Error al registrar la entrada: " . (isset($respuesta['error']) ? $respuesta['error'] : "");
            $_SESSION['tipo_mensaje'] = "danger";
        }
        
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            // Si es una petición AJAX, devolvemos JSON
            echo json_encode($respuesta);
            exit;
        } else {
            // Si es una petición normal, redirigimos
            header("location:?pagina=entrada");
            exit;
        }
    }
}

// Procesar la modificación de una entrada
if (isset($_POST['modificar_compra'])) {
    if (isset($_POST['id_compra']) && isset($_POST['fecha_entrada']) && isset($_POST['id_proveedor'])) {
        $entrada->set_Id_compra($_POST['id_compra']);
        $entrada->set_Fecha_entrada($_POST['fecha_entrada']);
        $entrada->set_Id_proveedor($_POST['id_proveedor']);
        
        // Procesamos los detalles de los productos
        $detalles = [];
        if (isset($_POST['id_producto']) && is_array($_POST['id_producto'])) {
            for ($i = 0; $i < count($_POST['id_producto']); $i++) {
                if (!empty($_POST['id_producto'][$i]) && $_POST['cantidad'][$i] > 0) {
                    $detalle = [
                        'id_producto' => $_POST['id_producto'][$i],
                        'cantidad' => $_POST['cantidad'][$i],
                        'precio_unitario' => $_POST['precio_unitario'][$i],
                        'precio_total' => $_POST['precio_total'][$i]
                    ];
                    $detalles[] = $detalle;
                }
            }
        }
        
        $entrada->set_Detalles($detalles);
        
        // Modificamos la entrada
        $respuesta = $entrada->modificar();
        
        if ($respuesta['respuesta'] == 1) {
            $_SESSION['mensaje'] = "Entrada modificada correctamente.";
            $_SESSION['tipo_mensaje'] = "success";
        } else {
            $_SESSION['mensaje'] = "Error al modificar la entrada: " . (isset($respuesta['error']) ? $respuesta['error'] : "");
            $_SESSION['tipo_mensaje'] = "danger";
        }
        
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            // Si es una petición AJAX, devolvemos JSON
            echo json_encode($respuesta);
            exit;
        } else {
            // Si es una petición normal, redirigimos
            header("location:?pagina=entrada");
            exit;
        }
    }
}

// Procesar la eliminación de una entrada
if (isset($_POST['eliminar_compra'])) {
    if (isset($_POST['id_compra'])) {
        $entrada->set_Id_compra($_POST['id_compra']);
        
        // Eliminamos la entrada
        $respuesta = $entrada->eliminar();
        
        if ($respuesta['respuesta'] == 1) {
            $_SESSION['mensaje'] = "Entrada eliminada correctamente.";
            $_SESSION['tipo_mensaje'] = "success";
        } else {
            $_SESSION['mensaje'] = "Error al eliminar la entrada: " . (isset($respuesta['error']) ? $respuesta['error'] : "");
            $_SESSION['tipo_mensaje'] = "danger";
        }
        
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            // Si es una petición AJAX, devolvemos JSON
            echo json_encode($respuesta);
            exit;
        } else {
            // Si es una petición normal, redirigimos
            header("location:?pagina=entrada");
            exit;
        }
    }
}

// Consultar datos para la vista
$compras = $entrada->consultar();

// Si hay un ID en la URL, consultamos los detalles de esa compra
if (isset($_GET['id'])) {
    $detalles_compra = $entrada->consultarDetalles($_GET['id']);
}

// Obtener la lista de productos y proveedores para los formularios
$productos_lista = $entrada->consultarProductos();
$proveedores = $entrada->consultarProveedores();

// Cargamos la vista
require_once 'vista/entrada.php';
?>