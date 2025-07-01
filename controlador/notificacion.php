<?php
session_start();
if (empty($_SESSION['id'])) {
    header('Location:?pagina=login');
    exit;
}

$nivel = (int)($_SESSION['nivel_rol'] ?? 0);

require_once 'modelo/notificacion.php';
$N = new Notificacion();

// 1) AJAX GET: conteo de notificaciones nuevas para el rol activo
if ($_SERVER['REQUEST_METHOD'] === 'GET' && ($_GET['accion'] ?? '') === 'count') {
    header('Content-Type: application/json');

    // regenerar pendientes
    $N->generarDePedidos();

    // contar según rol
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

// 2) POST: acciones sobre notificaciones → procesar + redirect
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['accion'])) {
    $accion = $_GET['accion'];
    $id     = isset($_POST['id']) ? (int) $_POST['id'] : 0;
    $msg    = '';
    
    switch ($accion) {
        case 'vaciar':
            if ($nivel === 3) {
                $res = $N->vaciarEntregadas();
                $deleted = $res['deleted'] ?? 0;
                $msg = $deleted
                     ? "Se eliminaron {$deleted} notificaciones entregadas."
                     : "No había notificaciones entregadas para vaciar.";
            } else {
                $msg = 'No autorizado.';
            }
            break;

        case 'marcarLeida':
            if ($nivel === 3 && $id > 0) {
                $ok = $N->marcarLeida($id);
                $msg = $ok
                     ? 'Notificación marcada como leída.'
                     : 'No se pudo marcar como leída.';
            } else {
                $msg = 'No autorizado.';
            }
            break;

        case 'entregar':
            if ($nivel === 2 && $id > 0) {
                $ok = $N->entregar($id);
                $msg = $ok
                     ? 'Notificación marcada como entregada.'
                     : 'No se pudo marcar como entregada.';
            } else {
                $msg = 'No autorizado.';
            }
            break;

        case 'eliminar':
            if ($nivel === 3 && $id > 0) {
                $ok = $N->eliminar($id);
                $msg = $ok
                     ? 'Notificación eliminada.'
                     : 'No se pudo eliminar la notificación.';
            } else {
                $msg = 'No autorizado.';
            }
            break;

        default:
            // acción desconocida
            header('HTTP/1.1 400 Bad Request');
            exit;
    }

    // guardar mensaje en sesión
    $_SESSION['flash_notif'] = $msg;

    // redirigir a la lista
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

// conteo de nuevas (para mostrar badge en nav si lo usas en PHP)
if ($nivel === 3) {
    $newCount = $N->contarNuevas();
} elseif ($nivel === 2) {
    $newCount = $N->contarParaAsesora();
} else {
    $newCount = 0;
}

// 4) Cargar vista
require_once 'vista/notificacion.php';
