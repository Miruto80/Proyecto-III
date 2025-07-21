<?php  
session_start();
if (empty($_SESSION["id"])) {
    header("location:?pagina=login");
    exit;
} /* Validacion URL */

require_once 'modelo/datos.php';
require_once 'modelo/bitacora.php';
require_once 'permiso.php';

$objdatos = new Datos();

if (isset($_POST['actualizar'])) {
     $datosUsuario = [
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

   

    $datos = $datosUsuario['datos'];

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

   $resultado = $objdatos->procesarUsuario(json_encode($datosUsuario));
    
     if ($resultado['respuesta'] == 1) {
        $bitacora = [
            'id_persona' => $_SESSION["id"],
            'accion' => 'Modificación de Usuario',
            'descripcion' => 'El usuario con ID: ' . $datosUsuario['datos']['id_persona'] . 
                           ' cedula: ' . $datosUsuario['datos']['cedula'] .
                            ' nombre: ' . $datosUsuario['datos']['nombre'] .
                            ' apellido: ' . $datosUsuario['datos']['apellido'] .
                            ' telefono: ' . $datosUsuario['datos']['telefono'] .
                           ' Correo: ' . $datosUsuario['datos']['correo']
        ];
        $bitacoraObj = new Bitacora();
        $bitacoraObj->registrarOperacion($bitacora['accion'], 'datos', $bitacora);
        
    }
        echo json_encode($resultado);
      
} else if(isset($_POST['actualizarclave'])){
    $datosUsuario = [
        'operacion' => 'actualizarclave',
        'datos' => [
            'id_persona' => $_SESSION["id"],
            'clave_actual' => $_POST['clave'],
            'clave' => $_POST["clavenueva"]
        ]
    ];

  $resultado = $objdatos->procesarUsuario(json_encode($datosUsuario));
    
     if ($resultado['respuesta'] == 1) {
        $bitacora = [
            'id_persona' => $_SESSION["id"],
            'accion' => 'Modificación de Usuario',
            'descripcion' => 'El usuario con cambio su clave, ID: ' . $datosUsuario['datos']['id_persona'] 
                           
        ];
        $bitacoraObj = new Bitacora();
        $bitacoraObj->registrarOperacion($bitacora['accion'], 'datos', $bitacora);
        
    }
        echo json_encode($resultado);

 
}else{
    $bitacora = [
            'id_persona' => $_SESSION["id"],
            'accion' => 'Acceso a Módulo',
            'descripcion' => 'módulo de Modificar Datos'
    ];
    $bitacoraObj = new Bitacora();
    $bitacoraObj->registrarOperacion($bitacora['accion'], 'datos', $bitacora);
   
    if ($_SESSION["nivel_rol"] != 2 && $_SESSION["nivel_rol"] != 3) {
    header("Location: ?pagina=catalogo");
    exit();
    }
    require_once 'vista/seguridad/datos.php';
} 


?>
