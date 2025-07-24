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
        p.precio_total_bs,
        p.precio_total_usd,
        p.tracking,
        p.id_pago,
        p.id_persona,
        
        cli.nombre AS nombre,
        cli.apellido AS apellido,
         cli.correo AS correo,   
        d.direccion_envio AS direccion,
        
        me.nombre AS metodo_entrega,
        me.descripcion AS descripcion_entrega,
        
        dp.referencia_bancaria,
        dp.telefono_emisor,
        dp.banco_destino,
        dp.banco,
        dp.monto,
        dp.monto_usd,
        dp.imagen,
        
        mp.nombre AS metodo_pago,
        mp.descripcion AS descripcion_pago

    FROM pedido p
    LEFT JOIN cliente cli ON p.id_persona = cli.id_persona
    LEFT JOIN direccion d ON p.id_direccion = d.id_direccion
    LEFT JOIN metodo_entrega me ON d.id_metodoentrega = me.id_entrega
    LEFT JOIN detalle_pago dp ON p.id_pago = dp.id_pago
    LEFT JOIN metodo_pago mp ON dp.id_metodopago = mp.id_metodopago

    WHERE p.tipo IN (1, 2, 3) AND p.id_persona = ?
    ORDER BY p.fecha DESC";

$stmt = $this->getconex1()->prepare($sql);  
$stmt->execute([$id_persona]);
return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
        
    public function consultarDetallesPedidoCatalogo($id_pedido) {
        $sql = "SELECT 
        pd.id_producto,
        pr.nombre AS nombre,
        pr.descripcion,
        pd.cantidad,
        pd.precio_unitario,
        (pd.cantidad * pd.precio_unitario) AS subtotal
    FROM pedido_detalles pd
    JOIN productos pr ON pd.id_producto = pr.id_producto
    WHERE pd.id_pedido = ?";

$stmt = $this->getconex1()->prepare($sql);
$stmt->execute([$id_pedido]);
return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
 
}
?>