<?php
require_once 'modelo/conexion.php';
require_once 'assets/dompdf/vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

class Reporte {

public static function compra($start = null, $end = null, $prodId = null): void {
    require_once 'modelo/conexion.php';
    require_once 'assets/dompdf/vendor/autoload.php';
    require_once __DIR__ . '/../assets/js/jpgraph/src/jpgraph.php';
    require_once __DIR__ . '/../assets/js/jpgraph/src/jpgraph_pie.php';
    require_once __DIR__ . '/../assets/js/jpgraph/src/jpgraph_pie3d.php';

    $conex = (new Conexion())->getConex1();

    // — Datos de gráfica —
    $whereG = ['p.estatus = 1']; $paramsG = [];
    if ($start && $end) {
        $whereG[]        = 'c.fecha_entrada BETWEEN :sG AND :eG';
        $paramsG[':sG']  = "$start 00:00:00";
        $paramsG[':eG']  = "$end 23:59:59";
    }
    if ($prodId) {
        $whereG[]        = 'cd.id_producto = :pidG';
        $paramsG[':pidG']= $prodId;
    }
    $sqlG = "
      SELECT p.nombre producto, SUM(cd.cantidad) total
      FROM compra_detalles cd
      JOIN productos p ON cd.id_producto=p.id_producto
      JOIN compra c ON cd.id_compra=c.id_compra
      WHERE " . implode(' AND ', $whereG) . "
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

    // — Generar gráfico y codificar —
    $imgDir  = __DIR__ . '/../assets/img/grafica_reportes/';
    $imgFile = $imgDir . 'grafico_compras.png';
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

    // — Tabla de Compras —
    $whereT = []; $paramsT = [];
    if ($start && $end) {
        $whereT[]        = 'c.fecha_entrada BETWEEN :sT AND :eT';
        $paramsT[':sT']  = "$start 00:00:00";
        $paramsT[':eT']  = "$end 23:59:59";
    }
    if ($prodId) {
        $whereT[]        = 'cd.id_producto = :pidT';
        $paramsT[':pidT']= $prodId;
    }
    $sqlT = "
      SELECT c.id_compra, c.fecha_entrada,
             pr.nombre proveedor,
             GROUP_CONCAT(p.nombre,' (',cd.cantidad,'u)') productos,
             SUM(cd.cantidad*cd.precio_unitario) total
      FROM compra c
      JOIN compra_detalles cd ON c.id_compra=cd.id_compra
      JOIN productos p        ON cd.id_producto=p.id_producto
      JOIN proveedor pr       ON c.id_proveedor=pr.id_proveedor
      WHERE 1=1"
      . (!empty($whereT) ? ' AND '.implode(' AND ',$whereT) : '')
      . " GROUP BY c.id_compra,c.fecha_entrada,pr.nombre
          ORDER BY c.id_compra DESC
    ";
    $stmtT = $conex->prepare($sqlT);
    $stmtT->execute($paramsT);
    $rows = $stmtT->fetchAll(PDO::FETCH_ASSOC);

    $conex = null;

    // — Filtro textual —
    if (!$start && !$end) {
        $filtro = 'Registro general';
    } elseif ($start && !$end) {
        $filtro = 'Reporte desde '.date('d/m/Y',strtotime($start));
    } elseif (!$start && $end) {
        $filtro = 'Reporte hasta '.date('d/m/Y',strtotime($end));
    } elseif ($start === $end) {
        $filtro = 'Reporte del '.date('d/m/Y',strtotime($start));
    } else {
        $filtro = 'Desde '.date('d/m/Y',strtotime($start))
                 .' hasta '.date('d/m/Y',strtotime($end));
    }

    // — Logo en Base64 —
    $iconPath = __DIR__ . '/../assets/img/icon.PNG';
    $logoData = file_exists($iconPath)
              ? 'data:image/png;base64,'.base64_encode(file_get_contents($iconPath))
              : '';

    // — Armar HTML con CSS ajustado —
    $html = '<html><head><style>
      @page { margin:120px 50px 60px 50px; }
      body { margin:0; font-family:Arial,sans-serif; font-size:12px; }
      header {
        position:fixed; top:-110px; left:0; right:0; height:110px;
        text-align:center;
      }
      header h1 {
        margin:0; font-size:24px;
      }
      header p {
        margin:4px 0 0; font-size:14px; color:#555;
      }
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
      th {
        background:#f36ca4; color:#fff;
      }
    </style></head><body>'
    // header
   . '<header>';
    if ($logoData) {
        $html .= '<img src="'.$logoData.'" class="logo-icon" alt="Logo"/>';
    }
    $html .= '<h1>LoveMakeup</h1>'
           . '<p>RIF: J-00000000</p>'
           . '</header>'
    // main
   . '<main>'
   . '<h1>Listado de Compras</h1>'
   . '<p><strong>Filtro:</strong> '.$filtro.'</p>'
   . (!empty($graf)
       ? '<h2>Top 10 Productos Comprados</h2>
          <div style="text-align:center;">
            <img src="'.$graf.'" width="600"/>
          </div>'
       : ''
     )
   . '<table><thead><tr>
        <th>ID Compra</th><th>Fecha</th><th>Proveedor</th>
        <th>Productos</th><th>Total</th>
      </tr></thead><tbody>';
    foreach ($rows as $r) {
        $d = date('d/m/Y', strtotime($r['fecha_entrada']));
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

    // — Render y numeración —
    $opts = new \Dompdf\Options();
    $opts->set('isRemoteEnabled', true);
    $pdf = new Dompdf($opts);
    $pdf->loadHtml($html);
    $pdf->setPaper('A4','portrait');
    $pdf->render();

    $cv = $pdf->getCanvas();
    $w  = $cv->get_width();
    $h  = $cv->get_height();
    $fn = $pdf->getFontMetrics()->get_font('helvetica','normal');
    $cv->page_text(
        $w/2 - 30,
        $h - 30,
        "Página {PAGE_NUM} de {PAGE_COUNT}",
        $fn,
        10,
        [0,0,0],
        0,
        0.5
    );

    $pdf->stream('Reporte_Compras.pdf', ['Attachment' => false]);
}


public static function producto($prodId = null, $provId = null, $catId = null): void {
    // includes y setup
    require_once 'modelo/conexion.php';
    require_once 'assets/dompdf/vendor/autoload.php';
    require_once __DIR__ . '/../assets/js/jpgraph/src/jpgraph.php';
    require_once __DIR__ . '/../assets/js/jpgraph/src/jpgraph_pie.php';
    require_once __DIR__ . '/../assets/js/jpgraph/src/jpgraph_pie3d.php';

    $conex = (new Conexion())->getConex1();

    // 1) Datos para gráfico Top 10 stock
    $whereG = ['1=1']; $paramsG = []; $joinG = '';
    if ($prodId) {
        $whereG[]        = 'p.id_producto = :pid';
        $paramsG[':pid'] = $prodId;
    }
    if ($provId) {
        $joinG = "
          JOIN compra_detalles cd ON cd.id_producto = p.id_producto
          JOIN compra c ON c.id_compra = cd.id_compra
        ";
        $whereG[]        = 'c.id_proveedor = :prov';
        $paramsG[':prov']= $provId;
    }
    if ($catId) {
        $whereG[]        = 'p.id_categoria = :cat';
        $paramsG[':cat'] = $catId;
    }
    $sqlG = "
      SELECT p.nombre, p.stock_disponible
      FROM productos p
      JOIN categoria cat ON cat.id_categoria = p.id_categoria
      $joinG
      WHERE ".implode(' AND ',$whereG)."
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

    // 2) Generar PNG y Base64
    $imgDir  = __DIR__ . '/../assets/img/grafica_reportes/';
    $imgFile = $imgDir . 'grafico_productos.png';
    if (!is_dir($imgDir)) mkdir($imgDir,0777,true);
    if (file_exists($imgFile)) unlink($imgFile);
    if ($data) {
        $g = new \PieGraph(900,500);
        $p = new \PiePlot3D($data);
        $p->SetLegends($labels);
        $p->SetCenter(0.5,0.5);
        $p->ExplodeSlice(1);
        $g->Add($p);
        $g->Stroke($imgFile);
    }
    $graf = file_exists($imgFile)
          ? 'data:image/png;base64,'.base64_encode(file_get_contents($imgFile))
          : '';

    // 3) Datos para tabla
    $whereT = ['1=1']; $paramsT = []; $joinT = '';
    if ($prodId) {
        $whereT[]        = 'p.id_producto = :pidT';
        $paramsT[':pidT']= $prodId;
    }
    if ($provId) {
        $joinT = "
          JOIN compra_detalles cd2 ON cd2.id_producto = p.id_producto
          JOIN compra c2 ON c2.id_compra = cd2.id_compra
        ";
        $whereT[]        = 'c2.id_proveedor = :provT';
        $paramsT[':provT']= $provId;
    }
    if ($catId) {
        $whereT[]        = 'p.id_categoria = :catT';
        $paramsT[':catT']= $catId;
    }
    $sqlT = "
      SELECT DISTINCT
        p.nombre, p.descripcion, p.marca,
        p.precio_detal, p.precio_mayor,
        p.stock_disponible, cat.nombre AS categoria
      FROM productos p
      JOIN categoria cat ON cat.id_categoria = p.id_categoria
      $joinT
      WHERE ".implode(' AND ',$whereT)."
      ORDER BY p.nombre ASC
    ";
    $stmtT = $conex->prepare($sqlT);
    $stmtT->execute($paramsT);
    $rows  = $stmtT->fetchAll(PDO::FETCH_ASSOC);

    //Cerrar conexión antes de montar HTML
    $conex = null;

    // 4) Sin datos, mensaje
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

    // 5) Texto filtro y fecha de generación
    $parts = [];
    if ($prodId) $parts[] = 'Producto: '.htmlspecialchars($rows[0]['nombre']);
    if ($provId) $parts[] = 'Proveedor ID: '.$provId;
    if ($catId)  $parts[] = 'Categoría: '.htmlspecialchars($rows[0]['categoria']);
    $filtro = $parts ? implode(' | ',$parts) : 'Listado general de productos';
    $fechaGen = date('d/m/Y H:i');

    // 6) Logo Base64
    $icon = __DIR__ . '/../assets/img/icon.PNG';
    $logoData = file_exists($icon)
              ? 'data:image/png;base64,'.base64_encode(file_get_contents($icon))
              : '';

    // 7) Construir HTML con CSS retocado
    $html = '<html><head><style>
      @page { margin:120px 50px 60px 50px; }
      body { margin:0; font-family:Arial,sans-serif; font-size:12px; }
      header {
        position:fixed; top:-110px; left:0; right:0; height:110px;
        text-align:center;
      }
      header h1 {
        margin:0; font-size:24px;
      }
      header p {
        margin:4px 0 0; font-size:14px; color:#555;
      }
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
      th {
        background:#f36ca4; color:#fff;
      }
    </style></head><body>'
    // header
   . '<header>';
    if ($logoData) {
        $html .= '<img src="'.$logoData.'" class="logo-icon" alt="Logo"/>';
    }
    $html .= '<h1>LoveMakeup</h1>'
           . '<p>RIF: J-00000000</p>'
           . '</header>'
    // main
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
    $cv = $pdf->getCanvas();
    $w  = $cv->get_width();
    $h  = $cv->get_height();
    $fn = $pdf->getFontMetrics()->get_font('helvetica','normal');
    $cv->page_text($w/2 - 30, $h - 30,
                   "Página {PAGE_NUM} de {PAGE_COUNT}",
                   $fn, 10, [0,0,0], 0, 0.5);
    $pdf->stream('Reporte_Productos.pdf',['Attachment'=>false]);
}


public static function venta($start = null, $end = null, $prodId = null): void {
    require_once 'modelo/conexion.php';
    require_once 'assets/dompdf/vendor/autoload.php';
    require_once __DIR__.'/../assets/js/jpgraph/src/jpgraph.php';
    require_once __DIR__.'/../assets/js/jpgraph/src/jpgraph_pie.php';
    require_once __DIR__.'/../assets/js/jpgraph/src/jpgraph_pie3d.php';

    $conex = (new Conexion())->getConex1();

    // 1) Datos gráfico Top 5 vendidos
    $whereG=['pe.estado=2','pe.tipo=1']; $paramsG=[];
    if ($start && $end) {
        $whereG[]='pe.fecha BETWEEN :sG AND :eG';
        $paramsG[':sG']="$start 00:00:00";
        $paramsG[':eG']="$end 23:59:59";
    }
    if ($prodId) {
        $whereG[]='pd.id_producto=:pidG';
        $paramsG[':pidG']=$prodId;
    }
    $sqlG = "
      SELECT p.nombre producto, SUM(pd.cantidad) total
      FROM pedido_detalles pd
      JOIN productos p ON pd.id_producto=p.id_producto
      JOIN pedido pe   ON pd.id_pedido=pe.id_pedido
      WHERE ".implode(' AND ',$whereG)."
      GROUP BY pd.id_producto
      ORDER BY total DESC
      LIMIT 5
    ";
    $stmtG = $conex->prepare($sqlG);
    $stmtG->execute($paramsG);
    $labels=$data=[];
    while($r=$stmtG->fetch(PDO::FETCH_ASSOC)){
      $labels[]=htmlspecialchars($r['producto']);
      $data[]  =(int)$r['total'];
    }

    // 2) Generar gráfico y Base64
    $imgDir=__DIR__.'/../assets/img/grafica_reportes/';
    $imgFile=$imgDir.'grafico_ventas.png';
    if(!is_dir($imgDir)) mkdir($imgDir,0777,true);
    if(file_exists($imgFile)) unlink($imgFile);
    if($data){
      $g=new \PieGraph(900,500);
      $p=new \PiePlot3D($data);
      $p->SetLegends($labels);
      $p->SetCenter(0.5,0.5);
      $p->ExplodeSlice(1);
      $g->Add($p);
      $g->Stroke($imgFile);
    }
    $graf = file_exists($imgFile)
          ? 'data:image/png;base64,'.base64_encode(file_get_contents($imgFile))
          : '';

    // 3) Datos tabla de ventas
    $whereT=['pe.tipo=1']; $paramsT=[];
    if ($start && $end) {
        $whereT[]='pe.fecha BETWEEN :sT AND :eT';
        $paramsT[':sT']="$start 00:00:00";
        $paramsT[':eT']="$end 23:59:59";
    }
    if ($prodId) {
        $whereT[]='pe.id_pedido IN (
                     SELECT id_pedido FROM pedido_detalles 
                     WHERE id_producto=:pidT
                   )';
        $paramsT[':pidT']=$prodId;
    }
    $sqlT = "
      SELECT 
        CONCAT(cl.nombre,' ',cl.apellido) cliente,
        pe.fecha, pe.estado, pe.precio_total,
        mp.nombre metodo_pago,
        me.nombre metodo_entrega
      FROM pedido pe
      JOIN cliente cl ON pe.id_persona=cl.id_persona
      JOIN metodo_pago mp ON pe.id_metodopago=mp.id_metodopago
      JOIN metodo_entrega me ON pe.id_entrega=me.id_entrega
      WHERE ".implode(' AND ',$whereT)."
      ORDER BY pe.fecha DESC
    ";
    $stmtT=$conex->prepare($sqlT);
    $stmtT->execute($paramsT);
    $rows=$stmtT->fetchAll(PDO::FETCH_ASSOC);

    //Cerrar conexión antes de montar HTML
    $conex = null;

    // 4) Texto filtros y fecha
    if (!$start && !$end && !$prodId) $filtro='Registro general';
    elseif ($start && !$end)         $filtro='Desde '.date('d/m/Y',strtotime($start));
    elseif (!$start && $end)         $filtro='Hasta '.date('d/m/Y',strtotime($end));
    else                              $filtro='Desde '.date('d/m/Y',strtotime($start))
                                         .' hasta '.date('d/m/Y',strtotime($end));
    if ($prodId && isset($labels[0])) {
      $filtro .= " | Producto: {$labels[0]}";
    }
    $fechaGen=date('d/m/Y H:i');

    // 5) Logo Base64
    $icon=__DIR__.'/../assets/img/icon.PNG';
    $logoData = file_exists($icon)
              ? 'data:image/png;base64,'.base64_encode(file_get_contents($icon))
              : '';

    // 6) Armar HTML (margen, título e imagen más grande)
    $html = '<html><head><style>
      @page { margin:120px 50px 60px 50px; }
      body { margin:0; font-family:Arial,sans-serif; font-size:12px; }
      header {
        position:fixed; top:-110px; left:0; right:0; height:110px;
        text-align:center;
      }
      header h1 {
        margin:0; font-size:24px;
      }
      header p {
        margin:4px 0 0; font-size:14px; color:#555;
      }
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
      th {
        background:#f36ca4; color:#fff;
      }
    </style></head><body>'
    // HEADER
   . '<header>';
    if ($logoData) {
      $html .= '<img src="'.$logoData.'" class="logo-icon" alt="Logo"/>';
    }
    $html .= '<h1>LoveMakeup</h1>'
           . '<p>RIF: J-00000000</p>'
           . '</header>'
    // MAIN
   . '<main>'
   . '<h1>Listado de Ventas</h1>'
   . "<p><strong>Generado:</strong> {$fechaGen}</p>"
   . "<p><strong>Filtro:</strong> {$filtro}</p>"
   . (!empty($graf)
      ? '<h2>Top 5 Productos Más Vendidos</h2>
         <div style="text-align:center;">
           <img src="'.$graf.'" width="600"/>
         </div>'
      : ''
     )
   . '<table><thead><tr>
        <th>Cliente</th><th>Fecha</th><th>Estado</th>
        <th>Total</th><th>Método Pago</th><th>Método Entrega</th>
      </tr></thead><tbody>';
    $estados=['0'=>'Cancelado','1'=>'Pendiente','2'=>'Entregado',
              '3'=>'En camino','4'=>'Enviado'];
    foreach ($rows as $r) {
      $d   = date('d/m/Y',strtotime($r['fecha']));
      $est = $estados[$r['estado']] ?? '';
      $tot = '$'.number_format($r['precio_total'],2);
      $html .= "<tr>
                 <td>".htmlspecialchars($r['cliente'])."</td>
                 <td>{$d}</td>
                 <td>{$est}</td>
                 <td>{$tot}</td>
                 <td>".htmlspecialchars($r['metodo_pago'])."</td>
                 <td>".htmlspecialchars($r['metodo_entrega'])."</td>
               </tr>";
    }
    $html .= '</tbody></table></main>'
           . '<footer>Página <span class="pageNumber"></span> de <span class="totalPages"></span></footer>'
           . '</body></html>';

    // 7) Render y numeración
    $opts = new \Dompdf\Options();
    $opts->set('isRemoteEnabled', true);
    $pdf = new Dompdf($opts);
    $pdf->loadHtml($html);
    $pdf->setPaper('A4','portrait');
    $pdf->render();
    $cv = $pdf->getCanvas();
    $w  = $cv->get_width(); $h = $cv->get_height();
    $fn = $pdf->getFontMetrics()->get_font('helvetica','normal');
    $cv->page_text($w/2 - 30, $h - 30,
                   "Página {PAGE_NUM} de {PAGE_COUNT}",
                   $fn, 10, [0,0,0], 0, 0.5);

    // 8) Emitir PDF
    $pdf->stream('Reporte_Ventas.pdf', ['Attachment'=>false]);
}


public static function pedidoWeb($start = null, $end = null, $prodId = null): void {
    // includes y setup
    require_once 'modelo/conexion.php';
    require_once 'assets/dompdf/vendor/autoload.php';
    require_once __DIR__.'/../assets/js/jpgraph/src/jpgraph.php';
    require_once __DIR__.'/../assets/js/jpgraph/src/jpgraph_pie.php';
    require_once __DIR__.'/../assets/js/jpgraph/src/jpgraph_pie3d.php';

    // 1) abrir conexión
    $conex = (new Conexion())->getConex1();

    // 2) datos para gráfica Top 5 pedidos web
    $whereG = ['p.tipo = 2'];
    $paramsG = [];
    if ($start && $end) {
        $whereG[]       = 'p.fecha BETWEEN :sG AND :eG';
        $paramsG[':sG'] = "$start 00:00:00";
        $paramsG[':eG'] = "$end   23:59:59";
    }
    if ($prodId) {
        $whereG[]         = 'pd.id_producto = :pidG';
        $paramsG[':pidG'] = $prodId;
    }
    $sqlG = "
      SELECT prod.nombre producto, SUM(pd.cantidad) total
      FROM pedido p
      JOIN pedido_detalles pd ON pd.id_pedido = p.id_pedido
      JOIN productos prod     ON prod.id_producto = pd.id_producto
      WHERE ".implode(' AND ', $whereG)."
      GROUP BY prod.id_producto
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

    // 3) generar gráfico y codificar a base64
    $imgDir  = __DIR__.'/../assets/img/grafica_reportes/';
    $imgFile = $imgDir.'grafico_pedidoweb.png';
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

    // 4) consulta tabla de pedidos web
    $whereT = ['p.tipo = 2'];
    $paramsT = [];
    if ($start && $end) {
        $whereT[]       = 'p.fecha BETWEEN :sT AND :eT';
        $paramsT[':sT'] = "$start 00:00:00";
        $paramsT[':eT'] = "$end   23:59:59";
    }
    if ($prodId) {
        $whereT[]         = 'p.id_pedido IN (
                               SELECT id_pedido
                               FROM pedido_detalles
                               WHERE id_producto = :pidT
                             )';
        $paramsT[':pidT']= $prodId;
    }
    $sqlT = "
      SELECT
        p.id_pedido, p.fecha, p.estado, p.precio_total,
        p.referencia_bancaria, p.telefono_emisor,
        CONCAT(cl.nombre,' ',cl.apellido) AS cliente,
        mp.nombre AS metodo_pago,
        me.nombre AS metodo_entrega
      FROM pedido p
      LEFT JOIN cliente       cl ON p.id_persona    = cl.id_persona
      LEFT JOIN metodo_pago   mp ON p.id_metodopago = mp.id_metodopago
      LEFT JOIN metodo_entrega me ON p.id_entrega    = me.id_entrega
      WHERE ".implode(' AND ', $whereT)."
      ORDER BY p.fecha DESC
    ";
    $stmtT = $conex->prepare($sqlT);
    $stmtT->execute($paramsT);
    $rows = $stmtT->fetchAll(PDO::FETCH_ASSOC);

