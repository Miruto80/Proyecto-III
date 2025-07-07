<?php  
     // Desactivar la salida de errores para respuestas AJAX
     if (isset($_POST['detalles']) || isset($_POST['limpiar']) || isset($_POST['eliminar_registro'])) {
         error_reporting(0);
         ini_set('display_errors', 0);
     }
     
     session_start();
     if (empty($_SESSION["id"])){
       header("location:?pagina=login");
     } /*  Validacion URL  */
    
    
    require_once 'modelo/bitacora.php';
    require_once 'permiso.php';
    $objBitacora = new Bitacora();

    // Función global para registrar en bitácora desde cualquier módulo
    function registrarEnBitacora($accion, $modulo, $detalle = '') {
        global $objBitacora;
        return $objBitacora->registrarOperacion($accion, $modulo, $detalle);
    }

    // Manejo de solicitudes AJAX
  if(isset($_POST['detalles'])) {
        header('Content-Type: application/json');
        try {
            $id_bitacora = (int)$_POST['detalles'];
            if ($id_bitacora <= 0) {
                echo json_encode(['error' => 'ID de bitácora inválido']);
                exit;
            }
            
         $registro = $objBitacora->obtenerRegistro($id_bitacora);
            if ($registro === false || empty($registro)) {
                echo json_encode(['error' => 'Registro no encontrado']);
                exit;
            }
            
         echo json_encode($registro);
        } catch (Exception $e) {
            echo json_encode(['error' => 'Error al obtener el registro: ' . $e->getMessage()]);
        }
        exit;
    }



    // Limpiar bitácora antigua
    if(isset($_POST['limpiar'])) {
        header('Content-Type: application/json');
        try {
            $resultado = $objBitacora->limpiarBitacoraAntigua();
            echo json_encode($resultado);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
        exit;
    }

    // Eliminar registro específico
    if(isset($_POST['eliminar_registro'])) {
        header('Content-Type: application/json');
        try {
            $id_bitacora = (int)$_POST['id_bitacora'];
            if ($id_bitacora <= 0) {
                echo json_encode(['respuesta' => 0, 'mensaje' => 'ID de bitácora inválido']);
                exit;
            }
            
            $objBitacora->set_Idbitacora($id_bitacora);
            $resultado = $objBitacora->eliminar();
            echo json_encode($resultado);
        } catch (Exception $e) {
            echo json_encode(['respuesta' => 0, 'mensaje' => 'Error: ' . $e->getMessage()]);
        }
        exit;
    }

    // Verificar permisos y mostrar vista
    if ($_SESSION["nivel_rol"] == 3 && tieneAcceso(12, 'ver')) {
        // Registrar acceso al módulo de bitácora
        $objBitacora->registrarOperacion('ACCESO A MÓDULO', 'Bitácora', 'Usuario accedió al módulo de Bitácora');

        require_once 'vista/seguridad/bitacora.php';
} else {
        require_once 'vista/seguridad/privilegio.php';
    }

    if ($_SESSION["nivel_rol"] == 1) {
    header("Location: ?pagina=catalogo");
    exit();
}
?>