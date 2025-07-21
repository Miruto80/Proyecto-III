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

    public function procesarUsuario($jsonDatos) {
        $datos = json_decode($jsonDatos, true);
        $operacion = $datos['operacion'];
        $datosProcesar = $datos['datos'];
        
        try {
            switch ($operacion) {
                case 'registrar':
                    if ($this->verificarExistencia(['campo' => 'cedula', 'valor' => $datosProcesar['cedula']])) {
                        return ['respuesta' => 0, 'accion' => 'incluir', 'text' => 'La cédula ya está registrada'];
                    }
                    if ($this->verificarExistencia(['campo' => 'correo', 'valor' => $datosProcesar['correo']])) {
                        return ['respuesta' => 0, 'accion' => 'incluir', 'text' => 'El correo electrónico ya está registrado'];
                    }
                    $datosProcesar['clave'] = $this->encryptClave($datosProcesar['clave']);
                    return $this->ejecutarRegistro($datosProcesar);
                    
               case 'actualizar':
                    $datosProcesar['insertar_permisos'] = false;

                    if ($datosProcesar['id_rol'] !== $datosProcesar['rol_actual']) {
                        $resultado = $this->ejecutarEliminacionPermisos($datosProcesar['id_persona']);
                        if ($resultado['respuesta'] === 0) {
                            return ['respuesta' => 0, 'accion' => 'actualizar', 'text' => 'No se pudo eliminar permisos'];
                        }
                        $datosProcesar['insertar_permisos'] = true;
                    }

                    return $this->ejecutarActualizacion($datosProcesar);
                    
                case 'eliminar':
                    return $this->ejecutarEliminacion($datosProcesar);
                
                case 'actualizar_permisos':
                    return $this->actualizarLotePermisos($datosProcesar);
    
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
            
            $parametros = [
                'cedula' => $datos['cedula'],
                'nombre' => $datos['nombre'],
                'apellido' => $datos['apellido'],
                'correo' => $datos['correo'],
                'telefono' => $datos['telefono'],
                'clave' => $datos['clave'],
                'id_rol' => $datos['id_rol']
                ];

            $stmt = $conex->prepare($sql);
            $resultado = $stmt->execute($parametros);
            
            $id_persona = $conex->lastInsertId();
 
            $nivel = $datos['nivel'];
                $datosPermisos = $this->generarPermisosPorNivel($id_persona, $nivel);

                $sqlPermiso = "INSERT INTO permiso (id_modulo, id_persona, accion, estado)
                            VALUES (:id_modulo, :id_persona, :accion, :estado)";
                $stmtPermiso = $conex->prepare($sqlPermiso);

                foreach ($datosPermisos as $permiso) {
                    $stmtPermiso->execute($permiso);
                }

                $conex->commit();
                $conex = null;
                return ['respuesta' => 1, 'accion' => 'incluir'];
                    
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

        $parametros = [
            'cedula' => $datos['cedula'],
            'correo' => $datos['correo'],
            'estatus' => $datos['estatus'],
            'id_rol' => $datos['id_rol'],
            'id_persona' => $datos['id_persona']
        ];

        $stmt = $conex->prepare($sql);
        $resultado = $stmt->execute($parametros);

        if ($resultado && !empty($datos['insertar_permisos'])) {
            
            $nivel = $datos['nivel'];
            $id_persona = $datos['id_persona'];
            $datosPermisos = $this->generarPermisosPorNivel($id_persona, $nivel);

            $sqlPermiso = "INSERT INTO permiso (id_modulo, id_persona, accion, estado)
                           VALUES (:id_modulo, :id_persona, :accion, :estado)";
            $stmtPermiso = $conex->prepare($sqlPermiso);

            foreach ($datosPermisos as $permiso) {
                $stmtPermiso->execute($permiso);
            }
        }

        $conex->commit();
        $conex = null;
        return ['respuesta' => 1, 'accion' => 'actualizar'];
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
                return ['respuesta' => 1, 'accion' => 'eliminar'];
            }
            
            $conex->rollBack();
            $conex = null;
            return ['respuesta' => 0, 'accion' => 'eliminar'];
            
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

    public function consultar() {
        $conex = $this->getConex2();
        try {
            $sql = "SELECT p.*, ru.id_rol, ru.nombre AS nombre_tipo, ru.nivel
                    FROM usuario p 
                    INNER JOIN rol_usuario ru ON p.id_rol = ru.id_rol
                    WHERE ru.nivel IN (2, 3) AND p.estatus >= 1";
                    
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

   private function generarPermisosPorNivel($id_persona, $nivel) {
    $permisos = [];

    if ($nivel == 3) { // Admin
        $permisos = [
            ['id_modulo' => 1, 'id_persona' => $id_persona, 'accion' => 'ver', 'estado' => '1'],
            ['id_modulo' => 2, 'id_persona' => $id_persona, 'accion' => 'ver', 'estado' => '1'],
            ['id_modulo' => 2, 'id_persona' => $id_persona, 'accion' => 'registrar', 'estado' => '1'],
            ['id_modulo' => 2, 'id_persona' => $id_persona, 'accion' => 'editar', 'estado' => '1'],
            ['id_modulo' => 3, 'id_persona' => $id_persona, 'accion' => 'ver', 'estado' => '1'],
            ['id_modulo' => 3, 'id_persona' => $id_persona, 'accion' => 'registrar', 'estado' => '1'],
            ['id_modulo' => 3, 'id_persona' => $id_persona, 'accion' => 'editar', 'estado' => '1'],
            ['id_modulo' => 3, 'id_persona' => $id_persona, 'accion' => 'eliminar', 'estado' => '1'],
            ['id_modulo' => 3, 'id_persona' => $id_persona, 'accion' => 'especial', 'estado' => '1'],
            ['id_modulo' => 4, 'id_persona' => $id_persona, 'accion' => 'ver', 'estado' => '1'],
            ['id_modulo' => 4, 'id_persona' => $id_persona, 'accion' => 'registrar', 'estado' => '1'],
            ['id_modulo' => 4, 'id_persona' => $id_persona, 'accion' => 'especial', 'estado' => '1'],
            ['id_modulo' => 5, 'id_persona' => $id_persona, 'accion' => 'ver', 'estado' => '1'],
            ['id_modulo' => 5, 'id_persona' => $id_persona, 'accion' => 'registrar', 'estado' => '1'],
            ['id_modulo' => 5, 'id_persona' => $id_persona, 'accion' => 'editar', 'estado' => '1'],
            ['id_modulo' => 6, 'id_persona' => $id_persona, 'accion' => 'ver', 'estado' => '1'],
            ['id_modulo' => 6, 'id_persona' => $id_persona, 'accion' => 'registrar', 'estado' => '1'],
            ['id_modulo' => 6, 'id_persona' => $id_persona, 'accion' => 'editar', 'estado' => '1'],
            ['id_modulo' => 6, 'id_persona' => $id_persona, 'accion' => 'eliminar', 'estado' => '1'],
            ['id_modulo' => 7, 'id_persona' => $id_persona, 'accion' => 'ver', 'estado' => '1'],
            ['id_modulo' => 7, 'id_persona' => $id_persona, 'accion' => 'registrar', 'estado' => '1'],
            ['id_modulo' => 7, 'id_persona' => $id_persona, 'accion' => 'editar', 'estado' => '1'],
            ['id_modulo' => 7, 'id_persona' => $id_persona, 'accion' => 'eliminar', 'estado' => '1'],
            ['id_modulo' => 8, 'id_persona' => $id_persona, 'accion' => 'ver', 'estado' => '1'],
            ['id_modulo' => 8, 'id_persona' => $id_persona, 'accion' => 'editar', 'estado' => '1'],
            ['id_modulo' => 9, 'id_persona' => $id_persona, 'accion' => 'ver', 'estado' => '1'],
            ['id_modulo' => 9, 'id_persona' => $id_persona, 'accion' => 'especial', 'estado' => '1'],
            ['id_modulo' => 10, 'id_persona' => $id_persona, 'accion' => 'ver', 'estado' => '1'],
            ['id_modulo' => 10, 'id_persona' => $id_persona, 'accion' => 'registrar', 'estado' => '1'],
            ['id_modulo' => 10, 'id_persona' => $id_persona, 'accion' => 'editar', 'estado' => '1'],
            ['id_modulo' => 10, 'id_persona' => $id_persona, 'accion' => 'eliminar', 'estado' => '1'],
            ['id_modulo' => 11, 'id_persona' => $id_persona, 'accion' => 'ver', 'estado' => '1'],
            ['id_modulo' => 11, 'id_persona' => $id_persona, 'accion' => 'registrar', 'estado' => '1'],
            ['id_modulo' => 11, 'id_persona' => $id_persona, 'accion' => 'editar', 'estado' => '1'],
            ['id_modulo' => 11, 'id_persona' => $id_persona, 'accion' => 'eliminar', 'estado' => '1'],
            ['id_modulo' => 12, 'id_persona' => $id_persona, 'accion' => 'ver', 'estado' => '0'],
            ['id_modulo' => 13, 'id_persona' => $id_persona, 'accion' => 'ver', 'estado' => '1'],
            ['id_modulo' => 13, 'id_persona' => $id_persona, 'accion' => 'registrar', 'estado' => '1'],
            ['id_modulo' => 13, 'id_persona' => $id_persona, 'accion' => 'editar', 'estado' => '1'],
            ['id_modulo' => 13, 'id_persona' => $id_persona, 'accion' => 'eliminar', 'estado' => '1'],
            ['id_modulo' => 13, 'id_persona' => $id_persona, 'accion' => 'especial', 'estado' => '1'],
            ['id_modulo' => 14, 'id_persona' => $id_persona, 'accion' => 'ver', 'estado' => '1'],
            ['id_modulo' => 14, 'id_persona' => $id_persona, 'accion' => 'registrar', 'estado' => '1'],
            ['id_modulo' => 14, 'id_persona' => $id_persona, 'accion' => 'editar', 'estado' => '1'],
            ['id_modulo' => 14, 'id_persona' => $id_persona, 'accion' => 'eliminar', 'estado' => '1']
        ];
    } elseif ($nivel == 2) { // Usuario básico
        $permisos = [
            ['id_modulo' => 1, 'id_persona' => $id_persona, 'accion' => 'ver', 'estado' => '1'],
            ['id_modulo' => 2, 'id_persona' => $id_persona, 'accion' => 'ver', 'estado' => '0'],
            ['id_modulo' => 2, 'id_persona' => $id_persona, 'accion' => 'registrar', 'estado' => '0'],
            ['id_modulo' => 2, 'id_persona' => $id_persona, 'accion' => 'editar', 'estado' => '0'],
           
            ['id_modulo' => 3, 'id_persona' => $id_persona, 'accion' => 'ver', 'estado' => '1'],
            ['id_modulo' => 3, 'id_persona' => $id_persona, 'accion' => 'registrar', 'estado' => '0'],
            ['id_modulo' => 3, 'id_persona' => $id_persona, 'accion' => 'editar', 'estado' => '0'],
            ['id_modulo' => 3, 'id_persona' => $id_persona, 'accion' => 'eliminar', 'estado' => '0'],
            ['id_modulo' => 3, 'id_persona' => $id_persona, 'accion' => 'especial', 'estado' => '0'],

            ['id_modulo' => 4, 'id_persona' => $id_persona, 'accion' => 'ver', 'estado' => '1'],
            ['id_modulo' => 4, 'id_persona' => $id_persona, 'accion' => 'registrar', 'estado' => '1'],
            ['id_modulo' => 4, 'id_persona' => $id_persona, 'accion' => 'especial', 'estado' => '0'],

            ['id_modulo' => 5, 'id_persona' => $id_persona, 'accion' => 'ver', 'estado' => '1'],
            ['id_modulo' => 5, 'id_persona' => $id_persona, 'accion' => 'registrar', 'estado' => '1'],
            ['id_modulo' => 5, 'id_persona' => $id_persona, 'accion' => 'editar', 'estado' => '0'],

            ['id_modulo' => 6, 'id_persona' => $id_persona, 'accion' => 'ver', 'estado' => '0'],
            ['id_modulo' => 6, 'id_persona' => $id_persona, 'accion' => 'registrar', 'estado' => '0'],
            ['id_modulo' => 6, 'id_persona' => $id_persona, 'accion' => 'editar', 'estado' => '0'],
            ['id_modulo' => 6, 'id_persona' => $id_persona, 'accion' => 'eliminar', 'estado' => '0'],
            
            ['id_modulo' => 7, 'id_persona' => $id_persona, 'accion' => 'ver', 'estado' => '0'],
            ['id_modulo' => 7, 'id_persona' => $id_persona, 'accion' => 'registrar', 'estado' => '0'],
            ['id_modulo' => 7, 'id_persona' => $id_persona, 'accion' => 'editar', 'estado' => '0'],
            ['id_modulo' => 7, 'id_persona' => $id_persona, 'accion' => 'eliminar', 'estado' => '0'],

            ['id_modulo' => 8, 'id_persona' => $id_persona, 'accion' => 'ver', 'estado' => '0'],
            ['id_modulo' => 8, 'id_persona' => $id_persona, 'accion' => 'editar', 'estado' => '0'],

            ['id_modulo' => 9, 'id_persona' => $id_persona, 'accion' => 'ver', 'estado' => '1'],
            ['id_modulo' => 9, 'id_persona' => $id_persona, 'accion' => 'especial', 'estado' => '0'],

            ['id_modulo' => 10, 'id_persona' => $id_persona, 'accion' => 'ver', 'estado' => '0'],
            ['id_modulo' => 10, 'id_persona' => $id_persona, 'accion' => 'registrar', 'estado' => '0'],
            ['id_modulo' => 10, 'id_persona' => $id_persona, 'accion' => 'editar', 'estado' => '0'],
            ['id_modulo' => 10, 'id_persona' => $id_persona, 'accion' => 'eliminar', 'estado' => '0'],

            ['id_modulo' => 11, 'id_persona' => $id_persona, 'accion' => 'ver', 'estado' => '0'],
            ['id_modulo' => 11, 'id_persona' => $id_persona, 'accion' => 'registrar', 'estado' => '0'],
            ['id_modulo' => 11, 'id_persona' => $id_persona, 'accion' => 'editar', 'estado' => '0'],
            ['id_modulo' => 11, 'id_persona' => $id_persona, 'accion' => 'eliminar', 'estado' => '0'],

            ['id_modulo' => 12, 'id_persona' => $id_persona, 'accion' => 'ver', 'estado' => '0'],

            ['id_modulo' => 13, 'id_persona' => $id_persona, 'accion' => 'ver', 'estado' => '0'],
            ['id_modulo' => 13, 'id_persona' => $id_persona, 'accion' => 'registrar', 'estado' => '0'],
            ['id_modulo' => 13, 'id_persona' => $id_persona, 'accion' => 'editar', 'estado' => '0'],
            ['id_modulo' => 13, 'id_persona' => $id_persona, 'accion' => 'eliminar', 'estado' => '0'],
            ['id_modulo' => 13, 'id_persona' => $id_persona, 'accion' => 'especial', 'estado' => '0'],

            ['id_modulo' => 14, 'id_persona' => $id_persona, 'accion' => 'ver', 'estado' => '0'],
            ['id_modulo' => 14, 'id_persona' => $id_persona, 'accion' => 'registrar', 'estado' => '0'],
            ['id_modulo' => 14, 'id_persona' => $id_persona, 'accion' => 'editar', 'estado' => '0'],
            ['id_modulo' => 14, 'id_persona' => $id_persona, 'accion' => 'eliminar', 'estado' => '0']
        ];
    }

    return array_map(function($permiso) {
        return [
            ':id_modulo' => $permiso['id_modulo'],
            ':id_persona' => $permiso['id_persona'],
            ':accion' => $permiso['accion'],
            ':estado' => $permiso['estado'],
        ];
    }, $permisos);
}

private function ejecutarEliminacionPermisos($id_persona) {
    $conex = $this->getConex2();
    try {
        $conex->beginTransaction();

        $sql = "DELETE FROM permiso WHERE id_persona = ?";
        $stmt = $conex->prepare($sql);

        $resultado = $stmt->execute([$id_persona]);

        if ($resultado) {
            $conex->commit();
            $conex = null;
            return ['respuesta' => 1, 'accion' => 'eliminar'];
        }

        $conex->rollBack();
        $conex = null;
        return ['respuesta' => 0, 'accion' => 'eliminar'];
    } catch (PDOException $e) {
        if ($conex) {
            $conex->rollBack();
            $conex = null;
        }
        throw $e;
    }
}

     public function buscar($id_persona) {
        $conex = $this->getConex2();
        try {
        $sql = "SELECT 
                permiso.*, 
                modulo.id_modulo, 
                modulo.nombre
                FROM permiso
                INNER JOIN modulo ON permiso.id_modulo = modulo.id_modulo
                WHERE permiso.id_persona = :id_persona;
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

    public function obtenerNivelPorId($id_persona) {
    $conex = $this->getConex2();
    try {
        $sql = "SELECT r.nivel
                FROM usuario u
                INNER JOIN rol_usuario r ON u.id_rol = r.id_rol
                WHERE u.id_persona = :id_persona";
        $stmt = $conex->prepare($sql);
        $stmt->execute(['id_persona' => $id_persona]);
        $nivel = $stmt->fetchColumn();
        $conex = null;
        return $nivel !== false ? (int)$nivel : null;
    } catch (PDOException $e) {
        if ($conex) $conex = null;
        throw $e;
    }
}



    private function actualizarLotePermisos($lista) {
    $conex = $this->getConex2();
    try {
        $conex->beginTransaction();

        $sql = "UPDATE permiso 
                SET estado = :estado 
                WHERE id_permiso = :id_permiso";

        $stmt = $conex->prepare($sql);

        foreach ($lista as $permiso) {
           
            $stmt->execute([
                'estado' => $permiso['estado'],
                'id_permiso' => $permiso['id_permiso']
            ]);
        }

        $conex->commit();
        $conex = null;
        return ['respuesta' => 1, 'accion' => 'actualizar_permisos'];

    } catch (PDOException $e) {
        if ($conex) {
            $conex->rollBack();
            $conex = null;
        }
        throw $e;
    }
}

}
