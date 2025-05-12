<?php  
session_start();
if (empty($_SESSION["id"])){
  header("location:?pagina=login");
} /*  Validacion URL  */

require_once 'modelo/cliente.php';

$objcliente = new Cliente();

$rol = $objcliente->obtenerRol();
$registro = $objcliente->consultar();

if (isset($_POST['registrar'])) {
if (!empty($_POST['nombre']) && !empty($_POST['apellido']) && !empty($_POST['cedula']) && !empty($_POST['telefono']) && !empty($_POST['correo']) && !empty($_POST['id_rol']) && !empty($_POST['clave'])) {

    $objcliente->set_Nombre($_POST['nombre']);
    $objcliente->set_Apellido($_POST['apellido']);
    $objcliente->set_Cedula($_POST['cedula']);
    $objcliente->set_Telefono($_POST['telefono']);
    $objcliente->set_Correo($_POST['correo']);
    $objcliente->set_Id_rol($_POST['id_rol']);
    $objcliente->set_Clave($_POST['clave']);

    // Registrar el usuario
    $resultadoRegistro = $objcliente->registrar();

    /* BITACORA */
    if ($resultadoRegistro['respuesta'] == 1) {
        $id_persona = $_SESSION["id"]; 
        // Registrar en la bitácora
        $accion = 'Registro de usuario';
        $descripcion = 'Se registró el usuario: ' . $_POST['nombre'] . ' ' . $_POST['apellido'];
        $objcliente->registrarBitacora($id_persona, $accion, $descripcion);
    } /* FIN BITACORA */

    echo json_encode($resultadoRegistro);
}

} else if(isset($_POST['eliminar'])){
  $id_usuario = $_POST['eliminar'];

 if ($id_usuario == 1) {
    $res = array('respuesta' => 0, 'accion' => 'eliminar', 'text' => 'No se puedes Eliminar a este Usuario');
         echo json_encode($res);
} else {

  $objusuario->set_Id_Usuario($id_usuario); 
  $result = $objcliente->eliminar();

    /* BITACORA */
    if (isset($result['respuesta']) && $result['respuesta'] == 1) {
        $id_persona = $_SESSION["id"]; // ID de la persona que realiza la acción
        $accion = 'Eliminación de usuario';
        $descripcion = 'Se eliminó el usuario con ID: ' . $id_usuario;
        $objcliente->registrarBitacora($id_persona, $accion, $descripcion);
    } /*FIN  BITACORA */


  echo json_encode($result);
    } 

} else if($_SESSION["nivel_rol"] == 3) { // Validacion si es administrador entra
    require_once 'vista/cliente.php';
}else{
    require_once 'vista/seguridad/privilegio.php';
}

?>