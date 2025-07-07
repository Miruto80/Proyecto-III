<?php
require_once 'conexion.php';

class Reservas extends Conexion {
    public function __construct() {
        parent::__construct();
    }

    public function procesarReserva($jsonDatos) {
        $datos = json_decode($jsonDatos, true);
        $operacion = $datos['operacion'];
        $datosProcesar = $datos['datos'] ?? null;

        try {
            switch ($operacion) {
                case 'eliminar':
                    return $this->eliminarReserva($datosProcesar);
                case 'cambiar_estado':
                    return $this->cambiarEstadoReserva($datosProcesar);
                case 'consultar':
                    return $this->consultarReservasCompletas();
                case 'consultar_personas':
                    return $this->consultarPersonas();
                case 'consultar_productos':
                    return $this->consultarProductos();
                case 'consultar_reserva':
                    return $this->consultarReserva($datosProcesar);
                case 'consultar_detalle':
                    return $this->consultarDetallesReserva($datosProcesar);
                default:
                    return ['respuesta' => 0, 'mensaje' => 'Operación no válida'];
            }
        } catch (Exception $e) {
            return ['respuesta' => 0, 'mensaje' => $e->getMessage()];
        }
    }

    private function eliminarReserva($id) {
        try {
            $conex = $this->getconex1();
            $conex->beginTransaction();

            $sqlDetalles = "SELECT id_producto, cantidad FROM pedido_detalles WHERE id_pedido = ?";
            $stmtDetalles = $conex->prepare($sqlDetalles);
            $stmtDetalles->execute([$id]);
            $detalles = $stmtDetalles->fetchAll(PDO::FETCH_ASSOC);

            foreach ($detalles as $detalle) {
                $sqlUpdateStock = "UPDATE productos SET stock_disponible = stock_disponible + ? WHERE id_producto = ?";
                $stmtStock = $conex->prepare($sqlUpdateStock);
                $stmtStock->execute([$detalle['cantidad'], $detalle['id_producto']]);
            }

            $sqlEliminar = "UPDATE pedido SET estado = 0 WHERE id_pedido = ?";
            $stmtEliminar = $conex->prepare($sqlEliminar);
            $stmtEliminar->execute([$id]);

            $conex->commit();
            return ['respuesta' => 1, 'msg' => 'Reserva eliminada correctamente'];
        } catch (Exception $e) {
            $conex->rollBack();
            return ['respuesta' => 0, 'msg' => 'Error al eliminar la reserva'];
        }
    }

    private function cambiarEstadoReserva($datos) {
        $sql = "UPDATE pedido SET estado = ? WHERE id_pedido = ?";
        $stmt = $this->getconex1()->prepare($sql);
        if ($stmt->execute([$datos['estado'], $datos['id_pedido']])) {
            return ['respuesta' => 1, 'msg' => 'Estado actualizado'];
        } else {
            return ['respuesta' => 0, 'msg' => 'No se pudo actualizar el estado'];
        }
    }

    public function consultarReservasCompletas() {
        $sql = "SELECT 
                    p.id_pedido,
                    p.tipo,
                    p.fecha,
                    p.estado,
                    p.precio_total_bs,
                    p.id_pago,
                    p.id_persona,
                    cli.nombre,
                    cli.apellido,
                    dp.banco,
                    dp.imagen
                FROM pedido p
                LEFT JOIN cliente cli ON p.id_persona = cli.id_persona
                LEFT JOIN detalle_pago dp ON p.id_pago = dp.id_pago
                WHERE p.tipo = 3
                ORDER BY p.fecha DESC";

        $stmt = $this->getconex1()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function consultarDetallesReserva($id_pedido) {
        $sql = "SELECT 
                    pd.id_producto,
                    pr.nombre,
                    pd.cantidad,
                    pd.precio_unitario
                FROM pedido_detalles pd
                JOIN productos pr ON pd.id_producto = pr.id_producto
                WHERE pd.id_pedido = ?";

        $stmt = $this->getconex1()->prepare($sql);
        $stmt->execute([$id_pedido]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function consultarReserva($id_pedido) {
        $sql = "SELECT * FROM pedido WHERE id_pedido = ?";
        $stmt = $this->getconex1()->prepare($sql);
        $stmt->execute([$id_pedido]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function consultarPersonas() {
        $sql = "SELECT id_persona, nombre, apellido FROM cliente";
        $stmt = $this->getconex1()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function consultarProductos() {
        $sql = "SELECT id_producto, nombre, stock_disponible, precio FROM productos WHERE activo = 1";
        $stmt = $this->getconex1()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
