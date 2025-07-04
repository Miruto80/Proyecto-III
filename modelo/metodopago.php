<?php
require_once __DIR__ . '/../modelo/conexion.php';

class MetodoPago extends Conexion {
    public function __construct() {
        parent::__construct(); 
    }

    public function procesarMetodoPago($jsonDatos) {
        $datos = json_decode($jsonDatos, true);
        $operacion = $datos['operacion'] ?? '';
        $datosProcesar = $datos['datos'] ?? [];

        try {
            switch ($operacion) {
                case 'incluir':
                    return $this->registrar($datosProcesar['nombre'], $datosProcesar['descripcion']);
                case 'modificar':
                    return $this->modificar(
                        $datosProcesar['id_metodopago'],
                        $datosProcesar['nombre'],
                        $datosProcesar['descripcion']
                    );
                case 'eliminar':
                    return $this->eliminar($datosProcesar['id_metodopago']);
                default:
                    return ['respuesta' => 0, 'mensaje' => 'Operación no válida'];
            }
        } catch (Exception $e) {
            return ['respuesta' => 0, 'accion' => $operacion, 'error' => $e->getMessage()];
        }
    }

    private function registrar($nombre, $descripcion) {
        $conex = $this->getConex1();
        try {
            $conex->beginTransaction();
            $sql = "INSERT INTO metodo_pago(nombre, descripcion, estatus) VALUES (:nombre, :descripcion, 1)";
            $stmt = $conex->prepare($sql);
            $result = $stmt->execute([
                'nombre' => $nombre,
                'descripcion' => $descripcion
            ]);
            $conex->commit();
            return $result ? ['respuesta' => 1, 'accion' => 'incluir'] : ['respuesta' => 0, 'accion' => 'incluir'];
        } catch (PDOException $e) {
            $conex->rollBack();
            throw $e;
        }
    }

    private function modificar($id_metodopago, $nombre, $descripcion) {
        $conex = $this->getConex1();
        try {
            $conex->beginTransaction();
            $sql = "UPDATE metodo_pago SET nombre = :nombre, descripcion = :descripcion WHERE id_metodopago = :id_metodopago";
            $stmt = $conex->prepare($sql);
            $result = $stmt->execute([
                'id_metodopago' => $id_metodopago,
                'nombre' => $nombre,
                'descripcion' => $descripcion
            ]);
            $conex->commit();
            return $result ? ['respuesta' => 1, 'accion' => 'actualizar'] : ['respuesta' => 0, 'accion' => 'actualizar'];
        } catch (PDOException $e) {
            $conex->rollBack();
            throw $e;
        }
    }

    private function eliminar($id_metodopago) {
        $conex = $this->getConex1();
        try {
            $conex->beginTransaction();
            $sql = "UPDATE metodo_pago SET estatus = 0 WHERE id_metodopago = :id_metodopago";
            $stmt = $conex->prepare($sql);
            $result = $stmt->execute(['id_metodopago' => $id_metodopago]);
            $conex->commit();
            return $result ? ['respuesta' => 1, 'accion' => 'eliminar'] : ['respuesta' => 0, 'accion' => 'eliminar'];
        } catch (PDOException $e) {
            $conex->rollBack();
            throw $e;
        }
    }

    public function consultar() {
        $sql = "SELECT * FROM metodo_pago WHERE estatus = 1";
        $stmt = $this->getConex1()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerMetodos() {
        $stmt = $this->getConex1()->prepare(
            "SELECT * FROM metodo_pago WHERE estatus = 1 AND id_metodopago = 1"
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
