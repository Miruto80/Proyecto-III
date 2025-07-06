<?php

session_start();

require_once 'modelo/login.php';

$objlogin = new Login();

if (isset($_POST['ingresar'])) {
    $datosLogin = [
        'operacion' => 'verificar',
        'datos' => [
            'cedula' => $_POST['usuario'],
            'clave' => $_POST['clave']
        ]
    ];

    $resultado = $objlogin->procesarLogin(json_encode($datosLogin));

  if ($resultado && isset($resultado->id_persona)) {
    if ((int)$resultado->estatus === 2) {
        echo json_encode([
            'respuesta' => 0,
            'accion' => 'ingresar',
            'text' => 'Lo sentimos, su cuenta está suspendida. Por favor, póngase en contacto con el administrador.'
        ]);
        exit;
    }

    if ((int)$resultado->estatus === 1) {
        $_SESSION["id"] = $resultado->id_persona;

        $id_persona = $_SESSION["id"]; 
        $resultadopermiso = $objlogin->consultar($id_persona);
        $_SESSION["permisos"] = $resultadopermiso;

        $_SESSION["nombre"] = $resultado->nombre;
        $_SESSION["apellido"] = $resultado->apellido;
        $_SESSION["nivel_rol"] = isset($resultado->nivel) ? $resultado->nivel : 1;
        $_SESSION['nombre_usuario'] = isset($resultado->nombre_usuario) ? $resultado->nombre_usuario : 'Cliente';
        $_SESSION["cedula"] = $resultado->cedula;
        $_SESSION["telefono"] = $resultado->telefono;
        $_SESSION["correo"] = $resultado->correo;
        $_SESSION["estatus"] = $resultado->estatus;

        if ($_SESSION["nivel_rol"] == 1) {
            echo json_encode(['respuesta' => 1, 'accion' => 'ingresar']);
            exit;
       
        } elseif ($_SESSION["nivel_rol"] == 2 || $_SESSION["nivel_rol"] == 3) {
            $bitacora = [
                'id_persona' => $resultado->id_persona,
                'accion' => 'Inicio de sesión',
                'descripcion' => 'El usuario ha iniciado sesión exitosamente.'
            ];
            $objlogin->registrarBitacora(json_encode($bitacora));
            
            echo json_encode(['respuesta' => 2, 'accion' => 'ingresar']);
            exit;
        } else {
            echo json_encode([
                'respuesta' => 0,
                'accion' => 'ingresar',
                'text' => 'Su nivel de acceso no está definido.'
            ]);
            exit;
        }
    }

} else {
   
    echo json_encode([
        'respuesta' => 0,
        'accion' => 'ingresar',
        'text' => 'Cédula y/o Clave inválida.'
    ]);
}

// ------------------

} else if (isset($_POST['cerrar'])) {
    if (isset($_SESSION["id"])) {
        $bitacora = [
            'id_persona' => $_SESSION["id"],
            'accion' => 'Cierre de sesión',
            'descripcion' => 'El usuario ha cerrado sesión.'
        ];
        $objlogin->registrarBitacora(json_encode($bitacora));
    }
    session_destroy();
    header('Location: ?pagina=login');
    exit;


// ------------------

} else if (isset($_POST['registrar'])) {
    $datosRegistro = [
        'operacion' => 'registrar',
        'datos' => [
            'nombre' => $_POST['nombre'],
            'apellido' => $_POST['apellido'],
            'cedula' => $_POST['cedula'],
            'telefono' => $_POST['telefono'],
            'correo' => $_POST['correo'],
            'clave' => $_POST['clave']
        ]
    ];

    $resultado = $objlogin->procesarLogin(json_encode($datosRegistro));
    echo json_encode($resultado);

// ------------------

} else if (isset($_POST['validarclave'])) {
    $datosValidar = [
        'operacion' => 'validar',
        'datos' => [
            'cedula' => $_POST['cedula']
        ]
    ];

    $persona = $objlogin->procesarLogin(json_encode($datosValidar));

    if ($persona && isset($persona->id_persona)) {
        $_SESSION["persona"] = $persona->id_persona;
        $_SESSION["nombres"] = $persona->nombre;
        $_SESSION["apellidos"] = $persona->apellido;
        $_SESSION["correos"] = $persona->correo;
        $_SESSION["iduser"] = 1;
        $_SESSION["tabla_origen"] = ($persona->origen == 'usuario') ? 2 : 1;
        echo json_encode(['respuesta' => 1, 'accion' => 'validarclave']);
        exit;
    } else {
        echo json_encode(['respuesta' => 0, 'accion' => 'validarclave', 'text' => 'Cédula incorrecta o no hay registro']);
    }

 // ------------------

} else if (isset($_POST['cerrarolvido'])) {    
    session_destroy();
    header('Location: ?pagina=login');
    exit;
    
// ------------------

} else if (!empty($_SESSION['id'])) {
    
    if (isset($_SESSION["nivel_rol"]) && ($_SESSION["nivel_rol"] == 2 || $_SESSION["nivel_rol"] == 3)) {
    $bitacora = [
        'id_persona' => $_SESSION["id"],
        'accion' => 'Cierre de sesión',
        'descripcion' => 'El usuario ha cerrado sesión por URL.'
    ];
    $objlogin->registrarBitacora(json_encode($bitacora));
    }
  
    session_destroy();
    header('Location: ?pagina=login');
    exit;

} else {    
    require_once 'vista/login.php';
}