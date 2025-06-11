<?php

require_once 'conexion.php';
require_once 'tipousuario.php';

class Usuario extends Conexion
{
    private $encryptionKey = "MotorLoveMakeup"; 
    private $cipherMethod = "AES-256-CBC";
    private $objtipousuario;
    
    function __construct() {
        parent::__construct();
        $this->objtipousuario = new Tipousuario();
    }

    private function encryptClave($clave) {
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($this->cipherMethod)); 
        $encrypted = openssl_encrypt($clave, $this->cipherMethod, $this->encryptionKey, 0, $iv);
        return base64_encode($iv . $encrypted);
    }

    private function decryptClave($claveEncriptada) {
        $data = base64_decode($claveEncriptada);
        $ivLength = openssl_cipher_iv_length($this->cipherMethod);
        $iv = substr($data, 0, $ivLength);
        $encrypted = substr($data, $ivLength);
        return openssl_decrypt($encrypted, $this->cipherMethod, $this->encryptionKey, 0, $iv);
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

    public function procesarUsuario($jsonDatos) {
        $datos = json_decode($jsonDatos, true);
        $operacion = $datos['operacion'];
        $datosProcesar = $datos['datos'];
        
        try {
            switch ($operacion) {
                case 'registrar':
                    if ($this->verificarExistencia('cedula', $datosProcesar['cedula'])) {
                        return ['respuesta' => 0, 'mensaje' => 'La cédula ya está registrada'];
                    }
                    if ($this->verificarExistencia('correo', $datosProcesar['correo'])) {
                        return ['respuesta' => 0, 'mensaje' => 'El correo ya está registrado'];
                    }
                    $datosProcesar['clave'] = $this->encryptClave($datosProcesar['clave']);
                    return $this->ejecutarRegistro($datosProcesar);
                    
                case 'actualizar':
                    return $this->ejecutarActualizacion($datosProcesar);
                    
                case 'eliminar':
                    return $this->ejecutarEliminacion($datosProcesar);
                    
                default:
                    return ['respuesta' => 0, 'mensaje' => 'Operación no válida'];
            }
        } catch (Exception $e) {
            return ['respuesta' => 0, 'mensaje' => $e->getMessage()];
        }
    }

    private function ejecutarRegistro($datos) {
        $conex = $this->getConex2();
        try {
            $conex->beginTransaction();
            
            $sql = "INSERT INTO usuario(cedula, nombre, apellido, correo, telefono, clave, estatus, id_rol)
                    VALUES(:cedula, :nombre, :apellido, :correo, :telefono, :clave, 1, :id_rol)";
            
            $stmt = $conex->prepare($sql);
            $resultado = $stmt->execute($datos);
            
            if ($resultado) {
                $conex->commit();
                $conex = null;
                return ['respuesta' => 1, 'mensaje' => 'Usuario registrado exitosamente'];
            }
            
            $conex->rollBack();
            $conex = null;
            return ['respuesta' => 0, 'mensaje' => 'Error al registrar usuario'];
            
        } catch (PDOException $e) {
            if ($conex) {
                $conex->rollBack();
                $conex = null;
            }
            throw $e;
        }
    }

    private function ejecutarActualizacion($datos) {
        $conex = $this->getConex2();
        try {
            $conex->beginTransaction();
            
            $sql = "UPDATE usuario 
                    SET cedula = :cedula, 
                        correo = :correo, 
                        estatus = :estatus, 
                        id_rol = :id_rol 
                    WHERE id_persona = :id_persona";
            
            $stmt = $conex->prepare($sql);
            $resultado = $stmt->execute($datos);
            
            if ($resultado) {
                $conex->commit();
                $conex = null;
                return ['respuesta' => 1, 'mensaje' => 'Usuario actualizado exitosamente'];
            }
            
            $conex->rollBack();
            $conex = null;
            return ['respuesta' => 0, 'mensaje' => 'Error al actualizar usuario'];
            
        } catch (PDOException $e) {
            if ($conex) {
                $conex->rollBack();
                $conex = null;
            }
            throw $e;
        }
    }

    private function ejecutarEliminacion($datos) {
        $conex = $this->getConex2();
        try {
            $conex->beginTransaction();
            
            $sql = "UPDATE usuario SET estatus = 0 WHERE id_persona = :id_persona";
            
            $stmt = $conex->prepare($sql);
            $resultado = $stmt->execute($datos);
            
            if ($resultado) {
                $conex->commit();
                $conex = null;
                return ['respuesta' => 1, 'mensaje' => 'Usuario eliminado exitosamente'];
            }
            
            $conex->rollBack();
            $conex = null;
            return ['respuesta' => 0, 'mensaje' => 'Error al eliminar usuario'];
            
        } catch (PDOException $e) {
            if ($conex) {
                $conex->rollBack();
                $conex = null;
            }
            throw $e;
        }
    }

    private function verificarExistencia($campo, $valor) {
        $conex = $this->getConex2();
        try {
            $sql = "SELECT COUNT(*) FROM usuario WHERE $campo = :valor";
            $stmt = $conex->prepare($sql);
            $stmt->execute(['valor' => $valor]);
            $resultado = $stmt->fetchColumn() > 0;
            $conex = null;
            return $resultado;
        } catch (PDOException $e) {
            if ($conex) {
                $conex = null;
            }
            throw $e;
        }
    }

    public function consultar() {
        $conex = $this->getConex2();
        try {
            $sql = "SELECT p.*, ru.id_rol, ru.nombre AS nombre_tipo, ru.nivel
                    FROM usuario p 
                    INNER JOIN rol_usuario ru ON p.id_rol = ru.id_rol
                    WHERE ru.nivel IN (2, 3)";
                    
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

    public function obtenerRol() {
        return $this->objtipousuario->consultar();
    }
}
