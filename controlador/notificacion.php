<?php  
     session_start();

     if (empty($_SESSION["id"])){
       header("location:?pagina=login");
       header('Content-Type: application/json'); // Asegura que el servidor envíe JSON

     } /*  Validacion URL  */
     
     require_once 'modelo/notificacion.php';

     $notificacion = new notificacion();


// Registrar nuevas notificaciones de pedidos pendientes
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion'], $_POST['id_notificaciones'])) {
        $id = $_POST['id_notificaciones'];
        $notificacion->setIdNotificacion($id);

        if ($_POST['accion'] === 'leer') {
            echo json_encode($notificacion->cambiarestato());
            exit;
        }

        if ($_POST['accion'] === 'eliminar') {
            echo json_encode($notificacion->eliminar());
            exit;
        }
    }

    if ($_POST['accion'] === 'vaciar') {
        echo json_encode($notificacion->eliminar());
        exit;
    }

    echo json_encode(['respuesta' => 0, 'accion' => 'sin datos válidos']);
    exit;
}


// Si no es POST, mostrar la vista
$resultadoRegistro = $notificacion->registrarNotificacionesDePedidos();
$notificaciones = $notificacion->obtenerNotificaciones();
require_once 'vista/notificacion.php';


// Obtener todas las notificaciones existentes


     


?>