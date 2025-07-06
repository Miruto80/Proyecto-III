<?php
require_once 'modelo/conexion.php';
require_once 'assets/dompdf/vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

class Reporte {

public static function compra(
    $start   = null,
    $end     = null,
    $prodId  = null,
    $catId   = null
): void {
    // 1) Normalizar rango de fechas
    $endParam = $end;
    if ($start && !$endParam) {
        $end = date('Y-m-d');
    }

    // 2) Dependencias y conexión
    require_once 'modelo/conexion.php';
    require_once 'assets/dompdf/vendor/autoload.php';
    require_once __DIR__.'/../assets/js/jpgraph/src/jpgraph.php';
    require_once __DIR__.'/../assets/js/jpgraph/src/jpgraph_pie.php';
    require_once __DIR__.'/../assets/js/jpgraph/src/jpgraph_pie3d.php';
    $conex = (new Conexion())->getConex1();

    //
    // 3) Top 10 productos comprados (gráfico)
    //
    $whereG  = [];
    $paramsG = [];
    if ($start && $end) {
        $whereG[]        = 'c.fecha_entrada BETWEEN :sG AND :eG';
        $paramsG[':sG']  = "$start 00:00:00";
        $paramsG[':eG']  = "$end   23:59:59";
    }
    if ($prodId) {
        $whereG[]         = 'cd.id_producto = :pidG';
        $paramsG[':pidG'] = $prodId;
    }
    if ($catId) {
        $whereG[]         = 'p.id_categoria = :catG';
        $paramsG[':catG'] = $catId;
    }
    $sqlG = "
      SELECT p.nombre AS producto,
             SUM(cd.cantidad) AS total
      FROM compra_detalles cd
      JOIN productos p ON p.id_producto = cd.id_producto
      JOIN compra    c ON c.id_compra   = cd.id_compra
      " . (!empty($whereG)
           ? 'WHERE '.implode(' AND ',$whereG)
           : ''
      ) . "
      GROUP BY p.id_producto
      ORDER BY total DESC
      LIMIT 10
    ";
    $stmtG = $conex->prepare($sqlG);
    $stmtG->execute($paramsG);

    $labels = $data = [];
    while ($r = $stmtG->fetch(PDO::FETCH_ASSOC)) {
        $labels[] = htmlspecialchars($r['producto']);
        $data[]   = (int)$r['total'];
    }

    // renderizar gráfico a Base64
    $imgDir  = __DIR__.'/../assets/img/grafica_reportes/';
    $imgFile = $imgDir.'grafico_compras.png';
    if (!is_dir($imgDir)) mkdir($imgDir,0777,true);
    if (file_exists($imgFile)) unlink($imgFile);
    if ($data) {
        $graph = new \PieGraph(900,500);
        $pie   = new \PiePlot3D($data);
        $pie->SetLegends($labels);
        $pie->SetCenter(0.5,0.5);
        $pie->ExplodeSlice(1);
        $graph->Add($pie);
        $graph->Stroke($imgFile);
    }
    $graf = file_exists($imgFile)
          ? 'data:image/png;base64,'.base64_encode(file_get_contents($imgFile))
          : '';

