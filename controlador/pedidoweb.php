<?php  
     session_start();
     if (empty($_SESSION["id"])){
       header("location:?pagina=login");
       exit;
     } 
     require_once __DIR__ . '/../modelo/pedidoweb.php';

       $pedido = new pedidoWeb();


       if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['accion']) && isset($_POST['id_pedido'])) {
            $id_pedido = $_POST['id_pedido'];
    
            if ($_POST['accion'] === 'confirmar') {
                $pedido->confirmarPedido($id_pedido);
                echo json_encode(['status' => 'ok', 'msg' => 'Pedido confirmado']);
                exit;
            }
    
            if ($_POST['accion'] === 'eliminar') {
                $pedido->eliminarPedido($id_pedido);
                echo json_encode(['status' => 'ok', 'msg' => 'Pedido eliminado']);
                exit;
            }
        }
    }
    
    $pedidos = $pedido->consultarPedidosCompletos();
       
     
       foreach ($pedidos as &$p) {
           $p['detalles'] = $pedido->consultarDetallesPedido($p['id_pedido']);
       }
       
       if (isset($_POST['generar'])) {
        $pedido->generarPDF(); // Asumiendo que este método existe y está bien
        exit;
    }
    
    // Generar gráfico de productos más vendidos en pedidos web
    function generarGraficoProductosMasVendidos() {
        require_once('assets/js/jpgraph/src/jpgraph.php');
        require_once('assets/js/jpgraph/src/jpgraph_pie.php');
        require_once('assets/js/jpgraph/src/jpgraph_pie3d.php');
    
        $db = new Conexion();
        $conex1 = $db->getConex1();
    
        // Consulta para obtener los 5 productos más vendidos en pedidos web (tipo = 2)
        $sql = "
            SELECT pr.nombre,(pd.cantidad) AS total_vendidos
            FROM pedido p
            JOIN pedido_detalles pd ON p.id_pedido = pd.id_pedido
            JOIN productos pr ON pd.id_producto = pr.id_producto
            WHERE p.tipo = 2 AND pr.estatus = 2
            GROUP BY pr.nombre
            ORDER BY total_vendidos DESC
            LIMIT 5
        ";
    
        $stmt = $conex1->prepare($sql);
        $stmt->execute();
        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        $data = [];
        $labels = [];
    
        foreach ($datos as $fila) {
            $data[] = $fila['total_vendidos'];
            $labels[] = $fila['nombre'] . ' (' . $fila['total_vendidos'] . ')';
        }
    
        // Verificar si hay datos válidos
        if (empty($data) || array_sum($data) == 0) {
            return; // No genera gráfico si no hay datos
        }
    
        // Crear gráfico
        $graph = new PieGraph(900, 500);
        $graph->title->Set("(Pedidos Web)");
        $p1 = new PiePlot3D($data);
        $p1->SetLegends($labels);
        $p1->SetCenter(0.5, 0.5);
        $p1->ExplodeSlice(1);
        $p1->SetLabelType(PIE_VALUE_ABS);
        $graph->Add($p1);
    
        // Ruta de almacenamiento
        $imgDir = __DIR__ . "/../assets/img/grafica_reportes/";
        $imagePath = $imgDir . "grafico_productos.png";
    
        // Crear carpeta si no existe
        if (!file_exists($imgDir)) {
            mkdir($imgDir, 0777, true);
        }
    
        // Eliminar imagen anterior
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    
        $graph->Stroke($imagePath);
    }
    
    // Generar gráfico antes de mostrar la vista
    generarGraficoProductosMasVendidos();
    

      require_once 'vista/pedidoweb.php';

  

?>