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
    $catId   = null,
    $provId  = null,
    $montoMin = null,
    $montoMax = null
): void {
    // 1) Guardar valores originales
    $origStart = $start;
    $origEnd   = $end;

    // 2) Si solo hay inicio → fin = hoy
    if ($origStart && !$origEnd) {
        $end = date('Y-m-d');
    }

    // 3) Dependencias
    require_once 'modelo/conexion.php';
    require_once 'assets/dompdf/vendor/autoload.php';
    require_once __DIR__ . '/../assets/js/jpgraph/src/jpgraph.php';
    require_once __DIR__ . '/../assets/js/jpgraph/src/jpgraph_pie.php';
    require_once __DIR__ . '/../assets/js/jpgraph/src/jpgraph_pie3d.php';
    

    $conex = (new Conexion())->getConex1();

    try {
        $conex->beginTransaction();

        // — Gráfico Top 10 productos comprados —
        $whereG = []; $paramsG = [];

        // a) Sólo inicio
        if ($origStart && !$origEnd) {
            $whereG[]       = 'c.fecha_entrada >= :sG';
            $paramsG[':sG'] = "$start 00:00:00";
        }
        // b) Sólo fin
        elseif (!$origStart && $origEnd) {
            $whereG[]       = 'c.fecha_entrada <= :eG';
            $paramsG[':eG'] = "$end   23:59:59";
        }
        // c) Ambas fechas
        elseif ($origStart && $origEnd) {
            $whereG[]       = 'c.fecha_entrada BETWEEN :sG AND :eG';
            $paramsG[':sG'] = "$start 00:00:00";
            $paramsG[':eG'] = "$end   23:59:59";
        }

        if ($prodId) {
            $whereG[]         = 'cd.id_producto = :pidG';
            $paramsG[':pidG'] = $prodId;
        }
        if ($catId) {
            $whereG[]         = 'p.id_categoria = :catG';
            $paramsG[':catG'] = $catId;
        }
        if ($provId) {
            $whereG[]         = 'c.id_proveedor = :provG';
            $paramsG[':provG'] = $provId;
        }

        // Filtros de montos - usar HAVING para funciones agregadas
        $havingG = [];
        if ($montoMin !== null) {
            $havingG[]       = 'SUM(cd.cantidad * cd.precio_unitario) >= :montoMinG';
            $paramsG[':montoMinG'] = $montoMin;
        }
        if ($montoMax !== null) {
            $havingG[]       = 'SUM(cd.cantidad * cd.precio_unitario) <= :montoMaxG';
            $paramsG[':montoMaxG'] = $montoMax;
        }

        $sqlG = "
          SELECT p.nombre AS producto, SUM(cd.cantidad) AS total
            FROM compra_detalles cd
            JOIN productos p ON p.id_producto = cd.id_producto
            JOIN compra    c ON c.id_compra   = cd.id_compra
           " . ($whereG
                 ? 'WHERE '.implode(' AND ', $whereG)
                 : ''
           ) . "
          GROUP BY p.id_producto
           " . ($havingG ? 'HAVING ' . implode(' AND ', $havingG) : '') . "
          ORDER BY total DESC
          LIMIT 10
        ";
        $stmtG = $conex->prepare($sqlG);
        $stmtG->execute($paramsG);

        $labels = []; $data = [];
        while ($r = $stmtG->fetch(PDO::FETCH_ASSOC)) {
            $labels[] = htmlspecialchars($r['producto']);
            $data[]   = (int)$r['total'];
        }

        // Generar gráfico.png
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

        // — Tabla de compras con categoría —
        $whereT = []; $paramsT = [];

        // a) Sólo inicio
        if ($origStart && !$origEnd) {
            $whereT[]        = 'c.fecha_entrada >= :sT';
            $paramsT[':sT']  = "$start 00:00:00";
        }
        // b) Sólo fin
        elseif (!$origStart && $origEnd) {
            $whereT[]        = 'c.fecha_entrada <= :eT';
            $paramsT[':eT']  = "$end   23:59:59";
        }
        // c) Ambas fechas
        elseif ($origStart && $origEnd) {
            $whereT[]        = 'c.fecha_entrada BETWEEN :sT AND :eT';
            $paramsT[':sT']  = "$start 00:00:00";
            $paramsT[':eT']  = "$end   23:59:59";
        }

        if ($prodId) {
            $whereT[]        = 'cd.id_producto = :pidT';
            $paramsT[':pidT']= $prodId;
        }
        if ($catId) {
            $whereT[]        = 'p.id_categoria = :catT';
            $paramsT[':catT']= $catId;
        }
        if ($provId) {
            $whereT[]        = 'c.id_proveedor = :provT';
            $paramsT[':provT']= $provId;
        }

        // Filtros de montos - usar HAVING porque es una función agregada
        $havingT = [];
        if ($montoMin !== null) {
            $havingT[]       = 'SUM(cd.cantidad * cd.precio_unitario) >= :montoMinT';
            $paramsT[':montoMinT'] = $montoMin;
        }
        if ($montoMax !== null) {
            $havingT[]       = 'SUM(cd.cantidad * cd.precio_unitario) <= :montoMaxT';
            $paramsT[':montoMaxT'] = $montoMax;
        }

        $sqlT = "
          SELECT
            c.fecha_entrada,
            pr.nombre AS proveedor,
            GROUP_CONCAT(
              p.nombre,' (',cd.cantidad,'u)'
              ORDER BY cd.cantidad DESC SEPARATOR ', '
            ) AS productos,
            GROUP_CONCAT(
              DISTINCT cat.nombre
              ORDER BY cat.nombre SEPARATOR ', '
            ) AS categorias,
            SUM(cd.cantidad * cd.precio_unitario) AS total
          FROM compra c
          JOIN compra_detalles cd ON cd.id_compra = c.id_compra
          JOIN productos        p  ON p.id_producto = cd.id_producto
          JOIN categoria        cat ON cat.id_categoria = p.id_categoria
          JOIN proveedor        pr ON pr.id_proveedor = c.id_proveedor
           " . ($whereT
                 ? 'WHERE '.implode(' AND ', $whereT)
                 : ''
           ) . "
          GROUP BY c.fecha_entrada, pr.nombre
           " . ($havingT ? 'HAVING ' . implode(' AND ', $havingT) : '') . "
          ORDER BY total DESC
        ";
        $stmtT = $conex->prepare($sqlT);
        $stmtT->execute($paramsT);
        $rows  = $stmtT->fetchAll(PDO::FETCH_ASSOC);

        // — Texto de filtros —
        if (!$origStart && !$origEnd) {
            $filtro = 'Registro general';
        }
        elseif ($origStart && !$origEnd) {
            $filtro = 'Desde '.date('d/m/Y',strtotime($origStart))
                    .' hasta '.date('d/m/Y');
        }
        elseif (!$origStart && $origEnd) {
            $filtro = 'Hasta '.date('d/m/Y',strtotime($origEnd));
        }
        elseif ($origStart === $origEnd) {
            $filtro = 'Reporte del '.date('d/m/Y',strtotime($origStart));
        }
        else {
            $filtro = 'Desde '.date('d/m/Y',strtotime($origStart))
                    .' hasta '.date('d/m/Y',strtotime($origEnd));
        }
        if ($prodId) {
            $pSt = $conex->prepare(
                'SELECT nombre FROM productos WHERE id_producto = :pid'
            );
            $pSt->execute([':pid' => $prodId]);
            $filtro .= ' | Producto: '.htmlspecialchars($pSt->fetchColumn());
        }
        if ($catId) {
            $cSt = $conex->prepare(
                'SELECT nombre FROM categoria WHERE id_categoria = :cid'
            );
            $cSt->execute([':cid' => $catId]);
            $filtro .= ' | Categoría: '.htmlspecialchars($cSt->fetchColumn());
        }
        if ($provId) {
            $provSt = $conex->prepare(
                'SELECT nombre FROM proveedor WHERE id_proveedor = :prov'
            );
            $provSt->execute([':prov' => $provId]);
            $filtro .= ' | Proveedor: '.htmlspecialchars($provSt->fetchColumn());
        }

        // — Armar y emitir PDF —
        $fechaGen = date('d/m/Y H:i:s');
        $icon     = __DIR__ . '/../assets/img/icon.PNG';
        $logoData = file_exists($icon)
                  ? 'data:image/png;base64,'.base64_encode(file_get_contents($icon))
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
          . '<h1>LoveMakeup</h1><p>RIF: J-505434403</p>'
          . '</header><main>'
          . '<h1>Listado de Compras</h1>'
          . "<p><strong>Generado:</strong> {$fechaGen}</p>"
          . "<p><strong>Filtro:</strong> {$filtro}</p>"
          . (!empty($graf)
              ? '<h2>Top 10 Productos Comprados</h2>
                 <div style="text-align:center"><img src="'.$graf.'" width="600"/></div>'
              : '')
          . '<table><thead><tr>'
          . '<th>Fecha</th>'
          . '<th>Proveedor</th>'
          . '<th>Productos</th>'
          . '<th>Categorías</th>'
          . '<th>Total</th>'
          . '</tr></thead><tbody>';
        foreach ($rows as $r) {
            $d = date('d/m/Y',strtotime($r['fecha_entrada']));
            $t = '$'.number_format($r['total'],2);
            $html .= "<tr>
                        <td>{$d}</td>
                        <td>".htmlspecialchars($r['proveedor'])."</td>
                        <td>".htmlspecialchars($r['productos'])."</td>
                        <td>".htmlspecialchars($r['categorias'])."</td>
                        <td>{$t}</td>
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
        $pdf->stream('Reporte_Compras.pdf',['Attachment'=>false]);

        $conex->commit();
    } catch (\Throwable $e) {
        $conex->rollBack();
        throw $e;
    } finally {
        // cerrar conexión
        $conex = null;
    }
}


