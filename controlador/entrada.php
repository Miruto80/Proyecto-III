<?php
session_start();
if (empty($_SESSION["id"])) {
    header("location:?pagina=login");
    exit;
} /* Validacion URL */
if (!empty($_SESSION['id'])) {
        require_once 'verificarsession.php';
} 

if ($_SESSION["nivel_rol"] == 1) {
        header("Location: ?pagina=catalogo");
        exit();
    }/*  Validacion cliente  */

    
require_once 'modelo/entrada.php';
require_once 'modelo/bitacora.php';
require_once 'permiso.php';
$entrada = new Entrada();

// Detectar si la solicitud es AJAX
function esAjax() {
    return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
}

// Función para sanitizar datos de entrada
function sanitizar($dato) {
    return htmlspecialchars(trim($dato), ENT_QUOTES, 'UTF-8');
}

// Procesar el registro de una nueva compra
if (isset($_POST['registrar_compra'])) {
    if (!empty($_POST['fecha_entrada']) && !empty($_POST['id_proveedor']) && isset($_POST['id_producto']) && is_array($_POST['id_producto'])) {
        $productos = [];
        for ($i = 0; $i < count($_POST['id_producto']); $i++) {
            if (!empty($_POST['id_producto'][$i]) && isset($_POST['cantidad'][$i]) && $_POST['cantidad'][$i] > 0) {
                $productos[] = array(
                    'id_producto' => intval($_POST['id_producto'][$i]),
                    'cantidad' => intval($_POST['cantidad'][$i]),
                    'precio_unitario' => floatval($_POST['precio_unitario'][$i]),
                    'precio_total' => floatval($_POST['precio_total'][$i])
                );
            }
        }

        $datosCompra = [
            'operacion' => 'registrar',
            'datos' => [
                'fecha_entrada' => $_POST['fecha_entrada'],
                'id_proveedor' => intval($_POST['id_proveedor']),
                'productos' => $productos
            ]
        ];

        $resultadoRegistro = $entrada->procesarCompra(json_encode($datosCompra));

        if ($resultadoRegistro['respuesta'] == 1) {
            $bitacora = [
                'id_persona' => $_SESSION["id"],
                'accion' => 'Registro de compra',
                'descripcion' => 'Se registró la compra ID: ' . $resultadoRegistro['id_compra']
            ];
            $bitacoraObj = new Bitacora();
            $bitacoraObj->registrarOperacion($bitacora['accion'], 'entrada', $bitacora);
        }

        if (esAjax()) {
            header('Content-Type: application/json');
            echo json_encode($resultadoRegistro);
            exit;
        } else {
            $_SESSION['message'] = [
                'title' => ($resultadoRegistro['respuesta'] == 1) ? '¡Éxito!' : 'Error',
                'text' => $resultadoRegistro['mensaje'],
                'icon' => ($resultadoRegistro['respuesta'] == 1) ? 'success' : 'error'
            ];
            
            header("Location: ?pagina=entrada");
            exit;
        }
    }
}

// Procesar la modificación de una compra
if (isset($_POST['modificar_compra'])) {
    $productos = [];
    for ($i = 0; $i < count($_POST['id_producto']); $i++) {
        if (!empty($_POST['id_producto'][$i]) && isset($_POST['cantidad'][$i]) && $_POST['cantidad'][$i] > 0) {
            $productos[] = array(
                'id_producto' => intval($_POST['id_producto'][$i]),
                'cantidad' => intval($_POST['cantidad'][$i]),
                'precio_unitario' => floatval($_POST['precio_unitario'][$i]),
                'precio_total' => floatval($_POST['precio_total'][$i])
            );
        }
    }

    $datosCompra = [
        'operacion' => 'actualizar',
        'datos' => [
            'id_compra' => intval($_POST['id_compra']),
            'fecha_entrada' => $_POST['fecha_entrada'],
            'id_proveedor' => intval($_POST['id_proveedor']),
            'productos' => $productos
        ]
    ];

    $resultado = $entrada->procesarCompra(json_encode($datosCompra));

    if ($resultado['respuesta'] == 1) {
        $bitacora = [
            'id_persona' => $_SESSION["id"],
            'accion' => 'Modificación de compra',
            'descripcion' => 'Se modificó la compra ID: ' . $datosCompra['datos']['id_compra']
        ];
        $bitacoraObj = new Bitacora();
        $bitacoraObj->registrarOperacion($bitacora['accion'], 'entrada', $bitacora);
    }

    if (esAjax()) {
        header('Content-Type: application/json');
        echo json_encode($resultado);
        exit;
    } else {
        $_SESSION['message'] = [
            'title' => ($resultado['respuesta'] == 1) ? '¡Éxito!' : 'Error',
            'text' => $resultado['mensaje'],
            'icon' => ($resultado['respuesta'] == 1) ? 'success' : 'error'
        ];
        header("Location: ?pagina=entrada");
        exit;
    }
}

