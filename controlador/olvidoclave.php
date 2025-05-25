<?php  
     session_start();
     if (empty($_SESSION["iduser"])){
       header("location:?pagina=login");
     } /*  Validacion URL  */
     
  require_once 'modelo/olvidoclave.php'; 
  $objclave = new Clave();   
  
  if (isset($_POST['cerrarolvido'])) {    
      session_destroy(); // Se cierra la sesión
      header('Location: ?pagina=login');
      exit;

} else  if (isset($_POST['validar'])) {    
   $correo = $_POST['correo'];
   $correodato = $_SESSION['correos'];    

   if ($correo === $correodato) {
      
      $res = array('respuesta' => 1, 'accion' => 'validar');
      echo json_encode($res);

   } else {

       // La clave actual no coincide
      $res = array('respuesta' => 0, 'accion' => 'validar', 'text' => 'El correo es incorrecto.');
      echo json_encode($res);

   }

} else if (isset($_POST['validarcodigo'])) {    
   $correo = $_POST['codigo'];
   $correodato = $_SESSION['correos'];    

   if ($correo === $correodato) {
      
      $res = array('respuesta' => 1, 'accion' => 'validarcodigo');
      echo json_encode($res);

   } else {

       // La clave actual no coincide
      $res = array('respuesta' => 0, 'accion' => 'validarcodigo', 'text' => 'El correo es incorrecto.');
      echo json_encode($res);

   }

} else{
    require_once 'vista/seguridad/olvidoclave.php';
}

?>