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
            WHERE p.estado = 0
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
        // Opcional: Evita notificaciones duplicadas
        
        if ($this->existeNotificacion($pedido['id_pedido'])) {
            continue;
        }
    

        $titulo = "Pedido pendientes";
        $mensaje = "Hay un nuevo pedido pendiente: #" . $pedido['id_pedido'];
        $estado = $pedido['estado'];
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

public function verificarConexion() {
    var_dump($this->conex1); // Esto mostrará la información de la conexión
    exit; // Detiene la ejecución para ver la salida en pantalla
}


public function obtenerNotificaciones() {
    $sql = "SELECT * FROM notificaciones ORDER BY fecha DESC";
    $stmt = $this->conex1->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


private function existeNotificacion($id_pedido) {
    $sql = "SELECT COUNT(*) FROM notificaciones WHERE id_pedido = :id_pedido AND estado = 0";
    $stmt = $this->conex1->prepare($sql);
    $stmt->bindParam(':id_pedido', $id_pedido);
    $stmt->execute();
    return $stmt->fetchColumn() > 0;
}

public function cambiarestato() {
    $verificarPedido = "SELECT estado FROM pedido WHERE id_pedido = (SELECT id_pedido FROM notificaciones WHERE id_notificaciones = :id_notificaciones)";
    $stmtPedido = $this->conex1->prepare($verificarPedido);
    $stmtPedido->bindParam(':id_notificaciones', $this->id_notificaciones);
    $stmtPedido->execute();
    $estadoPedido = $stmtPedido->fetchColumn();

    if ($estadoPedido == 1) {
        return ['respuesta' => 0, 'accion' => 'leer', 'mensaje' => 'No puedes marcar como leído un pedido eliminado'];
    }

    $registro = "UPDATE notificaciones SET estado = CASE WHEN estado = 1 THEN 0 ELSE 1 END WHERE id_notificaciones = :id_notificaciones";
    $strExec = $this->conex1->prepare($registro);
    $strExec->bindParam(':id_notificaciones', $this->id_notificaciones);
    $resul = $strExec->execute();

    return $resul ? ['respuesta' => 1, 'accion' => 'leer'] : ['respuesta' => 0, 'accion' => 'leer'];
}


public function eliminar() {
    $registroNotificacion = "UPDATE notificaciones SET estado = 1 WHERE id_notificaciones = :id_notificaciones";
    $registroPedido = "UPDATE pedido SET estado = 1 WHERE id_pedido = (SELECT id_pedido FROM notificaciones WHERE id_notificaciones = :id_notificaciones)";

    $stmtNotificacion = $this->conex1->prepare($registroNotificacion);
    $stmtNotificacion->bindParam(':id_notificaciones', $this->id_notificaciones);
    $resul1 = $stmtNotificacion->execute();

    $stmtPedido = $this->conex1->prepare($registroPedido);
    $stmtPedido->bindParam(':id_notificaciones', $this->id_notificaciones);
    $resul2 = $stmtPedido->execute();

    return ($resul1 && $resul2) ? ['respuesta' => 1, 'accion' => 'eliminar'] : ['respuesta' => 0, 'accion' => 'eliminar'];
}


public function setIdNotificacion($id) {
    $this->id_notificaciones = $id;
}


}
?>