<?php  
session_start();
$nombre = isset($_SESSION["nombre"]) && !empty($_SESSION["nombre"]) ? $_SESSION["nombre"] : "Estimado Cliente";
$apellido = isset($_SESSION["apellido"]) && !empty($_SESSION["apellido"]) ? $_SESSION["apellido"] : ""; 

$nombreCompleto = trim($nombre . " " . $apellido);

$sesion_activa = isset($_SESSION["id"]) && !empty($_SESSION["id"]);

if (!empty($_SESSION['id'])) {
    require_once 'verificarsession.php';
}

require_once 'modelo/catalogo_datos.php';

$objdatos = new Datoscliente();

  $entrega = $objdatos->obtenerEntrega();
  $direccion = $objdatos->consultardireccion();

if (isset($_POST['actualizar'])) {
     $datosCliente = [
        'operacion' => 'actualizar',
        'datos' => [
            'id_persona' => $_SESSION["id"],
            'nombre' => ucfirst(strtolower($_POST['nombre'])),
            'apellido' => ucfirst(strtolower($_POST['apellido'])),
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

    if ($resultado['respuesta'] == 1) {
        $id_persona = $_SESSION["id"];
        $resultado = $objdatos->consultardatos($id_persona);

                // Verificamos que hay al menos un resultado
            if (!empty($resultado) && is_array($resultado)) {
                $datos = $resultado[0]; // Accedemos al primer elemento

                $_SESSION["nombre"]   = $datos["nombre"];
                $_SESSION["apellido"] = $datos["apellido"];
                $_SESSION["telefono"] = $datos["telefono"];
                $_SESSION["correo"]   = $datos["correo"];
                $_SESSION["cedula"]   = $datos["cedula"];
            }
      }   
      
} else if (isset($_POST['actualizardireccion'])) {
    
    $datosCliente = [
        'operacion' => 'actualizardireccion',
        'datos' => [
            'direccion_envio' => $_POST['direccion_envio'],
            'sucursal_envio' => $_POST['sucursal_envio'],
            'id_direccion' => $_POST['id_direccion'],
            'id_metodoentrega' => $_POST['id_metodoentrega']
        ]
    ];

   $resultado = $objdatos->procesarCliente(json_encode($datosCliente));
   echo json_encode($resultado);

   
      
} else if (isset($_POST['incluir'])) {

    $sucursal = !empty($_POST['sucursal_envio']) ? $_POST['sucursal_envio'] : "no aplica";

    $datosCliente = [
        'operacion' => 'incluir',
        'datos' => [
            'id_metodoentrega' => $_POST['id_metodoentrega'],
            'id_persona' => $_SESSION["id"],
            'direccion_envio' => $_POST['direccion_envio'],
            'sucursal_envio' => $sucursal
        ]
    ];

   $resultado = $objdatos->procesarCliente(json_encode($datosCliente));
   echo json_encode($resultado);
   
} else if(isset($_POST['eliminar'])){
    
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