public static function producto(
    $prodId = null,
    $provId = null,
    $catId  = null,
    $precioMin = null,
    $precioMax = null,
    $stockMin = null,
    $stockMax = null,
    $estado = null
): void {
    // 1) Cargar dependencias
    require_once 'modelo/conexion.php';
    require_once 'assets/dompdf/vendor/autoload.php';
    require_once __DIR__ . '/../assets/js/jpgraph/src/jpgraph.php';
    require_once __DIR__ . '/../assets/js/jpgraph/src/jpgraph_pie.php';
    require_once __DIR__ . '/../assets/js/jpgraph/src/jpgraph_pie3d.php';

    $conex = (new Conexion())->getConex1();

    try {
        $conex->beginTransaction();

        // ——— Gráfico Top 10 stock > 0 ———
        $whereG  = ['p.stock_disponible > 0'];
        $paramsG = [];
        $joinG   = '';
        if ($prodId) {
            $whereG[]          = 'p.id_producto = :pid';
            $paramsG[':pid']   = $prodId;
        }
        if ($provId) {
            $joinG = "
              JOIN compra_detalles cd ON cd.id_producto = p.id_producto
              JOIN compra c          ON c.id_compra      = cd.id_compra
            ";
            $whereG[]          = 'c.id_proveedor = :prov';
            $paramsG[':prov']  = $provId;
        }
        if ($catId) {
            $whereG[]          = 'p.id_categoria = :cat';
            $paramsG[':cat']   = $catId;
        }
        if ($precioMin !== null) {
            $whereG[]          = 'p.precio_detal >= :precioMinG';
            $paramsG[':precioMinG'] = $precioMin;
        }
        if ($precioMax !== null) {
            $whereG[]          = 'p.precio_detal <= :precioMaxG';
            $paramsG[':precioMaxG'] = $precioMax;
        }
        if ($stockMin !== null) {
            $whereG[]          = 'p.stock_disponible >= :stockMinG';
            $paramsG[':stockMinG'] = $stockMin;
        }
        if ($stockMax !== null) {
            $whereG[]          = 'p.stock_disponible <= :stockMaxG';
            $paramsG[':stockMaxG'] = $stockMax;
        }
        if ($estado !== null) {
            $whereG[]          = 'p.estado = :estadoG';
            $paramsG[':estadoG'] = $estado;
        }

        $sqlG = "
          SELECT p.nombre, p.stock_disponible
            FROM productos p
            JOIN categoria cat ON cat.id_categoria = p.id_categoria
            {$joinG}
           WHERE " . implode(' AND ', $whereG) . "
           ORDER BY p.stock_disponible DESC
           LIMIT 10
        ";
        $stmtG = $conex->prepare($sqlG);
        $stmtG->execute($paramsG);

        $labels = []; $data = [];
        while ($r = $stmtG->fetch(PDO::FETCH_ASSOC)) {
            $labels[] = htmlspecialchars($r['nombre']);
            $data[]   = (int)$r['stock_disponible'];
        }

        // renderizar gráfico
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

        // ——— Tabla de productos ———
        $whereT  = ['1=1'];
        $paramsT = [];
        $joinT   = '';
        if ($prodId) {
            $whereT[]         = 'p.id_producto = :pidT';
            $paramsT[':pidT'] = $prodId;
        }
        if ($provId) {
            $joinT = "
              JOIN compra_detalles cd2 ON cd2.id_producto = p.id_producto
              JOIN compra c2          ON c2.id_compra     = cd2.id_compra
            ";
            $whereT[]         = 'c2.id_proveedor = :provT';
            $paramsT[':provT']= $provId;
        }
        if ($catId) {
            $whereT[]         = 'p.id_categoria = :catT';
            $paramsT[':catT'] = $catId;
        }
        if ($precioMin !== null) {
            $whereT[]         = 'p.precio_detal >= :precioMinT';
            $paramsT[':precioMinT'] = $precioMin;
        }
        if ($precioMax !== null) {
            $whereT[]         = 'p.precio_detal <= :precioMaxT';
            $paramsT[':precioMaxT'] = $precioMax;
        }
        if ($stockMin !== null) {
            $whereT[]         = 'p.stock_disponible >= :stockMinT';
            $paramsT[':stockMinT'] = $stockMin;
        }
        if ($stockMax !== null) {
            $whereT[]         = 'p.stock_disponible <= :stockMaxT';
            $paramsT[':stockMaxT'] = $stockMax;
        }
        if ($estado !== null) {
            $whereT[]         = 'p.estado = :estadoT';
            $paramsT[':estadoT'] = $estado;
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
          {$joinT}
         WHERE " . implode(' AND ', $whereT) . "
         ORDER BY p.stock_disponible DESC, p.nombre ASC
        ";
        $stmtT = $conex->prepare($sqlT);
        $stmtT->execute($paramsT);
        $rows  = $stmtT->fetchAll(PDO::FETCH_ASSOC);

        // ——— Texto de filtros ———
        $parts = [];
        if ($prodId && !empty($rows)) {
            $parts[] = 'Producto: ' . htmlspecialchars($rows[0]['nombre']);
        }
        if ($provId) {
            $pSt = $conex->prepare(
                'SELECT nombre FROM proveedor WHERE id_proveedor = :prov'
            );
            $pSt->execute([':prov' => $provId]);
            $parts[] = 'Proveedor: ' . htmlspecialchars($pSt->fetchColumn());
        }
        if ($catId) {
            // la cate﻿goría ya está en cada fila, usamos la primera
            $parts[] = 'Categoría: ' . htmlspecialchars($rows[0]['categoria'] ?? '');
        }
        $filtro   = $parts ? implode(' | ', $parts) : 'Listado general de productos';
        $fechaGen = date('d/m/Y H:i:s');

        // ——— Generar PDF ———
        $iconPath = __DIR__ . '/../assets/img/icon.PNG';
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
          . '<h1>LoveMakeup</h1><p>RIF: J-505434403</p>'
          . '</header><main>'
          . '<h1>Listado de Productos</h1>'
          . "<p><strong>Generado:</strong> {$fechaGen}</p>"
          . "<p><strong>Filtro:</strong> {$filtro}</p>"
          . (!empty($graf)
              ? '<h2>Top 10 Productos por Stock</h2>'
                . '<div style="text-align:center"><img src="'.$graf.'" width="600"/></div>'
              : '')
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

        $opts = new Options();
        $opts->set('isRemoteEnabled', true);
        $pdf  = new Dompdf($opts);
        $pdf->loadHtml($html);
        $pdf->setPaper('A4','portrait');
        $pdf->render();
        $pdf->stream('Reporte_Productos.pdf',['Attachment'=>false]);

        $conex->commit();
    } catch (\Throwable $e) {
        $conex->rollBack();
        throw $e;
    } finally {
        // cerrar conexión
        $conex = null;
    }
}


public static function venta(
    $start   = null,
    $end     = null,
    $prodId  = null,
    $catId   = null,
    $metodoPago = null,
    $montoMin = null,
    $montoMax = null
): void {
    // 1) Guardar valores originales
    $origStart = $start;
    $origEnd   = $end;

    // 2) Si solo hay inicio → fin = hoy
    if ($origStart && !$origEnd) {
        $end = date('Y-m-d');
    }

    // 3) Cargar dependencias
    require_once 'modelo/conexion.php';
    require_once 'assets/dompdf/vendor/autoload.php';
    require_once __DIR__ . '/../assets/js/jpgraph/src/jpgraph.php';
    require_once __DIR__ . '/../assets/js/jpgraph/src/jpgraph_pie.php';
    require_once __DIR__ . '/../assets/js/jpgraph/src/jpgraph_pie3d.php';

    $conex = (new Conexion())->getConex1();

    try {
        $conex->beginTransaction();

        // — Top 10 productos más vendidos (gráfico) —
        $whereG  = ['pe.tipo = 1'];
        $paramsG = [];

        // a) Sólo inicio
        if ($origStart && !$origEnd) {
            $whereG[]       = 'pe.fecha >= :sG';
            $paramsG[':sG'] = "$start 00:00:00";
        }
        // b) Sólo fin
        elseif (!$origStart && $origEnd) {
            $whereG[]       = 'pe.fecha <= :eG';
            $paramsG[':eG'] = "$end   23:59:59";
        }
        // c) Ambas fechas
        elseif ($origStart && $origEnd) {
            $whereG[]       = 'pe.fecha BETWEEN :sG AND :eG';
            $paramsG[':sG'] = "$start 00:00:00";
            $paramsG[':eG'] = "$end   23:59:59";
        }

        if ($prodId) {
            $whereG[]         = 'pd.id_producto = :pidG';
            $paramsG[':pidG'] = $prodId;
        }
        if ($catId) {
            $whereG[]         = 'pr.id_categoria = :catG';
            $paramsG[':catG'] = $catId;
        }
        if ($metodoPago) {
            $whereG[]         = 'pe.id_metodopago = :mpG';
            $paramsG[':mpG']  = $metodoPago;
        }
        if ($montoMin !== null) {
            $whereG[]         = 'pe.precio_total_usd >= :montoMinG';
            $paramsG[':montoMinG'] = $montoMin;
        }
        if ($montoMax !== null) {
            $whereG[]         = 'pe.precio_total_usd <= :montoMaxG';
            $paramsG[':montoMaxG'] = $montoMax;
        }

        $sqlG = "
          SELECT pr.nombre AS producto,
                 SUM(pd.cantidad) AS total
            FROM pedido pe
            JOIN pedido_detalles pd ON pd.id_pedido = pe.id_pedido
            JOIN productos       pr ON pr.id_producto = pd.id_producto
           WHERE " . implode(' AND ', $whereG) . "
          GROUP BY pr.id_producto
          ORDER BY total DESC
          LIMIT 10
        ";
        $stmtG = $conex->prepare($sqlG);
        $stmtG->execute($paramsG);

        $labels = []; 
        $data   = [];
        while ($r = $stmtG->fetch(PDO::FETCH_ASSOC)) {
            $labels[] = htmlspecialchars($r['producto']);
            $data[]   = (int)$r['total'];
        }

        // render gráfico a PNG
        $imgDir  = __DIR__ . '/../assets/img/grafica_reportes/';
        $imgFile = $imgDir . 'grafico_ventas.png';
        if (!is_dir($imgDir)) mkdir($imgDir, 0777, true);
        if (file_exists($imgFile)) unlink($imgFile);
        if ($data) {
            $graph = new \PieGraph(900, 500);
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

        // — Tabla de ventas con categoría —
        $whereT  = ['pe.tipo = 1'];
        $paramsT = [];

        // a) Sólo inicio
        if ($origStart && !$origEnd) {
            $whereT[]        = 'pe.fecha >= :sT';
            $paramsT[':sT']  = "$start 00:00:00";
        }
        // b) Sólo fin
        elseif (!$origStart && $origEnd) {
            $whereT[]        = 'pe.fecha <= :eT';
            $paramsT[':eT']  = "$end   23:59:59";
        }
        // c) Ambas fechas
        elseif ($origStart && $origEnd) {
            $whereT[]        = 'pe.fecha BETWEEN :sT AND :eT';
            $paramsT[':sT']  = "$start 00:00:00";
            $paramsT[':eT']  = "$end   23:59:59";
        }

        if ($prodId) {
            $whereT[]         = 'pd.id_producto = :pidT';
            $paramsT[':pidT'] = $prodId;
        }
        if ($catId) {
            $whereT[]         = 'pr.id_categoria = :catT';
            $paramsT[':catT'] = $catId;
        }
        if ($metodoPago) {
            $whereT[]         = 'pe.id_metodopago = :mpT';
            $paramsT[':mpT']  = $metodoPago;
        }
        if ($montoMin !== null) {
            $whereT[]         = 'pe.precio_total_usd >= :montoMinT';
            $paramsT[':montoMinT'] = $montoMin;
        }
        if ($montoMax !== null) {
            $whereT[]         = 'pe.precio_total_usd <= :montoMaxT';
            $paramsT[':montoMaxT'] = $montoMax;
        }

        $sqlT = "
          SELECT
            CONCAT(c.nombre,' ',c.apellido) AS cliente,
            pe.fecha,
            pe.precio_total_usd             AS total_usd,
            GROUP_CONCAT(
              pr.nombre,' (',pd.cantidad,'u)'
              ORDER BY pd.cantidad DESC
              SEPARATOR ', '
            ) AS productos,
            cat.nombre                      AS categoria
          FROM pedido pe
          JOIN cliente         c   ON c.id_persona     = pe.id_persona
          JOIN pedido_detalles pd  ON pd.id_pedido     = pe.id_pedido
          JOIN productos       pr  ON pr.id_producto   = pd.id_producto
          JOIN categoria       cat ON cat.id_categoria = pr.id_categoria
         WHERE " . implode(' AND ', $whereT) . "
        GROUP BY cliente, pe.fecha, total_usd, cat.nombre
        ORDER BY total_usd DESC
        ";
        $stmtT = $conex->prepare($sqlT);
        $stmtT->execute($paramsT);
        $rows  = $stmtT->fetchAll(PDO::FETCH_ASSOC);

        // — Texto de filtros —
        if (!$origStart && !$origEnd) {
            $filtro = 'Registro general';
        }
        elseif ($origStart && !$origEnd) {
            $filtro = 'Desde '.date('d/m/Y',strtotime($origStart))
                    .' hasta '.date('d/m/Y');
        }
        elseif (!$origStart && $origEnd) {
            $filtro = 'Hasta '.date('d/m/Y',strtotime($origEnd));
        }
        elseif ($origStart === $origEnd) {
            $filtro = 'Reporte del '.date('d/m/Y',strtotime($origStart));
        }
        else {
            $filtro = 'Desde '.date('d/m/Y',strtotime($origStart))
                    .' hasta '.date('d/m/Y',strtotime($origEnd));
        }
        if ($prodId) {
            $pSt = $conex->prepare(
                'SELECT nombre FROM productos WHERE id_producto = :pid'
            );
            $pSt->execute([':pid'=>$prodId]);
            $filtro .= ' | Producto: '.htmlspecialchars($pSt->fetchColumn());
        }
        if ($catId) {
            $filtro .= ' | Categoría: '.htmlspecialchars($rows[0]['categoria'] ?? '');
        }

        // — Generar PDF —
        $fechaGen = date('d/m/Y H:i:s');
        $icon     = __DIR__ . '/../assets/img/icon.PNG';
        $logoData = file_exists($icon)
                  ? 'data:image/png;base64,'.base64_encode(file_get_contents($icon))
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
          . '<h1>LoveMakeup</h1><p>RIF: J-505434403</p>'
          . '</header><main>'
          . '<h1>Listado de Ventas</h1>'
          . "<p><strong>Generado:</strong> {$fechaGen}</p>"
          . "<p><strong>Filtro:</strong> {$filtro}</p>"
          . (!empty($graf)
              ? '<h2>Top 10 Productos Más Vendidos</h2>
                 <div style="text-align:center"><img src="'.$graf.'" width="600"/></div>'
              : '')
          . '<table><thead><tr>'
          . '<th>Cliente</th><th>Fecha</th><th>Total (USD)</th><th>Productos</th><th>Categoría</th>'
          . '</tr></thead><tbody>';
        foreach ($rows as $r) {
            $d    = date('d/m/Y',strtotime($r['fecha']));
            $tot  = '$'.number_format($r['total_usd'],2);
            $cli  = htmlspecialchars($r['cliente']);
            $prods= htmlspecialchars($r['productos'] ?? '—');
            $catn = htmlspecialchars($r['categoria'] ?? '—');
            $html .= "<tr>
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
        $pdf  = new Dompdf($opts);
        $pdf->loadHtml($html);
        $pdf->setPaper('A4','portrait');
        $pdf->render();
        $pdf->stream('Reporte_Ventas.pdf',['Attachment'=>false]);

        $conex->commit();
    } catch (\Throwable $e) {
        $conex->rollBack();
        throw $e;
    } finally {
        // cerrar conexión
        $conex = null;
    }
}


public static function graficaVentaTop5(): array
{
    require_once 'modelo/conexion.php';
    $conex = (new Conexion())->getConex1();

    $sql = "
      SELECT pr.nombre    AS producto,
             SUM(pd.cantidad) AS total
        FROM pedido pe
        JOIN pedido_detalles pd ON pd.id_pedido = pe.id_pedido
        JOIN productos       pr ON pr.id_producto = pd.id_producto
       WHERE
         (pe.tipo = 1 AND pe.estado = 1)      
         OR
         (pe.tipo = 2 AND pe.estado IN (2,3,4,5))  
    GROUP BY pr.id_producto
    ORDER BY total DESC
       LIMIT 5
    ";
    $stmt = $conex->prepare($sql);
    $stmt->execute();

    $labels = [];
    $data   = [];
    while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $labels[] = htmlspecialchars($r['producto'], ENT_QUOTES);
        $data[]   = (int)$r['total'];
    }

    // cierra conexión
    $conex = null;

    // siempre devolvemos el array, incluso si está vacío
    return [
      'labels' => $labels,
      'data'   => $data
    ];
}






public static function pedidoWeb(
    ?string $start = null,
    ?string $end   = null,
    ?int    $prodId = null,
    ?int    $estado = null,
    ?int    $metodoPago = null,
    ?float  $montoMin = null,
    ?float  $montoMax = null
): void {
    // 1) Normalizar fechas
    $origStart = $start;
    $origEnd   = $end;
    if ($origStart && !$origEnd) {
        $end = date('Y-m-d');
    }

    // 2) Armar WHERE y params (solo tipo=2, excluyendo verificar pago)
    $where  = ['p.tipo = 2', 'p.estado != 1'];
    $params = [];
    if ($origStart && !$origEnd) {
        $where[]      = 'p.fecha >= :s AND p.fecha <= :e';
        $params[':s'] = "{$start} 00:00:00";
        $params[':e'] = "{$end}   23:59:59";
    } elseif (!$origStart && $origEnd) {
        $where[]      = 'p.fecha <= :e';
        $params[':e'] = "{$end}   23:59:59";
    } elseif ($origStart && $origEnd) {
        $where[]      = 'p.fecha BETWEEN :s AND :e';
        $params[':s'] = "{$start} 00:00:00";
        $params[':e'] = "{$end}   23:59:59";
    }
    if ($prodId) {
        $where[]        = 'pd.id_producto = :pid';
        $params[':pid'] = $prodId;
    }
    if ($estado !== null) {
        $where[]        = 'p.estado = :estado';
        $params[':estado'] = $estado;
    }
    if ($metodoPago !== null) {
        $where[]        = 'p.id_metodopago = :metodoPago';
        $params[':metodoPago'] = $metodoPago;
    }
    if ($montoMin !== null) {
        $where[]       = 'p.precio_total_bs >= :montoMin';
        $params[':montoMin'] = $montoMin;
    }
    if ($montoMax !== null) {
        $where[]       = 'p.precio_total_bs <= :montoMax';
        $params[':montoMax'] = $montoMax;
    }
    $whereSql = implode(' AND ', $where);

    // 3) Incluir dependencias
    require_once 'modelo/conexion.php';
    require_once 'assets/dompdf/vendor/autoload.php';
    require_once __DIR__ . '/../assets/js/jpgraph/src/jpgraph.php';
    require_once __DIR__ . '/../assets/js/jpgraph/src/jpgraph_pie.php';
    require_once __DIR__ . '/../assets/js/jpgraph/src/jpgraph_pie3d.php';

    $conex = (new Conexion())->getConex1();
    try {
        $conex->beginTransaction();

        // — Gráfico Top 5 Productos — (sin cambios) —
        $sqlG = "
          SELECT pr.nombre AS producto, SUM(pd.cantidad) AS total
            FROM pedido p
       LEFT JOIN pedido_detalles pd ON pd.id_pedido = p.id_pedido
       LEFT JOIN productos pr       ON pr.id_producto = pd.id_producto
           WHERE {$whereSql}
        GROUP BY pr.id_producto
        ORDER BY total DESC
           LIMIT 5
        ";
        $stmtG = $conex->prepare($sqlG);
        $stmtG->execute($params);
        $labels = []; $data = [];
        while ($r = $stmtG->fetch(PDO::FETCH_ASSOC)) {
            $labels[] = htmlspecialchars($r['producto']);
            $data[]   = (int)$r['total'];
        }
        $imgDir  = __DIR__ . '/../assets/img/grafica_reportes/';
        $imgFile = $imgDir . 'grafico_pedidoweb.png';
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

        // — Tabla de Pedidos Web, ahora con columna PRODUCTOS —
        $sqlT = "
          SELECT
            DATE_FORMAT(p.fecha, '%d/%m/%Y')        AS fecha,
            p.estado                               AS estado,
            p.precio_total_bs                      AS total,
            GROUP_CONCAT(
              DISTINCT pr.nombre
              ORDER BY pr.nombre
              SEPARATOR ', '
            )                                      AS productos,
            CONCAT(c.nombre,' ',c.apellido)        AS usuario
          FROM pedido p
          LEFT JOIN pedido_detalles pd ON pd.id_pedido   = p.id_pedido
          LEFT JOIN productos       pr ON pr.id_producto  = pd.id_producto
          LEFT JOIN cliente         c  ON c.id_persona   = p.id_persona
          WHERE {$whereSql}
          GROUP BY p.id_pedido
          ORDER BY p.precio_total_bs DESC
        ";
        $stmtT = $conex->prepare($sqlT);
        $stmtT->execute($params);
        $rows = $stmtT->fetchAll(PDO::FETCH_ASSOC);

        // Mapeo de estados
        $estados = [
          '0'=>'Anulado','1'=>'Verificar pago','2'=>'Pago verificado',
          '3'=>'Pendiente envío','4'=>'En camino','5'=>'Entregado'
        ];

        // Texto de filtro (igual que antes)
        if (!$origStart && !$origEnd) {
            $filtro = 'Todos los pedidos web';
        } elseif ($origStart && !$origEnd) {
            $filtro = "Desde {$start} hasta {$end}";
        } elseif (!$origStart && $origEnd) {
            $filtro = "Hasta {$end}";
        } elseif ($origStart === $origEnd) {
            $filtro = "Reporte del {$start}";
        } else {
            $filtro = "Desde {$start} hasta {$end}";
        }
        if ($prodId) {
            $pSt = $conex->prepare(
                "SELECT nombre FROM productos WHERE id_producto = :pid"
            );
            $pSt->execute([':pid'=>$prodId]);
            $filtro .= ' | Producto: '.htmlspecialchars($pSt->fetchColumn());
        }

        // Construir HTML y generar PDF
        $logoPath = __DIR__ . '/../assets/img/icon.PNG';
        $logoData = file_exists($logoPath)
                  ? 'data:image/png;base64,'.base64_encode(file_get_contents($logoPath))
                  : '';
        $fechaGen = date('d/m/Y H:i:s');

        $html = '<html><head><style>
          @page{margin:120px 50px 60px 50px}
          body{margin:0;font-family:Arial,sans-serif;font-size:12px}
          header{position:fixed;top:-110px;left:0;right:0;height:110px;text-align:center}
          header img{position:absolute;top:5px;right:5px;width:100px;height:100px}
          table{width:100%;border-collapse:collapse;margin-top:20px}
          th,td{border:1px solid #000;padding:6px;text-align:center}
          th{background:#f36ca4;color:#fff}
          footer{position:fixed;bottom:-40px;left:0;right:0;height:40px;text-align:center;font-size:10px;color:#666}
        </style></head><body>'
          . '<header>' . ($logoData? "<img src=\"{$logoData}\"/>":'')
          . '<h1>LoveMakeup</h1><p>RIF: J-505434403</p>'
          . '</header><main>'
          . '<h2>Reporte Pedidos Web</h2>'
          . "<p><strong>Generado:</strong> {$fechaGen}</p>"
          . "<p><strong>Filtro:</strong> {$filtro}</p>"
          . ($graf
              ? "<div style=\"text-align:center;margin:20px 0;\">
                   <h3>Top 5 Productos</h3>
                   <img src=\"{$graf}\" width=\"600\"/>
                 </div>"
              : '')
          . '<table><thead><tr>'
          . '<th>Fecha</th><th>Estado</th><th>Total (Bs.)</th>'
          . '<th>Productos</th><th>Usuario</th>'
          . '</tr></thead><tbody>';
        foreach ($rows as $r) {
            $e   = $estados[(string)$r['estado']] ?? 'Desconocido';
            $tot = 'Bs '.number_format($r['total'],2);
            $html .= "<tr>
                        <td>{$r['fecha']}</td>
                        <td>{$e}</td>
                        <td>{$tot}</td>
                        <td>".htmlspecialchars($r['productos'])."</td>
                        <td>".htmlspecialchars($r['usuario'])."</td>
                      </tr>";
        }
        $html .= '</tbody></table></main>'
               . '<footer>Página <span class="pageNumber"></span> de <span class="totalPages"></span></footer>'
               . '</body></html>';

        $opts = new \Dompdf\Options();
        $opts->set('isRemoteEnabled', true);
        $pdf = new \Dompdf\Dompdf($opts);
        $pdf->loadHtml($html);
        $pdf->setPaper('A4','portrait');
        $pdf->render();
        $pdf->stream('Reporte_PedidosWeb.pdf',['Attachment'=>false]);

        $conex->commit();
    } catch (\Throwable $e) {
        $conex->rollBack();
        throw $e;
    } finally {
        $conex = null;
    }
}














public static function countCompra($start = null, $end = null, $prodId = null, $catId = null, $provId = null, $montoMin = null, $montoMax = null): int {
    $conex     = (new Conexion())->getConex1();
    $origStart = $start;
    $origEnd   = $end;

    // Si sólo hay inicio → fin = hoy
    if ($origStart && !$origEnd) {
        $end = date('Y-m-d');
    }

    $where  = [];
    $params = [];
    $having = [];

    // a) Sólo inicio
    if ($origStart && !$origEnd) {
        $where[]      = 'c.fecha_entrada >= :s';
        $params[':s'] = $origStart . ' 00:00:00';
    }
    // b) Sólo fin
    elseif (!$origStart && $origEnd) {
        $where[]      = 'c.fecha_entrada <= :e';
        $params[':e'] = $origEnd   . ' 23:59:59';
    }
    // c) Ambas fechas
    elseif ($origStart && $origEnd) {
        $where[]      = 'c.fecha_entrada BETWEEN :s AND :e';
        $params[':s'] = $origStart . ' 00:00:00';
        $params[':e'] = $origEnd   . ' 23:59:59';
    }

    if ($prodId) {
        $where[]       = 'cd.id_producto = :pid';
        $params[':pid'] = $prodId;
    }

    if ($catId) {
        $where[]       = 'p.id_categoria = :cid';
        $params[':cid'] = $catId;
    }

    if ($provId) {
        $where[]       = 'c.id_proveedor = :prov';
        $params[':prov'] = $provId;
    }

    // Filtros de montos - usar HAVING porque es una función agregada
    if ($montoMin !== null) {
        $having[]       = 'SUM(cd.cantidad * cd.precio_unitario) >= :montoMin';
        $params[':montoMin'] = $montoMin;
    }
    if ($montoMax !== null) {
        $having[]       = 'SUM(cd.cantidad * cd.precio_unitario) <= :montoMax';
        $params[':montoMax'] = $montoMax;
    }

    // Usar el mismo JOIN que en el PDF para contar exactamente lo mismo
    $sql = "
      SELECT COUNT(*) FROM (
        SELECT
          c.fecha_entrada,
          pr.nombre AS proveedor
        FROM compra c
        JOIN compra_detalles cd ON cd.id_compra = c.id_compra
        JOIN productos        p  ON p.id_producto = cd.id_producto
        JOIN categoria        cat ON cat.id_categoria = p.id_categoria
        JOIN proveedor        pr ON pr.id_proveedor = c.id_proveedor
         " . ($where ? 'WHERE ' . implode(' AND ', $where) : '') . "
        GROUP BY c.fecha_entrada, pr.nombre
         " . ($having ? 'HAVING ' . implode(' AND ', $having) : '') . "
      ) AS subquery
    ";

    $stmt = $conex->prepare($sql);
    $stmt->execute($params);
    return (int) $stmt->fetchColumn();
}






public static function countProducto($prodId = null, $provId = null, $catId = null, $precioMin = null, $precioMax = null, $stockMin = null, $stockMax = null, $estado = null): int {
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
    if ($precioMin !== null) {
      $where[]          = 'p.precio_detal >= :precioMin';
      $params[':precioMin'] = $precioMin;
    }
    if ($precioMax !== null) {
      $where[]          = 'p.precio_detal <= :precioMax';
      $params[':precioMax'] = $precioMax;
    }
    if ($stockMin !== null) {
      $where[]          = 'p.stock_disponible >= :stockMin';
      $params[':stockMin'] = $stockMin;
    }
    if ($stockMax !== null) {
      $where[]          = 'p.stock_disponible <= :stockMax';
      $params[':stockMax'] = $stockMax;
    }
    if ($estado !== null) {
      $where[]          = 'p.estado = :estado';
      $params[':estado'] = $estado;
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


public static function countVenta(
    $start      = null,
    $end        = null,
    $prodId     = null,
    $metodoId   = null,
    $catId      = null,
    $montoMin   = null,
    $montoMax   = null
): int {
    $conex     = (new Conexion())->getConex1();
    $origStart = $start;
    $origEnd   = $end;

    // si sólo hay inicio → fin = hoy
    if ($origStart && !$origEnd) {
        $end = date('Y-m-d');
    }

    $where  = ['pe.tipo = 1'];
    $params = [];

    // a) Sólo inicio
    if ($origStart && !$origEnd) {
        $where[]      = 'pe.fecha >= :s';
        $params[':s'] = $origStart . ' 00:00:00';
    }
    // b) Sólo fin
    elseif (!$origStart && $origEnd) {
        $where[]      = 'pe.fecha <= :e';
        $params[':e'] = $origEnd   . ' 23:59:59';
    }
    // c) Ambas fechas
    elseif ($origStart && $origEnd) {
        $where[]      = 'pe.fecha BETWEEN :s AND :e';
        $params[':s'] = $origStart . ' 00:00:00';
        $params[':e'] = $origEnd   . ' 23:59:59';
    }

    if ($prodId) {
        $where[]        = 'pd.id_producto = :pid';
        $params[':pid'] = $prodId;
    }
    if ($metodoId) {
        $where[]         = 'pe.id_metodopago = :mp';
        $params[':mp']   = $metodoId;
    }
    if ($catId) {
        $where[]  = 'pr.id_categoria = :cat';
        $params[':cat'] = $catId;
    }

    // Filtros de montos
    if ($montoMin !== null) {
        $where[]       = 'pe.precio_total_usd >= :montoMin';
        $params[':montoMin'] = $montoMin;
    }
    if ($montoMax !== null) {
        $where[]       = 'pe.precio_total_usd <= :montoMax';
        $params[':montoMax'] = $montoMax;
    }

    // Usar la misma estructura que en el PDF para contar exactamente lo mismo
    $sql = "
      SELECT COUNT(*) FROM (
        SELECT
          CONCAT(c.nombre,' ',c.apellido) AS cliente,
          pe.fecha,
          pe.precio_total_usd             AS total_usd,
          cat.nombre                      AS categoria
        FROM pedido pe
        JOIN cliente         c   ON c.id_persona     = pe.id_persona
        JOIN pedido_detalles pd  ON pd.id_pedido     = pe.id_pedido
        JOIN productos       pr  ON pr.id_producto   = pd.id_producto
        JOIN categoria       cat ON cat.id_categoria = pr.id_categoria
        WHERE " . implode(' AND ', $where) . "
        GROUP BY cliente, pe.fecha, total_usd, cat.nombre
      ) AS subquery
    ";
    
    $stmt = $conex->prepare($sql);
    $stmt->execute($params);
    return (int) $stmt->fetchColumn();
}




public static function countPedidoWeb($start = null, $end = null, $prodId = null, $estado = null, $metodoPago = null, $montoMin = null, $montoMax = null): int {
    // 1) Normalizar rangos parciales
    $origStart = $start;
    $origEnd   = $end;
    if ($origStart && !$origEnd) {
        // si solo hay inicio, tomamos hasta hoy
        $end = date('Y-m-d');
    }

    // 2) Armar condiciones y parámetros
    $where  = ['p.tipo = 2', 'p.estado != 1'];
    $params = [];

    if ($origStart && !$origEnd) {
        // solo fecha de inicio
        $where[]        = 'p.fecha >= :s';
        $params[':s']   = $start . ' 00:00:00';
    }
    elseif (!$origStart && $origEnd) {
        // solo fecha de fin
        $where[]        = 'p.fecha <= :e';
        $params[':e']   = $end   . ' 23:59:59';
    }
    elseif ($origStart && $origEnd) {
        // ambos
        $where[]        = 'p.fecha BETWEEN :s AND :e';
        $params[':s']   = $start . ' 00:00:00';
        $params[':e']   = $end   . ' 23:59:59';
    }

    if ($prodId) {
        $where[]        = 'pd.id_producto = :pid';
        $params[':pid'] = $prodId;
    }

    if ($estado !== null) {
        $where[]        = 'p.estado = :estado';
        $params[':estado'] = $estado;
    }

    if ($metodoPago !== null) {
        $where[]        = 'p.id_metodopago = :metodoPago';
        $params[':metodoPago'] = $metodoPago;
    }

    // Filtros de montos
    if ($montoMin !== null) {
        $where[]       = 'p.precio_total_bs >= :montoMin';
        $params[':montoMin'] = $montoMin;
    }
    if ($montoMax !== null) {
        $where[]       = 'p.precio_total_bs <= :montoMax';
        $params[':montoMax'] = $montoMax;
    }

    $w = 'WHERE ' . implode(' AND ', $where);

    // 3) Ejecutar conteo
    $conex = (new Conexion())->getConex1();
    $sql  = "
      SELECT COUNT(DISTINCT p.id_pedido) AS cnt
        FROM pedido p
        JOIN pedido_detalles pd ON pd.id_pedido = p.id_pedido
      {$w}
    ";
    $stmt = $conex->prepare($sql);
    $stmt->execute($params);

    return (int) $stmt->fetchColumn();
}



}
