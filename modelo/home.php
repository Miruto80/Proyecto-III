<?php  
require_once('modelo/conexion.php');

class home extends Conexion {

    public function __construct() {
        parent::__construct();
    }

    public function consultarMasVendidos() {
         $conex = $this->getConex1();
        try {
            $sql = "
                SELECT 
                    productos.nombre AS nombre_producto, 
                    SUM(pedido_detalles.cantidad) AS cantidad_vendida, 
                    SUM(pedido_detalles.cantidad * pedido_detalles.precio_unitario) AS total_vendido
                FROM 
                    productos
                INNER JOIN 
                    pedido_detalles ON productos.id_producto = pedido_detalles.id_producto
                INNER JOIN 
                    pedido ON pedido.id_pedido = pedido_detalles.id_pedido
                WHERE 
                    pedido.estado = 2
                GROUP BY 
                    productos.id_producto
                ORDER BY 
                    cantidad_vendida DESC
                LIMIT 5
            ";

            $stmt = $conex->prepare($sql);
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $conex = null;
            return $resultado;
        } catch (PDOException $e) {
            if ($conex) {
                $conex = null;
            }
            throw $e;
        }
    }

    public function consultarTotales() {
        $conex = $this->getConex1();
        try {
            $sql = "
                SELECT 
                    SUM(precio_total_usd) AS total_ventas, 
                    SUM(CASE WHEN tipo = '2' THEN precio_total_usd ELSE 0 END) AS total_web, 
                    COUNT(CASE WHEN tipo = '2' THEN id_pedido ELSE NULL END) AS cantidad_pedidos_web
                FROM 
                    pedido
                WHERE 
                    estado = 2
            ";

            $stmt = $conex->prepare($sql);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            $conex = null;
            return $resultado;
        } catch (PDOException $e) {
            if ($conex) {
                $conex = null;
            }
            throw $e;
        }
    }

    public function consultarTotalesPendientes() {
        $conex = $this->getConex1();
        try {
            $sql = "
                SELECT 
                    COUNT(id_pedido) AS cantidad_pedidos_pendientes
                FROM 
                    pedido
                WHERE 
                    estado = 1
            ";

            $stmt = $conex->prepare($sql);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            $conex = null;
            return $resultado;
        } catch (PDOException $e) {
            if ($conex) {
                $conex = null;
            }
            throw $e;
        }
    }
}
?>