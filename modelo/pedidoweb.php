<?php

require_once 'conexion.php';


class pedidoWeb extends Conexion {
   

     function __construct() {
        parent::__construct();
      
    }

    public function procesarPedidoweb($jsonDatos){
        $datos = json_decode($jsonDatos, true);
        $operacion = $datos['operacion'];
        $datosProcesar = $datos['datos'];

        try {
            switch($operacion){
                case 'confirmar':
                    return $this->confirmarPedido($datosProcesar);

                case 'eliminar':
                    return $this->eliminarPedido($datosProcesar);
                
                default:
                    return    ['respuesta' => 0, 'mensaje' => 'Operación no válida'];
            }
        } catch (Exception $e){
                return ['respuesta' => 0, 'mensaje' => 'Operación no válida'];
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
        cli.nombre AS nombre,
        me.nombre AS metodo_entrega,
        mp.nombre AS metodo_pago
    FROM pedido p
    LEFT JOIN cliente cli ON p.id_persona = cli.id_persona
    LEFT JOIN metodo_entrega me ON p.id_entrega = me.id_entrega
    LEFT JOIN metodo_pago mp ON p.id_metodopago = mp.id_metodopago
    WHERE p.tipo = 2
    ORDER BY p.fecha DESC";

        $stmt = $this->getconex1()->prepare($sql);  
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

        $stmt = $this->getconex1()->prepare($sql);
        $stmt->execute([$id_pedido]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function eliminarPedido($id_pedido) {
        try {
            $conex = $this->getconex1();
            $conex->beginTransaction();

            $sqlDetalles = "SELECT id_producto, cantidad FROM pedido_detalles WHERE id_pedido = ?";
            $stmtDetalles = $conex->prepare($sqlDetalles);
            $stmtDetalles->execute([$id_pedido]);
            $detalles = $stmtDetalles->fetchAll(PDO::FETCH_ASSOC);

            foreach ($detalles as $detalle) {
                $sqlUpdateStock = "UPDATE productos SET stock_disponible = stock_disponible + ? WHERE id_producto = ?";
                $stmtStock = $conex->prepare($sqlUpdateStock);
                $stmtStock->execute([$detalle['cantidad'], $detalle['id_producto']]);
            }

            $sqlEliminar = "UPDATE pedido SET estado = 0 WHERE id_pedido = ?";
            $stmtEliminar = $conex->prepare($sqlEliminar);
            $stmtEliminar->execute([$id_pedido]);

            $conex->commit();
            return ['respuesta' => 1, 'msg' => 'Pedido eliminado correctamente'];
        } catch (Exception $e) {
            $conex->rollBack();
            error_log("Error al eliminar pedido: " . $e->getMessage());
            return ['respuesta' => 0, 'msg' => 'Error al eliminar el pedido'];
        }
    }

    private function confirmarPedido($id_pedido) {
        $sql = "UPDATE pedido SET estado = 2 WHERE id_pedido = ?";
        $stmt = $this->getConex1()->prepare($sql);
        if ($stmt->execute([$id_pedido])) {
            return ['respuesta' => 'ok', 'msg' => 'Pedido confirmado'];
        } else {
            return ['respuesta' => 'error', 'msg' => 'No se pudo confirmar el pedido'];
        }
        
    }


   
}

?>
