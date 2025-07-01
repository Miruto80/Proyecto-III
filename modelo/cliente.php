<?php

require_once 'conexion.php';

class Cliente extends Conexion
{

    function __construct() {
        parent::__construct(); // Llama al constructor de la clase padre
    }

    public function registrarBitacora($jsonDatos) {
        $datos = json_decode($jsonDatos, true);
        return $this->ejecutarSentenciaBitacora($datos);
    }

    private function ejecutarSentenciaBitacora($datos) {
        $conex = $this->getConex2();
        try {
            $conex->beginTransaction();
            
            $sql = "INSERT INTO bitacora (accion, fecha_hora, descripcion, id_persona) 
                    VALUES (:accion, NOW(), :descripcion, :id_persona)";
            
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

/*-----*/

    public function procesarCliente($jsonDatos) {
        $datos = json_decode($jsonDatos, true);
        $operacion = $datos['operacion'];
        $datosProcesar = $datos['datos'];
        
        try {
            switch ($operacion) {
                case 'actualizar':
                    // Verifica si cambió la cédula antes de validar existencia
                    if ($datosProcesar['cedula'] !== $datosProcesar['cedula_actual']) {
                        if ($this->verificarExistencia(['campo' => 'cedula', 'valor' => $datosProcesar['cedula']])) {
                            return ['respuesta' => 0, 'accion' => 'actualizar', 'text' => 'La cédula ya está registrada'];
                        }
                    }

                    // Verifica si cambió el correo antes de validar existencia
                    if ($datosProcesar['correo'] !== $datosProcesar['correo_actual']) {
                        if ($this->verificarExistencia(['campo' => 'correo', 'valor' => $datosProcesar['correo']])) {
                            return ['respuesta' => 0, 'accion' => 'actualizar', 'text' => 'El correo electrónico ya está registrado'];
                        }
                    }

                    return $this->ejecutarActualizacion($datosProcesar);
                                        
                default:
                    return ['respuesta' => 0, 'mensaje' => 'Operación no válida'];
            }
        } catch (Exception $e) {
            return ['respuesta' => 0, 'mensaje' => $e->getMessage()];
        }
    }

        public function consultar() {
            $conex = $this->getConex1();
            try {
                $sql = "SELECT * FROM cliente WHERE estatus >=1";
                        
                $stmt = $conex->prepare($sql);
                $stmt->execute();
                $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $conex = null;
                return $resultado;
            } catch (PDOException $e) {
                if ($conex) {
                    $conex = null;
                }
                throw $e;
            }
        }


    private function ejecutarActualizacion($datos) {
        $conex = $this->getConex1();
        try {
            $conex->beginTransaction();
            
            $sql = "UPDATE cliente 
                        SET cedula = :cedula, 
                            correo = :correo, 
                            estatus = :estatus 
                        WHERE id_persona = :id_persona";
            
               $parametros = [
                'cedula' => $datos['cedula'],
                'correo' => $datos['correo'],
                'estatus' => $datos['estatus'],
                'id_persona' => $datos['id_persona']
                ];

            $stmt = $conex->prepare($sql);
            $resultado = $stmt->execute($parametros);
            
            if ($resultado) {
                $conex->commit();
                $conex = null;
                return ['respuesta' => 1, 'accion' => 'actualizar'];
            }
            
            $conex->rollBack();
            $conex = null;
            return ['respuesta' => 0, 'accion' => 'actualizar'];
            
        } catch (PDOException $e) {
            if ($conex) {
                $conex->rollBack();
                $conex = null;
            }
            throw $e;
        }
    }

     private function verificarExistencia($datos) {
        $conex1 = $this->getConex1();
        $conex2 = $this->getConex2();
        try {
            // Verificar en clientes
            $sql = "SELECT COUNT(*) FROM cliente WHERE {$datos['campo']} = :valor AND estatus >= 1";
            $stmt = $conex1->prepare($sql);
            $stmt->execute(['valor' => $datos['valor']]);
            $existe = $stmt->fetchColumn() > 0;
            
            if (!$existe) {
                // Si no existe en clientes, verificar en usuarios
                $sql = "SELECT COUNT(*) FROM usuario WHERE {$datos['campo']} = :valor AND estatus >= 1";
                $stmt = $conex2->prepare($sql);
                $stmt->execute(['valor' => $datos['valor']]);
                $existe = $stmt->fetchColumn() > 0;
            }
            
            $conex1 = null;
            $conex2 = null;
            return $existe;
        } catch (PDOException $e) {
            if ($conex1) $conex1 = null;
            if ($conex2) $conex2 = null;
            throw $e;
        }
    }

}
