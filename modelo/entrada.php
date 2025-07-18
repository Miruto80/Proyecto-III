<?php
require_once('assets/dompdf/vendor/autoload.php');
use Dompdf\Dompdf;
use Dompdf\Options;
require_once 'conexion.php';

class Entrada extends Conexion {
    
    public function __construct() {
        parent::__construct();
    }

    public function registrarBitacora($jsonDatos) {
        $datos = json_decode($jsonDatos, true);
        
        try {
            $conex = $this->getConex2();
            $conex->beginTransaction();
            
            $sql = "INSERT INTO bitacora (accion, fecha_hora, descripcion, id_persona) 
                    VALUES (:accion, NOW(), :descripcion, :id_persona)";
            
            $stmt = $conex->prepare($sql);
            $stmt->execute($datos);
            
            $conex->commit();
            $conex = null;
            return ['respuesta' => 1, 'mensaje' => 'Registro en bitácora exitoso'];
        } catch (PDOException $e) {
            if ($conex) {
                $conex->rollBack();
                $conex = null;
            }
            return ['respuesta' => 0, 'mensaje' => $e->getMessage()];
        }
    }

    public function procesarCompra($jsonDatos) {
        $datos = json_decode($jsonDatos, true);
        $operacion = $datos['operacion'];
        $datosProcesar = isset($datos['datos']) ? $datos['datos'] : null;
        
        try {
            switch ($operacion) {
                case 'registrar':
                    return $this->ejecutarRegistro($datosProcesar);
                    
                case 'actualizar':
                    return $this->ejecutarActualizacion($datosProcesar);
                    
                case 'eliminar':
                    return $this->ejecutarEliminacion($datosProcesar);
                    
                case 'consultar':
                    return $this->ejecutarConsulta();
                    
                case 'consultarDetalles':
                    return $this->ejecutarConsultaDetalles($datosProcesar);
                    
                case 'consultarProductos':
                    return $this->ejecutarConsultaProductos();
                    
                case 'consultarProveedores':
                    return $this->ejecutarConsultaProveedores();
                    
                default:
                    return ['respuesta' => 0, 'mensaje' => 'Operación no válida'];
            }
        } catch (Exception $e) {
            return ['respuesta' => 0, 'mensaje' => $e->getMessage()];
        }
    }

    private function ejecutarRegistro($datos) {
        $conex = $this->getConex1();
        try {
            $conex->beginTransaction();
            
            // Validación de datos
            if (empty($datos['fecha_entrada']) || empty($datos['id_proveedor']) || empty($datos['productos'])) {
                throw new Exception('Datos incompletos');
            }
            
            // Validar stock máximo para cada producto
            foreach ($datos['productos'] as $producto) {
                $sql = "SELECT stock_disponible, stock_maximo FROM productos WHERE id_producto = :id_producto";
                $stmt = $conex->prepare($sql);
                $stmt->execute(['id_producto' => $producto['id_producto']]);
                $prod_info = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($prod_info) {
                    $stockTotal = $prod_info['stock_disponible'] + $producto['cantidad'];
                    if ($stockTotal > $prod_info['stock_maximo']) {
                        throw new Exception('La cantidad ingresada para el producto ID: ' . $producto['id_producto'] . 
                                         ' superaría el stock máximo permitido (' . $prod_info['stock_maximo'] . ')');
                    }
                }
            }
            
            // Insertar cabecera
            $sql = "INSERT INTO compra(fecha_entrada, id_proveedor) VALUES (:fecha_entrada, :id_proveedor)";
            $stmt = $conex->prepare($sql);
            $stmt->execute([
                'fecha_entrada' => $datos['fecha_entrada'],
                'id_proveedor' => $datos['id_proveedor']
            ]);
            $id_compra = $conex->lastInsertId();
            
            // Insertar detalles
            foreach ($datos['productos'] as $producto) {
                // Verificar producto
                $sql = "SELECT COUNT(*) FROM productos WHERE id_producto = :id_producto AND estatus = 1";
                $stmt = $conex->prepare($sql);
                $stmt->execute(['id_producto' => $producto['id_producto']]);
                if ($stmt->fetchColumn() == 0) {
                    throw new Exception('Producto no encontrado: ' . $producto['id_producto']);
                }
                
                // Insertar detalle
                $sql = "INSERT INTO compra_detalles(cantidad, precio_total, precio_unitario, id_compra, id_producto) 
                       VALUES (:cantidad, :precio_total, :precio_unitario, :id_compra, :id_producto)";
                $stmt = $conex->prepare($sql);
                $stmt->execute([
                    'cantidad' => $producto['cantidad'],
                    'precio_total' => $producto['precio_total'],
                    'precio_unitario' => $producto['precio_unitario'],
                    'id_compra' => $id_compra,
                    'id_producto' => $producto['id_producto']
                ]);
                
                // Actualizar stock
                $sql = "UPDATE productos SET stock_disponible = stock_disponible + :cantidad 
                       WHERE id_producto = :id_producto";
                $stmt = $conex->prepare($sql);
                $stmt->execute([
                    'cantidad' => $producto['cantidad'],
                    'id_producto' => $producto['id_producto']
                ]);
            }
            
            $conex->commit();
            $conex = null;
            
            return ['respuesta' => 1, 'mensaje' => 'Compra registrada exitosamente', 'id_compra' => $id_compra];
            
        } catch (PDOException $e) {
            if ($conex) {
                $conex->rollBack();
                $conex = null;
            }
            throw $e;
        }
    }

