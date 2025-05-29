<?php

session_start();

require_once 'modelo/login.php';

$objlogin = new Login();

 if (isset($_POST['ingresar'])) {
    if (!empty($_POST['usuario']) && !empty($_POST['clave'])) {
        $cedula = $_POST['usuario'];
        $clave = $_POST['clave'];

        // Intentar con la base de datos de usuarios administrativos
        $objlogin->set_Cedula($cedula);
        $objlogin->set_Clave($clave);
        $resultado = $objlogin->verificarUsuario();

        // Si no hay coincidencias, intentamos con la base de datos de clientes
        if (!$resultado) {
            $resultado = $objlogin->verificarCliente();
        }

        if ($resultado) {
            if (in_array($resultado->estatus, [1, 2, 3])) {
                
                $_SESSION["id"] = $resultado->id_persona;
                $_SESSION["nombre"] = $resultado->nombre;
                $_SESSION["apellido"] = $resultado->apellido;
                
                // Si el nivel de rol no está definido, asignar el valor por defecto (1)
                $_SESSION["nivel_rol"] = isset($resultado->nivel) ? $resultado->nivel : 1;

                $_SESSION['nombre_usuario'] = isset($resultado->nombre_usuario) ? $resultado->nombre_usuario :'Cliente';
                $_SESSION["cedula"] = $resultado->cedula;
                $_SESSION["telefono"] = $resultado->telefono;
                $_SESSION["correo"] = $resultado->correo;
                $_SESSION["estatus"] = $resultado->estatus;

                // Registrar en la bitácora
                $accion = 'Inicio de sesión';
                $descripcion = 'El usuario ha iniciado sesión exitosamente.';
                $objlogin->registrarBitacora($resultado->id_persona, $accion, $descripcion);  

                // Redirigir según el nivel de rol
                if ($_SESSION["nivel_rol"] == 1) {
                   
                      $res = array('respuesta' => 1, 'accion' => 'ingresar');
                      echo json_encode($res);
                      exit;

                } elseif ($_SESSION["nivel_rol"] == 2 || $_SESSION["nivel_rol"] == 3) {
                      $res = array('respuesta' => 2, 'accion' => 'ingresar');
                      echo json_encode($res);
                      exit;
                } else {
                  
                $res = array('respuesta' => 0, 'accion' => 'ingresar', 'text' => 'Su nivel de acceso no está definido.');
                 echo json_encode($res);
                }
            } else if (isset($resultado->noactiva)) {
            
                $res = array('respuesta' => 0, 'accion' => 'ingresar', 'text' => 'Lo sentimos, su cuenta está suspendida. Por favor, póngase en contacto con el administrador.');
                 echo json_encode($res);
            }
        } else {
        
            $res = array('respuesta' => 0, 'accion' => 'ingresar', 'text' => 'Cédula y/o Clave inválida.');
                 echo json_encode($res);
        }
    }
} else if (isset($_POST['cerrar'])) {
    if (isset($_SESSION["id"])) {
        $id_persona = $_SESSION["id"];
        $accion = 'Cierre de sesión';
        $descripcion = 'El usuario ha cerrado sesión.';
        $objlogin->registrarBitacora($id_persona, $accion, $descripcion);
    }
    
    session_destroy(); // Se cierra la sesión
    header('Location: ?pagina=login');
    exit;
} else if (isset($_POST['registrar'])) {

    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido']; 
    $cedula = $_POST['cedula'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $clave = $_POST['clave'];

    $objlogin->set_Nombre($nombre);
    $objlogin->set_Apellido($apellido);
    $objlogin->set_Cedula($cedula);
    $objlogin->set_Telefono($telefono);
    $objlogin->set_Correo($correo);
    $objlogin->set_Clave($clave);

    //  Verificar si la cédula ya existe
    if ($objlogin->existeCedula()) {
        $res = array('respuesta' => 0, 'accion' => 'incluir', 'text' => 'La cédula ya está registrada.');
        echo json_encode($res);
    }
    // Si la cédula no existe, verificar si el correo ya existe
    else if ($objlogin->existeCorreo()) {
        $res = array('respuesta' => 0, 'accion' => 'incluir', 'text' => 'El correo electrónico ya está registrado.');
        echo json_encode($res);
    }
    // Si ni la cédula ni el correo existen, proceder con el registro
    else {
        $resultadoRegistro = $objlogin->registrar();
        echo json_encode($resultadoRegistro);
    }
} else if (isset($_POST['validarclave'])) {

    $cedula = $_POST['cedula'];
    $objlogin->set_Cedula($cedula);
    
  
     $persona = $objlogin->obtenerPersonaPorCedula();

    if ($persona) {
        $res = array('respuesta' => 1, 'accion' => 'validarclave');
        echo json_encode($res);
        $_SESSION["persona"] = $persona->id_persona;
        $_SESSION["nombres"] = $persona->nombre;
        $_SESSION["apellidos"] = $persona->apellido;
        $_SESSION["correos"] = $persona->correo;
        $_SESSION["iduser"] = 1;
        $_SESSION["tabla_origen"] = ($persona->origen == 'usuario') ? 1 : 2;
       
        exit;
    } else {
        $res = array('respuesta' => 0, 'accion' => 'validarclave', 'text' => 'Cédula incorrecta o no hay registro');
        echo json_encode($res);
    }
} if (isset($_POST['cerrarolvido'])) {    
    session_destroy(); // Se cierra la sesión
    header('Location: ?pagina=login');
    exit;

} else if (!empty($_SESSION['id'])) {
    
    if (isset($_SESSION["id"])) {
        $id_persona = $_SESSION["id"];
        $accion = 'Cierre de sesión';
        $descripcion = 'El usuario ha cerrado sesión por URL.';
        $objlogin->registrarBitacora($id_persona, $accion, $descripcion);
    }    
    
    session_destroy(); // Se cierra la sesión
    header('Location: ?pagina=login');
    exit;

} else {    
    require_once 'vista/login.php';
}