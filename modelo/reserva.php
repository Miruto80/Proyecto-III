<?php
require_once 'conexion.php';

class Reserva {
    private $conex;
    private $id_reserva;
    private $fecha_apartado;
    private $id_persona;
    
function __construct() {
        $this->conex = new Conexion();
        $this->conex = $this->conex->Conex();
        $this->id_reserva = 0;
        $this->fecha_apartado = '';
        $this->id_persona = 0;
    }
    
    // Getters y Setters
    public function get_Id_reserva() {
        return $this->id_reserva;
    }
    
    public function set_Id_reserva($id_reserva) {
        $this->id_reserva = $id_reserva;
    }
    
    public function get_Fecha_apartado() {
        return $this->fecha_apartado;
    }
    
    public function set_Fecha_apartado($fecha_apartado) {
        $this->fecha_apartado = $fecha_apartado;
    }
    
    public function get_Id_persona() {
        return $this->id_persona;
    }
    
    public function set_Id_persona($id_persona) {
        $this->id_persona = $id_persona;
    }
    
    
    // Métodos CRUD
    // Registrar reserva y sus detalles
    public function registrar($productos, $cantidades, $precios_unit) {
        try {
            $this->conex->beginTransaction();
            
            // Insertar encabezado de reserva
           $sql = "INSERT INTO reserva (fecha_apartado, id_persona) VALUES (?, ?)";
            $stmt = $this->conex->prepare($sql);
            $stmt->bindParam(1, $this->fecha_apartado);
            $stmt->bindParam(2, $this->id_persona);
            
            if (!$stmt->execute()) {
                $this->conex->rollBack();
                return ['respuesta' => 0, 'accion' => 'incluir', 'mensaje' => 'Error al registrar la reserva'];
            }
            
            $id_reserva = $this->conex->lastInsertId();
            $this->id_reserva = $id_reserva;
            
            // Insertar detalles de reserva
            for ($i = 0; $i < count($productos); $i++) {
                if (empty($productos[$i])) continue;
                
                $id_producto = $productos[$i];
                $cantidad = $cantidades[$i];
                $precio = $precios_unit[$i];

                // Verificar stock disponible antes de insertar
                $sqlStock = "SELECT p.stock_disponible, p.nombre FROM productos p WHERE p.id_producto = ?";
                $stmtStock = $this->conex->prepare($sqlStock);
                $stmtStock->bindParam(1, $id_producto);
                $stmtStock->execute();
                $producto = $stmtStock->fetch(PDO::FETCH_ASSOC);
                
                if (!$producto || $producto['stock_disponible'] < $cantidad) {
                    $this->conex->rollBack();
                    return ['respuesta' => 0, 'accion' => 'incluir', 'mensaje' => 'Stock insuficiente para: ' . $producto['nombre']];
                }
                
                // Insertar detalle de reserva
                $sql = "INSERT INTO reserva_detalles (cantidad, precio, id_reserva, id_producto) VALUES (?, ?, ?, ?)";
                $stmt = $this->conex->prepare($sql);
                $stmt->bindParam(1, $cantidad);
                $stmt->bindParam(2, $precio);
                $stmt->bindParam(3, $id_reserva);
                $stmt->bindParam(4, $id_producto);
                
                if (!$stmt->execute()) {
                    $this->conex->rollBack();
                    return ['respuesta' => 0, 'accion' => 'incluir', 'mensaje' => 'Error al registrar los detalles'];
                }
                
                // Actualizar el stock del producto
                $sqlUpdate = "UPDATE productos SET stock_disponible = stock_disponible - ? WHERE id_producto = ?";
                $stmtUpdate = $this->conex->prepare($sqlUpdate);
                $stmtUpdate->bindParam(1, $cantidad);
                $stmtUpdate->bindParam(2, $id_producto);
                
                if (!$stmtUpdate->execute()) {
                    $this->conex->rollBack();
                    return ['respuesta' => 0, 'accion' => 'incluir', 'mensaje' => 'Error al actualizar el stock'];
                }
            }
            
            $this->conex->commit();
            return ['respuesta' => 1, 'accion' => 'incluir', 'mensaje' => 'Reserva registrada correctamente', 'id_reserva' => $id_reserva];
            
            
        } catch (Exception $e) {
            $this->conex->rollBack();
            return ['respuesta' => 0, 'accion' => 'incluir', 'mensaje' => 'Error: ' . $e->getMessage()];
        }
    }
    
    // Modificar reserva
    public function modificar() {
        try {
            $sql = "UPDATE reserva SET fecha_apartado = ?, id_persona = ? WHERE id_reserva = ?";
            $stmt = $this->conex->prepare($sql);
            $stmt->bindParam(1, $this->fecha_apartado);
            $stmt->bindParam(2, $this->id_persona);
            $stmt->bindParam(3, $this->id_reserva);
            
            if ($stmt->execute()) {
                
                return ['respuesta' => 1, 'accion' => 'actualizar', 'mensaje' => 'Reserva modificada correctamente'];
            } else {
                return ['respuesta' => 0, 'accion' => 'actualizar', 'mensaje' => 'Error al modificar la reserva'];
            }
        } catch (Exception $e) {
            return ['respuesta' => 0, 'accion' => 'actualizar', 'mensaje' => 'Error: ' . $e->getMessage()];
        }
    }
    
