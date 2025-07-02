<?php  
    session_start();
    if (empty($_SESSION["id"])){
      header("location:?pagina=login");
    } /*  Validacion URL  */
    
   require_once 'modelo/usuario.php';

    $objusuario = new Usuario();
    
    $rol = $objusuario->obtenerRol();
    $roll = $objusuario->obtenerRol();
    $registro = $objusuario->consultar();

if (isset($_POST['registrar'])) {
    if (!empty($_POST['nombre']) && !empty($_POST['apellido']) && !empty($_POST['cedula']) && !empty($_POST['telefono']) && !empty($_POST['correo']) && !empty($_POST['id_rol']) && !empty($_POST['clave'])) {

        $datosUsuario = [
            'operacion' => 'registrar',
            'datos' => [
                'nombre' => ucfirst(strtolower($_POST['nombre'])),
                'apellido' => ucfirst(strtolower($_POST['apellido'])),
                'cedula' => $_POST['cedula'],
                'telefono' => $_POST['telefono'],
                'correo' => strtolower($_POST['correo']),
                'clave' => $_POST['clave'],
                'id_rol' => $_POST['id_rol']
            ]
        ];

        $resultadoRegistro = $objusuario->procesarUsuario(json_encode($datosUsuario));

        if ($resultadoRegistro['respuesta'] == 1) {
            $bitacora = [
                'id_persona' => $_SESSION["id"],
                'accion' => 'Registro de usuario',
                'descripcion' => 'Se registró el usuario: ' . $datosUsuario['datos']['cedula'] . ' ' . 
                                $datosUsuario['datos']['nombre'] . ' ' . $datosUsuario['datos']['apellido']
            ];
            $objusuario->registrarBitacora(json_encode($bitacora));
        }

        echo json_encode($resultadoRegistro);
    }
} else if(isset($_POST['actualizar'])){
    $datosUsuario = [
        'operacion' => 'actualizar',
        'datos' => [
            'id_persona' => $_POST['id_persona'],
            'cedula' => $_POST['cedula'],
            'correo' => $_POST['correo'],
            'id_rol' => $_POST['id_rol'],
            'estatus' => $_POST['estatus'],
            'cedula_actual' => $_POST['cedulaactual'],
            'correo_actual' => $_POST['correoactual']
        ]
    ]; 

    if($datosUsuario['datos']['id_persona'] == 1) { 
        if($datosUsuario['datos']['id_rol'] != 1) {
            echo json_encode(['respuesta' => 0, 'accion' => 'actualizar', 'text' => 'No puedes cambiar el Rol del usuario administrador']);
            exit;
        }
        if($datosUsuario['datos']['estatus'] != 1) {
            echo json_encode(['respuesta' => 0, 'accion' => 'actualizar', 'text' => 'No puedes cambiar el estatus del usuario administrador']);
            exit;
        }
    }

    $resultado = $objusuario->procesarUsuario(json_encode($datosUsuario));

    if ($resultado['respuesta'] == 1) {
        $bitacora = [
            'id_persona' => $_SESSION["id"],
            'accion' => 'Modificación de usuario',
            'descripcion' => 'Se modificó el usuario con ID: ' . $datosUsuario['datos']['id_persona'] . 
                           ' Cédula: ' . $datosUsuario['datos']['cedula'] . 
                           ' Correo: ' . $datosUsuario['datos']['correo']
        ];
        $objusuario->registrarBitacora(json_encode($bitacora));
    }

    echo json_encode($resultado);

} else if(isset($_POST['eliminar'])){
    $datosUsuario = [
        'operacion' => 'eliminar',
        'datos' => [
            'id_persona' => $_POST['eliminar']
        ]
    ];

    if ($datosUsuario['datos']['id_persona'] == 1) {
        echo json_encode(['respuesta' => 0, 'accion' => 'eliminar', 'text' => 'No se puede eliminar al usuario administrador']);
        exit;
    } 
    
    if ($datosUsuario['datos']['id_persona'] == $_SESSION['id']) {
        echo json_encode(['respuesta' => 0, 'accion' => 'eliminar', 'text' => 'No puedes eliminarte a ti mismo']);
        exit;
    }

    $resultado = $objusuario->procesarUsuario(json_encode($datosUsuario));

    if ($resultado['respuesta'] == 1) {
        $bitacora = [
            'id_persona' => $_SESSION["id"],
            'accion' => 'Eliminación de usuario',
            'descripcion' => 'Se eliminó el usuario con ID: ' . $datosUsuario['datos']['id_persona']
        ];
        $objusuario->registrarBitacora(json_encode($bitacora));
    }

    echo json_encode($resultado);
} else if ($_SESSION["nivel_rol"] == 3) {
    
    $bitacora = [
        'id_persona' => $_SESSION["id"],
        'accion' => 'Acceso a Módulo',
        'descripcion' => 'módulo de Usuario'
    ];
    $objusuario->registrarBitacora(json_encode($bitacora));
    require_once 'vista/usuario.php';

} else if ($_SESSION["nivel_rol"] == 1) {

    header("Location: ?pagina=catalogo");
    exit();

} else {
    require_once 'vista/seguridad/privilegio.php';
}

       


?>