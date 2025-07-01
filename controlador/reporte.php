<?php
session_start();
if (empty($_SESSION['id'])) {
    header('Location:?pagina=login');
    exit;
}

require_once 'modelo/reporte.php';
require_once 'modelo/producto.php';
require_once 'modelo/proveedor.php';
require_once 'modelo/categoria.php';

// 1) Recoger y normalizar Filtros (GET o POST)
$start  = $_REQUEST['f_start'] ?? null;
$end    = $_REQUEST['f_end']   ?? null;
$prodId = $_REQUEST['f_id']    ?? null;
$provId = $_REQUEST['f_prov']  ?? null;
$catId  = $_REQUEST['f_cat']   ?? null;

// Limitar fechas a hoy y corregir orden
$today = date('Y-m-d');
if ($start && $start > $today) $start = $today;
if ($end   && $end   > $today) $end   = $today;
if ($start && $end && $start > $end) {
    list($start, $end) = [$end, $start];
}

// 2) AJAX GET → Conteos JSON
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['accion'])) {
    // Sólo contamos si la acción es una de las cuatro
    $validCounts = ['countCompra','countProducto','countVenta','countPedidoWeb'];
    if (in_array($_GET['accion'], $validCounts, true)) {
        header('Content-Type: application/json');
        switch ($_GET['accion']) {
            case 'countCompra':
                $cnt = Reporte::countCompra($start, $end, $prodId);
                break;
            case 'countProducto':
                $cnt = Reporte::countProducto($prodId, $provId, $catId);
                break;
            case 'countVenta':
                $cnt = Reporte::countVenta($start, $end, $prodId);
                break;
            case 'countPedidoWeb':
                $cnt = Reporte::countPedidoWeb($start, $end, $prodId);
                break;
        }
        echo json_encode(['count' => (int)$cnt]);
        exit;
    }
}

// 3) POST → Generar PDF según acción
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['accion'])) {
    switch ($_GET['accion']) {
        case 'compra':
            Reporte::compra($start, $end, $prodId);
            break;
        case 'producto':
            Reporte::producto($prodId, $provId, $catId);
            break;
        case 'venta':
            Reporte::venta($start, $end, $prodId);
            break;
        case 'pedidoWeb':
            Reporte::pedidoWeb($start, $end, $prodId);
            break;
    }
    exit; // El stream PDF ya finaliza la petición
}

// 4) GET normal → Carga listas y muestra pantalla
$productos_lista   = (new Producto())->consultar();
$proveedores_lista = (new Proveedor())->consultar();
$categorias_lista  = (new Categoria())->consultar();

require_once 'vista/reporte.php';
