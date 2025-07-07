<?php  
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
         $id_bitacora = $_POST['detalles'];
         $registro = $objBitacora->obtenerRegistro($id_bitacora);
         echo json_encode($registro);
         exit;
    }



    // Limpiar bitácora antigua
    if(isset($_POST['limpiar'])) {
        $resultado = $objBitacora->limpiarBitacoraAntigua();
        echo json_encode($resultado);
        exit;
    }

    // Eliminar registro específico
    if(isset($_POST['eliminar_registro'])) {
        $id_bitacora = $_POST['id_bitacora'];
        $objBitacora->set_Idbitacora($id_bitacora);
        $resultado = $objBitacora->eliminar();
        echo json_encode($resultado);
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