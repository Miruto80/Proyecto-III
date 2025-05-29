<?php

require_once 'conexion.php';

class Notificacion extends conexion{
    private $conex1;
    private $conex2;
    private $titulo;
    private $mensaje;
    private $estado;
    private $fecha;
    private $id_pedido;
    private $id_notificaciones;



function __construct() {
    parent::__construct(); // Llama al constructor de la clase padre

     // Obtener las conexiones de la clase padre
     $this->conex1 = $this->getConex1();
     $this->conex2 = $this->getConex2();
 }

 public function setDatos($titulo, $mensaje, $estado, $fecha, $id_pedido) {
    $this->titulo = $titulo;
    $this->mensaje = $mensaje;
    $this->estado = $estado;
    $this->fecha = $fecha;
    $this->id_pedido = $id_pedido;
}

public function consultarPedidosPendientes() {
       $sql = "SELECT 
                   p.id_pedido,
                   p.tipo,
                   p.fecha,
                   p.estado
               FROM pedido p
               WHERE p.estado IN (1, 2) AND p.id_pedido > 0
               ORDER BY p.fecha DESC"; 

    $stmt = $this->conex1->prepare($sql);  
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function registrar() {
    try {
        $registro = "INSERT INTO notificaciones (titulo, mensaje, estado, fecha, id_pedido)
                     VALUES (:titulo, :mensaje, :estado, :fecha, :id_pedido)";
        
        $strExec = $this->conex1->prepare($registro);
        $strExec->bindParam(':titulo', $this->titulo);
        $strExec->bindParam(':mensaje', $this->mensaje);
        $strExec->bindParam(':estado', $this->estado);
        $strExec->bindParam(':fecha', $this->fecha);
        $strExec->bindParam(':id_pedido', $this->id_pedido);

        $resul = $strExec->execute();

        return $resul ? ['respuesta' => 1, 'accion' => 'incluir'] : ['respuesta' => 0, 'accion' => 'incluir'];
    } catch (PDOException $e) {
        echo "Error al registrar notificación: " . $e->getMessage();
        return ['respuesta' => 0, 'accion' => 'error'];
    }
}

public function registrarNotificacionesDePedidos() {
    $pedidos = $this->consultarPedidosPendientes();
    $registradas = 0;

    foreach ($pedidos as $pedido) {
        // Evita duplicados antes de registrar una nueva notificación
        if ($this->existeNotificacion($pedido['id_pedido'])) {
            continue; // Si ya existe, no la agrega de nuevo
        }

        $titulo = "Pedido pendiente";
        $mensaje = "Tienes un nuevo pedido: #" . $pedido['id_pedido'];
        $estado = 1; // Estado inicial como pendiente
        $fecha = $pedido['fecha'];
        $id_pedido = $pedido['id_pedido'];

        $this->setDatos($titulo, $mensaje, $estado, $fecha, $id_pedido);
        $resultado = $this->registrar();

        if ($resultado['respuesta'] === 1) {
            $registradas++;
        }
    }

    return ['registradas' => $registradas];
}


public function obtenerNotificaciones() {
    $sql = "SELECT * FROM notificaciones WHERE estado IN (1, 2) ORDER BY fecha DESC, id_notificaciones DESC";
    $stmt = $this->conex1->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}




private function existeNotificacion($id_pedido) {
    $sql = "SELECT COUNT(*) FROM notificaciones WHERE id_pedido = :id_pedido";
    $stmt = $this->conex1->prepare($sql);
    $stmt->bindParam(':id_pedido', $id_pedido);
    $stmt->execute();
    return $stmt->fetchColumn() > 0;
}

public function cambiarestato() {
    $registro = "UPDATE notificaciones SET estado = 2 WHERE id_notificaciones = :id_notificaciones AND estado = 1";
    $strExec = $this->conex1->prepare($registro);
    $strExec->bindParam(':id_notificaciones', $this->id_notificaciones);
    $resul = $strExec->execute();
    return $resul ? ['respuesta' => 1, 'accion' => 'actualizar'] : ['respuesta' => 0, 'accion' => 'actualizar'];
}


public function eliminar() {
    $registro = "DELETE FROM notificaciones WHERE id_notificaciones = :id_notificaciones";
    $strExec = $this->conex1->prepare($registro);
    $strExec->bindParam(':id_notificaciones', $this->id_notificaciones);
    $resul = $strExec->execute();
    return $resul ? ['respuesta' => 1, 'accion' => 'eliminar'] : ['respuesta' => 0, 'accion' => 'eliminar'];
}




public function setIdNotificacion($id) {
    $this->id_notificaciones = $id;
}


}
?>