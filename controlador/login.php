<?php
// Verifica si el archivo modelo/login.php existe
if (!is_file("modelo/login.php")){
    echo "Falta definir la clase login"; // Mensaje de error si no existe
    exit; // Termina la ejecución del script
}

// Incluye el archivo modelo/login.php
require_once("modelo/login.php");

// Verifica si el archivo vista/login.php existe
if(is_file("vista/login.php")){
    // Comprueba si se ha enviado algún dato por POST
    if(!empty($_POST)){
        $o = new login(); // Crea una instancia de la clase login
        $accion = $_POST['accion']; // Obtiene la acción del formulario
        // Si la acción es 'login', autentica al usuario
        if($accion == 'login'){
            echo json_encode($o->autenticar($_POST['nombre_usuario'], $_POST['contraseña'])); // Devuelve el resultado de la autenticación en formato JSON
        }
        exit; // Termina la ejecución después de procesar la acción
    }
    // Incluye la vista de login si el archivo existe
    require_once("vista/login.php");
} else {
    echo "ERROR 404"; // Mensaje de error si la vista no existe
}
?>