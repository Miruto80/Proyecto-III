<?php  
    session_start();
    if (empty($_SESSION["id"])){
      header("location:?pagina=login");
    } /*  Validacion URL  */
    
   require_once 'modelo/usuario.php';
   require_once 'permiso.php';
   require_once 'modelo/bitacora.php';
    $objusuario = new Usuario();
    
    $rol = $objusuario->obtenerRol();
    $roll = $objusuario->obtenerRol();
    $registro = $objusuario->consultar();

 

if (isset($_POST['registrar'])) { /* -------  */
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
                'id_rol' => $_POST['id_rol'],
                'nivel' => $_POST['nivel']
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
            $bitacoraObj = new Bitacora();
            $bitacoraObj->registrarOperacion($bitacora['accion'], 'usuario', $bitacora);
        }

        echo json_encode($resultadoRegistro);
    }
} else  if(isset($_POST['modificar'])){ /* -------  */
     $id_persona = $_POST['modificar'];    
        
     if ($id_persona == $_SESSION['id']) {
        header("location:?pagina=usuario");
        exit;
    }
     if ($id_persona == 2) {
        header("location:?pagina=usuario");
        exit;
    }
       
        $modificar = $objusuario->buscar($id_persona);
        $nivel_usuario = $objusuario->obtenerNivelPorId($id_persona);
        
        $nombre_usuario = trim($_POST['permisonombre']);
        $apellido_usuario = trim($_POST['permisoapellido']);
        
        require_once ("vista/seguridad/permiso.php");
       
    }else if(isset($_POST['actualizar'])){ /* -------  */
    $datosUsuario = [
        'operacion' => 'actualizar',
        'datos' => [
            'id_persona' => $_POST['id_persona'],
            'cedula' => $_POST['cedula'],
            'correo' => $_POST['correo'],
            'id_rol' => $_POST['id_rol'],
            'estatus' => $_POST['estatus'],
            'cedula_actual' => $_POST['cedulaactual'],
            'correo_actual' => $_POST['correoactual'],
            'rol_actual' => $_POST['rol_actual'],
            'nivel' => $_POST['nivel']
        ]
    ]; 

    if($datosUsuario['datos']['id_persona'] == 2) { 
        if($datosUsuario['datos']['id_rol'] != 2) {
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
        $bitacoraObj = new Bitacora();
        $bitacoraObj->registrarOperacion($bitacora['accion'], 'usuario', $bitacora);
    }

    echo json_encode($resultado);

} else if (isset($_POST['actualizar_permisos'])) { /* -------  */
    $permisosRecibidos = $_POST['permiso'] ?? [];
    $permisosId = $_POST['permiso_id'] ?? [];

    $acciones = ['ver', 'registrar', 'editar', 'eliminar', 'especial'];
    $listaPermisos = [];

    foreach ($permisosId as $modulo_id => $accionesModulo) {
        foreach ($accionesModulo as $accion => $id_permiso) {
            $estado = isset($permisosRecibidos[$modulo_id][$accion]) ? 1 : 0;

            $listaPermisos[] = [
                'id_permiso' => $id_permiso,
                'id_modulo' => $modulo_id,
                'accion' => $accion,
                'estado' => $estado
            ];
        }
    }

    $datosPermiso = [
        'operacion' => 'actualizar_permisos',
        'datos' => $listaPermisos
    ];
   
    $resultado = $objusuario->procesarUsuario(json_encode($datosPermiso));

    if ($resultado['respuesta'] == 1) {
        $bitacora = [
            'id_persona' => $_SESSION["id"],
            'accion' => 'Modificar Permiso',
            'descripcion' => 'Se Modifico los permisos del usuario con ID: '
        ];
        $bitacoraObj = new Bitacora();
        $bitacoraObj->registrarOperacion($bitacora['accion'], 'usuario', $bitacora);
    }

    echo json_encode($resultado);

} else if(isset($_POST['eliminar'])){ /* -------  */
    $datosUsuario = [
        'operacion' => 'eliminar',
        'datos' => [
            'id_persona' => $_POST['eliminar']
        ]
    ];

    if ($datosUsuario['datos']['id_persona'] == 2) {
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
        $bitacoraObj = new Bitacora();
        $bitacoraObj->registrarOperacion($bitacora['accion'], 'usuario', $bitacora);
    }

    echo json_encode($resultado);

} else if ($_SESSION["nivel_rol"] == 3 && tieneAcceso(13, 'ver')) {
        $bitacora = [
            'id_persona' => $_SESSION["id"],
            'accion' => 'Acceso a Módulo',
            'descripcion' => 'módulo de Usuario'
        ];
        $bitacoraObj = new Bitacora();
        $bitacoraObj->registrarOperacion($bitacora['accion'], 'usuario', $bitacora);
        $pagina_actual = isset($_GET['pagina']) ? $_GET['pagina'] : 'usuario';
        require_once 'vista/usuario.php';
} else {
        require_once 'vista/seguridad/privilegio.php';

} if ($_SESSION["nivel_rol"] == 1) {
    header("Location: ?pagina=catalogo");
    exit();
}

    
?>