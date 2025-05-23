<?php  
session_start();
$nombre = isset($_SESSION["nombre"]) && !empty($_SESSION["nombre"]) ? $_SESSION["nombre"] : "Estimado Cliente";
$apellido = isset($_SESSION["apellido"]) && !empty($_SESSION["apellido"]) ? $_SESSION["apellido"] : ""; 

$nombreCompleto = trim($nombre . " " . $apellido);

$sesion_activa = isset($_SESSION["id"]) && !empty($_SESSION["id"]);


require_once 'modelo/catalogo_datos.php';

$objdatos = new Datos();

if (isset($_POST['actualizar'])) {
    $id_persona = $_SESSION["id"];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $cedula = $_POST['cedula'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];

    $cedula_actual = $_SESSION["cedula"];
    $correo_actual = $_SESSION["correo"];

    $cedula_actual = $_SESSION["cedula"];
    $correo_actual = $_SESSION["correo"];
    $nombre_actual = $_SESSION["nombre"];
    $apellido_actual = $_SESSION["apellido"];
    $telefono_actual = $_SESSION["telefono"];


    $objdatos->set_Id_persona($id_persona);
    $objdatos->set_Nombre($nombre);
    $objdatos->set_Apellido($apellido);
    $objdatos->set_Cedula($cedula);
    $objdatos->set_Telefono($telefono);
    $objdatos->set_Correo($correo);

    // Verificar si la cédula ha cambiado
   if ($cedula_actual !== $cedula) {
       if ($objdatos->existeCedula($cedula)) {
           $res = array('respuesta' => 0, 'accion' => 'actualizar', 'text' => 'La cédula ya está registrada.');
            echo json_encode($res);
            exit; // Se detiene la ejecución si la cédula existe
      } 
   }

   // Verificar si el correo ha cambiado
   if ($correo_actual !== $correo) {
       if ($objdatos->existeCorreo($correo)) {
           $res = array('respuesta' => 0, 'accion' => 'actualizar', 'text' => 'El correo electrónico ya está registrado.');
         echo json_encode($res);
         exit; // Se detiene la ejecución si el correo existe
      }
   }

    // Verificar si hubo cambios en nombre, apellido o teléfono
    if ($nombre_actual !== $nombre || $apellido_actual !== $apellido || $telefono_actual !== $telefono) {
        $hayCambios = true;
    } else {
        $hayCambios = false;
    }

    // Si no hubo cambios en ningún campo, devolver mensaje
    if (!$hayCambios) {
        $res = array('respuesta' => 0, 'accion' => 'actualizar', 'text' => 'No se realizaron cambios en los datos.');
         echo json_encode($res);
        exit;
    }

   // Si no hubo cambios en la cédula o el correo, proceder con la actualización
   $result = $objdatos->actualizar();
   echo json_encode($result);

}else if(isset($_POST['eliminar'])){
   $id_persona = $_POST['persona'];
  
   if (empty($id_persona)) {
       $res = array('respuesta' => 0, 'accion' => 'eliminar', 'text' => 'Error en eliminar cuenta - datos vacios');
       echo json_encode($res);
   } else{
     
      $objdatos->set_Id_persona($id_persona); 
      $result = $objdatos->eliminar();
      echo json_encode($result);

      session_destroy();
   }
      
 
} else if(isset($_POST['actualizarclave'])){
   $id_persona = $_POST['persona'];
   $clave_actual = $_POST['clave'];
   $clave_nueva =$_POST['clavenueva'];
 

   // Obtener la clave almacenada en la base de datos
   $clave_guardada = $objdatos->obtenerClave($id_persona);

   if ($clave_actual === $clave_guardada) {

          $objdatos->set_Id_persona($id_persona);
          $objdatos->set_Clave($clave_nueva);
          $result = $objdatos->actualizarClave();
          echo json_encode($result);

   } else {

       // La clave actual no coincide
      $res = array('respuesta' => 0, 'accion' => 'clave', 'text' => 'La clave actual es incorrecta.');
       echo json_encode($res);

   }

 
} if ($sesion_activa) {
     if($_SESSION["nivel_rol"] == 1) { 
      require_once('vista/tienda/catalogo_datos.php');
    } else{
        header('Location: ?pagina=catalogo');
    }   
} else {
   header('Location: ?pagina=catalogo');
}

?>
