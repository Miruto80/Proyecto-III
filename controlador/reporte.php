<?php

session_start();
if (empty($_SESSION['id'])) {
    header('Location:?pagina=login');
    exit;
}

require_once 'modelo/reporte.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reportType'])) {
    // 1) Recoger filtros
    $start = !empty($_POST['f_start']) ? $_POST['f_start'] : null;
    $end   = !empty($_POST['f_end'])   ? $_POST['f_end']   : null;
    $id    = !empty($_POST['f_id'])    ? $_POST['f_id']    : null;

    // 2) Fecha de hoy para validación
    $today = date('Y-m-d');

    // 3) Evitar fechas futuras
    if ($start && $start > $today) {
        $start = $today;
    }
    if ($end && $end > $today) {
        $end = $today;
    }

    // 4) Si invertimos el rango, intercambiamos
    if ($start && $end && $start > $end) {
        list($start, $end) = [$end, $start];
    }

    // 5) Llamar al método adecuado con filtros saneados
    switch ($_POST['reportType']) {
        case 'compra':
            Reporte::compra($start, $end, $id);
            break;

        case 'producto':
            Reporte::producto($start, $end, $id);
            break;

        case 'venta':
            Reporte::venta($start, $end, $id);
            break;

        case 'proveedor':
            Reporte::proveedor($start, $end, $id);
            break;

        case 'pedidoWeb':
            Reporte::pedidoWeb($start, $end, $id);
            break;

        default:
            echo "Tipo de reporte inválido.";
    }

    // Detener la ejecución antes de renderizar la vista
    exit;
}

// Si no es POST o no viene reportType, mostramos el formulario
require_once 'vista/reporte.php';
