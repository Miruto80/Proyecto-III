<?php
session_start();// Inicia la sesión PHP

if(!isset($_SESSION['usuario_id']) && (!isset($_GET['pagina']) || $_GET['pagina'] != 'login')){// Verifica si el usuario no está autenticado y no está en la página de login
    header("Location: ?pagina=login");// Redirige al usuario a la página de login
    exit;
}

if(isset($_GET['pagina']) && $_GET['pagina'] == 'logout'){// Maneja el cierre de sesión
    session_destroy();// Destruye la sesión actual
    header("Location: ?pagina=login");// Redirige al usuario a la página de login
    exit;
}

$pagina = "principal"; // Establece la página por defecto como "principal"

if (!empty($_GET['pagina'])){ // Si se proporciona una página en la URL, la usa
    $pagina = basename($_GET['pagina']);  
}

if(is_file("controlador/".$pagina.".php")){ // Verifica si existe el archivo del controlador para la página solicitada 
    require_once("controlador/".$pagina.".php");// Incluye el archivo del controlador
} else {// Si no existe el archivo, muestra un error 404
    require_once("vista/404.php");
}

/*
1.Gestión de sesiones: Inicia la sesión y verifica si el usuario está autenticado.
2.Control de acceso: Redirige a los usuarios no autenticados a la página de login.
3.Cierre de sesión: Maneja la solicitud de cierre de sesión.
4.Enrutamiento: Determina qué página debe cargarse basándose en el parámetro 'pagina' de la URL.
5.Carga de controladores: Incluye el archivo del controlador correspondiente a la página solicitada.
6.Manejo de errores: Muestra un error 404 si no se encuentra el controlador solicitado.
*/

?>