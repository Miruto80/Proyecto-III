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

                case 'delivery':
                        return $this->actualizarDelivery($datosProcesar);    
                
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
                    p.id_direccion,
                    p.precio_total_bs,
                    p.id_pago,
                    p.id_persona,
                    cli.nombre AS nombre,
                    d.direccion_envio AS direccion,
                    dp.banco AS banco
                FROM pedido p
                LEFT JOIN cliente cli ON p.id_persona = cli.id_persona
                LEFT JOIN direccion d ON p.id_direccion = d.id_direccion
                LEFT JOIN detalle_pago dp ON p.id_pago = dp.id_pago
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

         
            $conex = null;
            return ['respuesta' => 1, 'msg' => 'Pedido eliminado correctamente'];
        } catch (Exception $e) {
            $conex->rollBack();
            error_log("Error al eliminar pedido: " . $e->getMessage());
            return ['respuesta' => 0, 'msg' => 'Error al eliminar el pedido'];
            $conex = null;
        
        }
    }

    private function confirmarPedido($id_pedido) {
        $conex = $this->getConex1();
        try {
                $conex->beginTransaction();
        $sql = "UPDATE pedido SET estado = 2 WHERE id_pedido = ?";
        $stmt = $this->getConex1()->prepare($sql);
        if ($stmt->execute([$id_pedido])) {
            $conex->rollBack();
            $conex = null;
            return ['respuesta' => 1, 'msg' => 'Pedido confirmado'];
        } else {
            
            $conex = null;
            return ['respuesta' => 'error', 'msg' => 'No se pudo confirmar el pedido'];
        }
    }catch (PDOException $e) {
        if ($conex) {
            $conex->rollBack();
            $conex = null;
        }
        throw $e;
    }

        
    }

    private function actualizarDelivery($datos) {
        $conex = $this->getConex1();
        try {
            $conex->beginTransaction();
    
            $sql = "UPDATE pedido SET estado = ?, direccion = ? WHERE id_pedido = ?";
            $stmt = $conex->prepare($sql);
            $stmt->execute([$datos['estado_delivery'], $datos['direccion'], $datos['id_pedido']]);
    
            $conex->commit();
            return ['respuesta' => 1, 'msg' => 'Estado actualizado correctamente'];
        } catch (Exception $e) {
            $conex->rollBack();
            error_log("Error al actualizar delivery: " . $e->getMessage());
            return ['respuesta' => 0, 'msg' => 'Error al actualizar estado'];
        }
    }
    


   
}

?>
