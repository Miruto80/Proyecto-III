<?php  
     session_start();
     if (empty($_SESSION["iduser"])){
       header("location:?pagina=login");
     } /*  Validacion URL  */
     if (!empty($_SESSION['id'])) {
        require_once 'verificarsession.php';
} 
  require_once 'modelo/olvidoclave.php'; 
  $objolvido = new Olvido();    
  
  if (isset($_POST['cerrarolvido'])) {    
      session_destroy(); 
      header('Location: ?pagina=login');
      exit;

} else  if (isset($_POST['validar'])) {    
    $correo = strtolower($_POST['correo']);
    $correodato = $_SESSION['correos'];    

    if ($correo === $correodato) {
        
        $codigo_recuperacion = rand(100000, 999999);
        $_SESSION['codigo_recuperacion'] = $codigo_recuperacion;
        
        // Enviar correo con el código
        require_once 'modelo/enviarcorreo.php'; 
        
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



} else if (isset($_POST['btnReenviar'])) {
    $correo = $_SESSION['correos'];
    if ($correo) {
        $codigo_recuperacion = rand(100000, 999999);
        $_SESSION['codigo_recuperacion'] = $codigo_recuperacion;

        require_once 'modelo/enviarcorreo.php';
        enviarCodigoRecuperacion($correo, $codigo_recuperacion);

        $res = array('respuesta' => 1, 'accion' => 'reenviar');
    } else {
        $res = array('respuesta' => 0, 'accion' => 'reenviar', 'text' => 'al obtener el correo');
    }
    echo json_encode($res);
    exit;


} else if(isset($_POST['validarclave'])){
     $datosOlvido = [
        'operacion' => 'actualizar',
        'datos' => [
            'id_persona' => $_SESSION["persona"],
             'clave' => $_POST['clavenueva'],
            'tabla_origen' => $_SESSION["tabla_origen"]
        ]
    ]; 

    $resultado = $objolvido->procesarOlvido(json_encode($datosOlvido));
 
    echo json_encode($resultado);
} else{
    require_once 'vista/seguridad/olvidoclave.php';
}

?>