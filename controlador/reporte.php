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

$objProd = new Producto();

// 1) Recoger y normalizar filtros
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

// Acción solicitada
$accion = $_REQUEST['accion'] ?? '';

// 2) AJAX GET → conteos JSON
if ($_SERVER['REQUEST_METHOD'] === 'GET'
    && in_array($accion, ['countCompra','countProducto','countVenta','countPedidoWeb'], true)
) {
    header('Content-Type: application/json');
    switch ($accion) {
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

// 3) POST → generar PDF y registrar bitácora
if ($_SERVER['REQUEST_METHOD'] === 'POST'
    && in_array($accion, ['compra','producto','venta','pedidoWeb'], true)
) {
    $userId = $_SESSION['id'];
    $rol    = $_SESSION['nivel_rol']==2
            ? 'Asesora de Ventas'
            : 'Administrador';

    switch ($accion) {
        case 'compra':
            Reporte::compra($start, $end, $prodId);
            $desc = 'Generó Reporte de Compras';
            break;
        case 'producto':
            Reporte::producto($prodId, $provId, $catId);
            $desc = 'Generó Reporte de Productos';
            break;
        case 'venta':
            Reporte::venta($start, $end, $prodId);
            $desc = 'Generó Reporte de Ventas';
            break;
        case 'pedidoWeb':
            Reporte::pedidoWeb($start, $end, $prodId);
            $desc = 'Generó Reporte de Pedidos Web';
            break;
    }

    // Registrar en bitácora
    $objProd->registrarBitacora(json_encode([
        'id_persona'  => $userId,
        'accion'      => $desc,
        'descripcion' => "Usuario ($rol) ejecutó $desc"
    ]));

    exit; // PDF ya enviado
}

// 4) GET normal → cargar listas y mostrar pantalla
$productos_lista   = (new Producto())->consultar();
$proveedores_lista = (new Proveedor())->consultar();
$categorias_lista  = (new Categoria())->consultar();

require_once 'vista/reporte.php';
