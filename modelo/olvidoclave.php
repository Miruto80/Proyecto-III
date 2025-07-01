<?php

require_once 'conexion.php';

class Olvido extends Conexion{

    private $encryptionKey = "MotorLoveMakeup"; 
    private $cipherMethod = "AES-256-CBC";
    
    function __construct() {
       parent::__construct(); // Llama al constructor de la clase padre
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

/*--------*/
     
   public function procesarOlvido($jsonDatos) {
        $datos = json_decode($jsonDatos, true);
        $operacion = $datos['operacion'];
        $datosProcesar = $datos['datos'];
        
        try {
            switch ($operacion) {
                 case 'actualizar':
                     return $this->ejecutarActualizacionPorOrigen($datosProcesar);            
                default:
                    return ['respuesta' => 0, 'mensaje' => 'Operación no válida'];
            }
        } catch (Exception $e) {
            return ['respuesta' => 0, 'mensaje' => $e->getMessage()];
        }
    }

    private function ejecutarActualizacionPorOrigen($datosProcesar) {
        if (isset($datosProcesar['tabla_origen']) && $datosProcesar['tabla_origen'] == 1) {
            return $this->ejecutarActualizacionCliente($datosProcesar);
        } else {
            return $this->ejecutarActualizacionUsuario($datosProcesar);
        }
    }

    private function ejecutarActualizacionCliente($datos) {
        $conex = $this->getConex1();
        try {
            $conex->beginTransaction();
            
            $sql = "UPDATE cliente 
                        SET  clave = :clave
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

     private function ejecutarActualizacionUsuario($datos) {
        $conex = $this->getConex2();
        try {
            $conex->beginTransaction();
            
            $sql = "UPDATE usuario 
                        SET  clave = :clave
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

   
   
  
}
