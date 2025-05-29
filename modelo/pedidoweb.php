<?php

require_once 'conexion.php';
require_once(__DIR__ . '/../assets/dompdf/vendor/autoload.php');

use Dompdf\Dompdf; 
use Dompdf\Options;

class pedidoWeb extends Conexion {
    private $conex1;
    private $conex2;

    public function __construct() {
        parent::__construct();
        $this->conex1 = $this->getConex1();
        $this->conex2 = $this->getConex2();

        if (!$this->conex1 || !$this->conex2) {
            die('Error al conectar con las bases de datos');
        }
    }

    public function consultarPedidosCompletos() {
        $sql = "SELECT 
                    p.id_pedido,
                    p.tipo,
                    p.fecha,
                    p.estado,
                    p.precio_total,
                    p.referencia_bancaria,
                    p.telefono_emisor,
                    p.id_persona,
                    me.nombre AS metodo_entrega,
                    mp.nombre AS metodo_pago
                FROM pedido p
                LEFT JOIN metodo_entrega me ON p.id_entrega = me.id_entrega
                LEFT JOIN metodo_pago mp ON p.id_metodopago = mp.id_metodopago
                WHERE p.tipo = 2
                ORDER BY p.fecha DESC";

        $stmt = $this->conex1->prepare($sql);  
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function consultarDetallesPedido($id_pedido) {
        $sql = "SELECT 
                    pd.id_producto,
                    pr.nombre,
                    pd.cantidad,
                    pd.precio_unitario
                FROM pedido_detalles pd
                JOIN productos pr ON pd.id_producto = pr.id_producto
                WHERE pd.id_pedido = ?";

        $stmt = $this->conex1->prepare($sql);
        $stmt->execute([$id_pedido]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function eliminarPedido($id_pedido) {
        try {
            $this->conex1->beginTransaction();

            $sqlDetalles = "SELECT id_producto, cantidad FROM pedido_detalles WHERE id_pedido = ?";
            $stmtDetalles = $this->conex1->prepare($sqlDetalles);
            $stmtDetalles->execute([$id_pedido]);
            $detalles = $stmtDetalles->fetchAll(PDO::FETCH_ASSOC);

            foreach ($detalles as $detalle) {
                $sqlUpdateStock = "UPDATE productos SET stock_disponible = stock_disponible + ? WHERE id_producto = ?";
                $stmtStock = $this->conex1->prepare($sqlUpdateStock);
                $stmtStock->execute([$detalle['cantidad'], $detalle['id_producto']]);
            }

            $sqlEliminar = "UPDATE pedido SET estado = 0 WHERE id_pedido = ?";
            $stmtEliminar = $this->conex1->prepare($sqlEliminar);
            $stmtEliminar->execute([$id_pedido]);

            $this->conex1->commit();
            return true;
        } catch (Exception $e) {
            $this->conex1->rollBack();
            error_log("Error al eliminar pedido: " . $e->getMessage());
            return false;
        }
    }

    public function confirmarPedido($id_pedido) {
        $sql = "UPDATE pedido SET estado = 2 WHERE id_pedido = ?";
        $stmt = $this->conex1->prepare($sql);
        return $stmt->execute([$id_pedido]);
    }

    public function imgToBase64($ruta) {
        if (!file_exists($ruta)) return '';
        $imagen = file_get_contents($ruta);
        return 'data:image/png;base64,' . base64_encode($imagen);
    }

    public function generarPDF() {
        $pedidos = $this->consultarPedidosCompletos();
        $fechaHoraActual = date('d/m/Y h:i A');
        $graficoBase64 = $this->imgToBase64(__DIR__ . '/../assets/img/grafica_reportes/grafico_productos.png');

        $html = '<html><head><title>Top 5 Productos Más Vendidos (Pedidos Web)</title><style>
                body { font-family: Arial, sans-serif; font-size: 12px; }
                h1 { text-align: center; font-size: 22px; margin-bottom: 20px; }
                p { text-align: left; font-size: 12px; }
                h2 { font-size: 18px; text-align: center; margin-top: 20px; }
                table { width: 100%; border-collapse: collapse; margin-top: 15px; }
                th, td { border: 1px solid #000; padding: 6px; text-align: center; font-size: 11px; }
                th { background-color: #f48da2; }
            </style></head><body>';

        $html .= '<h1>LISTADO DE PEDIDOS WEB</h1><p><strong>Fecha de generación:</strong> ' . $fechaHoraActual . '</p>';

        if (!empty($graficoBase64)) {
            $html .= '<h2>Top 5 Productos Más Vendidos</h2><div style="text-align:center;"><img src="' . $graficoBase64 . '" width="600"></div><br>';
        }

        foreach ($pedidos as $pedido) {
            $id_persona = isset($pedido['id_persona']) ? htmlspecialchars($pedido['id_persona']) : 'N/A';

            $html .= '
                      <p><strong>Fecha:</strong> ' . htmlspecialchars($pedido['fecha']) ;
                    

            $detalles = $this->consultarDetallesPedido($pedido['id_pedido']);

            if (!empty($detalles)) {
                $html .= '<table><thead><tr>
                          <th>Producto</th><th>Cantidad</th><th>Precio Unitario</th><th>Subtotal</th>
                          </tr></thead><tbody>';
                foreach ($detalles as $item) {
                    $nombre = isset($item['nombre']) ? htmlspecialchars($item['nombre']) : 'Producto';
                    $subtotal = $item['cantidad'] * $item['precio_unitario'];
                    $html .= '<tr>
                              <td>' . $nombre . '</td>
                              <td>' . $item['cantidad'] . '</td>
                              <td>' . number_format($item['precio_unitario'], 2) . '</td>
                              <td>' . number_format($subtotal, 2) . '</td>
                              </tr>';
                }
                $html .= '</tbody></table><br>';
            }
        }

        $html .= '</body></html>';

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('productos_mas_vendidos_web.pdf', ['Attachment' => false]);
    }
}

?>
