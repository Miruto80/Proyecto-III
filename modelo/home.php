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
}



?>