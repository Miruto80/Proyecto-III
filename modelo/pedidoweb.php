<?php
require_once 'conexion.php';

class pedidoWeb extends Conexion {
    private $conex;
    private $id_pedido;

    public function __construct() {
        $this->conex = new Conexion(); 
        $this->conex = $this->conex->conex();
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
        me.nombre AS metodo_entrega,
        mp.nombre AS metodo_pago
    FROM pedido p
    LEFT JOIN metodo_entrega me ON p.id_entrega = me.id_entrega
    LEFT JOIN metodo_pago mp ON p.id_metodopago = mp.id_metodopago
    WHERE p.tipo = 2
    ORDER BY p.fecha DESC";
    
        $stmt = $this->conex->prepare($sql);  
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function consultarDetallesPedido($id_pedido) {
        $sql = "SELECT 
                    pd.id_producto,
                    pr.nombre AS nombre_producto,
                    pd.cantidad,
                    pd.precio_unitario
                FROM pedido_detalles pd
                JOIN productos pr ON pd.id_producto = pr.id_producto
                WHERE pd.id_pedido = ?";
        
        $stmt = $this->conex->prepare($sql);
        $stmt->execute([$id_pedido]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function eliminarPedido($id_pedido) {
        try {
            $this->conex->beginTransaction();
    
            // me traigo productos y cantidades de detalle
            $sqlDetalles = "SELECT id_producto, cantidad FROM pedido_detalles WHERE id_pedido = ?";
            $stmtDetalles = $this->conex->prepare($sqlDetalles);
            $stmtDetalles->execute([$id_pedido]);
            $detalles = $stmtDetalles->fetchAll(PDO::FETCH_ASSOC);
    
            // devuelvo la cantidad a stock disponible
            foreach ($detalles as $detalle) {
                $sqlUpdateStock = "UPDATE productos SET stock_disponible = stock_disponible + ? WHERE id_producto = ?";
                $stmtStock = $this->conex->prepare($sqlUpdateStock);
                $stmtStock->execute([$detalle['cantidad'], $detalle['id_producto']]);
            }
    
            // eliminado logico de pedido
            $sqlEliminar = "UPDATE pedido SET estado = 0 WHERE id_pedido = ?";
            $stmtEliminar = $this->conex->prepare($sqlEliminar);
            $stmtEliminar->execute([$id_pedido]);
    
            $this->conex->commit();
            return true;
        } catch (Exception $e) {
            $this->conex->rollBack();
            error_log("Error al eliminar pedido: " . $e->getMessage());
            return false;
        }
    }
    
    
    // Confirmar un pedido (estado = 2)
    public function confirmarPedido($id_pedido) {
        $sql = "UPDATE pedido SET estado = 2 WHERE id_pedido = ?";
        $stmt = $this->conex->prepare($sql);
        return $stmt->execute([$id_pedido]);
    }

}


?> 