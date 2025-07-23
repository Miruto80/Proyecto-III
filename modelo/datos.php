<?php

require_once 'conexion.php';

class Datos extends Conexion{
    
    public function __construct() {
        parent::__construct();
    }

    private function encryptClave($datos) {
        $config = [
            'key' => "MotorLoveMakeup",
            'method' => "AES-256-CBC"
        ];
        
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($config['method']));
        $encrypted = openssl_encrypt($datos['clave'], $config['method'], $config['key'], 0, $iv);
        return base64_encode($iv . $encrypted);
    }

    private function decryptClave($datos) {
        $config = [
            'key' => "MotorLoveMakeup",
            'method' => "AES-256-CBC"
        ];
        
        $data = base64_decode($datos['clave_encriptada']);
        $ivLength = openssl_cipher_iv_length($config['method']);
        $iv = substr($data, 0, $ivLength);
        $encrypted = substr($data, $ivLength);
        return openssl_decrypt($encrypted, $config['method'], $config['key'], 0, $iv);
    }

/*-----*/

    public function procesarUsuario($jsonDatos) {
        $datos = json_decode($jsonDatos, true);
        $operacion = $datos['operacion'];
        $datosProcesar = $datos['datos'];
        
        try {
            switch ($operacion) {
                case 'actualizar':
                    
                    if ($datosProcesar['cedula'] !== $datosProcesar['cedula_actual']) {
                        if ($this->verificarExistencia(['campo' => 'cedula', 'valor' => $datosProcesar['cedula']])) {
                            return ['respuesta' => 0, 'accion' => 'actualizar', 'text' => 'La cédula ya está registrada'];
                        }
                    }

                    if ($datosProcesar['correo'] !== $datosProcesar['correo_actual']) {
                        if ($this->verificarExistencia(['campo' => 'correo', 'valor' => $datosProcesar['correo']])) {
                            return ['respuesta' => 0, 'accion' => 'actualizar', 'text' => 'El correo electrónico ya está registrado'];
                        }
                    }

                    return $this->ejecutarActualizacion($datosProcesar);
                    
                    case 'actualizarclave':
                      
                     if (!$this->validarClaveActual($datosProcesar)) {
                        return ['respuesta' => 0, 'accion' => 'clave', 'text' => 'La clave actual es incorrecta.'];
                    }

                     return $this->ejecutarActualizacionClave($datosProcesar);

                default:
                    return ['respuesta' => 0, 'mensaje' => 'Operación no válida'];
            }
        } catch (Exception $e) {
            return ['respuesta' => 0, 'mensaje' => $e->getMessage()];
        }
    }
    
     private function ejecutarActualizacion($datos) {
        $conex = $this->getConex2();
        try {
            $conex->beginTransaction();
            
            $sql = "UPDATE usuario 
                        SET cedula = :cedula, 
                            correo = :correo, 
                            nombre = :nombre,
                            apellido = :apellido,
                            telefono = :telefono
                        WHERE id_persona = :id_persona";
            
               $parametros = [
                'cedula' => $datos['cedula'],
                'correo' => $datos['correo'],
                'nombre' => $datos['nombre'],
                'apellido' => $datos['apellido'],
                'telefono' => $datos['telefono'],
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

   private function validarClaveActual($datos) {
        $conex = $this->getConex2();
        try {
            $sql = "SELECT clave FROM usuario WHERE id_persona = :id_persona AND estatus >= 1";
            $stmt = $conex->prepare($sql);
            $stmt->execute(['id_persona' => $datos['id_persona']]);
            $resultado = $stmt->fetch(PDO::FETCH_OBJ);

            if ($resultado) {
                $claveDesencriptada = $this->decryptClave(['clave_encriptada' => $resultado->clave]);
                return $claveDesencriptada === $datos['clave_actual'];
            }
            
            $conex = null;
            return false;
        } catch (PDOException $e) {
            if ($conex) $conex = null;
            throw $e;
        }
    }


   private function ejecutarActualizacionClave($datos) {
        $conex = $this->getConex2();
        try {
            $conex->beginTransaction();
            
            $sql = "UPDATE usuario 
                        SET clave = :clave
                        WHERE id_persona = :id_persona";
            
               $parametros = [
                    'clave' => $this->encryptClave(['clave' => $datos['clave']]),
                    'id_persona' => $datos['id_persona']
                ];

            $stmt = $conex->prepare($sql);
            $resultado = $stmt->execute($parametros);
            
            if ($resultado) {
                $conex->commit();
                $conex = null;
                return ['respuesta' => 1, 'accion' => 'clave'];
            }
            
            $conex->rollBack();
            $conex = null;
            return ['respuesta' => 0, 'accion' => 'clave'];
            
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