// Procesar la eliminación de una compra
if (isset($_POST['eliminar_compra'])) {
    $datosCompra = [
        'operacion' => 'eliminar',
        'datos' => [
            'id_compra' => intval($_POST['id_compra'])
        ]
    ];

    $resultado = $entrada->procesarCompra(json_encode($datosCompra));

    if ($resultado['respuesta'] == 1) {
        $bitacora = [
            'id_persona' => $_SESSION["id"],
            'accion' => 'Eliminación de compra',
            'descripcion' => 'Se eliminó la compra ID: ' . $datosCompra['datos']['id_compra']
        ];
        $bitacoraObj = new Bitacora();
        $bitacoraObj->registrarOperacion($bitacora['accion'], 'entrada', $bitacora);
    }

    if (esAjax()) {
        header('Content-Type: application/json');
        echo json_encode($resultado);
        exit;
    } else {
        $_SESSION['message'] = [
            'title' => ($resultado['respuesta'] == 1) ? '¡Éxito!' : 'Error',
            'text' => $resultado['mensaje'],
            'icon' => ($resultado['respuesta'] == 1) ? 'success' : 'error'
        ];
        header("Location: ?pagina=entrada");
        exit;
    }
}

// Consultar datos para la vista
$resultadoCompras = $entrada->procesarCompra(json_encode(['operacion' => 'consultar']));
$compras = $resultadoCompras['datos'];

// Si hay un ID en la URL, consultamos los detalles de esa compra
$detalles_compra = [];
if (isset($_GET['id'])) {
    $resultadoDetalles = $entrada->procesarCompra(json_encode([
        'operacion' => 'consultarDetalles',
        'datos' => ['id_compra' => intval($_GET['id'])]
    ]));
    $detalles_compra = $resultadoDetalles['datos'];
}

// Obtener la lista de productos y proveedores para los formularios
$resultadoProductos = $entrada->procesarCompra(json_encode(['operacion' => 'consultarProductos']));
$productos_lista = $resultadoProductos['datos'];

$resultadoProveedores = $entrada->procesarCompra(json_encode(['operacion' => 'consultarProveedores']));
$proveedores = $resultadoProveedores['datos'];

if(isset($_POST['generar'])){
    // Eliminado: $entrada->generarPDF();
    // Eliminado: exit; // Evitar que se cargue la vista después del PDF
}

