<?php
require_once 'modelo/conexion.php';
require_once 'assets/dompdf/vendor/autoload.php';
use Dompdf\Dompdf;

class Reporte {


public static function compra($start = null, $end = null, $prodId = null): void {
    // 1) Includes y setup
    require_once 'modelo/conexion.php';
    require_once 'assets/dompdf/vendor/autoload.php';
    require_once __DIR__ . '/../assets/js/jpgraph/src/jpgraph.php';
    require_once __DIR__ . '/../assets/js/jpgraph/src/jpgraph_pie.php';
    require_once __DIR__ . '/../assets/js/jpgraph/src/jpgraph_pie3d.php';

    $cnx = (new Conexion())->getConex1();

    //
    // 2) Construir filtros para el gráfico
    //
    $whereG  = ['p.estatus = 1'];
    $paramsG = [];
    if ($start && $end) {
        $whereG[]       = 'c.fecha_entrada BETWEEN :sG AND :eG';
        $paramsG[':sG'] = $start . ' 00:00:00';
        $paramsG[':eG'] = $end   . ' 23:59:59';
    }
    if ($prodId) {
        $whereG[]        = 'cd.id_producto = :pidG';
        $paramsG[':pidG'] = $prodId;
    }
    $whereGSQL = implode(' AND ', $whereG);

    // 3) Query para el gráfico (Top 10 productos)
    $sqlG = "
      SELECT p.nombre AS producto, SUM(cd.cantidad) AS total_comprado
      FROM compra_detalles cd
      JOIN productos p ON cd.id_producto = p.id_producto
      JOIN compra c ON cd.id_compra = c.id_compra
      WHERE $whereGSQL
      GROUP BY p.id_producto, p.nombre
      ORDER BY total_comprado DESC
      LIMIT 10
    ";
    $stmtG = $cnx->prepare($sqlG);
    $stmtG->execute($paramsG);

    $labels = [];
    $data   = [];
    while ($r = $stmtG->fetch(PDO::FETCH_ASSOC)) {
        $labels[] = $r['producto'];
        $data[]   = (int)$r['total_comprado'];
    }

    // 4) Generar la imagen del gráfico 3D
    $imgDir  = __DIR__ . '/../assets/img/grafica_reportes/';
    $imgFile = $imgDir . 'grafico_compras.png';
    if (!is_dir($imgDir)) mkdir($imgDir, 0777, true);
    if (file_exists($imgFile)) unlink($imgFile);

    if (!empty($data)) {
        $graph = new \PieGraph(900, 500);
        $pie   = new \PiePlot3D($data);
        $pie->SetLegends($labels);
        $pie->SetCenter(0.5, 0.5);
        $pie->ExplodeSlice(1);
        $graph->Add($pie);
        $graph->Stroke($imgFile);
    }

    $graf = '';
    if (file_exists($imgFile)) {
        $graf = 'data:image/png;base64,' 
              . base64_encode(file_get_contents($imgFile));
    }

    //
    // 5) Construir filtros para la tabla
    //
    $whereT  = [];
    $paramsT = [];
    if ($start && $end) {
        $whereT[]        = 'c.fecha_entrada BETWEEN :sT AND :eT';
        $paramsT[':sT']  = $start . ' 00:00:00';
        $paramsT[':eT']  = $end   . ' 23:59:59';
    }
    if ($prodId) {
        $whereT[]         = 'cd.id_producto = :pidT';
        $paramsT[':pidT'] = $prodId;
    }
    $whereTSQL = !empty($whereT)
               ? 'AND ' . implode(' AND ', $whereT)
               : '';

    // 6) Query de la tabla (tu SQL original + filtros)
    $sqlT = "
      SELECT 
        c.id_compra,
        c.fecha_entrada,
        pr.nombre AS proveedor,
        GROUP_CONCAT(CONCAT(p.nombre,' (',cd.cantidad,'u)') SEPARATOR ', ') AS productos,
        SUM(cd.cantidad * cd.precio_unitario) AS total
      FROM compra c
      JOIN compra_detalles cd ON c.id_compra = cd.id_compra
      JOIN productos p        ON cd.id_producto = p.id_producto
      JOIN proveedor pr       ON c.id_proveedor = pr.id_proveedor
      WHERE 1 = 1
        $whereTSQL
      GROUP BY c.id_compra, c.fecha_entrada, pr.nombre
      ORDER BY c.id_compra DESC
    ";
    $stmtT = $cnx->prepare($sqlT);
    $stmtT->execute($paramsT);
    $rows  = $stmtT->fetchAll(PDO::FETCH_ASSOC);

    //
    // 7) Construir texto legible de filtros
    //
    if (!$start && !$end) {
        $filtroText = 'Registro general';
    } elseif ($start && !$end) {
        $filtroText = 'Desde ' . date('d/m/Y', strtotime($start));
    } elseif (!$start && $end) {
        $filtroText = 'Hasta ' . date('d/m/Y', strtotime($end));
    } else {
        $filtroText  = 'Desde ' . date('d/m/Y', strtotime($start))
                     . ' hasta ' . date('d/m/Y', strtotime($end));
    }

    //
    // 8) Armar el HTML completo para el PDF
    //
    $html = "<html><head><style>
      body{font-family:Arial;font-size:12px;}
      h1,h2{text-align:center;}
      p{margin:0 0 10px;}
      table{width:100%;border-collapse:collapse;margin-top:10px;}
      th,td{border:1px solid #000;padding:6px;text-align:center;}
      th{background:#f36ca4;color:#fff;}
    </style></head><body>
      <h1>Listado de Compras</h1>
      <p><strong>Filtro:</strong> $filtroText</p>";

    if ($graf) {
        $html .= "<h2>Top 10 Productos Comprados</h2>
        <div style='text-align:center;'>
          <img src=\"$graf\" width=\"600\"/>
        </div>";
    }

    $html .= "<table>
        <thead>
          <tr>
            <th>ID Compra</th>
            <th>Fecha Entrada</th>
            <th>Proveedor</th>
            <th>Productos</th>
            <th>Total</th>
          </tr>
        </thead><tbody>";

    foreach ($rows as $r) {
        $fechaCompra = date('d/m/Y', strtotime($r['fecha_entrada']));
        $totalFmt    = '$' . number_format($r['total'], 2);
        $html       .= "<tr>
            <td>{$r['id_compra']}</td>
            <td>{$fechaCompra}</td>
            <td>" . htmlspecialchars($r['proveedor']) . "</td>
            <td>" . htmlspecialchars($r['productos']) . "</td>
            <td>{$totalFmt}</td>
          </tr>";
    }

    $html .= "</tbody></table></body></html>";

    //
    // 9) Generar y enviar el PDF al navegador
    //
    $pdf = new Dompdf();
    $pdf->loadHtml($html);
    $pdf->setPaper('A4', 'portrait');
    $pdf->render();
    $pdf->stream('Reporte_Compras.pdf', ['Attachment' => false]);
}




public static function producto($start = null, $end = null, $prodId = null): void {
    // 1) includes y setup
    require_once 'modelo/conexion.php';
    require_once 'assets/dompdf/vendor/autoload.php';
    require_once __DIR__ . '/../assets/js/jpgraph/src/jpgraph.php';
    require_once __DIR__ . '/../assets/js/jpgraph/src/jpgraph_pie.php';
    require_once __DIR__ . '/../assets/js/jpgraph/src/jpgraph_pie3d.php';

    $cnx = (new Conexion())->getConex1();

    //
    // 2) Filtros para el gráfico (Top 10 stock)
    //
    $whereG  = ['p.estatus IN (1,2)'];
    $paramsG = [];

    // Rango de fechas (ajusta campo si es diferente)
    if ($start && $end) {
        $whereG[]        = 'p.fecha_ingreso BETWEEN :sG AND :eG';
        $paramsG[':sG']  = $start . ' 00:00:00';
        $paramsG[':eG']  = $end   . ' 23:59:59';
    }
    // Filtrar un único producto
    if ($prodId) {
        $whereG[]         = 'p.id_producto = :pidG';
        $paramsG[':pidG'] = $prodId;
    }

    $whereGSQL = implode(' AND ', $whereG);

    $sqlG = "
      SELECT p.nombre, p.stock_disponible
      FROM productos p
      JOIN categoria c ON p.id_categoria = c.id_categoria
      WHERE $whereGSQL
      ORDER BY p.stock_disponible DESC
      LIMIT 10
    ";
    $stmtG = $cnx->prepare($sqlG);
    $stmtG->execute($paramsG);

    $labels = $data = [];
    while ($row = $stmtG->fetch(PDO::FETCH_ASSOC)) {
        $labels[] = $row['nombre'];
        $data[]   = (int)$row['stock_disponible'];
    }

    // 3) Generar gráfico
    $imgDir  = __DIR__ . '/../assets/img/grafica_reportes/';
    $imgFile = $imgDir . 'grafico_productos.png';
    if (!is_dir($imgDir)) mkdir($imgDir, 0777, true);
    if (file_exists($imgFile)) unlink($imgFile);

    if (!empty($data)) {
        $graph = new \PieGraph(900, 500);
        $pie   = new \PiePlot3D($data);
        $pie->SetLegends($labels);
        $pie->SetCenter(0.5, 0.5);
        $pie->ExplodeSlice(1);
        $graph->Add($pie);
        $graph->Stroke($imgFile);
    }

    $graf = file_exists($imgFile)
          ? 'data:image/png;base64,' . base64_encode(file_get_contents($imgFile))
          : '';

    //
    // 4) Filtros para la tabla
    //
    $whereT  = [];
    $paramsT = [];

    if ($start && $end) {
        $whereT[]        = 'p.fecha_ingreso BETWEEN :sT AND :eT';
        $paramsT[':sT']  = $start . ' 00:00:00';
        $paramsT[':eT']  = $end   . ' 23:59:59';
    }
    if ($prodId) {
        $whereT[]         = 'p.id_producto = :pidT';
        $paramsT[':pidT'] = $prodId;
    }

    $whereTSQL = !empty($whereT)
               ? 'AND ' . implode(' AND ', $whereT)
               : '';

    $sqlT = "
      SELECT 
        p.nombre,
        p.descripcion,
        p.marca,
        p.precio_detal,
        p.precio_mayor,
        p.stock_disponible,
        c.nombre AS nombre_categoria
      FROM productos p
      JOIN categoria c ON p.id_categoria = c.id_categoria
      WHERE p.estatus IN (1,2)
        $whereTSQL
      ORDER BY p.nombre ASC
    ";
    $stmtT = $cnx->prepare($sqlT);
    $stmtT->execute($paramsT);
    $rows  = $stmtT->fetchAll(PDO::FETCH_ASSOC);

    //
    // 5) Texto legible de filtros
    //
    if (!$start && !$end && !$prodId) {
        $filtroText = 'Registro general';
    } elseif ($prodId && !$start && !$end) {
        // Obtén nombre de producto para mostrar
        $prodName   = htmlspecialchars($rows[0]['nombre'] ?? '—');
        $filtroText = "Producto: $prodName";
    } else {
        // Combinar fecha y/o producto
        $parts = [];
        if ($start && $end) {
            $parts[] = 'Desde ' . date('d/m/Y', strtotime($start))
                     . ' hasta ' . date('d/m/Y', strtotime($end));
        } elseif ($start) {
            $parts[] = 'Desde ' . date('d/m/Y', strtotime($start));
        } elseif ($end) {
            $parts[] = 'Hasta ' . date('d/m/Y', strtotime($end));
        }
        if ($prodId) {
            $parts[] = "Producto: $prodName";
        }
        $filtroText = implode(' | ', $parts);
    }

    //
    // 6) Armar el HTML
    //
    $html = "<html><head><style>
      body{font-family:Arial;font-size:12px;}
      h1,h2{text-align:center;}
      p{margin:0 0 10px;}
      table{width:100%;border-collapse:collapse;margin-top:10px;}
      th,td{border:1px solid #000;padding:6px;text-align:center;}
      th{background:#f36ca4;color:#fff;}
    </style></head><body>
      <h1>Listado de Productos</h1>
      <p><strong>Filtro:</strong> $filtroText</p>";

    if ($graf) {
        $html .= "<h2>Top 10 Productos por Stock</h2>
        <div style='text-align:center;'>
          <img src=\"$graf\" width=\"600\"/>
        </div>";
    }

    $html .= "<table>
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Marca</th>
            <th>Precio Detal</th>
            <th>Precio Mayor</th>
            <th>Stock</th>
            <th>Categoría</th>
          </tr>
        </thead><tbody>";

    foreach ($rows as $r) {
        $html .= "<tr>
            <td>" . htmlspecialchars($r['nombre']) . "</td>
            <td>" . htmlspecialchars($r['descripcion']) . "</td>
            <td>" . htmlspecialchars($r['marca']) . "</td>
            <td>" . number_format($r['precio_detal'], 2) . "</td>
            <td>" . number_format($r['precio_mayor'], 2) . "</td>
            <td>" . (int)$r['stock_disponible'] . "</td>
            <td>" . htmlspecialchars($r['nombre_categoria']) . "</td>
          </tr>";
    }

    $html .= "</tbody></table></body></html>";

    //
    // 7) Generar y stream
    //
    $pdf = new Dompdf();
    $pdf->loadHtml($html);
    $pdf->setPaper('A4', 'portrait');
    $pdf->render();
    $pdf->stream('Reporte_Productos.pdf', ['Attachment' => false]);
}




public static function venta($start = null, $end = null, $prodId = null): void {
    // 1) includes y setup
    require_once 'modelo/conexion.php';
    require_once 'assets/dompdf/vendor/autoload.php';
    require_once __DIR__ . '/../assets/js/jpgraph/src/jpgraph.php';
    require_once __DIR__ . '/../assets/js/jpgraph/src/jpgraph_pie.php';
    require_once __DIR__ . '/../assets/js/jpgraph/src/jpgraph_pie3d.php';

    $cnx = (new Conexion())->getConex1();

    //
    // 2) Filtros para el gráfico (Top 5 vendidos)
    //
    $whereG  = ['pe.estado = 2', 'pe.tipo = 1'];
    $paramsG = [];

    if ($start && $end) {
        $whereG[]        = 'pe.fecha BETWEEN :sG AND :eG';
        $paramsG[':sG']  = $start . ' 00:00:00';
        $paramsG[':eG']  = $end   . ' 23:59:59';
    }
    if ($prodId) {
        $whereG[]         = 'pd.id_producto = :pidG';
        $paramsG[':pidG'] = $prodId;
    }
    $whereGSQL = implode(' AND ', $whereG);

    // Query para el gráfico
    $sqlG = "
      SELECT p.nombre AS producto, SUM(pd.cantidad) AS total
      FROM pedido_detalles pd
      JOIN productos p ON pd.id_producto = p.id_producto
      JOIN pedido pe   ON pd.id_pedido   = pe.id_pedido
      WHERE $whereGSQL
      GROUP BY pd.id_producto, p.nombre
      ORDER BY total DESC
      LIMIT 5
    ";
    $stmtG = $cnx->prepare($sqlG);
    $stmtG->execute($paramsG);

    $labels = $data = [];
    while ($r = $stmtG->fetch(PDO::FETCH_ASSOC)) {
        $labels[] = $r['producto'];
        $data[]   = (int)$r['total'];
    }

    // Generar gráfico
    $imgDir  = __DIR__ . '/../assets/img/grafica_reportes/';
    $imgFile = $imgDir . 'grafico_ventas.png';
    if (!is_dir($imgDir)) mkdir($imgDir, 0777, true);
    if (file_exists($imgFile)) unlink($imgFile);
    if (!empty($data)) {
        $graph = new \PieGraph(900, 500);
        $pie   = new \PiePlot3D($data);
        $pie->SetLegends($labels);
        $pie->SetCenter(0.5, 0.5);
        $pie->ExplodeSlice(1);
        $graph->Add($pie);
        $graph->Stroke($imgFile);
    }
    $graf = file_exists($imgFile)
          ? 'data:image/png;base64,' . base64_encode(file_get_contents($imgFile))
          : '';

    //
    // 3) Filtros para la tabla
    //
    $whereT  = ['pe.tipo = 1'];
    $paramsT = [];

    if ($start && $end) {
        $whereT[]        = 'pe.fecha BETWEEN :sT AND :eT';
        $paramsT[':sT']  = $start . ' 00:00:00';
        $paramsT[':eT']  = $end   . ' 23:59:59';
    }
    if ($prodId) {
        // sólo pedidos que incluyan ese producto
        $whereT[]         = 'pe.id_pedido IN (
                               SELECT id_pedido 
                               FROM pedido_detalles 
                               WHERE id_producto = :pidT
                             )';
        $paramsT[':pidT'] = $prodId;
    }
    $whereTSQL = !empty($whereT)
               ? 'AND ' . implode(' AND ', $whereT)
               : '';

    // Query para la tabla de ventas
    $sqlT = "
      SELECT 
        CONCAT(cl.nombre,' ',cl.apellido) AS cliente,
        pe.fecha,
        pe.estado,
        pe.precio_total,
        mp.nombre AS metodo_pago,
        me.nombre AS metodo_entrega
      FROM pedido pe
      JOIN cliente       cl ON pe.id_persona = cl.id_persona
      JOIN metodo_pago   mp ON pe.id_metodopago = mp.id_metodopago
      JOIN metodo_entrega me ON pe.id_entrega = me.id_entrega
      WHERE pe.tipo = 1
        $whereTSQL
      ORDER BY pe.fecha DESC
    ";
    $stmtT = $cnx->prepare($sqlT);
    $stmtT->execute($paramsT);
    $rows  = $stmtT->fetchAll(PDO::FETCH_ASSOC);

    //
    // 4) Texto legible de filtros
    //
    if (!$start && !$end && !$prodId) {
        $filtroText = 'Registro general';
    } elseif ($start && !$end) {
        $filtroText = 'Desde ' . date('d/m/Y', strtotime($start));
    } elseif (!$start && $end) {
        $filtroText = 'Hasta ' . date('d/m/Y', strtotime($end));
    } else {
        $filtroText  = 'Desde ' . date('d/m/Y', strtotime($start))
                     . ' hasta ' . date('d/m/Y', strtotime($end));
    }
    if ($prodId) {
        $nombreProd = '';
        foreach ($labels as $idx => $nm) {
            if ($data[$idx] > 0) { $nombreProd = $nm; break; }
        }
        $filtroText .= ($filtroText ? ' | ' : '') 
                     . "Producto: $nombreProd";
    }

    //
    // 5) Armar HTML del PDF
    //
    $html = "<html><head><style>
      body{font-family:Arial;font-size:12px;}
      h1,h2{text-align:center;}
      p{margin:0 0 10px;}
      table{width:100%;border-collapse:collapse;margin-top:10px;}
      th,td{border:1px solid #000;padding:6px;text-align:center;}
      th{background:#f36ca4;color:#fff;}
    </style></head><body>
      <h1>Listado de Ventas</h1>
      <p><strong>Filtro:</strong> $filtroText</p>";

    if ($graf) {
        $html .= "<h2>Top 5 Productos Más Vendidos</h2>
        <div style='text-align:center;'>
          <img src=\"$graf\" width=\"600\"/>
        </div>";
    }

    $estados = ['0'=>'Cancelado','1'=>'Pendiente','2'=>'Entregado',
                '3'=>'En camino','4'=>'Enviado'];

    $html .= "<table>
        <thead>
          <tr>
            <th>Cliente</th>
            <th>Fecha</th>
            <th>Estado</th>
            <th>Total</th>
            <th>Método Pago</th>
            <th>Método Entrega</th>
          </tr>
        </thead><tbody>";

    foreach ($rows as $r) {
        $fechaV  = date('d/m/Y', strtotime($r['fecha']));
        $estadoT = $estados[$r['estado']] ?? 'Desconocido';
        $totalV  = '$' . number_format($r['precio_total'], 2);
        $html   .= "<tr>
            <td>" . htmlspecialchars($r['cliente']) . "</td>
            <td>$fechaV</td>
            <td>$estadoT</td>
            <td>$totalV</td>
            <td>" . htmlspecialchars($r['metodo_pago']) . "</td>
            <td>" . htmlspecialchars($r['metodo_entrega']) . "</td>
          </tr>";
    }

    $html .= "</tbody></table></body></html>";

    //
    // 6) Render y envío
    //
    $pdf = new Dompdf();
    $pdf->loadHtml($html);
    $pdf->setPaper('A4','portrait');
    $pdf->render();
    $pdf->stream('Reporte_Ventas.pdf',['Attachment'=>false]);
}




public static function proveedor($start = null, $end = null, $provId = null): void {
    // 1) includes y setup
    require_once 'modelo/conexion.php';
    require_once 'assets/dompdf/vendor/autoload.php';
    require_once __DIR__ . '/../assets/js/jpgraph/src/jpgraph.php';
    require_once __DIR__ . '/../assets/js/jpgraph/src/jpgraph_pie.php';
    require_once __DIR__ . '/../assets/js/jpgraph/src/jpgraph_pie3d.php';

    $cnx = (new Conexion())->getConex1();

    //
    // 2) Filtros para el gráfico (Top 5 proveedores con más compras)
    //
    $whereG  = ['pr.estatus = 1'];
    $paramsG = [];

    if ($start && $end) {
        $whereG[]       = 'c.fecha_entrada BETWEEN :sG AND :eG';
        $paramsG[':sG'] = $start . ' 00:00:00';
        $paramsG[':eG'] = $end   . ' 23:59:59';
    }
    if ($provId) {
        $whereG[]         = 'pr.id_proveedor = :pidG';
        $paramsG[':pidG'] = $provId;
    }
    $whereGSQL = implode(' AND ', $whereG);

    $sqlG = "
      SELECT pr.nombre, COUNT(c.id_compra) AS total_compras
      FROM compra c
      JOIN proveedor pr ON c.id_proveedor = pr.id_proveedor
      WHERE $whereGSQL
      GROUP BY pr.id_proveedor, pr.nombre
      ORDER BY total_compras DESC
      LIMIT 5
    ";
    $stmtG = $cnx->prepare($sqlG);
    $stmtG->execute($paramsG);

    $labels = [];
    $data   = [];
    while ($r = $stmtG->fetch(PDO::FETCH_ASSOC)) {
        $labels[] = $r['nombre'];
        $data[]   = (int)$r['total_compras'];
    }

    // 3) Generar gráfico 3D
    $imgDir  = __DIR__ . '/../assets/img/grafica_reportes/';
    $imgFile = $imgDir . 'grafico_proveedores.png';
    if (!is_dir($imgDir)) mkdir($imgDir, 0777, true);
    if (file_exists($imgFile)) unlink($imgFile);

    if ($data) {
        $graph = new \PieGraph(900, 500);
        $pie   = new \PiePlot3D($data);
        $pie->SetLegends($labels);
        $pie->SetCenter(0.5, 0.5);
        $pie->ExplodeSlice(1);
        $graph->Add($pie);
        $graph->Stroke($imgFile);
    }
    $graf = file_exists($imgFile)
          ? 'data:image/png;base64,' . base64_encode(file_get_contents($imgFile))
          : '';

    //
    // 4) Filtros para la tabla de proveedores
    //
    $whereT  = [];
    $paramsT = [];

    if ($start && $end) {
        $whereT[]        = 'c.fecha_entrada BETWEEN :sT AND :eT';
        $paramsT[':sT']  = $start . ' 00:00:00';
        $paramsT[':eT']  = $end   . ' 23:59:59';
    }
    if ($provId) {
        $whereT[]         = 'pr.id_proveedor = :pidT';
        $paramsT[':pidT'] = $provId;
    }
    $whereTSQL = !empty($whereT)
               ? 'AND ' . implode(' AND ', $whereT)
               : '';

    $sqlT = "
      SELECT DISTINCT
        pr.nombre,
        pr.tipo_documento,
        pr.numero_documento,
        pr.correo,
        pr.telefono,
        pr.direccion
      FROM proveedor pr
      JOIN compra c ON c.id_proveedor = pr.id_proveedor
      WHERE pr.estatus = 1
        $whereTSQL
      ORDER BY pr.nombre ASC
    ";
    $stmtT = $cnx->prepare($sqlT);
    $stmtT->execute($paramsT);
    $rows  = $stmtT->fetchAll(PDO::FETCH_ASSOC);

    //
    // 5) Construir texto legible de filtros
    //
    if (!$start && !$end && !$provId) {
        $filtroText = 'Registro general';
    } elseif ($provId && !$start && !$end) {
        // Obtener nombre del proveedor filtrado
        $provName   = htmlspecialchars($rows[0]['nombre'] ?? '—');
        $filtroText = "Proveedor: $provName";
    } else {
        if ($start && $end) {
            $filtroText = 'Desde ' . date('d/m/Y', strtotime($start))
                        . ' hasta ' . date('d/m/Y', strtotime($end));
        } elseif ($start) {
            $filtroText = 'Desde ' . date('d/m/Y', strtotime($start));
        } else {
            $filtroText = 'Hasta ' . date('d/m/Y', strtotime($end));
        }
        if ($provId) {
            $provName = htmlspecialchars($rows[0]['nombre'] ?? '—');
            $filtroText .= " | Proveedor: $provName";
        }
    }

    //
    // 6) Armar el HTML
    //
    $html = "<html><head><style>
      body{font-family:Arial;font-size:12px;}
      h1,h2{text-align:center;}
      p{margin:0 0 10px;}
      table{width:100%;border-collapse:collapse;margin-top:10px;}
      th,td{border:1px solid #000;padding:6px;text-align:center;}
      th{background:#f36ca4;color:#fff;}
    </style></head><body>
      <h1>Listado de Proveedores</h1>
      <p><strong>Filtro:</strong> $filtroText</p>";

    if ($graf) {
        $html .= "<h2>Top 5 Proveedores con Más Compras</h2>
        <div style='text-align:center;'>
          <img src=\"$graf\" width=\"600\"/>
        </div>";
    }

    $html .= "<table>
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Tipo Doc.</th>
            <th>Documento</th>
            <th>Correo</th>
            <th>Teléfono</th>
            <th>Dirección</th>
          </tr>
        </thead><tbody>";

    foreach ($rows as $r) {
        $html .= "<tr>
            <td>" . htmlspecialchars($r['nombre']) . "</td>
            <td>" . htmlspecialchars($r['tipo_documento']) . "</td>
            <td>" . htmlspecialchars($r['numero_documento']) . "</td>
            <td>" . htmlspecialchars($r['correo']) . "</td>
            <td>" . htmlspecialchars($r['telefono']) . "</td>
            <td>" . htmlspecialchars($r['direccion']) . "</td>
          </tr>";
    }

    $html .= "</tbody></table></body></html>";

    //
    // 7) Renderizar y enviar PDF
    //
    $pdf = new Dompdf();
    $pdf->loadHtml($html);
    $pdf->setPaper('A4', 'portrait');
    $pdf->render();
    $pdf->stream('Reporte_Proveedores.pdf', ['Attachment' => false]);
}



// En modelo/reporte.php
public static function pedidoWeb($start = null, $end = null, $prodId = null): void {
    // 1) includes y setup
    require_once 'modelo/conexion.php';
    require_once 'assets/dompdf/vendor/autoload.php';
    require_once __DIR__ . '/../assets/js/jpgraph/src/jpgraph.php';
    require_once __DIR__ . '/../assets/js/jpgraph/src/jpgraph_pie.php';
    require_once __DIR__ . '/../assets/js/jpgraph/src/jpgraph_pie3d.php';

    $cnx = (new Conexion())->getConex1();

    //
    // 2) Filtros para el gráfico (Top 5 productos en pedidos web)
    //
    $whereG  = ['p.tipo = 2'];
    $paramsG = [];

    if ($start && $end) {
        $whereG[]        = 'p.fecha BETWEEN :sG AND :eG';
        $paramsG[':sG']  = $start . ' 00:00:00';
        $paramsG[':eG']  = $end   . ' 23:59:59';
    }
    if ($prodId) {
        $whereG[]         = 'pd.id_producto = :pidG';
        $paramsG[':pidG'] = $prodId;
    }
    $whereGSQL = implode(' AND ', $whereG);

    $sqlG = "
      SELECT prod.nombre AS producto, SUM(pd.cantidad) AS total
      FROM pedido p
      JOIN pedido_detalles pd ON pd.id_pedido = p.id_pedido
      JOIN productos prod   ON prod.id_producto = pd.id_producto
      WHERE $whereGSQL
      GROUP BY prod.id_producto, prod.nombre
      ORDER BY total DESC
      LIMIT 5
    ";
    $stmtG = $cnx->prepare($sqlG);
    $stmtG->execute($paramsG);

    $labels = $data = [];
    while ($r = $stmtG->fetch(PDO::FETCH_ASSOC)) {
        $labels[] = $r['producto'];
        $data[]   = (int)$r['total'];
    }

    // 3) Generar gráfico 3D
    $imgDir  = __DIR__ . '/../assets/img/grafica_reportes/';
    $imgFile = $imgDir . 'grafico_pedidoweb.png';
    if (!is_dir($imgDir)) mkdir($imgDir, 0777, true);
    if (file_exists($imgFile)) unlink($imgFile);

    if (!empty($data)) {
        $graph = new \PieGraph(900, 500);
        $pie   = new \PiePlot3D($data);
        $pie->SetLegends($labels);
        $pie->SetCenter(0.5, 0.5);
        $pie->ExplodeSlice(1);
        $graph->Add($pie);
        $graph->Stroke($imgFile);
    }
    $graf = file_exists($imgFile)
          ? 'data:image/png;base64,' . base64_encode(file_get_contents($imgFile))
          : '';

    //
    // 4) Filtros para la tabla (pedidos web)
    //
    $whereT  = ['p.tipo = 2'];
    $paramsT = [];

    if ($start && $end) {
        $whereT[]        = 'p.fecha BETWEEN :sT AND :eT';
        $paramsT[':sT']  = $start . ' 00:00:00';
        $paramsT[':eT']  = $end   . ' 23:59:59';
    }
    if ($prodId) {
        $whereT[]         = 'p.id_pedido IN (
                               SELECT id_pedido
                               FROM pedido_detalles
                               WHERE id_producto = :pidT
                             )';
        $paramsT[':pidT'] = $prodId;
    }
    $whereTSQL = 'AND ' . implode(' AND ', $whereT);

    $sqlT = "
      SELECT
        p.id_pedido,
        p.fecha,
        p.estado,
        p.precio_total,
        p.referencia_bancaria,
        p.telefono_emisor,
        CONCAT(cl.nombre,' ',cl.apellido) AS cliente,
        mp.nombre AS metodo_pago,
        me.nombre AS metodo_entrega
      FROM pedido p
      LEFT JOIN cliente       cl ON p.id_persona    = cl.id_persona
      LEFT JOIN metodo_pago   mp ON p.id_metodopago = mp.id_metodopago
      LEFT JOIN metodo_entrega me ON p.id_entrega    = me.id_entrega
      WHERE 1=1
        $whereTSQL
      ORDER BY p.fecha DESC
    ";
    $stmtT = $cnx->prepare($sqlT);
    $stmtT->execute($paramsT);
    $rows  = $stmtT->fetchAll(PDO::FETCH_ASSOC);

    //
    // 5) Texto legible de filtros
    //
    if (!$start && !$end && !$prodId) {
        $filtroText = 'Registro general';
    } elseif ($start && !$end) {
        $filtroText = 'Desde ' . date('d/m/Y', strtotime($start));
    } elseif (!$start && $end) {
        $filtroText = 'Hasta ' . date('d/m/Y', strtotime($end));
    } else {
        $filtroText  = 'Desde ' . date('d/m/Y', strtotime($start))
                     . ' hasta ' . date('d/m/Y', strtotime($end));
    }
    if ($prodId) {
        $prodName   = htmlspecialchars($labels[0] ?? '—');
        $filtroText .= ($filtroText ? ' | ' : '') . "Producto: $prodName";
    }

    //
    // 6) Armar HTML del PDF
    //
    $html = "<html><head><style>
      body{font-family:Arial;font-size:12px;}
      h1,h2{text-align:center;}
      p{margin:0 0 10px;}
      table{width:100%;border-collapse:collapse;margin-top:10px;}
      th,td{border:1px solid #000;padding:6px;text-align:center;}
      th{background:#f36ca4;color:#fff;}
    </style></head><body>
      <h1>Listado de Pedidos Web</h1>
      <p><strong>Filtro:</strong> $filtroText</p>";

    if ($graf) {
        $html .= "<h2>Top 5 Productos Más Vendidos</h2>
        <div style='text-align:center;'>
          <img src=\"$graf\" width=\"600\"/>
        </div>";
    }

    $estados = ['0'=>'Cancelado','1'=>'Pendiente','2'=>'Confirmado'];

    $html .= "<table>
        <thead>
          <tr>
            <th>Pedido</th>
            <th>Fecha</th>
            <th>Estado</th>
            <th>Total</th>
            <th>Ref. Bancaria</th>
            <th>Teléfono</th>
            <th>Cliente</th>
            <th>Método Pago</th>
            <th>Método Ent.</th>
          </tr>
        </thead><tbody>";

    foreach ($rows as $r) {
        $fechaP = date('d/m/Y', strtotime($r['fecha']));
        $estado = $estados[$r['estado']] ?? 'Desconocido';
        $total  = '$' . number_format($r['precio_total'], 2);
        $html  .= "<tr>
            <td>{$r['id_pedido']}</td>
            <td>{$fechaP}</td>
            <td>{$estado}</td>
            <td>{$total}</td>
            <td>".htmlspecialchars($r['referencia_bancaria'])."</td>
            <td>".htmlspecialchars($r['telefono_emisor'])."</td>
            <td>".htmlspecialchars($r['cliente'])."</td>
            <td>".htmlspecialchars($r['metodo_pago'])."</td>
            <td>".htmlspecialchars($r['metodo_entrega'])."</td>
          </tr>";
    }

    $html .= "</tbody></table></body></html>";

    //
    // 7) Render y salida
    //
    $pdf = new Dompdf();
    $pdf->loadHtml($html);
    $pdf->setPaper('A4','portrait');
    $pdf->render();
    $pdf->stream('Reporte_PedidosWeb.pdf', ['Attachment' => false]);
}

}
