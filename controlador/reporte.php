<?php
session_start();
if (empty($_SESSION['id'])) {
    header('Location:?pagina=login');
    exit;
}
if (!empty($_SESSION['id'])) {
        require_once 'verificarsession.php';
} 
require_once 'modelo/reporte.php';
require_once 'modelo/producto.php';
require_once 'modelo/proveedor.php';
require_once 'modelo/categoria.php';
require_once 'permiso.php';

$objProd = new Producto();

// 1) Recoger valores "raw" (pueden venir como string vacíos)
$startRaw = $_REQUEST['f_start'] ?? '';
$endRaw   = $_REQUEST['f_end']   ?? '';
$prodRaw  = $_REQUEST['f_id']    ?? '';
$provRaw  = $_REQUEST['f_prov']  ?? '';
$catRaw   = $_REQUEST['f_cat']   ?? '';

// 2) Nuevos filtros avanzados
$montoMinRaw = $_REQUEST['monto_min'] ?? '';
$montoMaxRaw = $_REQUEST['monto_max'] ?? '';
$precioMinRaw = $_REQUEST['precio_min'] ?? '';
$precioMaxRaw = $_REQUEST['precio_max'] ?? '';
$stockMinRaw = $_REQUEST['stock_min'] ?? '';
$stockMaxRaw = $_REQUEST['stock_max'] ?? '';
$metodoPagoRaw = $_REQUEST['f_mp'] ?? '';
$metodoPagoWebRaw = $_REQUEST['metodo_pago'] ?? '';
$estadoRaw = $_REQUEST['estado'] ?? '';

// 3) Normalizar para que sean null o int/float
$start  = $startRaw ?: null;
$end    = $endRaw   ?: null;
$prodId = is_numeric($prodRaw) ? (int)$prodRaw : null;
$provId = is_numeric($provRaw) ? (int)$provRaw : null;
$catId  = is_numeric($catRaw)  ? (int)$catRaw  : null;

// Normalizar nuevos filtros
$montoMin = is_numeric($montoMinRaw) ? (float)$montoMinRaw : null;
$montoMax = is_numeric($montoMaxRaw) ? (float)$montoMaxRaw : null;
$precioMin = is_numeric($precioMinRaw) ? (float)$precioMinRaw : null;
$precioMax = is_numeric($precioMaxRaw) ? (float)$precioMaxRaw : null;
$stockMin = is_numeric($stockMinRaw) ? (int)$stockMinRaw : null;
$stockMax = is_numeric($stockMaxRaw) ? (int)$stockMaxRaw : null;
$metodoPago = is_numeric($metodoPagoRaw) ? (int)$metodoPagoRaw : null;
$metodoPagoWeb = is_numeric($metodoPagoWebRaw) ? (int)$metodoPagoWebRaw : null;
$estado = is_numeric($estadoRaw) ? (int)$estadoRaw : null;

// Limitar fechas a hoy y corregir orden
$today = date('Y-m-d');
if ($start && $start > $today) $start = $today;
if ($end   && $end   > $today) $end   = $today;
if ($start && $end && $start > $end) {
    list($start, $end) = [$end, $start];
}

// Acción solicitada
$accion = isset($_REQUEST['accion']) ? $_REQUEST['accion'] : '';

// 2) AJAX GET → conteos JSON
if ($_SERVER['REQUEST_METHOD'] === 'GET'
    && in_array($accion, ['countCompra','countProducto','countVenta','countPedidoWeb'], true)
) {
    header('Content-Type: application/json');

    switch ($accion) {
        case 'countCompra':
            $cnt = Reporte::countCompra($start, $end, $prodId, $catId, $provId, $montoMin, $montoMax);
            break;
        case 'countProducto':
            $cnt = Reporte::countProducto($prodId, $provId, $catId, $precioMin, $precioMax, $stockMin, $stockMax, $estado);
            break;
        case 'countVenta':
            $cnt = Reporte::countVenta($start, $end, $prodId, $metodoPago, $catId, $montoMin, $montoMax);
            break;
        case 'countPedidoWeb':
            $cnt = Reporte::countPedidoWeb($start, $end, $prodId, $estado, $metodoPagoWeb, $montoMin, $montoMax);
            break;
        default:
            $cnt = 0;
    }

    echo json_encode(['count' => (int)$cnt]);
    exit;
}

// 3) POST → generar PDF y registrar bitácora
if ($_SERVER['REQUEST_METHOD'] === 'POST'
    && in_array($accion, ['compra','producto','venta','pedidoWeb'], true)
) {
    $userId = $_SESSION['id'];
    $rol    = $_SESSION['nivel_rol'] == 2
            ? 'Asesora de Ventas'
            : 'Administrador';

    switch ($accion) {
        case 'compra':
            Reporte::compra($start, $end, $prodId, $catId, $provId, $montoMin, $montoMax);
            $desc = 'Generó Reporte de Compras';
            break;
        case 'producto':
            Reporte::producto($prodId, $provId, $catId, $precioMin, $precioMax, $stockMin, $stockMax, $estado);
            $desc = 'Generó Reporte de Productos';
            break;
        case 'venta':
            Reporte::venta($start, $end, $prodId, $catId, $metodoPago, $montoMin, $montoMax);
            $desc = 'Generó Reporte de Ventas';
            break;
        case 'pedidoWeb':
            Reporte::pedidoWeb($start, $end, $prodId, $estado, $metodoPagoWeb, $montoMin, $montoMax);
            $desc = 'Generó Reporte de Pedidos Web';
            break;
        default:
            $desc = '';
    }

    if ($desc) {
        $objProd->registrarBitacora(json_encode([
            'id_persona'  => $userId,
            'accion'      => $desc,
            'descripcion' => "Usuario ($rol) ejecutó $desc"
        ]));
    }

    exit; // PDF ya enviado
}

// 4) GET normal → cargar listas y mostrar pantalla
$productos_lista   = (new Producto())->consultar();
$proveedores_lista = (new Proveedor())->consultar();
$categorias_lista  = (new Categoria())->consultar();



if ($_SESSION["nivel_rol"] >= 2 && tieneAcceso(1, 'ver')) {
     $pagina_actual = isset($_GET['pagina']) ? $_GET['pagina'] : 'reporte';
        require_once 'vista/reporte.php';
} else {
        require_once 'vista/seguridad/privilegio.php';

} if ($_SESSION["nivel_rol"] == 1) {
    header("Location: ?pagina=catalogo");
    exit();
}

