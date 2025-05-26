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
        // Generar código aleatorio de 6 dígitos
        $codigo_recuperacion = rand(100000, 999999);
        $_SESSION['codigo_recuperacion'] = $codigo_recuperacion; // Guardar el código en la sesión
        
        // Enviar correo con el código
        require_once 'modelo/enviarcorreo.php'; // Archivo que contiene la lógica de PHP Mailer
        
        enviarCodigoRecuperacion($correo, $codigo_recuperacion);
        
        $res = array('respuesta' => 1, 'accion' => 'validar');
        echo json_encode($res);
    } else {
        $res = array('respuesta' => 0, 'accion' => 'validar', 'text' => 'El correo no encuentra en su registro.');
        echo json_encode($res);
    }
}else if (isset($_POST['validarcodigo'])) {    
    $codigo_ingresado = $_POST['codigo'];
    $codigo_guardado = isset($_SESSION['codigo_recuperacion']) ? $_SESSION['codigo_recuperacion'] : null;

    if ($codigo_guardado && $codigo_ingresado == $codigo_guardado) {
        $res = array('respuesta' => 1, 'accion' => 'validarcodigo');
    } else {
        $res = array('respuesta' => 0, 'accion' => 'validarcodigo', 'text' => 'Código incorrecto.');
    }

    echo json_encode($res);



} else if(isset($_POST['validarclave'])){
   $id_persona = $_SESSION["persona"];
   $clave_nueva =$_POST['clavenueva'];
    
    $objclave->set_Id_persona($id_persona);
    $objclave->set_Clave($clave_nueva);
    $result = $objclave->actualizarClave();
    echo json_encode($result);
 
} {
    require_once 'vista/seguridad/olvidoclave.php';
}

?>