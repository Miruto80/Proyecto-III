<?php  
// Iniciar la sesión solo si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Asegurarse de que todas las respuestas AJAX sean en formato JSON
$is_ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
          strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ||
          isset($_POST['registrar']) || isset($_POST['modificar']) || 
          isset($_POST['eliminar']) || isset($_POST['consultar_proveedor']);

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

require_once 'modelo/proveedor.php';

$objproveedor = new Proveedor(); 

try {
    if(isset($_POST['registrar'])) {
        if(!empty($_POST['nombre']) && 
           !empty($_POST['numero_documento']) && 
           !empty($_POST['tipo_documento'])) { 
            
            $objproveedor->set_Numero_documento($_POST['numero_documento']);
            $objproveedor->set_Tipo_documento($_POST['tipo_documento']);
            $objproveedor->set_Nombre($_POST['nombre']);
            $objproveedor->set_Correo($_POST['correo']);
            $objproveedor->set_Telefono($_POST['telefono']);
            $objproveedor->set_Direccion($_POST['direccion']);
            
            $result = $objproveedor->registrar();
            echo json_encode($result);
            exit;
        } else {
            echo json_encode(['respuesta' => 0, 'accion' => 'incluir', 'mensaje' => 'Campos requeridos incompletos']);
            exit;
        }
    } elseif (isset($_POST['modificar'])) {
        if (!empty($_POST['id_proveedor']) && !empty($_POST['nombre'])) {
            $objproveedor->set_Id_proveedor($_POST['id_proveedor']);
            $objproveedor->set_Numero_documento($_POST['numero_documento']);
            $objproveedor->set_Tipo_documento($_POST['tipo_documento']);
            $objproveedor->set_Nombre($_POST['nombre']);
            $objproveedor->set_Correo($_POST['correo']);
            $objproveedor->set_Telefono($_POST['telefono']);
            $objproveedor->set_Direccion($_POST['direccion']);
            
            $result = $objproveedor->modificar();
            echo json_encode($result);
            exit;
        } else {
            echo json_encode(['respuesta' => 0, 'accion' => 'actualizar', 'mensaje' => 'ID o nombre no proporcionados']);
            exit;
        }
    } elseif (isset($_POST['eliminar'])) {
        if (!empty($_POST['id_proveedor'])) {
            $objproveedor->set_Id_proveedor($_POST['id_proveedor']);
            $result = $objproveedor->eliminar();
            echo json_encode($result);
            exit;
        } else {
            echo json_encode(['respuesta' => 0, 'accion' => 'eliminar', 'mensaje' => 'ID no proporcionado']);
            exit;
        }
    } elseif (isset($_POST['consultar_proveedor'])) {
        if (!empty($_POST['id_proveedor'])) {
            $objproveedor->set_Id_proveedor($_POST['id_proveedor']);
            $result = $objproveedor->consultarPorId();
            echo json_encode($result);
            exit;
        } else {
            echo json_encode(['respuesta' => 0, 'accion' => 'consultar', 'mensaje' => 'ID no proporcionado']);
            exit;
        }
    } else {
        // Si no es una solicitud AJAX, cargar la vista
        $registro = $objproveedor->consultar();
        if($_SESSION["nivel_rol"] == 3) { // Validacion si es administrador entra
            require_once 'vista/proveedor.php';
        }else{
            require_once 'vista/seguridad/privilegio.php';
        }
    }
} catch (Exception $e) {
    if ($is_ajax) {
        echo json_encode(['respuesta' => 0, 'accion' => 'error', 'mensaje' => $e->getMessage()]);
        exit;
    } else {
        // Mostrar error en la interfaz
        $error_message = $e->getMessage();
        $registro = $objproveedor->consultar();
        if($_SESSION["nivel_rol"] == 3) { // Validacion si es administrador entra
             $id_persona = $_SESSION["id"];
             $accion = 'Acceso a Módulo';
             $descripcion = 'módulo de Proveedor';
             $objproveedor->registrarBitacora($id_persona, $accion, $descripcion);
            require_once 'vista/proveedor.php';
        }else{
            require_once 'vista/seguridad/privilegio.php';
        }
    }
}    