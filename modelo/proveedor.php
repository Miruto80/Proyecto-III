<?php
require_once 'assets/dompdf/vendor/autoload.php';
use Dompdf\Dompdf;

require_once 'modelo/conexion.php';

class proveedor extends Conexion {
    //---------------------------------------------------
    // 1) Bitácora JSON-driven
    //---------------------------------------------------
    // Eliminar método registrarBitacora y ejecutarBitacora

    //---------------------------------------------------
    // 2) Procesador único de operaciones
    //---------------------------------------------------
    public function procesarProveedor(string $jsonDatos): array {
        $payload   = json_decode($jsonDatos, true);
        $operacion = $payload['operacion'] ?? '';
        $datos     = $payload['datos']    ?? [];

        try {
            switch ($operacion) {
                case 'registrar':
                    return $this->ejecutarRegistro($datos);
                case 'actualizar':
                    return $this->ejecutarActualizacion($datos);
                case 'eliminar':
                    return $this->ejecutarEliminacion($datos);
                default:
                    return ['respuesta'=>0, 'accion'=>$operacion, 'mensaje'=>'Operación inválida'];
            }
        } catch (\Exception $e) {
            return ['respuesta'=>0, 'accion'=>$operacion, 'mensaje'=>$e->getMessage()];
        }
    }

    //---------------------------------------------------
    // 3) Métodos privados de cada operación
    //---------------------------------------------------
    private function ejecutarRegistro(array $d): array {
        $conex = $this->getConex1();
        try {
            $conex->beginTransaction();
            $sql = "INSERT INTO proveedor(numero_documento, tipo_documento, nombre, correo, telefono, direccion, estatus)
                    VALUES (:numero_documento, :tipo_documento, :nombre, :correo, :telefono, :direccion, 1)";
            $stmt = $conex->prepare($sql);
            $ok   = $stmt->execute($d);
            if ($ok) {
                $conex->commit();
                $conex = null;
                return ['respuesta'=>1, 'accion'=>'incluir', 'mensaje'=>'Proveedor registrado'];
            }
            $conex->rollBack();
            $conex = null;
            return ['respuesta'=>0, 'accion'=>'incluir', 'mensaje'=>'Error al registrar'];
        } catch (\PDOException $e) {
            if ($conex) { $conex->rollBack(); $conex = null; }
            throw $e;
        }
    }

    private function ejecutarActualizacion(array $d): array {
        $conex = $this->getConex1();
        try {
            $conex->beginTransaction();
            $sql = "UPDATE proveedor SET
                        numero_documento = :numero_documento,
                        tipo_documento   = :tipo_documento,
                        nombre           = :nombre,
                        correo           = :correo,
                        telefono         = :telefono,
                        direccion        = :direccion
                    WHERE id_proveedor = :id_proveedor";
            $stmt = $conex->prepare($sql);
            $ok   = $stmt->execute($d);
            if ($ok) {
                $conex->commit();
                $conex = null;
                return ['respuesta'=>1, 'accion'=>'actualizar', 'mensaje'=>'Proveedor actualizado'];
            }
            $conex->rollBack();
            $conex = null;
            return ['respuesta'=>0, 'accion'=>'actualizar', 'mensaje'=>'Error al actualizar'];
        } catch (\PDOException $e) {
            if ($conex) { $conex->rollBack(); $conex = null; }
            throw $e;
        }
    }

    private function ejecutarEliminacion(array $d): array {
        $conex = $this->getConex1();
        try {
            $conex->beginTransaction();
            $sql = "UPDATE proveedor SET estatus = 0 WHERE id_proveedor = :id_proveedor";
            $stmt = $conex->prepare($sql);
            $ok   = $stmt->execute($d);
            if ($ok) {
                $conex->commit();
                $conex = null;
                return ['respuesta'=>1, 'accion'=>'eliminar', 'mensaje'=>'Proveedor eliminado'];
            }
            $conex->rollBack();
            $conex = null;
            return ['respuesta'=>0, 'accion'=>'eliminar', 'mensaje'=>'Error al eliminar'];
        } catch (\PDOException $e) {
            if ($conex) { $conex->rollBack(); $conex = null; }
            throw $e;
        }
    }

