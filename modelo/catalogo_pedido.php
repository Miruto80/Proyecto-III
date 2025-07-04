<?php 
require_once 'conexion.php';

class Catalogopedido extends Conexion{
  
   
    public function __construct() {
        parent::__construct(); // Llama al constructor de la clase padre

   
    }
    
    
    public function consultarPedidosCompletosCatalogo($id_persona) {
        $sql = "SELECT 
        p.id_pedido,
        p.tipo,
        p.fecha,
        p.estado,
        p.precio_total,
        p.referencia_bancaria,
        p.banco_destino,
        p.telefono_emisor,
        p.direccion,
        me.nombre AS metodo_entrega,
        mp.nombre AS metodo_pago
    FROM pedido p
    LEFT JOIN metodo_entrega me ON p.id_entrega = me.id_entrega
    LEFT JOIN metodo_pago mp ON p.id_metodopago = mp.id_metodopago
    WHERE p.tipo = 2 AND p.id_persona = ?
    ORDER BY p.fecha DESC";
    
        $stmt = $this->getConex1()->prepare($sql);  
        $stmt->execute([$id_persona]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
        
    public function consultarDetallesPedidoCatalogo($id_pedido) {
        $sql = "SELECT 
                    pd.id_producto,
                    pr.nombre AS nombre_producto,
                    pd.cantidad,
                    pd.precio_unitario
                FROM pedido_detalles pd
                JOIN productos pr ON pd.id_producto = pr.id_producto
                WHERE pd.id_pedido = ?";
        
        $stmt = $this->getConex1()->prepare($sql);
        $stmt->execute([$id_pedido]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
 
}
?>