    private function ejecutarActualizacion($datos) {
        $conex = $this->getConex1();
        try {
            $conex->beginTransaction();
            
            // Verificar que existe la compra
            $sql = "SELECT COUNT(*) FROM compra WHERE id_compra = :id_compra";
            $stmt = $conex->prepare($sql);
            $stmt->execute(['id_compra' => $datos['id_compra']]);
            if ($stmt->fetchColumn() == 0) {
                throw new Exception('La compra no existe');
            }
            
            // Validar stock máximo para cada producto
            foreach ($datos['productos'] as $producto) {
                $sql = "SELECT p.stock_disponible, p.stock_maximo, COALESCE(cd.cantidad, 0) as cantidad_actual 
                       FROM productos p 
                       LEFT JOIN compra_detalles cd ON cd.id_producto = p.id_producto 
                       AND cd.id_compra = :id_compra 
                       WHERE p.id_producto = :id_producto";
                $stmt = $conex->prepare($sql);
                $stmt->execute([
                    'id_compra' => $datos['id_compra'],
                    'id_producto' => $producto['id_producto']
                ]);
                $prod_info = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($prod_info) {
                    $stockTotal = ($prod_info['stock_disponible'] - $prod_info['cantidad_actual']) + $producto['cantidad'];
                    if ($stockTotal > $prod_info['stock_maximo']) {
                        throw new Exception('La cantidad ingresada para el producto ID: ' . $producto['id_producto'] . 
                                         ' superaría el stock máximo permitido (' . $prod_info['stock_maximo'] . ')');
                    }
                }
            }
            
            // Actualizar cabecera
            $sql = "UPDATE compra SET fecha_entrada = :fecha_entrada, id_proveedor = :id_proveedor 
                   WHERE id_compra = :id_compra";
            $stmt = $conex->prepare($sql);
            $stmt->execute([
                'fecha_entrada' => $datos['fecha_entrada'],
                'id_proveedor' => $datos['id_proveedor'],
                'id_compra' => $datos['id_compra']
            ]);
            
            // Obtener detalles actuales para ajustar stock
            $sql = "SELECT id_producto, cantidad FROM compra_detalles WHERE id_compra = :id_compra";
            $stmt = $conex->prepare($sql);
            $stmt->execute(['id_compra' => $datos['id_compra']]);
            $detalles_actuales = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Restar stock actual
            foreach ($detalles_actuales as $detalle) {
                $sql = "UPDATE productos SET stock_disponible = stock_disponible - :cantidad 
                       WHERE id_producto = :id_producto";
                $stmt = $conex->prepare($sql);
                $stmt->execute([
                    'cantidad' => $detalle['cantidad'],
                    'id_producto' => $detalle['id_producto']
                ]);
            }
            
            // Eliminar detalles actuales
            $sql = "DELETE FROM compra_detalles WHERE id_compra = :id_compra";
            $stmt = $conex->prepare($sql);
            $stmt->execute(['id_compra' => $datos['id_compra']]);
            
            // Insertar nuevos detalles
            foreach ($datos['productos'] as $producto) {
                // Verificar producto
                $sql = "SELECT COUNT(*) FROM productos WHERE id_producto = :id_producto AND estatus = 1";
                $stmt = $conex->prepare($sql);
                $stmt->execute(['id_producto' => $producto['id_producto']]);
                if ($stmt->fetchColumn() == 0) {
                    throw new Exception('Producto no encontrado: ' . $producto['id_producto']);
                }
                
                // Insertar detalle
                $sql = "INSERT INTO compra_detalles(cantidad, precio_total, precio_unitario, id_compra, id_producto) 
                       VALUES (:cantidad, :precio_total, :precio_unitario, :id_compra, :id_producto)";
                $stmt = $conex->prepare($sql);
                $stmt->execute([
                    'cantidad' => $producto['cantidad'],
                    'precio_total' => $producto['precio_total'],
                    'precio_unitario' => $producto['precio_unitario'],
                    'id_compra' => $datos['id_compra'],
                    'id_producto' => $producto['id_producto']
                ]);
                
                // Actualizar stock
                $sql = "UPDATE productos SET stock_disponible = stock_disponible + :cantidad 
                       WHERE id_producto = :id_producto";
                $stmt = $conex->prepare($sql);
                $stmt->execute([
                    'cantidad' => $producto['cantidad'],
                    'id_producto' => $producto['id_producto']
                ]);
            }
            
            $conex->commit();
            $conex = null;
            return ['respuesta' => 1, 'mensaje' => 'Compra actualizada exitosamente'];
            
        } catch (PDOException $e) {
            if ($conex) {
                $conex->rollBack();
                $conex = null;
            }
            throw $e;
        }
    }

