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

    if (isset($_POST['entrar'])){
         // Verifica si se ha enviado la clave
        if (isset($_POST['clave']) && $_POST['clave'] === '1355') {
              $registro = $objBitacora->consultar();
              $objBitacora->registrarOperacion(
                  Bitacora::ACCESO_MODULO,
                  'Bitácora'
              );
              require 'vista/seguridad/bitacora.php';
        } else {
           $_SESSION['message'] = array('title' => 'Clave Inválida', 'text' => 'Por favor, verifica tus datos y vuelve a intentarlo', 'icon' => 'error');
            header('Location: ?pagina=bitacora'); 
            exit;
        }
    } else if(isset($_POST['detalles'])) {
         $id_bitacora = $_POST['detalles'];
         $registro = $objBitacora->obtenerRegistro($id_bitacora);
         echo json_encode($registro);
    } else if($_SESSION["nivel_rol"] == 3) { // Validacion si es administrador entra
        require_once 'vista/bitacora.php';
    } else {
        require_once 'vista/seguridad/privilegio.php';
    }
?>