    //---------------------------------------------------
    // 4) Consultas “simples”
    //---------------------------------------------------
    public function consultar(): array {
        $conex = $this->getConex1();
        $sql   = "SELECT * FROM proveedor WHERE estatus = 1";
        $stmt  = $conex->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $conex = null;
        return $data;
    }

    public function consultarPorId(int $id): array {
        $conex = $this->getConex1();
        $sql   = "SELECT * FROM proveedor WHERE id_proveedor = :id_proveedor";
        $stmt  = $conex->prepare($sql);
        $stmt->execute(['id_proveedor'=>$id]);
        $row   = $stmt->fetch(\PDO::FETCH_ASSOC) ?: [];
        $conex  = null;
        return $row;
    }




    /**
 * Regenera en disco el PNG con el Top 5 de proveedores.
 */
private function generarGrafico(): void {
    // 1) Incluir JPGraph
    require_once __DIR__ . '/../assets/js/jpgraph/src/jpgraph.php';
    require_once __DIR__ . '/../assets/js/jpgraph/src/jpgraph_pie.php';
    require_once __DIR__ . '/../assets/js/jpgraph/src/jpgraph_pie3d.php';

    // 2) Conexión y conteos
    $conex = $this->getConex1();
    $sql = "SELECT COUNT(DISTINCT pr.id_proveedor) AS total_activos
            FROM compra c
            JOIN proveedor pr ON c.id_proveedor=pr.id_proveedor
            WHERE pr.estatus=1";
    $stmt = $conex->prepare($sql);
    $stmt->execute();
    $total = (int)$stmt->fetchColumn();
    $lim   = min(5, max(0, $total));

    // 3) Consulta Top 5 (o menos si no hay suficientes)
    $sql = "SELECT pr.nombre, COUNT(c.id_compra) AS total_compras
            FROM compra c
            JOIN proveedor pr ON c.id_proveedor=pr.id_proveedor
            WHERE pr.estatus=1
            GROUP BY pr.nombre
            ORDER BY total_compras DESC
            LIMIT :lim";
    $stmt = $conex->prepare($sql);
    $stmt->bindValue(':lim', $lim, \PDO::PARAM_INT);
    $stmt->execute();

    $data   = [];
    $labels = [];
    while ($r = $stmt->fetch(\PDO::FETCH_ASSOC)) {
        $labels[] = $r['nombre'];
        $data[]   = (int)$r['total_compras'];
    }
    // Si no hay datos, salimos
    if (empty($data) || array_sum($data) === 0) {
        return;
    }

    // 4) Generar gráfico 3D
    $graph = new \PieGraph(900, 500);
    $pie   = new \PiePlot3D($data);
    $pie->SetLegends($labels);
    $pie->SetCenter(0.5, 0.5);
    $pie->ExplodeSlice(1);
    $graph->Add($pie);

    // 5) Crear carpeta si no existe y borrar viejo archivo
    $imgDir  = __DIR__ . '/../assets/img/grafica_reportes/';
    $imgFile = $imgDir . 'grafico_proveedores.png';
    if (!is_dir($imgDir)) mkdir($imgDir, 0777, true);
    if (file_exists($imgFile)) unlink($imgFile);

    // 6) Guardar el PNG
    $graph->Stroke($imgFile);
}





    public function generarPDF(): void {
    // A) Regenerar el PNG antes de todo
    $this->generarGrafico();

    // B) Leer lista y fecha
    $lista = $this->consultar();
    $fecha = date('d/m/Y h:i A');

    // C) Cargar PNG y convertir a base64
    $ruta = realpath(__DIR__ . '/../assets/img/grafica_reportes/grafico_proveedores.png');
    $graf = '';
    if ($ruta && file_exists($ruta)) {
        $bin  = file_get_contents($ruta);
        $graf = 'data:image/png;base64,' . base64_encode($bin);
    }

    // D) Montar HTML
    $html = "
    <html><head><style>
      body{font-family:Arial; font-size:12px;}
      table{width:100%;border-collapse:collapse;}
      th,td{border:1px solid #000;padding:6px;text-align:center;}
      th{background:#f36cA4;}
    </style></head><body>
      <h1>Listado de Proveedores</h1>
      <p>Fecha: {$fecha}</p>";
    if ($graf !== '') {
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
    foreach ($lista as $p) {
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

    // E) Render y stream con DOMPDF
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream("Reporte_Proveedores.pdf", ["Attachment" => false]);
}

}
