<?php
require_once 'conexion.php';

class Reserva extends Conexion{
    public function __construct() {
        parent::__construct();
    }

    public function registrarBitacora($jsonDatos) {
        $datos = json_decode($jsonDatos, true);
        try {
            $conex = $this->getConex2();
            $conex->beginTransaction();
            $sql = "INSERT INTO bitacora (accion, fecha_hora, descripcion, id_persona) VALUES (:accion, NOW(), :descripcion, :id_persona)";
            $stmt = $conex->prepare($sql);
            $stmt->execute($datos);
            $conex->commit();
            $conex = null;
            return ['respuesta' => 1, 'mensaje' => 'Registro en bitácora exitoso'];
        } catch (PDOException $e) {
            if ($conex) {
                $conex->rollBack();
                $conex = null;
            }
            return ['respuesta' => 0, 'mensaje' => $e->getMessage()];
        }
    }

    public function procesarReserva($jsonDatos) {
        $datos = json_decode($jsonDatos, true);
        $operacion = $datos['operacion'];
        $datosProcesar = isset($datos['datos']) ? $datos['datos'] : null;
        try {
            switch ($operacion) {
                case 'registrar':
                    return $this->ejecutarRegistroReserva($datosProcesar);
                case 'modificar':
                    return $this->ejecutarModificarReserva($datosProcesar);
                case 'eliminar':
                    return $this->ejecutarEliminarReserva($datosProcesar);
                case 'cambiar_estado':
                    return $this->ejecutarCambiarEstadoReserva($datosProcesar);
                case 'consultar':
                    return $this->ejecutarConsultarReservas();
                case 'consultar_personas':
                    return $this->ejecutarConsultarPersonas();
                case 'consultar_productos':
                    return $this->ejecutarConsultarProductos();
                case 'consultar_reserva':
                    return $this->ejecutarConsultarReserva($datosProcesar);
                case 'consultar_detalle':
                    return $this->ejecutarConsultarDetalle($datosProcesar);
                default:
                    return ['respuesta' => 0, 'mensaje' => 'Operación no válida'];
            }
        } catch (Exception $e) {
            return ['respuesta' => 0, 'mensaje' => $e->getMessage()];
        }
    }

    private function ejecutarRegistroReserva($datos) {
        $conex = $this->getConex1();
        try {
            $conex->beginTransaction();
            if (empty($datos['fecha_apartado']) || empty($datos['id_persona']) || empty($datos['productos'])) {
                throw new Exception('Datos incompletos');
            }
            $sql = "INSERT INTO reserva (fecha_apartado, id_persona, estatus) VALUES (:fecha_apartado, :id_persona, 1)";
            $stmt = $conex->prepare($sql);
            $stmt->execute([
                'fecha_apartado' => $datos['fecha_apartado'],
                'id_persona' => $datos['id_persona']
            ]);
            $id_reserva = $conex->lastInsertId();
            foreach ($datos['productos'] as $producto) {
                $sql = "INSERT INTO reserva_detalles (cantidad, precio, id_reserva, id_producto) VALUES (:cantidad, :precio, :id_reserva, :id_producto)";
                $stmt = $conex->prepare($sql);
                $stmt->execute([
                    'cantidad' => $producto['cantidad'],
                    'precio' => $producto['precio'],
                    'id_reserva' => $id_reserva,
                    'id_producto' => $producto['id_producto']
                ]);
                $sql = "UPDATE productos SET stock_disponible = stock_disponible - :cantidad WHERE id_producto = :id_producto";
                $stmt = $conex->prepare($sql);
                $stmt->execute([
                    'cantidad' => $producto['cantidad'],
                    'id_producto' => $producto['id_producto']
                ]);
            }
            $conex->commit();
            $conex = null;
            $bitacora = [
                'id_persona' => isset($datos['id_persona']) ? $datos['id_persona'] : null,
                'accion' => 'Registro de reserva',
                'descripcion' => 'Se registró la reserva ID: ' . $id_reserva
            ];
            $this->registrarBitacora(json_encode($bitacora));
            return ['respuesta' => 1, 'mensaje' => 'Reserva registrada correctamente', 'id_reserva' => $id_reserva];
        } catch (Exception $e) {
            if ($conex) {
                $conex->rollBack();
                $conex = null;
            }
            return ['respuesta' => 0, 'mensaje' => $e->getMessage()];
        }
    }