    // 5) cerrar conexión antes de generar HTML
    $conex = null;

    // 6) texto de filtros y fecha
    if (!$start && !$end && !$prodId) {
        $filtro = 'Registro general';
    } elseif ($start && !$end) {
        $filtro = 'Desde '.date('d/m/Y',strtotime($start));
    } elseif (!$start && $end) {
        $filtro = 'Hasta '.date('d/m/Y',strtotime($end));
    } else {
        $filtro = 'Desde '.date('d/m/Y',strtotime($start))
                 .' hasta '.date('d/m/Y',strtotime($end));
    }
    if ($prodId && isset($labels[0])) {
        $filtro .= " | Producto: {$labels[0]}";
    }
    $fechaGen = date('d/m/Y H:i');

    // 7) logo en base64
    $icon     = __DIR__.'/../assets/img/icon.PNG';
    $logoData = file_exists($icon)
              ? 'data:image/png;base64,'.base64_encode(file_get_contents($icon))
              : '';

    // 8) armar HTML
    $html = '<html><head><style>
      @page { margin:120px 50px 60px 50px; }
      body { margin:0; font-family:Arial,sans-serif; font-size:12px; }
      header {
        position:fixed; top:-110px; left:0; right:0; height:110px;
        text-align:center;
      }
      header h1 { margin:0; font-size:24px; }
      header p  { margin:4px 0 0; font-size:14px; color:#555; }
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
      th {
        background:#f36ca4; color:#fff;
      }
    </style></head><body>'
    . '<header>'
    . ($logoData? '<img src="'.$logoData.'" class="logo-icon" alt="Logo"/>' : '')
    . '<h1>LoveMakeup</h1>'
    . '<p>RIF: J-00000000</p>'
    . '</header>'
    . '<main>'
    . '<h1>Listado de Pedidos Web</h1>'
    . '<p><strong>Generado:</strong> '.$fechaGen.'</p>'
    . '<p><strong>Filtro:</strong> '.$filtro.'</p>'
    . (!empty($graf)
        ? '<h2>Top 5 Productos Más Vendidos</h2>
           <div style="text-align:center;"><img src="'.$graf.'" width="600"/></div>'
        : ''
      )
    . '<table><thead><tr>
         <th>Pedido</th><th>Fecha</th><th>Estado</th><th>Total</th>
         <th>Ref. Bancaria</th><th>Teléfono</th><th>Cliente</th>
         <th>Método Pago</th><th>Método Entrega</th>
       </tr></thead><tbody>';
    $estados = ['0'=>'Cancelado','1'=>'Pendiente','2'=>'Confirmado'];
    foreach ($rows as $r) {
        $d   = date('d/m/Y',strtotime($r['fecha']));
        $est = $estados[$r['estado']] ?? '';
        $tot = '$'.number_format($r['precio_total'],2);
        $html .= "<tr>
                    <td>{$r['id_pedido']}</td>
                    <td>{$d}</td>
                    <td>{$est}</td>
                    <td>{$tot}</td>
                    <td>".htmlspecialchars($r['referencia_bancaria'])."</td>
                    <td>".htmlspecialchars($r['telefono_emisor'])."</td>
                    <td>".htmlspecialchars($r['cliente'])."</td>
                    <td>".htmlspecialchars($r['metodo_pago'])."</td>
                    <td>".htmlspecialchars($r['metodo_entrega'])."</td>
                  </tr>";
    }
    $html .= '</tbody></table></main>'
           . '<footer>Página <span class="pageNumber"></span> de <span class="totalPages"></span></footer>'
           . '</body></html>';

    // 9) render y numeración
    $opts = new Options();
    $opts->set('isRemoteEnabled', true);
    $pdf  = new Dompdf($opts);
    $pdf->loadHtml($html);
    $pdf->setPaper('A4','portrait');
    $pdf->render();
    $canvas = $pdf->getCanvas();
    $w      = $canvas->get_width();
    $h      = $canvas->get_height();
    $fn     = $pdf->getFontMetrics()->get_font('helvetica','normal');
    $canvas->page_text($w/2 - 30, $h - 30,
                       "Página {PAGE_NUM} de {PAGE_COUNT}",
                       $fn, 10, [0,0,0], 0, 0.5);

    // 10) emitir PDF
    $pdf->stream('Reporte_PedidosWeb.pdf',['Attachment'=>false]);
}






public static function countCompra($start = null, $end = null, $prodId = null): int {
    $conex = (new Conexion())->getConex1();
    $where  = [];
    $params = [];

    if ($start && $end) {
      $where[]        = 'c.fecha_entrada BETWEEN :s AND :e';
      $params[':s']   = $start . ' 00:00:00';
      $params[':e']   = $end   . ' 23:59:59';
    }
    if ($prodId) {
      $where[]           = 'cd.id_producto = :pid';
      $params[':pid']    = $prodId;
    }
    $w = $where ? 'WHERE ' . implode(' AND ', $where) : '';

    $sql  = "
      SELECT COUNT(DISTINCT c.id_compra) AS cnt
      FROM compra c
      JOIN compra_detalles cd ON cd.id_compra = c.id_compra
      $w
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
public static function countVenta($start = null, $end = null, $prodId = null): int {
    $conex = (new Conexion())->getConex1();
    $where  = ['pe.tipo = 1'];
    $params = [];

    if ($start && $end) {
      $where[]        = 'pe.fecha BETWEEN :s AND :e';
      $params[':s']   = $start . ' 00:00:00';
      $params[':e']   = $end   . ' 23:59:59';
    }
    if ($prodId) {
      $where[]           = 'pd.id_producto = :pid';
      $params[':pid']    = $prodId;
    }
    $w = 'WHERE ' . implode(' AND ', $where);

    $sql  = "
      SELECT COUNT(DISTINCT pe.id_pedido) AS cnt
      FROM pedido pe
      JOIN pedido_detalles pd ON pd.id_pedido = pe.id_pedido
      $w
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
