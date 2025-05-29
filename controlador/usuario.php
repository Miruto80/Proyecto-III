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

        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido']; 
        $cedula = $_POST['cedula'];
        $telefono = $_POST['telefono'];
        $correo = $_POST['correo'];
        $clave = $_POST['clave'];
        $id_rol=$_POST['id_rol'];
        
        $objusuario->set_Nombre($nombre);
        $objusuario->set_Apellido($apellido);
        $objusuario->set_Cedula($cedula);
        $objusuario->set_Telefono($telefono);
        $objusuario->set_Correo($correo);
        $objusuario->set_Clave($clave);
        $objusuario->set_Id_rol($id_rol);
    
      //  Verificar si la cédula ya existe
    if ($objusuario->existeCedula($cedula)) {
        $res = array('respuesta' => 0, 'accion' => 'incluir', 'text' => 'La cédula ya está registrada.');
        echo json_encode($res);
        exit;
    }
    // Si la cédula no existe, verificar si el correo ya existe
    else if ($objusuario->existeCorreo($correo)) {
        $res = array('respuesta' => 0, 'accion' => 'incluir', 'text' => 'El correo electrónico ya está registrado.');
        echo json_encode($res);
         exit;
    }
    // Si ni la cédula ni el correo existen, proceder con el registro
    else { 
        
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
 }
} else if(isset($_POST['actualizar'])){

   $id_persona = $_POST['id_persona'];
   $cedula = $_POST['cedula'];
   $correo = $_POST['correo'];
   $id_rol = $_POST['id_rol'];
   $estatus = $_POST['estatus'];
   $cedula_actual = $_POST['cedulaactual'];
   $correo_actual = $_POST['correoactual'];
    
   if($id_persona==1){ 
        if($id_rol != 1){
        $res = array('respuesta' => 0, 'accion' => 'actualizar', 'text' => 'No Puedes cambiar el Rol');
        echo json_encode($res);
        exit;
    }      
   } 

    if($id_persona==1){ 
        if($estatus != 1){
        $res = array('respuesta' => 0, 'accion' => 'actualizar', 'text' => 'No Puedes cambiar el estatus');
        echo json_encode($res);
        exit;
    }      
   }

    $objusuario->set_Id_Usuario($id_persona);
    $objusuario->set_Cedula($cedula); 
    $objusuario->set_Correo($correo);
    $objusuario->set_Id_rol($id_rol);
    $objusuario->set_Estatus($estatus);
    
   if ($cedula_actual !== $cedula) {
       if ($objusuario->existeCedula($cedula)) {
           $res = array('respuesta' => 0, 'accion' => 'actualizar', 'text' => 'La cédula ya está registrada.');
            echo json_encode($res);
            exit; // Se detiene la ejecución si la cédula existe
      } 
   }

   if ($correo_actual !== $correo) {
       if ($objusuario->existeCorreo($correo)) {
           $res = array('respuesta' => 0, 'accion' => 'actualizar', 'text' => 'El correo electrónico ya está registrado.');
         echo json_encode($res);
         exit; 
      }
   }
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