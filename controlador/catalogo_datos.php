<?php  
session_start();
$nombre = isset($_SESSION["nombre"]) && !empty($_SESSION["nombre"]) ? $_SESSION["nombre"] : "Estimado Cliente";
$apellido = isset($_SESSION["apellido"]) && !empty($_SESSION["apellido"]) ? $_SESSION["apellido"] : ""; 

$nombreCompleto = trim($nombre . " " . $apellido);

$sesion_activa = isset($_SESSION["id"]) && !empty($_SESSION["id"]);


require_once 'modelo/catalogo_datos.php';

$objdatos = new Datos();

if (isset($_POST['actualizar'])) {
     $datosCliente = [
        'operacion' => 'actualizar',
        'datos' => [
            'id_persona' => $_SESSION["id"],
            'nombre' => $_POST['nombre'],
            'apellido' => $_POST['apellido'],
            'cedula' => $_POST['cedula'],
            'correo' => strtolower($_POST['correo']),
            'telefono' => $_POST['telefono'],
            'cedula_actual' => $_SESSION["cedula"],
            'correo_actual' => $_SESSION["correo"]
        ]
    ];

    $nombre_actual = $_SESSION["nombre"];
    $apellido_actual = $_SESSION["apellido"];
    $telefono_actual = $_SESSION["telefono"];

   

    $datos = $datosCliente['datos'];

    $hayCambios = (
        $nombre_actual !== $datos['nombre'] ||
        $apellido_actual !== $datos['apellido'] ||
        $telefono_actual !== $datos['telefono'] ||
        $datos['cedula_actual'] !== $datos['cedula'] ||
        strtolower($datos['correo_actual']) !== strtolower($datos['correo']) // Comparación case-insensitive
    );

    if (!$hayCambios) {
        $res = [
            'respuesta' => 0,
            'accion' => 'actualizar',
            'text' => 'No se realizaron cambios en los datos.'
        ];
        echo json_encode($res);
        exit;
    }

   $resultado = $objdatos->procesarCliente(json_encode($datosCliente));
    
   echo json_encode($resultado);
      
}else if(isset($_POST['eliminar'])){
    
    $datosCliente = [
        'operacion' => 'eliminar',
        'datos' => [
            'id_persona' => $_POST['persona']
        ]
    ];

      $resultado = $objdatos->procesarCliente(json_encode($datosCliente));
      echo json_encode($resultado);

      session_destroy();
    

} else if(isset($_POST['actualizarclave'])){
    $datosCliente = [
        'operacion' => 'actualizarclave',
        'datos' => [
            'id_persona' => $_SESSION["id"],
            'clave_actual' => $_POST['clave'],
            'clave' => $_POST["clavenueva"]
        ]
    ];

  $resultado = $objdatos->procesarCliente(json_encode($datosCliente));
    echo json_encode($resultado);

 
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