    private function ejecutarModificarReserva($datos) {
        $conex = $this->getConex1();
        try {
            $conex->beginTransaction();
            if (empty($datos['id_reserva']) || empty($datos['fecha_apartado']) || empty($datos['id_persona'])) {
                throw new Exception('Datos incompletos');
            }
            $sql = "SELECT estatus FROM reserva WHERE id_reserva = :id_reserva";
            $stmt = $conex->prepare($sql);
            $stmt->execute(['id_reserva' => $datos['id_reserva']]);
            $reserva = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$reserva) throw new Exception('Reserva no encontrada');
            if ($reserva['estatus'] != 1) throw new Exception('Solo se puede modificar reservas activas');
            $sql = "UPDATE reserva SET fecha_apartado = :fecha_apartado, id_persona = :id_persona WHERE id_reserva = :id_reserva";
            $stmt = $conex->prepare($sql);
            $stmt->execute([
                'fecha_apartado' => $datos['fecha_apartado'],
                'id_persona' => $datos['id_persona'],
                'id_reserva' => $datos['id_reserva']
            ]);
            $conex->commit();
            $conex = null;
            $bitacora = [
                'id_persona' => isset($datos['id_persona']) ? $datos['id_persona'] : null,
                'accion' => 'Modificación de reserva',
                'descripcion' => 'Se modificó la reserva ID: ' . $datos['id_reserva']
            ];
            $this->registrarBitacora(json_encode($bitacora));
            return ['respuesta' => 1, 'mensaje' => 'Reserva modificada correctamente'];
        } catch (Exception $e) {
            if ($conex) {
                $conex->rollBack();
                $conex = null;
            }
            return ['respuesta' => 0, 'mensaje' => $e->getMessage()];
        }
    }

    private function ejecutarEliminarReserva($datos) {
        $conex = $this->getConex1();
        try {
            $conex->beginTransaction();
            if (empty($datos['id_reserva'])) throw new Exception('ID de reserva no proporcionado');
            $sql = "SELECT estatus FROM reserva WHERE id_reserva = :id_reserva";
            $stmt = $conex->prepare($sql);
            $stmt->execute(['id_reserva' => $datos['id_reserva']]);
            $reserva = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$reserva) throw new Exception('Reserva no encontrada');
            if ($reserva['estatus'] != 1) throw new Exception('Solo se puede eliminar reservas activas');
            $sql = "SELECT id_producto, cantidad FROM reserva_detalles WHERE id_reserva = :id_reserva";
            $stmt = $conex->prepare($sql);
            $stmt->execute(['id_reserva' => $datos['id_reserva']]);
            $detalles = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($detalles as $detalle) {
                $sqlStock = "UPDATE productos SET stock_disponible = stock_disponible + :cantidad WHERE id_producto = :id_producto";
                $stmtStock = $conex->prepare($sqlStock);
                $stmtStock->execute([
                    'cantidad' => $detalle['cantidad'],
                    'id_producto' => $detalle['id_producto']
                ]);
            }
            $sql = "DELETE FROM reserva_detalles WHERE id_reserva = :id_reserva";
            $stmt = $conex->prepare($sql);
            $stmt->execute(['id_reserva' => $datos['id_reserva']]);
            $sql = "DELETE FROM reserva WHERE id_reserva = :id_reserva";
            $stmt = $conex->prepare($sql);
            $stmt->execute(['id_reserva' => $datos['id_reserva']]);
            $conex->commit();
            $conex = null;
            $bitacora = [
                'id_persona' => isset($datos['id_persona']) ? $datos['id_persona'] : null,
                'accion' => 'Eliminación de reserva',
                'descripcion' => 'Se eliminó la reserva ID: ' . $datos['id_reserva']
            ];
            $this->registrarBitacora(json_encode($bitacora));
            return ['respuesta' => 1, 'mensaje' => 'Reserva eliminada correctamente'];
        } catch (Exception $e) {
            if ($conex) {
                $conex->rollBack();
                $conex = null;
            }
            return ['respuesta' => 0, 'mensaje' => $e->getMessage()];
        }
    }

    private function ejecutarCambiarEstadoReserva($datos) {
        $conex = $this->getConex1();
        try {
            $conex->beginTransaction();
            if (empty($datos['id_reserva']) || !isset($datos['nuevo_estatus'])) throw new Exception('Datos incompletos');
            $sql = "SELECT estatus FROM reserva WHERE id_reserva = :id_reserva";
            $stmt = $conex->prepare($sql);
            $stmt->execute(['id_reserva' => $datos['id_reserva']]);
            $reserva = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$reserva) throw new Exception('Reserva no encontrada');
            if ($reserva['estatus'] == 0 || $reserva['estatus'] == 2) throw new Exception('No se puede cambiar el estado de una reserva inactiva o entregada');
            $sql = "UPDATE reserva SET estatus = :nuevo_estatus WHERE id_reserva = :id_reserva";
            $stmt = $conex->prepare($sql);
            $stmt->execute([
                'nuevo_estatus' => $datos['nuevo_estatus'],
                'id_reserva' => $datos['id_reserva']
            ]);
            $conex->commit();
            $conex = null;
            $bitacora = [
                'id_persona' => isset($datos['id_persona']) ? $datos['id_persona'] : null,
                'accion' => 'Cambio de estado de reserva',
                'descripcion' => 'Se cambió el estado de la reserva ID: ' . $datos['id_reserva']
            ];
            $this->registrarBitacora(json_encode($bitacora));
            return ['respuesta' => 1, 'mensaje' => 'Estado de reserva cambiado correctamente'];
        } catch (Exception $e) {
            if ($conex) {
                $conex->rollBack();
                $conex = null;
            }
            return ['respuesta' => 0, 'mensaje' => $e->getMessage()];
        }
    }

    private function ejecutarConsultarReservas() {
        $conex = $this->getConex1();
        try {
            $sql = "SELECT r.id_reserva, r.fecha_apartado, r.estatus, c.nombre, c.apellido, c.id_persona FROM reserva r INNER JOIN cliente c ON r.id_persona = c.id_persona ORDER BY r.id_reserva DESC";
            $stmt = $conex->prepare($sql);
                $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $conex = null;
            return ['respuesta' => 1, 'datos' => $result];
        } catch (Exception $e) {
            if ($conex) $conex = null;
            return ['respuesta' => 0, 'mensaje' => $e->getMessage()];
        }
    }
    
    private function ejecutarConsultarPersonas() {
        $conex = $this->getConex1();
        try {
            $sql = "SELECT id_persona, nombre, apellido, cedula FROM cliente ORDER BY nombre";
            $stmt = $conex->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $conex = null;
            return ['respuesta' => 1, 'datos' => $result];
        } catch (Exception $e) {
            if ($conex) $conex = null;
            return ['respuesta' => 0, 'mensaje' => $e->getMessage()];
        }
    }
    
    private function ejecutarConsultarProductos() {
        $conex = $this->getConex1();
        try {
            $sql = "SELECT id_producto, nombre, descripcion, marca, precio_detal, stock_disponible FROM productos WHERE stock_disponible > 0 ORDER BY nombre";
            $stmt = $conex->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $conex = null;
            return ['respuesta' => 1, 'datos' => $result];
        } catch (Exception $e) {
            if ($conex) $conex = null;
            return ['respuesta' => 0, 'mensaje' => $e->getMessage()];
        }
    }

    private function ejecutarConsultarReserva($datos) {
        $conex = $this->getConex1();
        try {
            $sql = "SELECT r.*, c.nombre, c.apellido FROM reserva r INNER JOIN cliente c ON r.id_persona = c.id_persona WHERE r.id_reserva = :id_reserva";
            $stmt = $conex->prepare($sql);
            $stmt->execute(['id_reserva' => $datos['id_reserva']]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $conex = null;
            if ($result) {
                $result['nombre_completo'] = $result['nombre'] . ' ' . $result['apellido'];
            }
            return $result;
        } catch (Exception $e) {
            if ($conex) $conex = null;
            return null;
        }
    }
    
    private function ejecutarConsultarDetalle($datos) {
        $conex = $this->getConex1();
        try {
            $sql = "SELECT rd.id_detalle_reserva, rd.cantidad, rd.precio, rd.id_producto, p.nombre as nombre_producto, p.descripcion, p.marca FROM reserva_detalles rd INNER JOIN productos p ON rd.id_producto = p.id_producto WHERE rd.id_reserva = :id_reserva";
            $stmt = $conex->prepare($sql);
            $stmt->execute(['id_reserva' => $datos['id_reserva']]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $conex = null;
            return $result;
    } catch (Exception $e) {
            if ($conex) $conex = null;
        return [];
        }
    }
}

// Fin de la clase Reserva