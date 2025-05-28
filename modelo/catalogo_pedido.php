<?php 
require_once 'conexion.php';

class Catalogopedido extends Conexion{
    private $conex1;
    private $conex2;
   


    public function __construct() {
        parent::__construct(); // Llama al constructor de la clase padre

        // Obtener las conexiones de la clase padre
        $this->conex1 = $this->getConex1();
        $this->conex2 = $this->getConex2();
    
         // Verifica si las conexiones son exitosas
        if (!$this->conex1) {
            die('Error al conectar con la primera base de datos');
        }

        if (!$this->conex2) {
            die('Error al conectar con la segunda base de datos');
        }
    }
    
    
    public function consultarPedidosCompletos($id_persona) {
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
    
        $stmt = $this->conex1->prepare($sql);  
        $stmt->execute([$id_persona]);
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
        
        $stmt = $this->conex1->prepare($sql);
        $stmt->execute([$id_pedido]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
 
}
?>