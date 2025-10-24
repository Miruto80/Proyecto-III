<?php
require_once __DIR__ . '/../assets/dompdf/vendor/autoload.php';
use Dompdf\Dompdf;

require_once __DIR__ . '/conexion.php'; 

class proveedor extends Conexion {
 
    private $bitacoraObj;

    function __construct() {
        parent::__construct();
        require_once __DIR__ . '/bitacora.php';    
        $this->bitacoraObj = new Bitacora();
    }


    /**
     * Guarda una entrada en la bitácora para este módulo.
     * Retorna true si no hubo excepción, false en caso contrario.
     */
    public function registrarBitacora(string $jsonDatos): bool {
        $datos = json_decode($jsonDatos, true);
        try {
            $this->bitacoraObj->registrarOperacion(
                $datos['accion'],
                'proveedor',
                $datos
            );
            return true;
        } catch (\Throwable $e) {
            error_log('Bitacora fallo (proveedor): ' . $e->getMessage());
            return false;
        }
    }

    public function procesarProveedor(string $jsonDatos): array {
        $payload   = json_decode($jsonDatos, true);
        $operacion = $payload['operacion'] ?? '';
        $datos     = $payload['datos']    ?? [];

        try {
            switch ($operacion) {
                case 'registrar':
                    return $this->ejecutarRegistro($datos);
                case 'actualizar':
                    return $this->ejecutarActualizacion($datos);
                case 'eliminar':
                    return $this->ejecutarEliminacion($datos);
                default:
                    return ['respuesta'=>0, 'accion'=>$operacion, 'mensaje'=>'Operación inválida'];
            }
        } catch (\Exception $e) {
            return ['respuesta'=>0, 'accion'=>$operacion, 'mensaje'=>$e->getMessage()];
        }
    }

    //---------------------------------------------------
    // 3) Métodos privados de cada operación
    //---------------------------------------------------
    private function ejecutarRegistro(array $d): array {
        $conex = $this->getConex1();
        try {
            // Verificar si ya existe un proveedor con el mismo número de documento
            $sqlCheck = "SELECT COUNT(*) FROM proveedor WHERE numero_documento = :numero_documento AND tipo_documento = :tipo_documento AND estatus = 1";
            $stmtCheck = $conex->prepare($sqlCheck);
            $stmtCheck->execute([
                'numero_documento' => $d['numero_documento'],
                'tipo_documento' => $d['tipo_documento']
            ]);
            
            if ($stmtCheck->fetchColumn() > 0) {
                throw new Exception("Ya existe un proveedor registrado con el mismo tipo y número de documento.");
            }
            
            $conex->beginTransaction();
            $sql = "INSERT INTO proveedor(numero_documento, tipo_documento, nombre, correo, telefono, direccion, estatus)
                    VALUES (:numero_documento, :tipo_documento, :nombre, :correo, :telefono, :direccion, 1)";
            $stmt = $conex->prepare($sql);
            $ok   = $stmt->execute($d);
            if ($ok) {
                $conex->commit();
                $conex = null;
                return ['respuesta'=>1, 'accion'=>'incluir', 'mensaje'=>'Proveedor registrado'];
            }
            $conex->rollBack();
            $conex = null;
            return ['respuesta'=>0, 'accion'=>'incluir', 'mensaje'=>'Error al registrar'];
        } catch (\PDOException $e) {
            if ($conex) { $conex->rollBack(); $conex = null; }
            throw $e;
        }
    }

    private function ejecutarActualizacion(array $d): array {
        $conex = $this->getConex1();
        try {
            // Verificar si ya existe otro proveedor con el mismo número de documento
            $sqlCheck = "SELECT COUNT(*) FROM proveedor WHERE numero_documento = :numero_documento AND tipo_documento = :tipo_documento AND id_proveedor != :id_proveedor AND estatus = 1";
            $stmtCheck = $conex->prepare($sqlCheck);
            $stmtCheck->execute([
                'numero_documento' => $d['numero_documento'],
                'tipo_documento' => $d['tipo_documento'],
                'id_proveedor' => $d['id_proveedor']
            ]);
            
            if ($stmtCheck->fetchColumn() > 0) {
                throw new Exception("Ya existe otro proveedor registrado con el mismo tipo y número de documento.");
            }
            
            $conex->beginTransaction();
            $sql = "UPDATE proveedor SET
                        numero_documento = :numero_documento,
                        tipo_documento   = :tipo_documento,
                        nombre           = :nombre,
                        correo           = :correo,
                        telefono         = :telefono,
                        direccion        = :direccion
                    WHERE id_proveedor = :id_proveedor";
            $stmt = $conex->prepare($sql);
            $ok   = $stmt->execute($d);
            if ($ok) {
                $conex->commit();
                $conex = null;
                return ['respuesta'=>1, 'accion'=>'actualizar', 'mensaje'=>'Proveedor actualizado'];
            }
            $conex->rollBack();
            $conex = null;
            return ['respuesta'=>0, 'accion'=>'actualizar', 'mensaje'=>'Error al actualizar'];
        } catch (\PDOException $e) {
            if ($conex) { $conex->rollBack(); $conex = null; }
            throw $e;
        }
    }

    private function ejecutarEliminacion(array $d): array {
        $conex = $this->getConex1();
        try {
            $conex->beginTransaction();
            $sql = "UPDATE proveedor SET estatus = 0 WHERE id_proveedor = :id_proveedor";
            $stmt = $conex->prepare($sql);
            $ok   = $stmt->execute($d);
            if ($ok) {
                $conex->commit();
                $conex = null;
                return ['respuesta'=>1, 'accion'=>'eliminar', 'mensaje'=>'Proveedor eliminado'];
            }
            $conex->rollBack();
            $conex = null;
            return ['respuesta'=>0, 'accion'=>'eliminar', 'mensaje'=>'Error al eliminar'];
        } catch (\PDOException $e) {
            if ($conex) { $conex->rollBack(); $conex = null; }
            throw $e;
        }
    }

    //---------------------------------------------------
    // 4) Consultas "simples"
    //---------------------------------------------------
    public function consultar(): array {
        $conex = $this->getConex1();
        $sql   = "SELECT * FROM proveedor WHERE estatus = 1 ORDER BY id_proveedor DESC";
        $stmt  = $conex->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $conex = null;
        return $data;
    }

    public function consultarPorId(int $id): array {
        $conex = $this->getConex1();
        $sql   = "SELECT * FROM proveedor WHERE id_proveedor = :id_proveedor";
        $stmt  = $conex->prepare($sql);
        $stmt->execute(['id_proveedor'=>$id]);
        $row   = $stmt->fetch(\PDO::FETCH_ASSOC) ?: [];
        $conex  = null;
        return $row;
    }
}