<?php

require_once 'conexion.php';

class Login extends Conexion {
    function __construct() {
        parent::__construct();
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

    public function procesarLogin($jsonDatos) {
        $datos = json_decode($jsonDatos, true);
        $operacion = $datos['operacion'];
        $datosProcesar = $datos['datos'];
        
        try {
            switch ($operacion) {
                case 'verificar':
                    return $this->verificarCredenciales($datosProcesar);
                case 'registrar':
                    if ($this->verificarExistencia(['campo' => 'cedula', 'valor' => $datosProcesar['cedula']])) {
                        return ['respuesta' => 0, 'accion' => 'incluir', 'text' => 'La cédula ya está registrada'];
                    }
                    if ($this->verificarExistencia(['campo' => 'correo', 'valor' => $datosProcesar['correo']])) {
                        return ['respuesta' => 0, 'accion' => 'incluir', 'text' => 'El correo electrónico ya está registrado'];
                    }
                    return $this->registrarCliente($datosProcesar);
                case 'validar':
                    return $this->obtenerPersonaPorCedula(['cedula' => $datosProcesar['cedula']]);

                case 'activocliente':
                    return $this->activocliente($datosProcesar);
                    
                case 'activousuaio':
                    return $this->activousuario($datosProcesar);

                default:
                    return ['respuesta' => 0, 'mensaje' => 'Operación no válida'];
            }
        } catch (Exception $e) {
            return ['respuesta' => 0, 'mensaje' => $e->getMessage()];
        }
    }

 private function verificarCredenciales($datos) {
    $conex1 = $this->getConex1();
    $conex2 = $this->getConex2();

    try {
        
        $sql = "SELECT p.*, ru.nombre AS nombre_usuario, ru.nivel, p.clave
                FROM usuario p
                INNER JOIN rol_usuario ru ON p.id_rol = ru.id_rol
                WHERE p.cedula = :cedula AND p.estatus IN (1, 2)";

        $stmt = $conex2->prepare($sql);
        $stmt->execute(['cedula' => $datos['cedula']]);
        $resultado = $stmt->fetchObject();

        if ($resultado) {
            $claveDesencriptada = $this->decryptClave(['clave_encriptada' => $resultado->clave]);
            if ($claveDesencriptada === $datos['clave']) {
                $conex1 = null;
                $conex2 = null;
                return $resultado;
            }
        }

        // Verificar en clientes (estatus 1 o 2)
        $sql = "SELECT * FROM cliente WHERE cedula = :cedula AND estatus IN (1, 2)";
        $stmt = $conex1->prepare($sql);
        $stmt->execute(['cedula' => $datos['cedula']]);
        $resultado = $stmt->fetchObject();

        if ($resultado) {
            $claveDesencriptada = $this->decryptClave(['clave_encriptada' => $resultado->clave]);
            if ($claveDesencriptada === $datos['clave']) {
                $conex1 = null;
                $conex2 = null;
                return $resultado;
            }
        }

        $conex1 = null;
        $conex2 = null;
        return null;

    } catch (PDOException $e) {
        if ($conex1) $conex1 = null;
        if ($conex2) $conex2 = null;
        throw $e;
    }
}



    private function registrarCliente($datos) {
        $conex = $this->getConex1();
        try {
            $conex->beginTransaction();
            
            $sql = "INSERT INTO cliente(cedula, nombre, apellido, correo, telefono, clave, estatus, rol)
                    VALUES(:cedula, :nombre, :apellido, :correo, :telefono, :clave, 1, 1)";
            
            $datosInsertar = [
                'cedula' => $datos['cedula'],
                'nombre' => ucfirst(strtolower($datos['nombre'])),
                'apellido' => ucfirst(strtolower($datos['apellido'])),
                'correo' => strtolower($datos['correo']),
                'telefono' => $datos['telefono'],
                'clave' => $this->encryptClave(['clave' => $datos['clave']])
            ];
            
            $stmt = $conex->prepare($sql);
            $resultado = $stmt->execute($datosInsertar);
            
            if ($resultado) {
                $conex->commit();
                $conex = null;
                return ['respuesta' => 1, 'accion' => 'incluir'];
            }
            
            $conex->rollBack();
            $conex = null;
            return ['respuesta' => 0, 'accion' => 'incluir'];
            
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

    private function obtenerPersonaPorCedula($datos) {
        $conex1 = $this->getConex1();
        $conex2 = $this->getConex2();
        try {
            // Buscar en cliente
            $sql = "SELECT *, 'cliente' AS origen FROM cliente WHERE cedula = :cedula AND estatus >= 1";
            $stmt = $conex1->prepare($sql);
            $stmt->execute(['cedula' => $datos['cedula']]);
            
            if ($stmt->rowCount() > 0) {
                $resultado = $stmt->fetchObject();
                $conex1 = null;
                $conex2 = null;
                return $resultado;
            }

            // Buscar en usuario
            $sql = "SELECT *, 'usuario' AS origen FROM usuario WHERE cedula = :cedula AND estatus >= 1";
            $stmt = $conex2->prepare($sql);
            $stmt->execute(['cedula' => $datos['cedula']]);
            
            if ($stmt->rowCount() > 0) {
                $resultado = $stmt->fetchObject();
                $conex1 = null;
                $conex2 = null;
                return $resultado;
            }
            
            $conex1 = null;
            $conex2 = null;
            return null;
        } catch (PDOException $e) {
            if ($conex1) $conex1 = null;
            if ($conex2) $conex2 = null;
            throw $e;
        }
    }



     public function consultar($id_persona) {
        $conex = $this->getConex2();
        try {
        $sql = "SELECT 
                ru.id_rol, 
                p.id_persona, 
                permiso.*
                FROM usuario p
                INNER JOIN rol_usuario ru ON p.id_rol = ru.id_rol
                INNER JOIN permiso ON p.id_persona = permiso.id_persona
                WHERE p.id_persona = :id_persona
                ";
                    
           $stmt = $conex->prepare($sql);
             $stmt->execute(['id_persona' => $id_persona]);

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


    private function activocliente($datos) {
        $conex = $this->getConex1();
        try {
            $conex->beginTransaction();
            
           $sql = "UPDATE cliente  SET estatus = :estatus, 
                    WHERE id_persona = :id_persona";
            
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

    private function activousuario($datos) {
        $conex = $this->getConex2();
        try {
            $conex->beginTransaction();
            
                $sql = "UPDATE usuario  SET estatus = :estatus, 
                        WHERE id_persona = :id_persona";
            
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
}