    private function ejecutarEliminacion($datos) {
        $conex = $this->getConex1();
        try {
            $conex->beginTransaction();
            
            // Verificar que existe la compra
            $sql = "SELECT COUNT(*) FROM compra WHERE id_compra = :id_compra";
            $stmt = $conex->prepare($sql);
            $stmt->execute(['id_compra' => $datos['id_compra']]);
            if ($stmt->fetchColumn() == 0) {
                throw new Exception('La compra no existe');
            }
            
            // Obtener detalles para ajustar stock
            $sql = "SELECT id_producto, cantidad FROM compra_detalles WHERE id_compra = :id_compra";
            $stmt = $conex->prepare($sql);
            $stmt->execute(['id_compra' => $datos['id_compra']]);
            $detalles = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Verificar stock disponible
            foreach ($detalles as $detalle) {
                $sql = "SELECT stock_disponible FROM productos WHERE id_producto = :id_producto";
                $stmt = $conex->prepare($sql);
                $stmt->execute(['id_producto' => $detalle['id_producto']]);
                $stock_actual = $stmt->fetchColumn();
                
                if ($stock_actual < $detalle['cantidad']) {
                    throw new Exception('No se puede eliminar la compra porque el producto ID: ' . $detalle['id_producto'] . 
                                     ' no tiene suficiente stock disponible');
                }
            }
            
            // Actualizar stock
            foreach ($detalles as $detalle) {
                $sql = "UPDATE productos SET stock_disponible = stock_disponible - :cantidad 
                       WHERE id_producto = :id_producto";
                $stmt = $conex->prepare($sql);
                $stmt->execute([
                    'cantidad' => $detalle['cantidad'],
                    'id_producto' => $detalle['id_producto']
                ]);
            }
            
            // Eliminar detalles
            $sql = "DELETE FROM compra_detalles WHERE id_compra = :id_compra";
            $stmt = $conex->prepare($sql);
            $stmt->execute(['id_compra' => $datos['id_compra']]);
            
            // Eliminar cabecera
            $sql = "DELETE FROM compra WHERE id_compra = :id_compra";
            $stmt = $conex->prepare($sql);
            $stmt->execute(['id_compra' => $datos['id_compra']]);
            
            $conex->commit();
            $conex = null;
            return ['respuesta' => 1, 'mensaje' => 'Compra eliminada exitosamente'];
            
        } catch (PDOException $e) {
            if ($conex) {
                $conex->rollBack();
                $conex = null;
            }
            throw $e;
        }
    }

    private function ejecutarConsulta() {
        $conex = $this->getConex1();
        try {
            $sql = "SELECT c.id_compra, c.fecha_entrada, p.nombre as proveedor_nombre, p.telefono as proveedor_telefono, p.id_proveedor 
                   FROM compra c 
                   JOIN proveedor p ON c.id_proveedor = p.id_proveedor 
                   ORDER BY c.id_compra DESC";
            $stmt = $conex->prepare($sql);
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $conex = null;
            return ['respuesta' => 1, 'datos' => $resultado];
        } catch (PDOException $e) {
            if ($conex) {
                $conex = null;
            }
            throw $e;
        }
    }

    private function ejecutarConsultaDetalles($datos) {
        $conex = $this->getConex1();
        try {
            $sql = "SELECT cd.id_detalle_compra, cd.cantidad, cd.precio_total, cd.precio_unitario, 
                   p.id_producto, p.nombre as producto_nombre, p.marca 
                   FROM compra_detalles cd 
                   JOIN productos p ON cd.id_producto = p.id_producto 
                   WHERE cd.id_compra = :id_compra";
            $stmt = $conex->prepare($sql);
            $stmt->execute(['id_compra' => $datos['id_compra']]);
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $conex = null;
            return ['respuesta' => 1, 'datos' => $resultado];
        } catch (PDOException $e) {
            if ($conex) {
                $conex = null;
            }
            throw $e;
        }
    }

    private function ejecutarConsultaProductos() {
        $conex = $this->getConex1();
        try {
            $sql = "SELECT id_producto, nombre, marca, stock_disponible FROM productos WHERE estatus = 1";
            $stmt = $conex->prepare($sql);
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $conex = null;
            return ['respuesta' => 1, 'datos' => $resultado];
        } catch (PDOException $e) {
            if ($conex) {
                $conex = null;
            }
            throw $e;
        }
    }

    private function ejecutarConsultaProveedores() {
        $conex = $this->getConex1();
        try {
            $sql = "SELECT id_proveedor, nombre FROM proveedor WHERE estatus = 1";
            $stmt = $conex->prepare($sql);
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $conex = null;
            return ['respuesta' => 1, 'datos' => $resultado];
        } catch (PDOException $e) {
            if ($conex) {
                $conex = null;
            }
            throw $e;
        }
    }
}
?>