<?php

require_once 'conexion.php';

class notificacion extends conexion{
    private $conex1;
    private $conex2;



function __construct() {
    parent::__construct(); // Llama al constructor de la clase padre

     // Obtener las conexiones de la clase padre
     $this->conex1 = $this->getConex1();
     $this->conex2 = $this->getConex2();
 }


 public function consultarPedidosPendientes() {
    $sql = "SELECT 
    p.id_pedido,
    p.tipo,
    p.fecha,
    p.estado
FROM pedido p
WHERE p.tipo = 1
ORDER BY p.fecha DESC";

    $stmt = $this->conex1->prepare($sql);  
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}





}
?>