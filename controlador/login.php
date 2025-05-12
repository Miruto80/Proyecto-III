<?php

session_start();

require_once 'modelo/login.php';

$objlogin = new Login();

if (isset($_POST['ingresar'])) {
    if (!empty($_POST['usuario']) && !empty($_POST['clave'])) {
        $cedula = $_POST['usuario'];
        $clave = $_POST['clave'];

        $objlogin->set_Cedula($cedula );
        $objlogin->set_Clave( $clave);
       
        $resultado = $objlogin->verificarUsuario();
        if ($resultado) {
            if ($resultado->estatus == 1) { // Account is active

                $_SESSION["id"] = $resultado->id_persona;
                $_SESSION["nombre"] = $resultado->nombre;
                $_SESSION["apellido"] = $resultado->apellido;
             
                $_SESSION['id_rol'] = $resultado->id_tipo;
                $_SESSION["nivel_rol"] = $resultado->nivel;
                $_SESSION['nombre_usuario'] = $resultado->nombre_usuario; 
                
                $_SESSION["cedula"] = $resultado->cedula;
                $_SESSION["telefono"] = $resultado->telefono;
                $_SESSION["correo"] = $resultado->correo;    

                // Registrar en la bitácora
                 $accion = 'Inicio de sesión';
                $descripcion = 'El usuario ha iniciado sesión exitosamente.';
                $objlogin->registrarBitacora($resultado->id_persona, $accion, $descripcion);  

                 // Verificar el nivel de rol para redirigir a la vista adecuada
                if ($_SESSION["nivel_rol"] == 1) {
                    header("Location: ?pagina=catalogo");
                } elseif ($_SESSION["nivel_rol"] == 2 || $_SESSION["nivel_rol"] == 3) {
                    header("Location: ?pagina=home");
                } else {
                    $_SESSION['message'] = array('title' => 'Acceso no permitido', 'text' => 'Su nivel de acceso no está definido.', 'icon' => 'error');
                    header('Location: ?pagina=login');
                    exit;
                }

            } else if (isset($resultado->noactiva)) {
                $_SESSION['message'] = array('title' => 'Cuenta No Activa', 'text' => 'Lo sentimos, su cuenta está suspendida. Por favor, póngase en contacto con el administrador.', 'icon' => 'warning');
                header('Location: ?pagina=login'); 
                exit;
            }
        } else {
            $_SESSION['message'] = array('title' => 'Cedula y/o clave Invalida', 'text' => 'Por favor, verifica tus datos y vuelve a intentarlo', 'icon' => 'error');
            header('Location: ?pagina=login'); 
            exit;
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
} else {
    require_once 'vista/login.php';
}