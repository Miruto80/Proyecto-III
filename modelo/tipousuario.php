<?php
// modelo/tipousuario.php

require_once __DIR__ . '/conexion.php';

class tipousuario extends Conexion {

    public function procesarTipousuario(string $jsonDatos): array {
        $p = json_decode($jsonDatos, true);
        return match($p['operacion'] ?? '') {
            'registrar'  => $this->registro($p['datos']),
            'actualizar' => $this->actualizacion($p['datos']),
            'eliminar'   => $this->eliminacion($p['datos']),
            default      => ['respuesta'=>0,'accion'=>'','mensaje'=>'Operación inválida']
        };
    }

    private function registro(array $d): array {
        $c = $this->getConex2();
        try {
            // Verificar si ya existe un tipo de usuario con el mismo nombre
            $sqlCheck = "SELECT COUNT(*) FROM rol_usuario WHERE LOWER(nombre) = LOWER(:nombre) AND estatus = 1";
            $stmtCheck = $c->prepare($sqlCheck);
            $stmtCheck->execute(['nombre' => $d['nombre']]);
            
            if ($stmtCheck->fetchColumn() > 0) {
                return ['respuesta'=>0,'accion'=>'incluir','mensaje'=>"Ya existe un tipo de usuario registrado con el nombre \"{$d['nombre']}\"."];
            }
            
            $nextId = (int)$c
                ->query("SELECT COALESCE(MAX(id_rol),0) + 1 FROM rol_usuario")
                ->fetchColumn();

            $c->beginTransaction();
            $sql = "INSERT INTO rol_usuario (id_rol, nombre, nivel, estatus)
                    VALUES (:id_rol, :nombre, :nivel, 1)";
            $stmt = $c->prepare($sql);
            $ok   = $stmt->execute([
                'id_rol' => $nextId,
                'nombre' => $d['nombre'],
                'nivel'  => $d['nivel']
            ]);

            if ($ok) {
                $c->commit();
                $c = null;
                return ['respuesta'=>1,'accion'=>'incluir','mensaje'=>'Tipo Usuario registrado'];
            }

            $c->rollBack();
            $c = null;
            return ['respuesta'=>0,'accion'=>'incluir','mensaje'=>'Error al registrar'];
        } catch (\Throwable $e) {
            if (isset($c)) {
                $c->rollBack();
                $c = null;
            }
            return ['respuesta'=>0,'accion'=>'incluir','mensaje'=>$e->getMessage()];
        }
    }

    private function actualizacion(array $d): array {
        $c = $this->getConex2();
        try {
            // Verificar si ya existe otro tipo de usuario con el mismo nombre
            $sqlCheck = "SELECT COUNT(*) FROM rol_usuario WHERE LOWER(nombre) = LOWER(:nombre) AND id_rol != :id_tipo AND estatus = 1";
            $stmtCheck = $c->prepare($sqlCheck);
            $stmtCheck->execute([
                'nombre' => $d['nombre'],
                'id_tipo' => $d['id_tipo']
            ]);
            
            if ($stmtCheck->fetchColumn() > 0) {
                return ['respuesta'=>0,'accion'=>'actualizar','mensaje'=>"Ya existe otro tipo de usuario registrado con el nombre \"{$d['nombre']}\"."];
            }
            
            $c->beginTransaction();
            $sql = "UPDATE rol_usuario
                    SET nombre  = :nombre,
                        nivel   = :nivel,
                        estatus = :estatus
                    WHERE id_rol = :id_tipo";
            $ok = $c->prepare($sql)->execute([
                'nombre'  => $d['nombre'],
                'nivel'   => $d['nivel'],
                'estatus' => $d['estatus'],
                'id_tipo' => $d['id_tipo']
            ]);

            if ($ok) {
                $c->commit();
                $c = null;
                return ['respuesta'=>1,'accion'=>'actualizar','mensaje'=>'Tipo Usuario actualizado'];
            }

            $c->rollBack();
            $c = null;
            return ['respuesta'=>0,'accion'=>'actualizar','mensaje'=>'Error al actualizar'];
        } catch (\Throwable $e) {
            if (isset($c)) {
                $c->rollBack();
                $c = null;
            }
            return ['respuesta'=>0,'accion'=>'actualizar','mensaje'=>$e->getMessage()];
        }
    }

    private function eliminacion(array $d): array {
        $c = $this->getConex2();
        try {
            $c->beginTransaction();
            $sql = "UPDATE rol_usuario SET estatus = 0 WHERE id_rol = :id_tipo";
            $ok = $c->prepare($sql)->execute(['id_tipo'=>$d['id_tipo']]);

            if ($ok) {
                $c->commit();
                $c = null;
                return ['respuesta'=>1,'accion'=>'eliminar','mensaje'=>'Tipo Usuario eliminado'];
            }

            $c->rollBack();
            $c = null;
            return ['respuesta'=>0,'accion'=>'eliminar','mensaje'=>'Error al eliminar'];
        } catch (\Throwable $e) {
            if (isset($c)) {
                $c->rollBack();
                $c = null;
            }
            return ['respuesta'=>0,'accion'=>'eliminar','mensaje'=>$e->getMessage()];
        }
    }

    public function consultar(): array {
        $c = $this->getConex2();
        $stmt = $c->prepare("SELECT * FROM rol_usuario WHERE estatus >= 1 AND id_rol > 1");
        $stmt->execute();
        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $c = null;
        return $data;
    }
}