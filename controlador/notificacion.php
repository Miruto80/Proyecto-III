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

    // Primero: generar notificaciones de pedidos pendientes
    $N->generarDePedidos();

    // Luego: contar según rol
    if ($nivel === 3) {
        // Admin ve notificaciones sin leer (estado = 1)
        $count = $N->contarNuevas();
    } elseif ($nivel === 2) {
        // Asesora ve notificaciones leídas (estado = 2)
        $count = $N->contarParaAsesora();
    } else {
        $count = 0;
    }

    echo json_encode(['count' => $count]);
    exit;
}

// 2) AJAX POST: acciones sobre notificaciones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $accion = $_GET['accion']  ?? '';
    $id     = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $resp   = ['ok' => false, 'error' => 'Acción inválida'];

    switch ($accion) {
        case 'marcarLeida':
            if ($nivel === 3 && $id > 0) {
                $resp = $N->marcarLeida($id);
            } else {
                $resp['error'] = 'Solo Admin';
            }
            break;

        case 'entregar':
            if ($nivel === 2 && $id > 0) {
                $resp = $N->entregar($id);
            } else {
                $resp['error'] = 'Solo Asesora';
            }
            break;

        case 'eliminar':
            if ($nivel === 3 && $id > 0) {
                $resp = $N->eliminar($id);
            } else {
                $resp['error'] = 'No autorizado';
            }
            break;

        case 'vaciar':
            if ($nivel === 3) {
                $resp = $N->vaciarEntregadas();
            } else {
                $resp['error'] = 'Solo Admin';
            }
            break;
    }

    echo json_encode($resp);
    exit;
}

// 3) GET normal: regenerar notificaciones y obtener listado
$N->generarDePedidos();
$res = $N->getAll();
$all = $res['ok'] ? $res['data'] : [];

// 4) Filtrar según rol para tabla
if ($nivel === 2) {
    // Asesora ve solo estado = 2
    $notificaciones = array_values(
        array_filter($all, fn($n) => intval($n['estado']) === 2)
    );
} else {
    // Admin ve estados 1,2,3
    $notificaciones = $all;
}

// 5) Conteo de nuevas para el badge rosa en el nav
// (puede usarse en el nav o pasarse a la vista de notificaciones)
if ($nivel === 3) {
    $newCount = $N->contarNuevas();
} elseif ($nivel === 2) {
    $newCount = $N->contarParaAsesora();
} else {
    $newCount = 0;
}

// 6) Cargar la vista de notificaciones
require_once 'vista/notificacion.php';
