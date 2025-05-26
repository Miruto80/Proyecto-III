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
         /* BITACORA */
        if ($result['respuesta'] == 1) {
            $id_persona = $_SESSION["id"]; 
            // Registrar en la bitácora
            $accion = 'Cambio de Estatus de Cliente';
            $descripcion = 'Se cambio estatus A: Favorito ID: '.$id_persona;
            $objcliente->registrarBitacora($id_persona, $accion, $descripcion);
        } /* FIN BITACORA */
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
     /* BITACORA */
        if ($result['respuesta'] == 1) {
            $id_persona = $_SESSION["id"]; 
            // Registrar en la bitácora
            $accion = 'Cambio de Estatus de Cliente';
            $descripcion = 'Se cambio estatus A: Mal Cliente ID: '.$id_persona;
            $objcliente->registrarBitacora($id_persona, $accion, $descripcion);
        } /* FIN BITACORA */
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
    /* BITACORA */
        if ($result['respuesta'] == 1) {
            $id_persona = $_SESSION["id"]; 
            // Registrar en la bitácora
            $accion = 'Cambio de Estatus de Cliente';
            $descripcion = 'Se cambio estatus A: cliente activo ID: '.$id_persona;
            $objcliente->registrarBitacora($id_persona, $accion, $descripcion);
        } /* FIN BITACORA */
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
     /* BITACORA */
        if ($result['respuesta'] == 1) {
            $id_persona = $_SESSION["id"]; 
            // Registrar en la bitácora
            $accion = 'Modificación de Cliente';
            $descripcion = 'Se Modifico de Cliente ID:'.$id_persona.' Cedula:'.$cedula;
            $objcliente->registrarBitacora($id_persona, $accion, $descripcion);
        } 
    /* FIN BITACORA */
    echo json_encode($result);

      
} else if($_SESSION["nivel_rol"] == 3) { // Validacion si es administrador entra
    $id_persona = $_SESSION["id"];
    $accion = 'Acceso a Módulo';
    $descripcion = 'módulo de Cliente';
    $objcliente->registrarBitacora($id_persona, $accion, $descripcion);
    require_once 'vista/cliente.php';
}else{
    require_once 'vista/seguridad/privilegio.php';
}

?>