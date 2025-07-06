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

  if(isset($_POST['detalles'])) {
         $id_bitacora = $_POST['detalles'];
         $registro = $objBitacora->obtenerRegistro($id_bitacora);
         echo json_encode($registro);
   
   
   
} else if ($_SESSION["nivel_rol"] == 3 && tieneAcceso(12, 'ver')) {

        require_once 'vista/seguridad/bitacora.php';
} else {
        require_once 'vista/seguridad/privilegio.php';

} if ($_SESSION["nivel_rol"] == 1) {
    header("Location: ?pagina=catalogo");
    exit();
}
?>