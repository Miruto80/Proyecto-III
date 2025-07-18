<?php
require_once 'modelo/conexion.php';

class Categoria extends Conexion {
    function __construct() {
        parent::__construct();
    }

    // 1) Bitácora
    public function registrarBitacora(string $jsonDatos): bool {
        $datos = json_decode($jsonDatos, true);
        return $this->ejecutarSentenciaBitacora($datos);
    }

    private function ejecutarSentenciaBitacora(array $datos): bool {
        $conex = $this->getConex2();
        try {
            $conex->beginTransaction();

            $sql = "INSERT INTO bitacora
                      (accion, fecha_hora, descripcion, id_persona)
                    VALUES
                      (:accion, NOW(), :descripcion, :id_persona)";
            $stmt = $conex->prepare($sql);
            $stmt->execute($datos);

            $conex->commit();
            $conex = null;
            return true;
        } catch (PDOException $e) {
            if ($conex) {
                $conex->rollBack();
                $conex = null;
            }
            throw $e;
        }
    }

    // 2) Router JSON → CRUD
    public function procesarCategoria(string $jsonDatos): array {
        $payload   = json_decode($jsonDatos, true);
        $operacion = $payload['operacion'] ?? '';
        $d         = $payload['datos']    ?? [];

        try {
            switch ($operacion) {
                case 'incluir':    return $this->insertar($d);
                case 'actualizar': return $this->actualizar($d);
                case 'eliminar':   return $this->eliminarLogico($d);
                default:
                    return [
                      'respuesta'=>0,
                      'accion'   =>$operacion,
                      'mensaje'  =>'Operación no válida'
                    ];
            }
        } catch (PDOException $e) {
            return [
              'respuesta'=>0,
              'accion'   =>$operacion,
              'mensaje'  =>$e->getMessage()
            ];
        }
    }

    // 3a) Incluir
    private function insertar(array $d): array {
        $conex = $this->getConex1();
        try {
            $conex->beginTransaction();

            $sql  = "INSERT INTO categoria (nombre, estatus)
                     VALUES (:nombre, 1)";
            $stmt = $conex->prepare($sql);
            $ok   = $stmt->execute(['nombre'=>$d['nombre']]);

            if ($ok) {
                $conex->commit();
                $respuesta = ['respuesta'=>1,'accion'=>'incluir','mensaje'=>'Categoría creada'];
            } else {
                $conex->rollBack();
                $respuesta = ['respuesta'=>0,'accion'=>'incluir','mensaje'=>'Error al crear'];
            }
            $conex = null;
            return $respuesta;
        } catch (PDOException $e) {
            if ($conex) {
                $conex->rollBack();
                $conex = null;
            }
            throw $e;
        }
    }

    // 3b) Actualizar
    private function actualizar(array $d): array {
        $conex = $this->getConex1();
        try {
            $conex->beginTransaction();

            $sql  = "UPDATE categoria
                     SET nombre = :nombre
                     WHERE id_categoria = :id";
            $stmt= $conex->prepare($sql);
            $ok  = $stmt->execute([
                'id'     => $d['id_categoria'],
                'nombre' => $d['nombre']
            ]);

            if ($ok) {
                $conex->commit();
                $respuesta = ['respuesta'=>1,'accion'=>'actualizar','mensaje'=>'Categoría modificada'];
            } else {
                $conex->rollBack();
                $respuesta = ['respuesta'=>0,'accion'=>'actualizar','mensaje'=>'Error al modificar'];
            }
            $conex = null;
            return $respuesta;
        } catch (PDOException $e) {
            if ($conex) {
                $conex->rollBack();
                $conex = null;
            }
            throw $e;
        }
    }

    // 3c) Eliminar lógico
    private function eliminarLogico(array $d): array {
        $conex = $this->getConex1();
        try {
            $conex->beginTransaction();

            $sql  = "UPDATE categoria
                     SET estatus = 0
                     WHERE id_categoria = :id";
            $stmt= $conex->prepare($sql);
            $ok  = $stmt->execute(['id'=>$d['id_categoria']]);

            if ($ok) {
                $conex->commit();
                $respuesta = ['respuesta'=>1,'accion'=>'eliminar','mensaje'=>'Categoría eliminada'];
            } else {
                $conex->rollBack();
                $respuesta = ['respuesta'=>0,'accion'=>'eliminar','mensaje'=>'Error al eliminar'];
            }
            $conex = null;
            return $respuesta;
        } catch (PDOException $e) {
            if ($conex) {
                $conex->rollBack();
                $conex = null;
            }
            throw $e;
        }
    }

    // 4) Consultar (listado)
    public function consultar(): array {
        $conex = $this->getConex1();
        try {
            $sql   = "SELECT id_categoria, nombre
                      FROM categoria
                      WHERE estatus = 1";
            $stmt  = $conex->prepare($sql);
            $stmt->execute();
            $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $conex = null;
            return $datos;
        } catch (PDOException $e) {
            if ($conex) $conex = null;
            throw $e;
        }
    }
}
