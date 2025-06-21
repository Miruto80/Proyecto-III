<?php
session_start();
if (empty($_SESSION['id'])) {
    header('Location:?pagina=login');
    exit;
}

require_once 'modelo/reporte.php';

if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['reportType'])) {
    switch ($_POST['reportType']) {
        case 'compra':
            Reporte::compra();
            break;
        case 'producto':
            Reporte::producto();
            break;
        case 'venta':
            Reporte::venta();
            break;
        case 'proveedor':
            Reporte::proveedor();
            break;
        case 'pedidoWeb':
            Reporte::pedidoWeb();
            break;
    }
    exit;  // detenemos para que no cargue la vista
}

// Si no es POST, mostramos la vista:
require_once 'vista/reporte.php';
