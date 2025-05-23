<?php  
session_start();
if (empty($_SESSION["id"])){
  header("location:?pagina=login");
} /*  Validacion URL  */

require_once 'modelo/cliente.php';

$objcliente = new Cliente();


$registro = $objcliente->consultar();


if (isset($_POST['favorito'])) {
    $id_persona = $_POST['id_persona']; 

    if (empty($id_persona)) {
        $res = array('respuesta' => 0, 'accion' => 'favorito', 'text' => 'No se puede eliminar a este usuario');
        echo json_encode($res);
    } else {
        $objcliente->set_Id_persona($id_persona);
        $result = $objcliente->favorito();
        echo json_encode($result);
    }
}else if(isset($_POST['malcliente'])){
    $id_persona = $_POST['id_persona']; 

    if (empty($id_persona)) {
        $res = array('respuesta' => 0, 'accion' => 'malcliente', 'text' => 'No se puede eliminar a este usuario');
        echo json_encode($res);
    } else {
  
    $objcliente->set_Id_persona($id_persona); 
    $result = $objcliente->malcliente();
    echo json_encode($result);
    }
      
} else if(isset($_POST['clienteactivo'])){
    $id_persona = $_POST['id_persona']; 

    if (empty($id_persona)) {
        $res = array('respuesta' => 0, 'accion' => 'clienteactivo', 'text' => 'No se puede eliminar a este usuario');
        echo json_encode($res);
    } else {
  
    $objcliente->set_Id_persona($id_persona); 
    $result = $objcliente->clienteactivo();
    echo json_encode($result);
   } 
      
} else if(isset($_POST['actualizar'])){
   $id_persona = $_POST['id_persona'];
   $cedula = $_POST['cedula'];
   $correo = $_POST['correo'];
  

    $objcliente->set_Id_persona($id_persona);
    $objcliente->set_Cedula($cedula); 
    $objcliente->set_Correo($correo);  
    $result = $objcliente->actualizar();
    echo json_encode($result);

      
} else if($_SESSION["nivel_rol"] == 3) { // Validacion si es administrador entra
    require_once 'vista/cliente.php';
}else{
    require_once 'vista/seguridad/privilegio.php';
}

?>