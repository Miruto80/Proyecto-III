<?php  
    session_start();
    if (empty($_SESSION["id"])){
      header("location:?pagina=login");
    } /*  Validacion URL  */
    
   require_once 'modelo/usuario.php';

    $objusuario = new Usuario();
    
    $rol = $objusuario->obtenerRol();
    $roll = $objusuario->obtenerRol();
    $registro = $objusuario->consultar();

if (isset($_POST['registrar'])) {
    if (!empty($_POST['nombre']) && !empty($_POST['apellido']) && !empty($_POST['cedula']) && !empty($_POST['telefono']) && !empty($_POST['correo']) && !empty($_POST['id_rol']) && !empty($_POST['clave'])) {

        $objusuario->set_Nombre($_POST['nombre']);
        $objusuario->set_Apellido($_POST['apellido']);
        $objusuario->set_Cedula($_POST['cedula']);
        $objusuario->set_Telefono($_POST['telefono']);
        $objusuario->set_Correo($_POST['correo']);
        $objusuario->set_Id_rol($_POST['id_rol']);
        $objusuario->set_Clave($_POST['clave']);

        // Registrar el usuario
        $resultadoRegistro = $objusuario->registrar();

        /* BITACORA */
        if ($resultadoRegistro['respuesta'] == 1) {
            $id_persona = $_SESSION["id"]; 
            // Registrar en la bitácora
            $accion = 'Registro de usuario';
            $descripcion = 'Se registró el usuario: ' . $_POST['cedula'] . ' ' . $_POST['nombre']. ' ' . $_POST['apellido'];
            $objusuario->registrarBitacora($id_persona, $accion, $descripcion);
        } /* FIN BITACORA */

        echo json_encode($resultadoRegistro);
    }

} else if(isset($_POST['actualizar'])){

   
   $id_persona = $_POST['id_persona'];
   $cedula = $_POST['cedula'];
   $correo = $_POST['correo'];
   $id_rol = $_POST['id_rol'];
    
   if($id_persona==1){ 
        if($id_rol != 1){
        $res = array('respuesta' => 0, 'accion' => 'actualizar', 'text' => 'No Puedes cambiar el Rol');
        echo json_encode($res);
        exit;
    }      
   } 


    $objusuario->set_Id_Usuario($id_persona);
    $objusuario->set_Cedula($cedula); 
    $objusuario->set_Correo($correo);
    $objusuario->set_Id_rol($id_rol);    
    $result = $objusuario->actualizar();
      /* BITACORA */
        if (isset($result['respuesta']) && $result['respuesta'] == 1) {
            $id_persona = $_SESSION["id"]; // ID de la persona que realiza la acción
            $accion = 'Modificacion de usuario';
            $descripcion = 'Se Modifico el usuario con ID: ' .$id_persona.' '.$cedula.' '.$correo;
            $objusuario->registrarBitacora($id_persona, $accion, $descripcion);
        } /*FIN  BITACORA */
    echo json_encode($result);

      
} else if(isset($_POST['eliminar'])){
      $id_usuario = $_POST['eliminar'];

     if ($id_usuario == 1) {
        $res = array('respuesta' => 0, 'accion' => 'eliminar', 'text' => 'No se puedes Eliminar a este Usuario');
             echo json_encode($res);
     } else if ($id_usuario == $_SESSION['id']) {
        $res = array('respuesta' => 0, 'accion' => 'eliminar', 'text' => 'No puedes eliminarte tu mismo');
             echo json_encode($res);
     } else {

      $objusuario->set_Id_Usuario($id_usuario); 
      $result = $objusuario->eliminar();

        /* BITACORA */
        if (isset($result['respuesta']) && $result['respuesta'] == 1) {
            $id_persona = $_SESSION["id"]; // ID de la persona que realiza la acción
            $accion = 'Eliminación de usuario';
            $descripcion = 'Se eliminó el usuario con ID: ' . $id_usuario;
            $objusuario->registrarBitacora($id_persona, $accion, $descripcion);
        } /*FIN  BITACORA */


      echo json_encode($result);
        } 
    
  } else if($_SESSION["nivel_rol"] == 3) { // Validacion si es administrador entra
    $id_persona = $_SESSION["id"];
    $accion = 'Acceso a Módulo';
    $descripcion = 'módulo de Usuario';
    $objusuario->registrarBitacora($id_persona, $accion, $descripcion);
    require_once 'vista/usuario.php';
}else{
    require_once 'vista/seguridad/privilegio.php';
}

       


?>