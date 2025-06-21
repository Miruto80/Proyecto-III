<?php
require_once 'modelo/conexion.php';
require_once 'assets/dompdf/vendor/autoload.php';
use Dompdf\Dompdf;

class Reporte {

public static function compra(): void {
    // 1) Cargamos dependencias
    require_once 'modelo/conexion.php';
    require_once 'assets/dompdf/vendor/autoload.php';
    require_once __DIR__ . '/../assets/js/jpgraph/src/jpgraph.php';
    require_once __DIR__ . '/../assets/js/jpgraph/src/jpgraph_pie.php';
    require_once __DIR__ . '/../assets/js/jpgraph/src/jpgraph_pie3d.php';

    // 2) Conexión
    $cnx = (new Conexion())->getConex1();

    // 3) Top 5 productos más comprados
    $stmt = $cnx->prepare("
      SELECT p.nombre AS producto, SUM(cd.cantidad) AS total_comprado
      FROM compra_detalles cd
      JOIN productos p ON cd.id_producto=p.id_producto
      JOIN compra c ON cd.id_compra=c.id_compra
      WHERE p.estatus=1
      GROUP BY p.id_producto, p.nombre
      ORDER BY total_comprado DESC
      LIMIT 5
    ");
    $stmt->execute();
    $labels = $data = [];
    while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $labels[] = $r['producto'];
        $data[]   = (int)$r['total_comprado'];
    }

    // 4) Genera pie‐chart 3D
    if (!empty($data)) {
        $imgDir  = __DIR__ . '/../assets/img/grafica_reportes/';
        $imgFile = $imgDir . 'grafico_compras.png';
        if (!is_dir($imgDir)) mkdir($imgDir,0777,true);
        if (file_exists($imgFile)) unlink($imgFile);

        $graph = new \PieGraph(900,500);
        $pie   = new \PiePlot3D($data);
        $pie->SetLegends($labels);
        $pie->SetCenter(0.5, 0.5);
        $pie->ExplodeSlice(1);
        $graph->Add($pie);
        $graph->Stroke($imgFile);
    }

    // 5) Leer gráfico en base64
    $graf = '';
    $ruta = realpath(__DIR__ . '/../assets/img/grafica_reportes/grafico_compras.png');
    if ($ruta && file_exists($ruta)) {
        $bin  = file_get_contents($ruta);
        $graf = 'data:image/png;base64,'.base64_encode($bin);
    }

    // 6) Consulta resumen de compras
    $stmt = $cnx->prepare("
      SELECT 
        c.id_compra, c.fecha_entrada,
        pr.nombre AS proveedor,
        GROUP_CONCAT(CONCAT(p.nombre,' (',cd.cantidad,'u)') SEPARATOR ', ') AS productos,
        SUM(cd.cantidad*cd.precio_unitario) AS total
      FROM compra c
      JOIN compra_detalles cd ON c.id_compra=cd.id_compra
      JOIN productos p ON cd.id_producto=p.id_producto
      JOIN proveedor pr ON c.id_proveedor=pr.id_proveedor
      GROUP BY c.id_compra, c.fecha_entrada, pr.nombre
      ORDER BY c.id_compra DESC
    ");
    $stmt->execute();
    $compras = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 7) Construir HTML
    $fecha = date('d/m/Y h:i A');
    $html  = "<html><head><style>
      body{font-family:Arial;font-size:12px;}
      h1,h2{text-align:center;}
      p{margin:0;}
      table{width:100%;border-collapse:collapse;margin-top:10px;}
      th,td{border:1px solid #000;padding:6px;text-align:center;}
      th{background:#f36ca4;}
    </style></head><body>
      <h1>Listado de Compras</h1>
      <p><strong>Fecha:</strong> {$fecha}</p>";

    if ($graf) {
        $html .= "
      <h2>Top 5 Productos Comprados</h2>
      <div style='text-align:center;margin-bottom:20px;'>
        <img src=\"{$graf}\" width=\"600\"/>
      </div><br>";
    }

    $html .= "
      <table>
        <thead>
          <tr>
            <th>ID Compra</th>
            <th>Fecha Entrada</th>
            <th>Proveedor</th>
            <th>Productos</th>
            <th>Total</th>
          </tr>
        </thead><tbody>";
    foreach ($compras as $c) {
        $f   = date('d/m/Y', strtotime($c['fecha_entrada']));
        $tot = '$'.number_format($c['total'],2);
        $html .= "<tr>
          <td>".htmlspecialchars($c['id_compra'])."</td>
          <td>{$f}</td>
          <td>".htmlspecialchars($c['proveedor'])."</td>
          <td>".htmlspecialchars($c['productos'])."</td>
          <td>{$tot}</td>
        </tr>";
    }
    $html .= "</tbody></table></body></html>";

    // 8) Generar PDF
    $pdf = new Dompdf();
    $pdf->loadHtml($html);
    $pdf->setPaper('A4','portrait');
    $pdf->render();
    $pdf->stream('Reporte_Compras.pdf',['Attachment'=>false]);
}


public static function producto(): void {
    // 1) includes necesarios
    require_once 'modelo/conexion.php';
    require_once 'assets/dompdf/vendor/autoload.php';
    require_once __DIR__ . '/../assets/js/jpgraph/src/jpgraph.php';
    require_once __DIR__ . '/../assets/js/jpgraph/src/jpgraph_pie.php';
    require_once __DIR__ . '/../assets/js/jpgraph/src/jpgraph_pie3d.php';
    

    // 2) Conexión
    $cnx = (new Conexion())->getConex1();

    // 3) Top 10 productos por stock
    $stmt = $cnx->prepare("
      SELECT nombre, stock_disponible
      FROM productos
      WHERE estatus IN (1,2)
      ORDER BY stock_disponible DESC
      LIMIT 10
    ");
    $stmt->execute();
    $labels = $data = [];
    while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $labels[] = $r['nombre'];
        $data[]   = (int)$r['stock_disponible'];
    }

    // 4) Generar pie chart 3D (igual que proveedor)
    if (!empty($data)) {
        $imgDir  = __DIR__ . '/../assets/img/grafica_reportes/';
        $imgFile = $imgDir . 'grafico_productos.png';
        if (!is_dir($imgDir)) mkdir($imgDir, 0777, true);
        if (file_exists($imgFile)) unlink($imgFile);

        $graph = new \PieGraph(900,500);
        $pie   = new \PiePlot3D($data);
        $pie->SetLegends($labels);
        $pie->SetCenter(0.5, 0.5);
        $pie->ExplodeSlice(1);
        $graph->Add($pie);
        $graph->Stroke($imgFile);
    }

    // 5) Leer la imagen como base64
    $graf = '';
    $ruta = realpath(__DIR__ . '/../assets/img/grafica_reportes/grafico_productos.png');
    if ($ruta && file_exists($ruta)) {
        $bin  = file_get_contents($ruta);
        $graf = 'data:image/png;base64,'.base64_encode($bin);
    }

    // 6) Consultar todos los productos
    $stmt = $cnx->prepare("
      SELECT p.*, c.nombre AS nombre_categoria
      FROM productos p
      JOIN categoria c ON p.id_categoria=c.id_categoria
      WHERE p.estatus IN (1,2)
      ORDER BY p.nombre ASC
    ");
    $stmt->execute();
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 7) Construir el HTML
    $fecha = date('d/m/Y h:i A');
    $html  = "<html><head><style>
      body{font-family:Arial;font-size:12px;}
      h1, h2 { text-align:center; }
      table{width:100%;border-collapse:collapse;margin-top:20px;}
      th,td{border:1px solid #000;padding:6px;text-align:center;}
      th{background:#f36ca4;}
      </style></head><body>
      <h1>Listado de Productos</h1>
      <p><strong>Fecha:</strong> {$fecha}</p>";

    if ($graf) {
        $html .= "
      <h2>Top 10 Productos con Más Stock</h2>
      <div style='text-align:center;'>
        <img src=\"{$graf}\" width=\"600\"/>
      </div><br>";
    }

    $html .= "
      <table>
        <thead>
          <tr>
            <th>Nombre</th><th>Descripción</th><th>Marca</th>
            <th>Precio Detal</th><th>Precio Mayor</th><th>Stock</th>
          </tr>
        </thead><tbody>";
    foreach ($productos as $p) {
        $html .= "<tr>
          <td>".htmlspecialchars($p['nombre'])."</td>
          <td>".htmlspecialchars($p['descripcion'])."</td>
          <td>".htmlspecialchars($p['marca'])."</td>
          <td>".htmlspecialchars($p['precio_detal'])."</td>
          <td>".htmlspecialchars($p['precio_mayor'])."</td>
          <td>".htmlspecialchars($p['stock_disponible'])."</td>
        </tr>";
    }
    $html .= "</tbody></table></body></html>";

    // 8) Generar PDF
    $pdf = new Dompdf();
    $pdf->loadHtml($html);
    $pdf->setPaper('A4','portrait');
    $pdf->render();
    $pdf->stream('Reporte_Productos.pdf', ['Attachment' => false]);
}



public static function venta(): void {
    // 1) Includes
    require_once 'modelo/conexion.php';
    require_once 'assets/dompdf/vendor/autoload.php';
    require_once __DIR__ . '/../assets/js/jpgraph/src/jpgraph.php';
    require_once __DIR__ . '/../assets/js/jpgraph/src/jpgraph_pie.php';
    require_once __DIR__ . '/../assets/js/jpgraph/src/jpgraph_pie3d.php';
 

    // 2) Conexión
    $cnx = (new Conexion())->getConex1();

    // 3) Top 5 productos más vendidos (para pie‐chart)
    $stmt = $cnx->prepare("
      SELECT p.nombre AS producto, SUM(pd.cantidad) AS total
      FROM pedido_detalles pd
      JOIN productos p ON pd.id_producto = p.id_producto
      JOIN pedido pe ON pd.id_pedido = pe.id_pedido
      WHERE pe.estado = 2        /* solo entregados */
      GROUP BY pd.id_producto, p.nombre
      ORDER BY total DESC
      LIMIT 5
    ");
    $stmt->execute();
    $labels = $data = [];
    while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $labels[] = $r['producto'];
        $data[]   = (int)$r['total'];
    }

    // 4) Generar pie‐chart 3D
    if (!empty($data)) {
        $imgDir  = __DIR__ . '/../assets/img/grafica_reportes/';
        $imgFile = $imgDir . 'grafico_ventas.png';
        if (!is_dir($imgDir)) mkdir($imgDir, 0777, true);
        if (file_exists($imgFile)) unlink($imgFile);

        $graph = new \PieGraph(900,500);
        $pie   = new \PiePlot3D($data);
        $pie->SetLegends($labels);
        $pie->SetCenter(0.5, 0.5);
        $pie->ExplodeSlice(1);
        $graph->Add($pie);
        $graph->Stroke($imgFile);
    }

    // 5) Leer gráfico en base64
    $graf = '';
    $ruta = realpath(__DIR__ . '/../assets/img/grafica_reportes/grafico_ventas.png');
    if ($ruta && file_exists($ruta)) {
        $bin  = file_get_contents($ruta);
        $graf = 'data:image/png;base64,' . base64_encode($bin);
    }

    // 6) Consulta resumen de ventas
    $stmt = $cnx->prepare("
      SELECT 
        CONCAT(cl.nombre,' ',cl.apellido) AS cliente,
        pe.fecha,
        pe.estado,
        pe.precio_total,
        mp.nombre AS metodo_pago,
        me.nombre AS metodo_entrega
      FROM pedido pe
      JOIN cliente cl ON pe.id_persona = cl.id_persona
      JOIN metodo_pago mp ON pe.id_metodopago = mp.id_metodopago
      JOIN metodo_entrega me ON pe.id_entrega = me.id_entrega
      WHERE pe.tipo = 1
      ORDER BY pe.fecha DESC
    ");
    $stmt->execute();
    $ventas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 7) HTML para PDF
    $fecha = date('d/m/Y h:i A');
    $html  = "<html><head><style>
      body{font-family:Arial;font-size:12px;}
      table{width:100%;border-collapse:collapse;margin-top:20px;}
      th,td{border:1px solid #000;padding:6px;text-align:center;}
      th{background:#f36ca4;}
      h1,h2{text-align:center;}
      p{margin:0;}
    </style></head><body>
      <h1>Listado de Ventas</h1>
      <p><strong>Fecha:</strong> {$fecha}</p>";

    if ($graf) {
      $html .= "
      <h2>Top 5 Productos Más Vendidos</h2>
      <div style='text-align:center;margin-bottom:20px;'>
        <img src=\"{$graf}\" width=\"600\"/>
      </div><br>";
    }

    $html .= "
      <table>
        <thead>
          <tr>
            <th>Cliente</th><th>Fecha</th><th>Estado</th>
            <th>Total</th><th>Método Pago</th><th>Método Entrega</th>
          </tr>
        </thead><tbody>";

    // Mapeo de estados
    $estados = ['0'=>'Cancelado','1'=>'Pendiente','2'=>'Entregado','3'=>'En camino','4'=>'Enviado'];
    foreach ($ventas as $v) {
        $f = date('d/m/Y',strtotime($v['fecha']));
        $tot = '$'.number_format($v['precio_total'],2);
        $txt = $estados[$v['estado']] ?? 'Desconocido';
        $html .= "<tr>
          <td>".htmlspecialchars($v['cliente'])."</td>
          <td>{$f}</td>
          <td>{$txt}</td>
          <td>{$tot}</td>
          <td>".htmlspecialchars($v['metodo_pago'])."</td>
          <td>".htmlspecialchars($v['metodo_entrega'])."</td>
        </tr>";
    }

    $html .= "</tbody></table></body></html>";

    // 8) Renderizar PDF
    $pdf = new Dompdf();
    $pdf->loadHtml($html);
    $pdf->setPaper('A4','portrait');
    $pdf->render();
    $pdf->stream('Reporte_Ventas.pdf',['Attachment'=>false]);
}


public static function proveedor(): void {
    require_once 'assets/dompdf/vendor/autoload.php';
    require_once 'modelo/conexion.php';
    require_once 'assets/js/jpgraph/src/jpgraph.php';
    require_once 'assets/js/jpgraph/src/jpgraph_pie.php';
    require_once 'assets/js/jpgraph/src/jpgraph_pie3d.php';

    // conexión manual
    $cnx = (new Conexion())->getConex1();

    // 1) Generar gráfico
    $sql = "SELECT COUNT(DISTINCT pr.id_proveedor) AS total_activos
            FROM compra c
            JOIN proveedor pr ON c.id_proveedor = pr.id_proveedor
            WHERE pr.estatus=1";
    $stmt = $cnx->prepare($sql);
    $stmt->execute();
    $total = (int)$stmt->fetchColumn();
    $lim = min(5, max(0, $total));

    $sql = "SELECT pr.nombre, COUNT(c.id_compra) AS total_compras
            FROM compra c
            JOIN proveedor pr ON c.id_proveedor=pr.id_proveedor
            WHERE pr.estatus=1
            GROUP BY pr.nombre
            ORDER BY total_compras DESC
            LIMIT :lim";
    $stmt = $cnx->prepare($sql);
    $stmt->bindValue(':lim', $lim, PDO::PARAM_INT);
    $stmt->execute();

    $data = $labels = [];
    while($r = $stmt->fetch(PDO::FETCH_ASSOC)){
        $labels[] = $r['nombre'];
        $data[] = (int)$r['total_compras'];
    }

    if ($data && array_sum($data) > 0) {
        $graph = new \PieGraph(900,500);
        $pie = new \PiePlot3D($data);
        $pie->SetLegends($labels);
        $pie->SetCenter(0.5, 0.5);
        $pie->ExplodeSlice(1);
        $graph->Add($pie);

        $imgDir  = __DIR__ . '/../assets/img/grafica_reportes/';
        $imgFile = $imgDir . 'grafico_proveedores.png';
        if (!is_dir($imgDir)) mkdir($imgDir,0777,true);
        if (file_exists($imgFile)) unlink($imgFile);
        $graph->Stroke($imgFile);
    }

    // 2) Consultar proveedores
    $sql = "SELECT * FROM proveedor WHERE estatus = 1 ORDER BY nombre ASC";
    $stmt = $cnx->prepare($sql);
    $stmt->execute();
    $proveedores = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 3) Convertir imagen a base64
    $ruta = realpath(__DIR__ . '/../assets/img/grafica_reportes/grafico_proveedores.png');
    $graf = '';
    if ($ruta && file_exists($ruta)) {
        $bin = file_get_contents($ruta);
        $graf = 'data:image/png;base64,'.base64_encode($bin);
    }

    // 4) Generar HTML del PDF
    $fecha = date('d/m/Y h:i A');
    $html = "<html><head><style>
      body{font-family:Arial;font-size:12px;}
      h1, h2 { text-align:center; }
      table{width:100%;border-collapse:collapse;margin-top:20px;}
      th,td{border:1px solid #000;padding:6px;text-align:center;}
      th{background:#f36ca4;}
      </style></head><body>
      <h1>Listado de Proveedores</h1>
      <p>Fecha: {$fecha}</p>";

    if ($graf) {
        $html .= "
        <h2>Top 5 Proveedores con Más Compras</h2>
        <div style='text-align:center;'>
          <img src=\"{$graf}\" width=\"600\"/>
        </div><br>";
    }

    $html .= "<table>
      <tr>
        <th>Nombre</th><th>Tipo Doc.</th><th>Documento</th>
        <th>Correo</th><th>Teléfono</th><th>Dirección</th>
      </tr>";

    foreach($proveedores as $p){
      $html .= "<tr>
        <td>{$p['nombre']}</td>
        <td>{$p['tipo_documento']}</td>
        <td>{$p['numero_documento']}</td>
        <td>{$p['correo']}</td>
        <td>{$p['telefono']}</td>
        <td>{$p['direccion']}</td>
      </tr>";
    }

    $html .= "</table></body></html>";

    // 5) Generar PDF
    $pdf = new Dompdf();
    $pdf->loadHtml($html);
    $pdf->setPaper('A4','portrait');
    $pdf->render();
    $pdf->stream("Reporte_Proveedores.pdf",["Attachment"=>false]);
}


    public static function pedidoWeb(): void {
    // Includes
    require_once 'modelo/conexion.php';
    require_once 'assets/dompdf/vendor/autoload.php';
    require_once __DIR__ . '/../assets/js/jpgraph/src/jpgraph.php';
    require_once __DIR__ . '/../assets/js/jpgraph/src/jpgraph_pie.php';
    require_once __DIR__ . '/../assets/js/jpgraph/src/jpgraph_pie3d.php';
 

    // Conexión
    $cnx = (new Conexion())->getConex1();

    // 1) Top 5 productos más vendidos en pedidos web (tipo=2)
    $stmt = $cnx->prepare("
      SELECT pr.nombre AS producto, SUM(pd.cantidad) AS total
      FROM pedido p
      JOIN pedido_detalles pd ON p.id_pedido=pd.id_pedido
      JOIN productos pr ON pd.id_producto=pr.id_producto
      WHERE p.tipo=2 AND pr.estatus=1
      GROUP BY pr.id_producto, pr.nombre
      ORDER BY total DESC
      LIMIT 5
    ");
    $stmt->execute();
    $labels = $data = [];
    while($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $labels[] = $r['producto'];
        $data[]   = (int)$r['total'];
    }

    // 2) Generar gráfico de torta 3D
    if (!empty($data)) {
        $imgDir  = __DIR__ . '/../assets/img/grafica_reportes/';
        $imgFile = $imgDir . 'grafico_pedidoweb.png';
        if (!is_dir($imgDir)) mkdir($imgDir, 0777, true);
        if (file_exists($imgFile)) unlink($imgFile);

        $graph = new \PieGraph(900,500);
        $pie   = new \PiePlot3D($data);
        $pie->SetLegends($labels);
        $pie->SetCenter(0.5,0.5);
        $pie->ExplodeSlice(1);
        $graph->Add($pie);
        $graph->Stroke($imgFile);
    }

    // 3) Leer gráfico en base64
    $graf = '';
    $ruta = realpath(__DIR__ . '/../assets/img/grafica_reportes/grafico_pedidoweb.png');
    if ($ruta && file_exists($ruta)) {
        $bin  = file_get_contents($ruta);
        $graf = 'data:image/png;base64,'.base64_encode($bin);
    }

    // 4) Consultar pedidos completos
    $stmt = $cnx->prepare("
      SELECT 
        p.id_pedido, p.fecha, p.estado, p.precio_total,
        p.referencia_bancaria, p.telefono_emisor,
        CONCAT(cl.nombre,' ',cl.apellido) AS cliente,
        mp.nombre AS metodo_pago, me.nombre AS metodo_entrega
      FROM pedido p
      LEFT JOIN cliente cl ON p.id_persona=cl.id_persona
      LEFT JOIN metodo_pago mp ON p.id_metodopago=mp.id_metodopago
      LEFT JOIN metodo_entrega me ON p.id_entrega=me.id_entrega
      WHERE p.tipo=2
      ORDER BY p.fecha DESC
    ");
    $stmt->execute();
    $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 5) Construir HTML
    $fecha = date('d/m/Y h:i A');
    $html  = "<html><head><style>
      body{font-family:Arial;font-size:12px;}
      h1,h2{text-align:center;}
      p.time{margin:0;}
      table{width:100%;border-collapse:collapse;margin-top:10px;}
      th,td{border:1px solid #000;padding:6px;text-align:center;}
      th{background:#f48da2;}
    </style></head><body>
      <h1>Listado de Pedidos Web</h1>
      <p class='time'><strong>Fecha:</strong> {$fecha}</p>";

    if ($graf) {
        $html .= "
      <h2>Top 5 Productos Más Vendidos</h2>
      <div style='text-align:center;margin-bottom:20px;'>
        <img src=\"{$graf}\" width=\"600\"/>
      </div><br>";
    }

    // 6) Detalle de cada pedido
    foreach($pedidos as $p) {
        $estado_text = ['0'=>'Cancelado','1'=>'Pendiente','2'=>'Confirmado'];
        $f = date('d/m/Y',strtotime($p['fecha']));
        $tot = '$'.number_format($p['precio_total'],2);
        $est = $estado_text[$p['estado']] ?? 'Desconocido';

        $html .= "<p><strong>Pedido #{$p['id_pedido']}</strong> - Cliente: {$p['cliente']} - Fecha: {$f} - Estado: {$est}</p>";

        // obtener detalles
        $stmt2 = $cnx->prepare("
          SELECT pr.nombre, pd.cantidad, pd.precio_unitario
          FROM pedido_detalles pd
          JOIN productos pr ON pd.id_producto=pr.id_producto
          WHERE pd.id_pedido=:id
        ");
        $stmt2->execute(['id'=>$p['id_pedido']]);
        $det = $stmt2->fetchAll(PDO::FETCH_ASSOC);

        if ($det) {
            $html .= "<table><thead><tr>
              <th>Producto</th><th>Cantidad</th><th>Precio Unitario</th><th>Subtotal</th>
            </tr></thead><tbody>";
            foreach($det as $d){
                $sub = $d['cantidad']*$d['precio_unitario'];
                $html .= "<tr>
                  <td>".htmlspecialchars($d['nombre'])."</td>
                  <td>{$d['cantidad']}</td>
                  <td>$".number_format($d['precio_unitario'],2)."</td>
                  <td>$".number_format($sub,2)."</td>
                </tr>";
            }
            $html .= "</tbody></table><br>";
        }
    }

    $html .= "</body></html>";

    // 7) Generar PDF
    $pdf = new Dompdf();
    $pdf->loadHtml($html);
    $pdf->setPaper('A4','portrait');
    $pdf->render();
    $pdf->stream('Reporte_PedidosWeb.pdf',['Attachment'=>false]);
}

}
