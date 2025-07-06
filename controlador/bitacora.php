<?php  
     session_start();
     if (empty($_SESSION["id"])){
       header("location:?pagina=login");
     } /*  Validacion URL  */
    
    
    require_once 'modelo/bitacora.php';
    $objBitacora = new Bitacora();

    // Función global para registrar en bitácora desde cualquier módulo
    function registrarEnBitacora($accion, $modulo, $detalle = '') {
        global $objBitacora;
        return $objBitacora->registrarOperacion($accion, $modulo, $detalle);
    }

  if(isset($_POST['detalles'])) {
         $id_bitacora = $_POST['detalles'];
         $registro = $objBitacora->obtenerRegistro($id_bitacora);
         echo json_encode($registro);
   
   
   
} else if ($_SESSION["nivel_rol"] == 3 && !empty($_SESSION['permisos'])) {
    $mostrar_bitacora = false;

    foreach ($_SESSION['permisos'] as $permiso) {
        if (
            $permiso['id_modulo'] == 12 &&
            $permiso['accion'] === 'ver' &&
            $permiso['estado'] == 1
        ) {
            $mostrar_bitacora = true;
            break;
        }
    }

    if ($mostrar_bitacora) {

        require_once 'vista/seguridad/bitacora.php';
    } else {
        require_once 'vista/seguridad/privilegio.php';
    }

} else if ($_SESSION["nivel_rol"] == 1) {
    header("Location: ?pagina=catalogo");
    exit();

} else {
    require_once 'vista/seguridad/privilegio.php';
}
?>