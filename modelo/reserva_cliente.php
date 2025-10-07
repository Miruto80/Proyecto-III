<?php
require_once 'conexion.php';
require_once 'metodopago.php';

class ReservaCliente extends Conexion {
    private $objmetodopago;

    public function __construct() {
        parent::__construct();
        $this->objmetodopago = new MetodoPago();
    }

    public function obtenerMetodosPago() {
        return $this->objmetodopago->obtenerMetodos();
    }

    public function procesarReserva($jsonDatos) {
        $datos = json_decode($jsonDatos, true);
        if (!isset($datos['operacion']) || $datos['operacion'] !== 'registrar_reserva') {
            return ['success' => false, 'message' => 'Operación no válida.'];
        }

        $d = $datos['datos'];
        $d['tipo'] = 3; // tipo reserva

        try {
            $this->validarStockCarrito($d['carrito']);

            // Procesar imagen (comprobante)
            $nombreArchivo = null;
            if (isset($d['imagen']) && is_array($d['imagen']) && $d['imagen']['error'] === UPLOAD_ERR_OK) {
                $nombreArchivo = uniqid() . '_' . basename($d['imagen']['name']);
                $rutaDestino = __DIR__ . '/../uploads/' . $nombreArchivo;
                if (!move_uploaded_file($d['imagen']['tmp_name'], $rutaDestino)) {
                    throw new Exception("No se pudo guardar el comprobante.");
                }
            }
            $d['imagen'] = $nombreArchivo;

            // 1. Registrar pedido SIN dirección
            $idPedido = $this->registrarPedido([
                'tipo' => $d['tipo'],
                'fecha' => $d['fecha'] ?? date('Y-m-d H:i:s'),
                'estado' => $d['estado'] ?? 'pendiente',
                'precio_total_usd' => $d['precio_total_usd'],
                'precio_total_bs' => $d['precio_total_bs'],
                'id_direccion' => null,
                'id_pago' => null,
                'id_persona' => $d['id_persona']
            ]);

            // 2. Registrar detalle de pago
            $idPago = $this->registrarDetallePago([
                'id_pedido' => $idPedido,
                'id_metodopago' => $d['id_metodopago'],
                'referencia_bancaria' => $d['referencia_bancaria'],
                'telefono_emisor' => $d['telefono_emisor'],
                'banco_destino' => $d['banco_destino'],
                'banco' => $d['banco'],
                'monto' => $d['monto'],
                'monto_usd' => $d['monto_usd'],
                'imagen' => $d['imagen']
            ]);

            // 3. Asociar el pago al pedido
            $this->actualizarPedidoConIdPago($idPedido, $idPago);

            // 4. Registrar detalles y actualizar stock
            foreach ($d['carrito'] as $item) {
                $precio = ($item['cantidad'] >= $item['cantidad_mayor']) ? $item['precio_mayor'] : $item['precio_detal'];
                $this->registrarDetalle([
                    'id_pedido' => $idPedido,
                    'id_producto' => $item['id'],
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $precio
                ]);
                $this->actualizarStock($item['id'], $item['cantidad']);
            }

            return ['success' => true, 'id_pedido' => $idPedido, 'message' => 'Reserva registrada correctamente'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // ---------------------- Métodos auxiliares ----------------------

    private function actualizarPedidoConIdPago($idPedido, $idPago) {
        $conex = $this->getConex1();
        try {
            $conex->beginTransaction();
            $stmt = $conex->prepare("UPDATE pedido SET id_pago=:id_pago WHERE id_pedido=:id_pedido");
            $stmt->execute(['id_pago' => $idPago, 'id_pedido' => $idPedido]);
            $conex->commit();
        } catch (PDOException $e) {
            if ($conex->inTransaction()) {
                $conex->rollBack();
            }
            throw $e;
        }
    }

    private function validarStockCarrito($carrito) {
        $conex = $this->getConex1();
        try {
            $conex->beginTransaction();
            foreach ($carrito as $item) {
                $stmt = $conex->prepare("SELECT stock_disponible, nombre FROM productos WHERE id_producto = :id");
                $stmt->execute(['id' => $item['id']]);
                $p = $stmt->fetch(PDO::FETCH_ASSOC);
                if (!$p) throw new Exception("Producto {$item['id']} no encontrado.");
                if ($item['cantidad'] > $p['stock_disponible']) {
                    throw new Exception("Stock insuficiente para {$p['nombre']}.");
                }
            }
            $conex->commit();
        } catch (Exception $e) {
            $conex->rollBack();
            throw $e;
        }
    }

    private function registrarPedido($d) {
        $conex = $this->getConex1();
        try {
            $conex->beginTransaction();
            $sql = "INSERT INTO pedido(tipo, fecha, estado, precio_total_usd, precio_total_bs, id_direccion, id_pago, id_persona)
                    VALUES (:tipo, :fecha, :estado, :precio_total_usd, :precio_total_bs, :id_direccion, :id_pago, :id_persona)";
            $stmt = $conex->prepare($sql);
            $stmt->execute($d);
            $id = $conex->lastInsertId();
            $conex->commit();
            return $id;
        } catch (Exception $e) {
            $conex->rollBack();
            throw $e;
        }
    }

    private function registrarDetallePago($d) {
        $conex = $this->getConex1();
        try {
            $conex->beginTransaction();
            $sql = "INSERT INTO detalle_pago(id_pedido, id_metodopago, referencia_bancaria, telefono_emisor, banco_destino, banco, monto, monto_usd, imagen)
                    VALUES (:id_pedido, :id_metodopago, :referencia_bancaria, :telefono_emisor, :banco_destino, :banco, :monto, :monto_usd, :imagen)";
            $stmt = $conex->prepare($sql);
            $stmt->execute($d);
            $id = $conex->lastInsertId();
            $conex->commit();
            return $id;
        } catch (Exception $e) {
            $conex->rollBack();
            throw $e;
        }
    }

    private function registrarDetalle($d) {
        $conex = $this->getConex1();
        try {
            $conex->beginTransaction();
            $sql = "INSERT INTO pedido_detalles(id_pedido, id_producto, cantidad, precio_unitario)
                    VALUES (:id_pedido, :id_producto, :cantidad, :precio_unitario)";
            $stmt = $conex->prepare($sql);
            $stmt->execute($d);
            $conex->commit();
        } catch (Exception $e) {
            $conex->rollBack();
            throw $e;
        }
    }

    private function actualizarStock($id, $cantidad) {
        $conex = $this->getConex1();
        try {
            $conex->beginTransaction();
            $sql = "UPDATE productos SET stock_disponible = stock_disponible - :cantidad WHERE id_producto = :id";
            $stmt = $conex->prepare($sql);
            $stmt->execute(['cantidad' => $cantidad, 'id' => $id]);
            $conex->commit();
        } catch (Exception $e) {
            $conex->rollBack();
            throw $e;
        }
    }
}
?>
