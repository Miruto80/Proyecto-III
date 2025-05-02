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
                $_SESSION['id_rol'] = $resultado->id_rol;
                $_SESSION['nombre_usuario'] = $resultado->nombre_usuario; 
                    
                header("location:?pagina=home");

            } else if (isset($resultado->noactiva)) {
                $_SESSION['message'] = array('title' => 'Cuenta No Activa', 'text' => 'Lo sentimos, su cuenta está suspendida. Por favor, póngase en contacto con el administrador.', 'icon' => 'warning');
                header('Location: ?pagina=login'); 
                exit;
            }
        } else {
            $_SESSION['message'] = array('title' => 'Usuario y/o clave Invalida', 'text' => 'Por favor, verifica tus datos y vuelve a intentarlo', 'icon' => 'error');
            header('Location: ?pagina=login'); 
            exit;
        } 
    } 
} else if (isset($_POST['cerrar'])) {
    
    session_destroy(); // Se Cierra la Session
    header('Location: ?pagina=login');
    exit;

} else {
    require_once 'vista/login.php';
}