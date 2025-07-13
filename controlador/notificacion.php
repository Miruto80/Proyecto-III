<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
if (empty($_SESSION['id'])) {
    header('Location:?pagina=login');
    exit;
}

$nivel = (int)($_SESSION['nivel_rol'] ?? 0);


require_once __DIR__ . '/../modelo/notificacion.php';
require_once __DIR__ . '/../modelo/tipousuario.php';  // antes: 'modelo/tipousuario.php'
require_once __DIR__ . '/permiso.php';               // antes: 'permiso.php'



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

// 3) POST → solo ‘leer’ y siempre respondo JSON
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['accion'])) {
    header('Content-Type: application/json');

    $accion = $_GET['accion'];
    $id     = (int)($_POST['id'] ?? 0);
    $success = false;
    $mensaje = '';

    // Admin
    if ($accion === 'marcarLeida' && $nivel === 3 && $id > 0) {
        $success = $N->marcarLeida($id);
        $mensaje = $success
            ? 'Notificación marcada como leída.'
            : 'Error al marcar como leída.';
    }
    // Asesora
    elseif ($accion === 'marcarLeidaAsesora' && $nivel === 2 && $id > 0) {
        $success = $N->marcarLeidaAsesora($id);
        $mensaje = $success
            ? 'Notificación marcada como leída para ti.'
            : 'Error al marcar como leída.';
    }
    else {
        http_response_code(400);
        echo json_encode(['success' => false, 'mensaje' => 'Acción inválida o no autorizada.']);
        exit;
    }

    // Respondo siempre JSON y salgo
    echo json_encode(['success' => $success, 'mensaje' => $mensaje]);
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
    require_once __DIR__ . '/../vista/notificacion.php';

} elseif ($nivel === 1) {
    header("Location: ?pagina=catalogo");
    exit();
} else {
    require_once 'vista/seguridad/privilegio.php';
}
