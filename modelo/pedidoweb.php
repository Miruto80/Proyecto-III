<?php
require_once 'conexion.php';

class PedidoWeb extends Conexion {
    private $conex;

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
}


?> 