<?php
session_start();
if (empty($_SESSION['id'])) {
    header('Location:?pagina=login');
    exit;
}

$nivel = (int)($_SESSION['nivel_rol'] ?? 0);

require_once __DIR__ . '/../modelo/Notificacion.php';

require_once 'modelo/tipousuario.php';  // para bitácora
require_once 'permiso.php';


$N   = new Notificacion();
$Bit = new tipousuario();

// 1) AJAX GET → sólo devuelvo el conteo (badge)
if ($_SERVER['REQUEST_METHOD'] === 'GET'
    && ($_GET['accion'] ?? '') === 'count')
{
    header('Content-Type: application/json');
    $N->generarDePedidos();

    if ($nivel === 3) {
        // Admin cuenta estados 1 y 4
        $count = $N->contarParaAdmin();
    } elseif ($nivel === 2) {
        // Asesora cuenta solo estado 1
        $count = $N->contarNuevas();
    } else {
        $count = 0;
    }

    echo json_encode(['count' => $count]);
    exit;
}

// 2) AJAX GET → nuevos pedidos/reservas
if ($_SERVER['REQUEST_METHOD'] === 'GET'
    && ($_GET['accion'] ?? '') === 'nuevos')
{
    header('Content-Type: application/json');
    // Asegura notificaciones antes de listar
    $N->generarDePedidos();

    $lastId = (int)($_GET['lastId'] ?? 0);
    $nuevos = $N->getNuevosPedidos($lastId);

    echo json_encode([
      'count'   => count($nuevos),
      'pedidos' => $nuevos
    ]);
    exit;
}

// 3) POST → acciones sobre notificaciones
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['accion'])) {
    $accion = $_GET['accion'];
    $id     = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $msg    = '';

    // Preparo datos para bitácora si aplica
    if (in_array($accion, ['marcarLeida','marcarLeidaAsesora','entregar','eliminar'], true)) {
        $row = (new Conexion())->getConex1()
               ->query("SELECT id_pedido, mensaje 
                         FROM notificaciones 
                        WHERE id_notificacion = {$id}")
               ->fetch(PDO::FETCH_ASSOC);
        $pedido = $row['id_pedido'] ?? '';
        $texto  = $row['mensaje']   ?? '';
    }

    switch ($accion) {
        case 'vaciar':
            if ($nivel === 3) {
                $deleted = $N->vaciarEntregadas();
                $msg = $deleted
                     ? "Se vaciaron todas las notificaciones entregadas."
                     : "No había notificaciones entregadas.";

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
            // Admin global (1|4 → 2)
            if ($nivel === 3 && $id > 0) {
                $ok  = $N->marcarLeida($id);
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

        case 'marcarLeidaAsesora':
            // Asesora solo para ella (1 → 4)
            if ($nivel === 2 && $id > 0) {
                $ok  = $N->marcarLeidaAsesora($id);
                $msg = $ok
                     ? 'Notificación marcada como leída para ti.'
                     : 'No se pudo marcar como leída.';
            } else {
                $msg = 'No autorizado.';
            }
            break;

        case 'entregar':
            if ($nivel === 2 && $id > 0) {
                $ok  = $N->entregar($id);
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
                $ok  = $N->eliminar($id);
                $msg = $ok
                     ? 'Notificación eliminada.'
                     : 'Solo se pueden borrar notificaciones entregadas.';
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

// 4) GET normal: regenerar y listar
$N->generarDePedidos();
$all = $N->getAll();

// FILTRADO según rol:
//  - Admin ve estados 1 (nuevas) y 4 (leídas solo por asesora)
//  - Asesora ve solo estado 1
if ($nivel === 3) {
    $notificaciones = array_filter(
      $all,
      fn($n) => in_array((int)$n['estado'], [1,4])
    );
}
elseif ($nivel === 2) {
    $notificaciones = array_filter(
      $all,
      fn($n) => (int)$n['estado'] === 1
    );
}
else {
    $notificaciones = [];
}

// Conteo para badge nav
if ($nivel === 3) {
    $newCount = $N->contarParaAdmin();
}
elseif ($nivel === 2) {
    $newCount = $N->contarNuevas();
}
else {
    $newCount = 0;
}

// 5) Cargar vista
if ($nivel >= 2) {
    require_once __DIR__ . '/../vista/Notificacion.php';

} elseif ($nivel === 1) {
    header("Location: ?pagina=catalogo");
    exit();
} else {
    require_once 'vista/seguridad/privilegio.php';
}
