<?php  
    session_start();
    if (empty($_SESSION["id"])){
      header("location:?pagina=login");
    } /*  Validacion URL  */
    
   require_once 'modelo/usuario.php';

    $objusuario = new Usuario();
    
    $rol = $objusuario->obtenerRol();
    $registro = $objusuario->consultar();

if (isset($_POST['registrar'])) {
    if (!empty($_POST['nombre']) && !empty($_POST['apellido']) && !empty($_POST['cedula'])  && !empty($_POST['telefono'])  && !empty($_POST['correo']) && !empty($_POST['id_rol'])  && !empty($_POST['clave'])) {

        $objusuario->set_Nombre($_POST['nombre']);
        $objusuario->set_Apellido($_POST['apellido']);
        $objusuario->set_Cedula($_POST['cedula']);
        $objusuario->set_Telefono($_POST['telefono']);
        $objusuario->set_Correo($_POST['correo']);
        $objusuario->set_Id_rol($_POST['id_rol']);
        $objusuario->set_Clave($_POST['clave']);

        echo json_encode($objusuario->registrar());
        }

}else if(isset($_POST['eliminar'])){
      $id_usuario = $_POST['eliminar'];

     if ($id_usuario == 1) {
        $res = array('respuesta' => 0, 'accion' => 'eliminar', 'text' => 'No se puedes Eliminar a este Usuario');
             echo json_encode($res);
    } else {

      $objusuario->set_Id_Usuario($id_usuario); 
      $result = $objusuario->eliminar();
      echo json_encode($result);
        } 
    
  } else {
     require_once 'vista/usuario.php';
  }

       


?>