    //
    // 4) Tabla de compras, ordenada por total DESC
    //
    $whereT  = [];
    $paramsT = [];
    if ($start && $end) {
        $whereT[]        = 'c.fecha_entrada BETWEEN :sT AND :eT';
        $paramsT[':sT']  = "$start 00:00:00";
        $paramsT[':eT']  = "$end   23:59:59";
    }
    if ($prodId) {
        $whereT[]         = 'cd.id_producto = :pidT';
        $paramsT[':pidT'] = $prodId;
    }
    if ($catId) {
        $whereT[]         = 'p.id_categoria = :catT';
        $paramsT[':catT'] = $catId;
    }
    $sqlT = "
      SELECT
        c.id_compra,
        c.fecha_entrada,
        pr.nombre AS proveedor,
        GROUP_CONCAT(
          p.nombre,' (',cd.cantidad,'u)'
          ORDER BY cd.cantidad DESC
          SEPARATOR ', '
        ) AS productos,
        SUM(cd.cantidad*cd.precio_unitario) AS total
      FROM compra c
      JOIN compra_detalles cd ON cd.id_compra = c.id_compra
      JOIN productos        p  ON p.id_producto = cd.id_producto
      JOIN proveedor        pr ON pr.id_proveedor = c.id_proveedor
      " . (!empty($whereT)
           ? 'WHERE '.implode(' AND ',$whereT)
           : ''
      ) . "
      GROUP BY c.id_compra, c.fecha_entrada, pr.nombre
      ORDER BY total DESC
    ";
    $stmtT = $conex->prepare($sqlT);
    $stmtT->execute($paramsT);
    $rows = $stmtT->fetchAll(PDO::FETCH_ASSOC);
    $conex = null;

    //
    // 5) Texto de filtros (fechas, producto y categoría)
    //
    $parts = [];
    if ($start)    $parts[] = 'Desde '.date('d/m/Y',strtotime($start));
    if ($endParam) $parts[] = 'Hasta '.date('d/m/Y',strtotime($endParam));
    if ($prodId) {
        $db = (new Conexion())->getConex1();
        $p  = $db->prepare('SELECT nombre FROM productos WHERE id_producto=:pid');
        $p->execute([':pid'=>$prodId]);
        $parts[] = 'Producto: '.htmlspecialchars($p->fetchColumn());
    }
    if ($catId) {
        $db = (new Conexion())->getConex1();
        $c  = $db->prepare('SELECT nombre FROM categoria WHERE id_categoria=:cid');
        $c->execute([':cid'=>$catId]);
        $parts[] = 'Categoría: '.htmlspecialchars($c->fetchColumn());
    }
    $filtro   = $parts ? implode(' | ',$parts) : 'Registro general';
    $fechaGen = date('d/m/Y H:i:s');

    //
    // 6) Cabecera + HTML + PDF
    //
    $iconPath = __DIR__.'/../assets/img/icon.PNG';
    $logoData = file_exists($iconPath)
              ? 'data:image/png;base64,'.base64_encode(file_get_contents($iconPath))
              : '';

    $html = '<html><head><style>
      @page{margin:120px 50px 60px 50px}
      body{margin:0;font-family:Arial,sans-serif;font-size:12px}
      header{position:fixed;top:-110px;left:0;right:0;height:110px;text-align:center}
      header img.logo-icon{position:absolute;top:5px;right:5px;width:100px;height:100px}
      header h1{margin:0;font-size:24px}
      header p{margin:4px 0;font-size:14px;color:#555}
      table{width:100%;border-collapse:collapse;margin-top:10px}
      th,td{border:1px solid #000;padding:6px;text-align:center}
      th{background:#f36ca4;color:#fff}
      footer{position:fixed;bottom:-40px;left:0;right:0;height:40px;text-align:center;font-size:10px;color:#666}
    </style></head><body>'
      . '<header>'
      . ($logoData?'<img src="'.$logoData.'" class="logo-icon"/>':'')
      . '<h1>LoveMakeup</h1><p>RIF: J-00000000</p>'
      . '</header><main>'
      . '<h1>Listado de Compras</h1>'
      . "<p><strong>Generado:</strong> {$fechaGen}</p>"
      . "<p><strong>Filtro:</strong> {$filtro}</p>"
      . (!empty($graf)
          ? '<h2>Top 10 Productos Comprados</h2>
             <div style="text-align:center"><img src="'.$graf.'" width="600"/></div>'
          : '')
      . '<table><thead><tr>'
      . '<th>ID Compra</th><th>Fecha</th><th>Proveedor</th><th>Productos</th><th>Total</th>'
      . '</tr></thead><tbody>';
    foreach ($rows as $r) {
        $d = date('d/m/Y',strtotime($r['fecha_entrada']));
        $t = '$'.number_format($r['total'],2);
        $html .= "<tr>
                    <td>{$r['id_compra']}</td>
                    <td>{$d}</td>
                    <td>".htmlspecialchars($r['proveedor'])."</td>
                    <td>".htmlspecialchars($r['productos'])."</td>
                    <td>{$t}</td>
                  </tr>";
    }
    $html .= '</tbody></table></main>'
           . '<footer>Página <span class="pageNumber"></span> de <span class="totalPages"></span></footer>'
           . '</body></html>';

    $opts = new Options();
    $opts->set('isRemoteEnabled',true);
    $pdf = new Dompdf($opts);
    $pdf->loadHtml($html);
    $pdf->setPaper('A4','portrait');
    $pdf->render();
    $cv = $pdf->getCanvas();
    $cv->page_text(
        $cv->get_width()/2-30,
        $cv->get_height()-30,
        "Página {PAGE_NUM} de {PAGE_COUNT}",
        $pdf->getFontMetrics()->get_font('helvetica','normal'),
        10,[0,0,0]
    );
    $pdf->stream('Reporte_Compras.pdf',['Attachment'=>false]);
}





public static function producto($prodId = null, $provId = null, $catId = null): void {
    require_once 'modelo/conexion.php';
    require_once 'assets/dompdf/vendor/autoload.php';
    require_once __DIR__ . '/../assets/js/jpgraph/src/jpgraph.php';
    require_once __DIR__ . '/../assets/js/jpgraph/src/jpgraph_pie.php';
    require_once __DIR__ . '/../assets/js/jpgraph/src/jpgraph_pie3d.php';

    $conex = (new Conexion())->getConex1();

    // Obtener nombre de proveedor (si viene $provId)
    $provName = '';
    if ($provId) {
        $stmtProv = $conex->prepare("SELECT nombre FROM proveedor WHERE id_proveedor = :prov");
        $stmtProv->execute([':prov' => $provId]);
        $provName = $stmtProv->fetchColumn() ?: '';
    }

    // 1) Datos para gráfico Top 10 stock (solo >0)
    $whereG  = ['p.stock_disponible > 0'];
    $paramsG = [];
    $joinG   = '';
    if ($prodId) {
        $whereG[]        = 'p.id_producto = :pid';
        $paramsG[':pid'] = $prodId;
    }
    if ($provId) {
        $joinG = "
          JOIN compra_detalles cd ON cd.id_producto = p.id_producto
          JOIN compra c          ON c.id_compra      = cd.id_compra
        ";
        $whereG[]         = 'c.id_proveedor = :prov';
        $paramsG[':prov'] = $provId;
    }
    if ($catId) {
        $whereG[]         = 'p.id_categoria = :cat';
        $paramsG[':cat']  = $catId;
    }
    $sqlG = "
      SELECT p.nombre, p.stock_disponible
      FROM productos p
      JOIN categoria cat ON cat.id_categoria = p.id_categoria
      $joinG
      WHERE " . implode(' AND ', $whereG) . "
      ORDER BY p.stock_disponible DESC
      LIMIT 10
    ";
    $stmtG = $conex->prepare($sqlG);
    $stmtG->execute($paramsG);
    $labels = $data = [];
    while ($r = $stmtG->fetch(PDO::FETCH_ASSOC)) {
        $labels[] = htmlspecialchars($r['nombre']);
        $data[]   = (int)$r['stock_disponible'];
    }

    // 2) Generar gráfico y Base64
    $imgDir  = __DIR__ . '/../assets/img/grafica_reportes/';
    $imgFile = $imgDir . 'grafico_productos.png';
    if (!is_dir($imgDir)) mkdir($imgDir, 0777, true);
    if (file_exists($imgFile)) unlink($imgFile);
    if ($data) {
        $graph = new \PieGraph(900,500);
        $pie   = new \PiePlot3D($data);
        $pie->SetLegends($labels);
        $pie->SetCenter(0.5,0.5);
        $pie->ExplodeSlice(1);
        $graph->Add($pie);
        $graph->Stroke($imgFile);
    }
    $graf = file_exists($imgFile)
          ? 'data:image/png;base64,'.base64_encode(file_get_contents($imgFile))
          : '';

    // 3) Datos para tabla (incluye stock = 0), ordenados por stock DESC
    $whereT   = ['1=1'];
    $paramsT  = [];
    $joinT    = '';
    if ($prodId) {
        $whereT[]         = 'p.id_producto = :pidT';
        $paramsT[':pidT'] = $prodId;
    }
    if ($provId) {
        $joinT = "
          JOIN compra_detalles cd2 ON cd2.id_producto = p.id_producto
          JOIN compra c2          ON c2.id_compra     = cd2.id_compra
        ";
        $whereT[]          = 'c2.id_proveedor = :provT';
        $paramsT[':provT'] = $provId;
    }
    if ($catId) {
        $whereT[]         = 'p.id_categoria = :catT';
        $paramsT[':catT'] = $catId;
    }
    $sqlT = "
      SELECT DISTINCT
        p.nombre,
        p.descripcion,
        p.marca,
        p.precio_detal,
        p.precio_mayor,
        p.stock_disponible,
        cat.nombre AS categoria
      FROM productos p
      JOIN categoria cat ON cat.id_categoria = p.id_categoria
      $joinT
      WHERE " . implode(' AND ', $whereT) . "
      ORDER BY p.stock_disponible DESC, p.nombre ASC
    ";
    $stmtT = $conex->prepare($sqlT);
    $stmtT->execute($paramsT);
    $rows = $stmtT->fetchAll(PDO::FETCH_ASSOC);

    // Cerrar conexión
    $conex = null;

    // 4) Si no hay productos
    if (empty($rows)) {
        $html = '<html><head><style>
          body{font-family:Arial;font-size:14px;text-align:center;padding:40px;}
          h1{color:#555;}
        </style></head><body>
          <h1>Lo siento 😔</h1>
          <p>No hay datos para generar el reporte de Productos.</p>
        </body></html>';
        $pdf = new Dompdf();
        $pdf->loadHtml($html);
        $pdf->setPaper('A4','portrait');
        $pdf->render();
        $pdf->stream('Reporte_Productos.pdf',['Attachment'=>false]);
        return;
    }

    // 5) Texto filtro y fecha
    $parts = [];
    if ($prodId) $parts[] = 'Producto: ' . htmlspecialchars($rows[0]['nombre']);
    if ($provId) $parts[] = 'Proveedor: ' . ($provName ?: 'ID '.$provId);
    if ($catId)  $parts[] = 'Categoría: ' . htmlspecialchars($rows[0]['categoria']);
    $filtro  = $parts ? implode(' | ', $parts) : 'Listado general de productos';
    $fechaGen = date('d/m/Y H:i:s');

    // 6) Logo en Base64
    $icon     = __DIR__ . '/../assets/img/icon.PNG';
    $logoData = file_exists($icon)
              ? 'data:image/png;base64,'.base64_encode(file_get_contents($icon))
              : '';

    // 7) Construir HTML
    $html = '<html><head><style>
      @page { margin:120px 50px 60px 50px; }
      body { margin:0; font-family:Arial,sans-serif; font-size:12px; }
      header {
        position:fixed; top:-110px; left:0; right:0; height:110px;
        text-align:center;
      }
      header h1 { margin:0; font-size:24px; }
      header p  { margin:4px 0; font-size:14px; color:#555; }
      header img.logo-icon {
        position:absolute; top:5px; right:5px;
        width:100px; height:100px;
      }
      footer {
        position:fixed; bottom:-40px; left:0; right:0; height:40px;
        text-align:center; font-size:10px; color:#666;
      }
      table {
        width:100%; border-collapse:collapse; margin-top:10px;
      }
      th,td {
        border:1px solid #000; padding:6px; text-align:center;
      }
      th { background:#f36ca4; color:#fff; }
    </style></head><body>'
   . '<header>'
   . ($logoData? '<img src="'.$logoData.'" class="logo-icon" alt="Logo"/>' : '')
   . '<h1>LoveMakeup</h1>'
   . '<p>RIF: J-00000000</p>'
   . '</header>'
   . '<main>'
   . '<h1>Listado de Productos</h1>'
   . '<p><strong>Generado:</strong> '.$fechaGen.'</p>'
   . '<p><strong>Filtro:</strong> '.$filtro.'</p>'
   . (!empty($graf)
       ? '<h2>Top 10 Productos por Stock</h2>
          <div style="text-align:center;"><img src="'.$graf.'" width="600"/></div>'
       : ''
     )
   . '<table><thead><tr>'
   . '<th>Nombre</th><th>Descripción</th><th>Marca</th>'
   . '<th>Precio Detal</th><th>Precio Mayor</th>'
   . '<th>Stock</th><th>Categoría</th>'
   . '</tr></thead><tbody>';
    foreach ($rows as $r) {
        $html .= '<tr>'
               . '<td>'.htmlspecialchars($r['nombre']).'</td>'
               . '<td>'.htmlspecialchars($r['descripcion']).'</td>'
               . '<td>'.htmlspecialchars($r['marca']).'</td>'
               . '<td>'.number_format($r['precio_detal'],2).'</td>'
               . '<td>'.number_format($r['precio_mayor'],2).'</td>'
               . '<td>'.(int)$r['stock_disponible'].'</td>'
               . '<td>'.htmlspecialchars($r['categoria']).'</td>'
               . '</tr>';
    }
    $html .= '</tbody></table></main>'
           . '<footer>Página <span class="pageNumber"></span> de <span class="totalPages"></span></footer>'
           . '</body></html>';

    // 8) Render y numeración
    $opts = new \Dompdf\Options();
    $opts->set('isRemoteEnabled', true);
    $pdf = new Dompdf($opts);
    $pdf->loadHtml($html);
    $pdf->setPaper('A4','portrait');
    $pdf->render();
    $canvas = $pdf->getCanvas();
    $w      = $canvas->get_width();
    $h      = $canvas->get_height();
    $font   = $pdf->getFontMetrics()->get_font('helvetica','normal');
    $canvas->page_text($w/2 - 30, $h - 30,
                       "Página {PAGE_NUM} de {PAGE_COUNT}",
                       $font, 10, [0,0,0], 0, 0.5);

    // 9) Emitir PDF
    $pdf->stream('Reporte_Productos.pdf', ['Attachment' => false]);
}




public static function venta(
    $start   = null,
    $end     = null,
    $prodId  = null,
    $catId   = null): void {
    // 1) Normalizar fechas
    $endParam = $end;
    if ($start && !$endParam) {
        $end = date('Y-m-d');
    }

    // 2) Dependencias y conexión
    require_once 'modelo/conexion.php';
    require_once 'assets/dompdf/vendor/autoload.php';
    require_once __DIR__ . '/../assets/js/jpgraph/src/jpgraph.php';
    require_once __DIR__ . '/../assets/js/jpgraph/src/jpgraph_pie.php';
    require_once __DIR__ . '/../assets/js/jpgraph/src/jpgraph_pie3d.php';
    $conex = (new Conexion())->getConex1();

    // 3) Top 10 productos más vendidos (gráfico)
    $whereG  = ['p.tipo = 1'];
    $paramsG = [];
    if ($start && $end) {
        $whereG[]        = 'p.fecha BETWEEN :sG AND :eG';
        $paramsG[':sG']  = "$start 00:00:00";
        $paramsG[':eG']  = "$end   23:59:59";
    }
    if ($prodId) {
        $whereG[]         = 'pd.id_producto = :pidG';
        $paramsG[':pidG'] = $prodId;
    }
    if ($catId) {
        $whereG[]         = 'pr.id_categoria = :catG';
        $paramsG[':catG'] = $catId;
    }

    $sqlG = "
      SELECT pr.nombre AS producto,
             SUM(pd.cantidad) AS total
      FROM pedido_detalles pd
      JOIN productos pr ON pr.id_producto = pd.id_producto
      JOIN pedido    p  ON p.id_pedido    = pd.id_pedido
      WHERE " . implode(' AND ', $whereG) . "
      GROUP BY pr.id_producto, pr.nombre
      ORDER BY total DESC
      LIMIT 10
    ";
    $stmtG = $conex->prepare($sqlG);
    $stmtG->execute($paramsG);

    $labels = $data = [];
    while ($r = $stmtG->fetch(PDO::FETCH_ASSOC)) {
        $labels[] = htmlspecialchars($r['producto']);
        $data[]   = (int)$r['total'];
    }

    // render gráfico a Base64
    $imgDir  = __DIR__ . '/../assets/img/grafica_reportes/';
    $imgFile = $imgDir . 'grafico_ventas.png';
    if (!is_dir($imgDir)) mkdir($imgDir, 0777, true);
    if (file_exists($imgFile)) unlink($imgFile);
    if ($data) {
        $graph = new \PieGraph(900,500);
        $pie   = new \PiePlot3D($data);
        $pie->SetLegends($labels);
        $pie->SetCenter(0.5,0.5);
        $pie->ExplodeSlice(1);
        $graph->Add($pie);
        $graph->Stroke($imgFile);
    }
    $graf = file_exists($imgFile)
          ? 'data:image/png;base64,'.base64_encode(file_get_contents($imgFile))
          : '';

    // 4) Ventas para la tabla (ordenado por total_desc)
    $whereT  = ['p.tipo = 1'];
    $paramsT = [];
    if ($start && $end) {
        $whereT[]        = 'p.fecha BETWEEN :sT AND :eT';
        $paramsT[':sT']  = "$start 00:00:00";
        $paramsT[':eT']  = "$end   23:59:59";
    }
    if ($prodId) {
        $whereT[]         = 'pd.id_producto = :pidT';
        $paramsT[':pidT'] = $prodId;
    }
    if ($catId) {
        $whereT[]         = 'cat.id_categoria = :catT';
        $paramsT[':catT'] = $catId;
    }

    $sqlT = "
      SELECT
        p.id_pedido,
        CONCAT(c.nombre,' ',c.apellido) AS cliente,
        p.fecha,
        p.precio_total_usd AS total_usd,
        GROUP_CONCAT(
          pr.nombre,' (',pd.cantidad,'u)'
          ORDER BY pd.cantidad DESC
          SEPARATOR ', '
        ) AS productos,
        cat.nombre AS categoria
      FROM pedido p
      JOIN cliente         c   ON c.id_persona    = p.id_persona
      JOIN pedido_detalles pd  ON pd.id_pedido    = p.id_pedido
      JOIN productos       pr  ON pr.id_producto  = pd.id_producto
      JOIN categoria       cat ON cat.id_categoria = pr.id_categoria
      WHERE " . implode(' AND ', $whereT) . "
      GROUP BY p.id_pedido, cliente, p.fecha, p.precio_total_usd, cat.nombre
      ORDER BY total_usd DESC
    ";
    $stmtT = $conex->prepare($sqlT);
    $stmtT->execute($paramsT);
    $rows = $stmtT->fetchAll(PDO::FETCH_ASSOC);
    $conex = null;

    // 5) Texto de filtros
    if (!$start && !$endParam) {
        $filtro = 'Registro general';
    }
    elseif ($start && !$endParam) {
        $filtro = 'Desde '.date('d/m/Y',strtotime($start))
                .' hasta '.date('d/m/Y').'';
    }
    elseif (!$start && $endParam) {
        $filtro = 'Hasta '.date('d/m/Y',strtotime($endParam));
    }
    elseif ($start === $endParam) {
        $filtro = 'Reporte del '.date('d/m/Y',strtotime($start));
    }
    else {
        $filtro = 'Desde '.date('d/m/Y',strtotime($start))
                .' hasta '.date('d/m/Y',strtotime($end));
    }
    if ($prodId) {
        $db = (new Conexion())->getConex1();
        $n  = $db->prepare('SELECT nombre FROM productos WHERE id_producto=:pid');
        $n->execute([':pid' => $prodId]);
        $filtro .= ' | Producto: '.htmlspecialchars($n->fetchColumn());
    }
    if ($catId) {
        $filtro .= ' | Categoría: '.htmlspecialchars($rows[0]['categoria'] ?? '');
    }

    // 6) Cabecera + logo
    $fechaGen = date('d/m/Y H:i:s');
    $icon     = __DIR__ . '/../assets/img/icon.PNG';
    $logoData = file_exists($icon)
              ? 'data:image/png;base64,'.base64_encode(file_get_contents($icon))
              : '';

    // 7) Montar HTML y renderizar PDF
    $html = '<html><head><style>
      @page{margin:120px 50px 60px 50px}
      body{margin:0;font-family:Arial,sans-serif;font-size:12px}
      header{position:fixed;top:-110px;left:0;right:0;height:110px;text-align:center}
      header img.logo-icon{position:absolute;top:5px;right:5px;width:100px;height:100px}
      header h1{margin:0;font-size:24px}
      header p{margin:4px 0;font-size:14px;color:#555}
      footer{position:fixed;bottom:-40px;left:0;right:0;height:40px;text-align:center;font-size:10px;color:#666}
      table{width:100%;border-collapse:collapse;margin-top:10px}
      th,td{border:1px solid #000;padding:6px;text-align:center}
      th{background:#f36ca4;color:#fff}
    </style></head><body>'
      . '<header>'
      . ($logoData?'<img src="'.$logoData.'" class="logo-icon"/>':'')
      . '<h1>LoveMakeup</h1><p>RIF: J-00000000</p>'
      . '</header><main>'
      . '<h1>Listado de Ventas</h1>'
      . "<p><strong>Generado:</strong> {$fechaGen}</p>"
      . "<p><strong>Filtro:</strong> {$filtro}</p>"
      . (!empty($graf)
          ? '<h2>Top 10 Productos Más Vendidos</h2>'
            . '<div style="text-align:center"><img src="'.$graf.'" width="600"/></div>'
          : '')
      . '<table><thead><tr>'
      . '<th>ID Venta</th><th>Cliente</th><th>Fecha</th>'
      . '<th>Total (USD)</th><th>Productos</th><th>Categoría</th>'
      . '</tr></thead><tbody>';
    foreach ($rows as $r) {
        $d    = date('d/m/Y',strtotime($r['fecha']));
        $tot  = '$'.number_format($r['total_usd'],2);
        $prods=htmlspecialchars($r['productos'] ?? '—');
        $catn =htmlspecialchars($r['categoria'] ?? '—');
        $cli  =htmlspecialchars($r['cliente']);
        $html .= "<tr>
                    <td>{$r['id_pedido']}</td>
                    <td>{$cli}</td>
                    <td>{$d}</td>
                    <td>{$tot}</td>
                    <td>{$prods}</td>
                    <td>{$catn}</td>
                  </tr>";
    }
    $html .= '</tbody></table></main>'
           . '<footer>Página <span class="pageNumber"></span> de <span class="totalPages"></span></footer>'
           . '</body></html>';

    $opts = new Options();
    $opts->set('isRemoteEnabled', true);
    $pdf = new Dompdf($opts);
    $pdf->loadHtml($html);
    $pdf->setPaper('A4','portrait');
    $pdf->render();
    $cv = $pdf->getCanvas();
    $cv->page_text(
        $cv->get_width()/2 - 30,
        $cv->get_height() - 30,
        "Página {PAGE_NUM} de {PAGE_COUNT}",
        $pdf->getFontMetrics()->get_font('helvetica','normal'),
        10, [0,0,0]
    );
    $pdf->stream('Reporte_Ventas.pdf', ['Attachment' => false]);
}





public static function pedidoWeb($start = null, $end = null, $prodId = null): void {
    // 1) Normalizar fechas
    $endParam = $end;
    if ($start && ! $endParam) {
        $end = date('Y-m-d');
    }

    // 2) Dependencias y conexión
    require_once 'modelo/conexion.php';
    require_once 'assets/dompdf/vendor/autoload.php';
    require_once __DIR__ . '/../assets/js/jpgraph/src/jpgraph.php';
    require_once __DIR__ . '/../assets/js/jpgraph/src/jpgraph_pie.php';
    require_once __DIR__ . '/../assets/js/jpgraph/src/jpgraph_pie3d.php';
    $conex = (new Conexion())->getConex1();

    // 3) Top 5 productos pedidos web
    $whereG  = ['p.tipo = 2'];
    $paramsG = [];
    if ($start && $end) {
        $whereG[]        = 'p.fecha BETWEEN :sG AND :eG';
        $paramsG[':sG']  = "$start 00:00:00";
        $paramsG[':eG']  = "$end   23:59:59";
    }
    if ($prodId) {
        $whereG[]        = 'pd.id_producto = :pidG';
        $paramsG[':pidG'] = $prodId;
    }
    $sqlG = "
      SELECT pr.nombre AS producto, SUM(pd.cantidad) AS total
      FROM pedido_detalles pd
      JOIN productos pr ON pd.id_producto = pr.id_producto
      JOIN pedido p     ON pd.id_pedido   = p.id_pedido
      WHERE " . implode(' AND ', $whereG) . "
      GROUP BY pr.id_producto
      ORDER BY total DESC
      LIMIT 5
    ";
    $stmtG = $conex->prepare($sqlG);
    $stmtG->execute($paramsG);
    $labels = $data = [];
    while ($r = $stmtG->fetch(PDO::FETCH_ASSOC)) {
        $labels[] = htmlspecialchars($r['producto']);
        $data[]   = (int)$r['total'];
    }

    // 4) Renderizar gráfico a Base64
    $imgDir  = __DIR__ . '/../assets/img/grafica_reportes/';
    $imgFile = $imgDir . 'grafico_pedidoweb.png';
    if (!is_dir($imgDir)) mkdir($imgDir,0777,true);
    if (file_exists($imgFile)) unlink($imgFile);
    if ($data) {
        $graph = new \PieGraph(900,500);
        $pie   = new \PiePlot3D($data);
        $pie->SetLegends($labels);
        $pie->SetCenter(0.5,0.5);
        $pie->ExplodeSlice(1);
        $graph->Add($pie);
        $graph->Stroke($imgFile);
    }
    $graf = file_exists($imgFile)
          ? 'data:image/png;base64,'.base64_encode(file_get_contents($imgFile))
          : '';

    // 5) Datos para la tabla (ordenados por total desc)
    $whereT  = ['p.tipo = 2'];
    $paramsT = [];
    if ($start && $end) {
        $whereT[]        = 'p.fecha BETWEEN :sT AND :eT';
        $paramsT[':sT']  = "$start 00:00:00";
        $paramsT[':eT']  = "$end   23:59:59";
    }
    if ($prodId) {
        $whereT[]         = 'pd.id_producto = :pidT';
        $paramsT[':pidT'] = $prodId;
    }

    $sqlT = "
      SELECT 
        p.id_pedido,
        p.fecha,
        p.estado,
        p.precio_total,
        p.referencia_bancaria AS referencia,
        p.telefono_emisor    AS telefono,
        me.nombre            AS entrega,
        mp.nombre            AS pago,
        CONCAT(c.nombre,' ',c.apellido) AS cliente
      FROM pedido p
      JOIN cliente c          ON p.id_persona = c.id_persona
      LEFT JOIN metodo_entrega me ON p.id_entrega    = me.id_entrega
      LEFT JOIN metodo_pago    mp ON p.id_metodopago = mp.id_metodopago
      JOIN pedido_detalles pd  ON p.id_pedido     = pd.id_pedido
      WHERE " . implode(' AND ', $whereT) . "
      GROUP BY
        p.id_pedido, p.fecha, p.estado, p.precio_total,
        p.referencia_bancaria, p.telefono_emisor,
        me.nombre, mp.nombre, c.nombre, c.apellido
      ORDER BY p.precio_total DESC
    ";
    $stmtT = $conex->prepare($sqlT);
    $stmtT->execute($paramsT);
    $rows = $stmtT->fetchAll(PDO::FETCH_ASSOC);
    $conex = null;

    // 6) Construir texto de filtros
    if (!$start && !$endParam) {
        $filtro = 'Todos los pedidos web';
    }
    elseif ($start && !$endParam) {
        $filtro = 'Desde '.date('d/m/Y',strtotime($start))
                .' hasta '.date('d/m/Y').'';
    }
    elseif (!$start && $endParam) {
        $filtro = 'Hasta '.date('d/m/Y',strtotime($endParam));
    }
    else {
        $filtro = 'Desde '.date('d/m/Y',strtotime($start))
                .' hasta '.date('d/m/Y',strtotime($end));
    }
    if ($prodId) {
        $db = (new Conexion())->getConex1();
        $p  = $db->prepare('SELECT nombre FROM productos WHERE id_producto=:pid');
        $p->execute([':pid'=>$prodId]);
        $filtro .= ' | Producto: '.htmlspecialchars($p->fetchColumn());
    }

    // 7) Cabecera y logo
    $fechaGen = date('d/m/Y H:i:s');
    $icon     = __DIR__ . '/../assets/img/icon.PNG';
    $logoData = file_exists($icon)
              ? 'data:image/png;base64,'.base64_encode(file_get_contents($icon))
              : '';

    // 8) Montar HTML y generar PDF
    $html = '<html><head><style>
      @page{margin:120px 50px 60px 50px}
      body{margin:0;font-family:Arial,sans-serif;font-size:12px}
      header{position:fixed;top:-110px;left:0;right:0;height:110px;text-align:center}
      header img.logo-icon{position:absolute;top:5px;right:5px;width:100px;height:100px}
      header h1{margin:0;font-size:24px}
      header p{margin:4px 0;font-size:14px;color:#555}
      footer{position:fixed;bottom:-40px;left:0;right:0;height:40px;text-align:center;font-size:10px;color:#666}
      table{width:100%;border-collapse:collapse;margin-top:10px}
      th,td{border:1px solid #000;padding:6px;text-align:center}
      th{background:#f36ca4;color:#fff}
    </style></head><body>'
      . '<header>'
      . ($logoData?'<img src="'.$logoData.'" class="logo-icon"/>':'')
      . '<h1>LoveMakeup</h1><p>RIF: J-00000000</p>'
      . '</header><main>'
      . '<h1>Reporte de Pedidos Web</h1>'
      . "<p><strong>Generado:</strong> {$fechaGen}</p>"
      . "<p><strong>Filtro:</strong> {$filtro}</p>"
      . (!empty($graf)
          ? '<h2>Top 5 Productos</h2><div style="text-align:center"><img src="'.$graf.'" width="600"/></div>'
          : '')
      . '<table><thead><tr>'
      . '<th>ID</th><th>Fecha</th><th>Estado</th><th>Total</th>'
      . '<th>Ref.</th><th>Teléfono</th><th>Entrega</th><th>Pago</th><th>Cliente</th>'
      . '</tr></thead><tbody>';
    $estados = ['0'=>'Anulado','1'=>'Verificar pago','2'=>'Entregado','3'=>'Pendiente enví','4'=>'En camino','5'=>'Enviado'];
    foreach ($rows as $r) {
        $d   = date('d/m/Y',strtotime($r['fecha']));
        $est = $estados[(string)$r['estado']] ?? 'Desconocido';
        $tot = '$'.number_format($r['precio_total'],2);
        $html .= "<tr>
                    <td>{$r['id_pedido']}</td>
                    <td>{$d}</td>
                    <td>{$est}</td>
                    <td>{$tot}</td>
                    <td>".htmlspecialchars($r['referencia'])."</td>
                    <td>".htmlspecialchars($r['telefono'])."</td>
                    <td>".htmlspecialchars($r['entrega'])."</td>
                    <td>".htmlspecialchars($r['pago'])."</td>
                    <td>".htmlspecialchars($r['cliente'])."</td>
                  </tr>";
    }
    $html .= '</tbody></table></main>'
           . '<footer>Página <span class="pageNumber"></span> de <span class="totalPages"></span></footer>'
           . '</body></html>';

    $opts = new \Dompdf\Options();
    $opts->set('isRemoteEnabled', true);
    $pdf = new Dompdf($opts);
    $pdf->loadHtml($html);
    $pdf->setPaper('A4','portrait');
    $pdf->render();
    $cv = $pdf->getCanvas();
    $cv->page_text(
        $cv->get_width()/2-30,
        $cv->get_height()-30,
        "Página {PAGE_NUM} de {PAGE_COUNT}",
        $pdf->getFontMetrics()->get_font('helvetica','normal'),
        10,
        [0,0,0]
    );
    $pdf->stream('Reporte_PedidosWeb.pdf',['Attachment'=>false]);
}







// modelo/reporte.php
public static function countCompra($start=null, $end=null, $prodId=null, $catId=null): int {
    $conex = (new Conexion())->getConex1();
    $where  = [];
    $params = [];
    if ($start && $end) {
        $where[]      = 'c.fecha_entrada BETWEEN :s AND :e';
        $params[':s'] = $start . ' 00:00:00';
        $params[':e'] = $end   . ' 23:59:59';
    }
    if ($prodId) {
        $where[]        = 'cd.id_producto = :pid';
        $params[':pid']= $prodId;
    }
    if ($catId) {
        // unimos productos para poder filtrar categoría
        $where[]         = 'p.id_categoria = :cid';
        $params[':cid']  = $catId;
    }
    $join = '';
    if ($catId) {
        $join = 'JOIN productos p ON p.id_producto = cd.id_producto';
    }
    $sql = "
      SELECT COUNT(DISTINCT c.id_compra) 
      FROM compra c
      JOIN compra_detalles cd ON cd.id_compra = c.id_compra
      $join
      ".($where? 'WHERE '.implode(' AND ',$where):'')."
    ";
    $stmt = $conex->prepare($sql);
    $stmt->execute($params);
    return (int)$stmt->fetchColumn();
}





public static function countProducto($prodId = null, $provId = null, $catId = null): int {
    $conex = (new Conexion())->getConex1();
    $where  = ['1=1'];
    $params = [];
    $join   = '';

    if ($prodId) {
      $where[]          = 'p.id_producto = :pid';
      $params[':pid']   = $prodId;
    }
    if ($catId) {
      $where[]          = 'p.id_categoria = :cat';
      $params[':cat']   = $catId;
    }
    if ($provId) {
      $join = "
        JOIN compra_detalles cd ON cd.id_producto = p.id_producto
        JOIN compra           c  ON c.id_compra    = cd.id_compra
      ";
      $where[]          = 'c.id_proveedor = :prov';
      $params[':prov']  = $provId;
    }

    $w   = implode(' AND ', $where);
    $sql = "
      SELECT COUNT(DISTINCT p.id_producto) 
      FROM productos p
      $join
      WHERE $w
    ";
    $stmt = $conex->prepare($sql);
    $stmt->execute($params);
    return (int)$stmt->fetchColumn();
}

  /**
   * Devuelve el número de ventas (tipo 1) que cumplen filtros.
   */
public static function countVenta(
    $start    = null,
    $end      = null,
    $prodId   = null,
    $metodoId = null,
    $catId    = null): int {
    $conex  = (new Conexion())->getConex1();
    $where  = ['pe.tipo = 1'];
    $params = [];

    if ($start && $end) {
        $where[]         = 'pe.fecha BETWEEN :s AND :e';
        $params[':s']    = $start . ' 00:00:00';
        $params[':e']    = $end   . ' 23:59:59';
    }
    if ($prodId) {
        $where[]         = 'pd.id_producto = :pid';
        $params[':pid']  = $prodId;
    }
    if ($metodoId) {
        $where[]         = 'pe.id_metodopago = :mp';
        $params[':mp']   = $metodoId;
    }

    // Siempre necesitamos el detalle para filtrar producto y categoría
    $join = 'JOIN pedido_detalles pd ON pd.id_pedido = pe.id_pedido';

    if ($catId) {
        $join .= ' JOIN productos pr ON pr.id_producto = pd.id_producto ';
        $where[] = 'pr.id_categoria = :cat';
        $params[':cat'] = $catId;
    }

    $sql = "
      SELECT COUNT(DISTINCT pe.id_pedido) AS cnt
      FROM pedido pe
      $join
      WHERE " . implode(' AND ', $where) . "
    ";
    $stmt = $conex->prepare($sql);
    $stmt->execute($params);
    return (int)$stmt->fetchColumn();
}



  /**
   * Devuelve el número de pedidos web (tipo 2) que cumplen filtros.
   */
  public static function countPedidoWeb($start = null, $end = null, $prodId = null): int {
    $conex = (new Conexion())->getConex1();
    $where  = ['p.tipo = 2'];
    $params = [];

    if ($start && $end) {
      $where[]        = 'p.fecha BETWEEN :s AND :e';
      $params[':s']   = $start . ' 00:00:00';
      $params[':e']   = $end   . ' 23:59:59';
    }
    if ($prodId) {
      $where[]           = 'pd.id_producto = :pid';
      $params[':pid']    = $prodId;
    }
    $w = 'WHERE ' . implode(' AND ', $where);

    $sql  = "
      SELECT COUNT(DISTINCT p.id_pedido) AS cnt
      FROM pedido p
      JOIN pedido_detalles pd ON pd.id_pedido = p.id_pedido
      $w
    ";
    $stmt = $conex->prepare($sql);
    $stmt->execute($params);
    return (int)$stmt->fetchColumn();
  }


}
