<?php 
require_once('modelo/conexion.php');

class home extends Conexion{
    private $conex;

    function __construct(){
        $this->conex = new Conexion();
        $this->conex = $this->conex->conex();
    }



        public function consultarMasVendidos() {
            $registro = "
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

            $consulta = $this->conex->prepare($registro);
            $resul = $consulta->execute();
        
            $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        
            if ($resul) {
                return $datos;
            } else {
                return 0;
            }
        }
        public function consultarTotales() {
            $registro = "
            SELECT 
                SUM(precio_total) AS total_ventas, 
                SUM(CASE WHEN tipo = '2' THEN precio_total ELSE 0 END) AS total_web, 
                COUNT(CASE WHEN tipo = '2' THEN id_pedido ELSE NULL END) AS cantidad_pedidos_web
            FROM 
                pedido
            WHERE 
                estado = 2
        ";
        
            $consulta = $this->conex->prepare($registro);
            $resul = $consulta->execute();
        
            $datos = $consulta->fetch(PDO::FETCH_ASSOC);
        
            if ($resul) {
                return $datos;
            } else {
                return 0;
            }
        }
        public function consultarTotalesPendientes() {
            $registro = "
                SELECT 
                    COUNT(id_pedido) AS cantidad_pedidos_pendientes
                FROM 
                    pedido
                WHERE 
                    estado = 1
            ";
            $consulta = $this->conex->prepare($registro);
            $resul = $consulta->execute();
        
            $datos = $consulta->fetch(PDO::FETCH_ASSOC);
        
            if ($resul) {
                return $datos;
            } else {
                return 0;
            }
        }
        
        
}



?>