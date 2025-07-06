<?php

session_start();
if (empty($_SESSION['id'])) {
    header('Location:?pagina=login');
    exit;
}

$nivel = (int)($_SESSION['nivel_rol'] ?? 0);

require_once 'modelo/notificacion.php';
require_once 'modelo/tipousuario.php';  // para bitácora
 require_once 'permiso.php';
$N    = new Notificacion();
$Bit  = new tipousuario();

// 1) AJAX GET: conteo de notificaciones nuevas para el rol activo
if ($_SERVER['REQUEST_METHOD'] === 'GET' && ($_GET['accion'] ?? '') === 'count') {
    header('Content-Type: application/json');
    $N->generarDePedidos();
    if ($nivel === 3) {
        $count = $N->contarNuevas();
    } elseif ($nivel === 2) {
        $count = $N->contarParaAsesora();
    } else {
        $count = 0;
    }
    echo json_encode(['count' => $count]);
    exit;
}

// 2) POST: acciones sobre notificaciones → procesar + bitácora + redirect
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['accion'])) {
    $accion = $_GET['accion'];
    $id     = isset($_POST['id']) ? (int) $_POST['id'] : 0;
    $msg    = '';

    // Antes de actualizar, obtengo datos de la notificación
    if (in_array($accion, ['marcarLeida','entregar','eliminar'])) {
        // extraigo id_pedido y mensaje para descripción
        $row = (new Conexion())->getConex1()
               ->query("SELECT id_pedido, mensaje 
                        FROM notificaciones 
                        WHERE id_notificaciones = {$id}")
               ->fetch(PDO::FETCH_ASSOC);
        $pedido = $row['id_pedido'] ?? '';
        $texto  = $row['mensaje']   ?? '';
    }

    switch ($accion) {
        case 'vaciar':
            if ($nivel === 3) {
                $res     = $N->vaciarEntregadas();
                $deleted = $res['deleted'] ?? 0;
                $msg     = $deleted
                         ? "Se vaciaron todas las notificaciones entregadas."
                         : "No había notificaciones entregadas.";
                // bitácora
                $Bit->registrarBitacora(json_encode([
                    'id_persona' => $_SESSION['id'],
                    'accion'     => 'Vaciar notificaciones',
                    'descripcion'=> "Se vaciaron {$deleted} notificaciones entregadas"
                ]));
            } else {
                $msg = 'No autorizado.';
            }
            break;

        case 'marcarLeida':
            if ($nivel === 3 && $id > 0) {
                $res = $N->marcarLeida($id);
                $ok  = $res['ok'];
                $msg = $ok
                     ? 'Notificación marcada como leída.'
                     : 'No se pudo marcar como leída.';
                if ($ok) {
                    $Bit->registrarBitacora(json_encode([
                        'id_persona' => $_SESSION['id'],
                        'accion'     => 'Leer notificación',
                        'descripcion'=> "Se marcó como leída la notificación “{$texto}” del pedido #{$pedido}"
                    ]));
                }
            } else {
                $msg = 'No autorizado.';
            }
            break;

        case 'entregar':
            if ($nivel === 2 && $id > 0) {
                $res = $N->entregar($id);
                $ok  = $res['ok'];
                $msg = $ok
                     ? 'Notificación marcada como entregada.'
                     : 'No se pudo marcar como entregada.';
                if ($ok) {
                    $Bit->registrarBitacora(json_encode([
                        'id_persona' => $_SESSION['id'],
                        'accion'     => 'Entregar notificación',
                        'descripcion'=> "Se entregó la notificación “{$texto}” del pedido #{$pedido}"
                    ]));
                }
            } else {
                $msg = 'No autorizado.';
            }
            break;

        case 'eliminar':
            if ($nivel === 3 && $id > 0) {
                $res = $N->eliminar($id);
                $ok  = $res['ok'];
                $msg = $ok
                     ? 'Notificación eliminada.'
                     : $res['error'] ?? 'No se pudo eliminar.';
                if ($ok) {
                    $Bit->registrarBitacora(json_encode([
                        'id_persona' => $_SESSION['id'],
                        'accion'     => 'Eliminar notificación',
                        'descripcion'=> "Se eliminó la notificación “{$texto}” del pedido #{$pedido}"
                    ]));
                }
            } else {
                $msg = 'No autorizado.';
            }
            break;

        default:
            header('HTTP/1.1 400 Bad Request');
            exit;
    }

    $_SESSION['flash_notif'] = $msg;
    header('Location:?pagina=notificacion');
    exit;
}

// 3) GET normal: regenerar y listar
$N->generarDePedidos();
$res = $N->getAll();
$all = $res['ok'] ? $res['data'] : [];

// filtrar según rol
if ($nivel === 2) {
    $notificaciones = array_values(
        array_filter($all, fn($n) => intval($n['estado']) === 2)
    );
} else {
    $notificaciones = $all;
}

// conteo de nuevas (badge nav)
if ($nivel === 3) {
    $newCount = $N->contarNuevas();
} elseif ($nivel === 2) {
    $newCount = $N->contarParaAsesora();
} else {
    $newCount = 0;
}

// 4) Cargar vista


if($_SESSION["nivel_rol"] >=2) { // Validacion si es administrador entra
       /* $bitacora = [
            'id_persona' => $_SESSION["id"],
            'accion' => 'Acceso a Módulo',
            'descripcion' => 'módulo de Metodo Pago'
        ];
        $N->registrarBitacora(json_encode($bitacora));*/
        // Para GET o acceso normal, se carga la vista con los métodos activos
            // Carga inicial de la vista con los métodos activos
       require_once 'vista/notificacion.php';

        } else if ($_SESSION["nivel_rol"] == 1) {

            header("Location: ?pagina=catalogo");
            exit();

        } else {
        require_once 'vista/seguridad/privilegio.php';
    }
