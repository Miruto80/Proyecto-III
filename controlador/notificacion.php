<?php
session_start();
if (empty($_SESSION['id'])) {
    header('Location:?pagina=login');
    exit;
}
$nivel = (int)($_SESSION['nivel_rol'] ?? 0);

require_once 'modelo/notificacion.php';
$N = new Notificacion();

// POST: acciones AJAX
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

// GET: regenerar + traer + filtrar
$N->generarDePedidos();
$res = $N->getAll();
$all = $res['ok'] ? $res['data'] : [];

// Filtrado según rol
if ($nivel === 2) {
    // Asesora ve solo estado = 2
    $notificaciones = array_values(array_filter($all, fn($n) => intval($n['estado']) === 2));
} else {
    // Admin ve 1,2,3
    $notificaciones = $all;
}

require_once 'vista/notificacion.php';