// Generar gráfico antes de cargar la vista
function generarGrafico() {
    try {
        require_once('assets/js/jpgraph/src/jpgraph.php');
        require_once('assets/js/jpgraph/src/jpgraph_pie.php');
        require_once('assets/js/jpgraph/src/jpgraph_pie3d.php');

        $db = new Conexion();
        $conex1 = $db->getConex1();

        // Primero verificamos si hay datos en las tablas necesarias
        $SQL_verificacion = "SELECT COUNT(*) as total FROM compra c 
                           INNER JOIN compra_detalles cd ON c.id_compra = cd.id_compra";
        $stmt_verificacion = $conex1->prepare($SQL_verificacion);
        $stmt_verificacion->execute();
        $total = $stmt_verificacion->fetch(PDO::FETCH_ASSOC)['total'];

        if ($total == 0) {
            // Si no hay datos, creamos un gráfico con mensaje
            $graph = new PieGraph(900, 500);
            $graph->SetShadow();
            
            // Configurar título
            $graph->title->Set("No hay datos de compras disponibles");
            $graph->title->SetFont(FF_ARIAL, FS_BOLD, 16);
            
            // Crear un gráfico vacío con mensaje
            $p1 = new PiePlot3D([100]);
            $p1->SetLegends(['No hay datos']);
            $p1->SetCenter(0.5, 0.45);
            $p1->SetSize(0.3);
            $p1->SetSliceColors(['#CCCCCC']);
            
            $graph->Add($p1);
            
            // Guardar el gráfico
            $imgDir = __DIR__ . "/../assets/img/grafica_reportes/";
            if (!file_exists($imgDir)) {
                mkdir($imgDir, 0777, true);
            }

            $imagePath = $imgDir . "grafico_entradas.png";
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }

            $graph->Stroke($imagePath);
            error_log("Se generó un gráfico vacío porque no hay datos de compras");
            return;
        }

        // Si hay datos, procedemos con la consulta normal
        $SQL = "SELECT 
                    DISTINCT p.nombre as nombre_producto,
                    COALESCE(SUM(cd.cantidad), 0) as total_comprado 
                FROM productos p 
                INNER JOIN compra_detalles cd ON p.id_producto = cd.id_producto 
                INNER JOIN compra c ON cd.id_compra = c.id_compra 
                WHERE p.estatus = 1 
                GROUP BY p.id_producto, p.nombre 
                HAVING total_comprado > 0
                ORDER BY total_comprado DESC 
                LIMIT 5";

        $stmt = $conex1->prepare($SQL);
        $stmt->execute();

        $data = [];
        $labels = [];

        // Verificar si hay resultados
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (empty($resultados)) {
            error_log("No se encontraron productos en la consulta");
            // Si no hay datos, crear datos de ejemplo
            $data = [100];
            $labels = ['No hay datos de compras'];
        } else {
            foreach ($resultados as $resultado) {
                error_log("Producto encontrado: " . print_r($resultado, true));
                $labels[] = $resultado['nombre_producto'];
                $data[] = (int)$resultado['total_comprado'];
            }
        }

        // Crear el gráfico con configuración mejorada
        $graph = new PieGraph(900, 500);
        $graph->SetShadow();
        
        $p1 = new PiePlot3D($data);
        $p1->SetLegends($labels);
        $p1->SetCenter(0.5, 0.45);
        $p1->SetSize(0.3);
        
        $p1->ShowBorder();
        $p1->SetSliceColors(['#FF9999','#66B2FF','#99FF99','#FFCC99','#FF99CC']);
        
        $p1->SetLabelType(PIE_VALUE_ABS);
        $p1->value->SetFont(FF_ARIAL, FS_BOLD, 11);
        $p1->value->SetColor("black");
        
        $graph->Add($p1);

        // Guardar el gráfico
        $imgDir = __DIR__ . "/../assets/img/grafica_reportes/";
        if (!file_exists($imgDir)) {
            mkdir($imgDir, 0777, true);
        }

        $imagePath = $imgDir . "grafico_entradas.png";
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        $graph->Stroke($imagePath);
        error_log("Gráfico generado exitosamente con datos reales");
        
    } catch (Exception $e) {
        error_log("Error al generar el gráfico de compras: " . $e->getMessage());
    }
}

// Llamar la función para generar la gráfica ANTES de cargar la vista
generarGrafico();

// Cargamos la vista

if ($_SESSION["nivel_rol"] == 3 && tieneAcceso(2, 'ver')) {
     $pagina_actual = isset($_GET['pagina']) ? $_GET['pagina'] : 'entrada';
        require_once 'vista/entrada.php';
} else {
        require_once 'vista/seguridad/privilegio.php';

} 
?>