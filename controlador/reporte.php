<?php

session_start();
if (empty($_SESSION['id'])) {
    header('Location:?pagina=login');
    exit;
}

// Modelos necesarios
require_once 'modelo/reporte.php';
require_once 'modelo/producto.php';
require_once 'modelo/proveedor.php';  // Solo para el filtro en Producto
require_once 'modelo/categoria.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reportType'])) {
    // 1) Recoger filtros
    $start   = !empty($_POST['f_start']) ? $_POST['f_start'] : null;
    $end     = !empty($_POST['f_end'])   ? $_POST['f_end']   : null;
    $prodId  = !empty($_POST['f_id'])    ? $_POST['f_id']    : null;
    $provId  = !empty($_POST['f_prov'])  ? $_POST['f_prov']  : null;  // Filtrar en Producto
    $catId   = !empty($_POST['f_cat'])   ? $_POST['f_cat']   : null;

    // 2) Validar fechas (si aplica)
    $today = date('Y-m-d');
    if ($start && $start > $today) $start = $today;
    if ($end   && $end   > $today) $end   = $today;
    if ($start && $end && $start > $end) {
        list($start, $end) = [$end, $start];
    }

    // 3) ¿Chequeo AJAX (sin generar PDF)?
    $checkOnly = isset($_POST['checkOnly']);

    // 4) Disparar según tipo
    switch ($_POST['reportType']) {
        case 'compra':
            if ($checkOnly) {
                $cnt = Reporte::countCompra($start, $end, $prodId);
                header('Content-Type: application/json');
                echo json_encode(['count' => $cnt]);
                exit;
            }
            Reporte::compra($start, $end, $prodId);
            break;

        case 'producto':
            if ($checkOnly) {
                $cnt = Reporte::countProducto($prodId, $provId, $catId);
                header('Content-Type: application/json');
                echo json_encode(['count' => $cnt]);
                exit;
            }
            Reporte::producto($prodId, $provId, $catId);
            break;

        case 'venta':
            if ($checkOnly) {
                $cnt = Reporte::countVenta($start, $end, $prodId);
                header('Content-Type: application/json');
                echo json_encode(['count' => $cnt]);
                exit;
            }
            Reporte::venta($start, $end, $prodId);
            break;

        case 'pedidoWeb':
            if ($checkOnly) {
                $cnt = Reporte::countPedidoWeb($start, $end, $prodId);
                header('Content-Type: application/json');
                echo json_encode(['count' => $cnt]);
                exit;
            }
            Reporte::pedidoWeb($start, $end, $prodId);
            break;

        default:
            echo "Tipo de reporte inválido.";
    }

    exit; // no renderizar vista
}

// --- GET: cargar listas para los <select> de los modales ---

$prodModel         = new Producto();
$productos_lista   = $prodModel->consultar();

$provModel         = new Proveedor();
$proveedores_lista = $provModel->consultar();

$catModel          = new Categoria();
$categorias_lista  = $catModel->consultar();

require_once 'vista/reporte.php';
