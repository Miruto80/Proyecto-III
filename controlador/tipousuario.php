<?php
 session_start();
 if (empty($_SESSION["id"])){
   header("location:?pagina=login");
 } /*  Validacion URL  */
 
require_once 'modelo/tipousuario.php';

$objtipousuario = new Tipousuario();

// Si se desea obtener la lista de tipos de usuario al cargar la página o realizar alguna acción inicial
$registro = $objtipousuario->consultar(); // Aquí obtenemos todos los tipos de usuario

if (isset($_POST['registrar'])) {
    // Si se presiona el botón para registrar un nuevo tipo de usuario
    if (!empty($_POST['nombre']) && !empty($_POST['nivel'])) {
        $objtipousuario->set_Nombre($_POST['nombre']);
        $objtipousuario->set_Nivel($_POST['nivel']);
        $objtipousuario->set_Estatus(1); // Estatus activo por defecto
        $result = $objtipousuario->registrar();

       /* BITACORA */
        if (isset($result['respuesta']) && $result['respuesta'] == 1) {
            $id_persona = $_SESSION["id"]; // ID de la persona que realiza la acción
            $accion = 'Registrar de tipo usuario';
            $descripcion = 'Se registró de tipo usuario: ' . $_POST['nombre'] . ' ' . $_POST['nivel'];
            $objtipousuario->registrarBitacora($id_persona, $accion, $descripcion);
        } /*FIN  BITACORA */

        echo json_encode($result);
    }
} elseif (isset($_POST['modificar'])) {
    // Si se presiona el botón para modificar un tipo de usuario
    if (!empty($_POST['id_tipo']) && !empty($_POST['nombre']) && !empty($_POST['nivel'])) {
        $objtipousuario->set_Id_tipo($_POST['id_tipo']);
        $objtipousuario->set_Nombre($_POST['nombre']);
        $objtipousuario->set_Nivel($_POST['nivel']);
        $objtipousuario->set_Estatus($_POST['estatus']);
        $result = $objtipousuario->modificar();

          if (isset($result['respuesta']) && $result['respuesta'] == 1) {
            $id_persona = $_SESSION["id"]; // ID de la persona que realiza la acción
            $accion = 'Modificación de tipo usuario';
            $descripcion = 'Se Modifico de tipo usuario: ' . $_POST['nombre'] . ' ' . $_POST['nivel'];
            $objtipousuario->registrarBitacora($id_persona, $accion, $descripcion);
        } /*FIN  BITACORA */

        echo json_encode($result);
    }
} elseif (isset($_POST['eliminar'])) {
    // Si se presiona el botón para eliminar un tipo de usuario
    if (!empty($_POST['id_tipo'])) {

        if($_POST['id_tipo'] == 1){
             $res = array('respuesta' => 0, 'accion' => 'eliminar');
             echo json_encode($res);
             exit;
        }

        $objtipousuario->set_Id_tipo($_POST['id_tipo']);
        $result = $objtipousuario->eliminar();

        if (isset($result['respuesta']) && $result['respuesta'] == 1) {
            $id_persona = $_SESSION["id"]; // ID de la persona que realiza la acción
            $accion = 'Eliminación de tipo usuario';
            $descripcion = 'Se eliminó de tipo usuario: ' .$_POST['id_tipo'];
            $objtipousuario->registrarBitacora($id_persona, $accion, $descripcion);
        } /*FIN  BITACORA */

        echo json_encode($result);
    }
} else if($_SESSION["nivel_rol"] == 3) { // Validacion si es administrador entra
     $id_persona = $_SESSION["id"];
    $accion = 'Acceso a Módulo';
    $descripcion = 'módulo de Tipo Usuario';
    $objtipousuario->registrarBitacora($id_persona, $accion, $descripcion);
    require_once 'vista/tipousuario.php';
}else{
    require_once 'vista/seguridad/privilegio.php';
}
?>