    // Eliminar reserva y sus detalles
    public function eliminar() {
        try {
            $this->conex->beginTransaction();
            
            // Primero eliminar los detalles asociados a la reserva
            $sql = "DELETE FROM reserva_detalles WHERE id_reserva = ?";
            $stmt = $this->conex->prepare($sql);
            $stmt->bindParam(1, $this->id_reserva);
            
            if (!$stmt->execute()) {
                $this->conex->rollBack();
                return ['respuesta' => 0, 'accion' => 'eliminar', 'mensaje' => 'Error al eliminar los detalles'];
            }
            
            // Luego eliminar la reserva
            $sql = "DELETE FROM reserva WHERE id_reserva = ?";
            $stmt = $this->conex->prepare($sql);
            $stmt->bindParam(1, $this->id_reserva);
            
            if (!$stmt->execute()) {
                $this->conex->rollBack();
                return ['respuesta' => 0, 'accion' => 'eliminar', 'mensaje' => 'Error al eliminar la reserva'];
            }
            
            $this->conex->commit();
            return ['respuesta' => 1, 'accion' => 'eliminar', 'mensaje' => 'Reserva eliminada correctamente'];
            
        } catch (Exception $e) {
            $this->conex->rollBack();
            return ['respuesta' => 0, 'accion' => 'eliminar', 'mensaje' => 'Error: ' . $e->getMessage()];
        }
    }
    
    // Eliminar un detalle específico de la reserva
    public function eliminarDetalle($id_detalle) {
        try {
            $sql = "DELETE FROM reserva_detalles WHERE id_detalle_reserva = ?";
            $stmt = $this->conex->prepare($sql);
            $stmt->bindParam(1, $id_detalle);
            
            if ($stmt->execute()) {
                
                return ['respuesta' => 1, 'accion' => 'eliminar_detalle', 'mensaje' => 'Detalle eliminado correctamente'];
            } else {
                return ['respuesta' => 0, 'accion' => 'eliminar_detalle', 'mensaje' => 'Error al eliminar el detalle'];
            }
        } catch (Exception $e) {
            return ['respuesta' => 0, 'accion' => 'eliminar_detalle', 'mensaje' => 'Error: ' . $e->getMessage()];
        }
    }
    
    // Consultar todas las reservas
    public function consultarTodos() {
        try {
            $sql = "SELECT r.id_reserva, r.fecha_apartado, 
                p.nombre, p.apellido, p.id_persona 
                 FROM reserva r 
                INNER JOIN personas p ON r.id_persona = p.id_persona 
                ORDER BY r.id_reserva DESC";
                $stmt = $this->conex->prepare($sql);
                $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
    
    // Consultar una reserva por ID
    public function consultarPorId() {
        try {
            $sql = "SELECT * FROM reserva WHERE id_reserva = ?";
            $stmt = $this->conex->prepare($sql);
            $stmt->bindParam(1, $this->id_reserva);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                return $result;
            } else {
                return null;
            }
        } catch (Exception $e) {
            return null;
        }
    }
    
    // Consultar los detalles de una reserva
    public function consultarDetalle() {
        try {
            $sql = "SELECT rd.id_detalle_reserva, rd.cantidad, rd.precio, rd.id_producto, 
                   p.nombre as nombre_producto, p.descripcion, p.marca 
                   FROM reserva_detalles rd 
                   INNER JOIN productos p ON rd.id_producto = p.id_producto 
                   WHERE rd.id_reserva = ?";
            $stmt = $this->conex->prepare($sql);
            $stmt->bindParam(1, $this->id_reserva);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
    
    // Obtener datos del cliente asociado a la reserva
    public function obtenerDatosCliente() {
        try {
            $sql = "SELECT nombre, apellido, cedula, correo, telefono FROM personas WHERE id_persona = ?";
            $stmt = $this->conex->prepare($sql);
            $stmt->bindParam(1, $this->id_persona);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return null;
        }
    }
    
    // Consultar todas las personas disponibles para asociar a una reserva
    public function consultarPersonas() {
    try {
        $sql = "SELECT id_persona, nombre, apellido, cedula FROM personas ORDER BY nombre";
        $stmt = $this->conex->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        return [];
        }
    }
    
    // Consultar todos los productos disponibles para agregar a una reserva
   public function consultarProductos() {
    try {
        $sql = "SELECT id_producto, nombre, descripcion, marca, precio_detal, stock_disponible 
               FROM productos WHERE stock_disponible > 0 ORDER BY nombre";
        $stmt = $this->conex->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        return [];
    }
}
    }
// Fin de la clase Reserva