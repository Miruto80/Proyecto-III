<?php
require_once 'conexion.php';

class Entrada extends Conexion {
    private $conex;
    private $id_compra;
    private $fecha_entrada;
    private $id_proveedor;
    private $detalles; // Para almacenar los detalles de la compra (productos)

    function __construct() {
        $this->conex = new Conexion();
        $this->conex = $this->conex->Conex();
    }

    public function registrar() {
        try {
            $this->conex->beginTransaction();
            
            // Validación de datos antes de insertar
            if (empty($this->fecha_entrada) || empty($this->id_proveedor) || empty($this->detalles)) {
                return ['respuesta' => 0, 'accion' => 'incluir', 'error' => 'Datos incompletos'];
            }
            
            // Insertamos la cabecera de la compra
            $registro = "INSERT INTO compra(fecha_entrada, id_proveedor) VALUES (:fecha_entrada, :id_proveedor)";
            $strExec = $this->conex->prepare($registro);
            $strExec->bindParam(':fecha_entrada', $this->fecha_entrada);
            $strExec->bindParam(':id_proveedor', $this->id_proveedor);
            $resul = $strExec->execute();
            
            if (!$resul) {
                $this->conex->rollBack();
                $error = $strExec->errorInfo();
                return ['respuesta' => 0, 'accion' => 'incluir', 'error' => $error[2]];
            }
            
            // Obtenemos el ID de la compra recién insertada
            $id_compra = $this->conex->lastInsertId();
            
            // Insertamos los detalles de la compra
            if (isset($this->detalles) && !empty($this->detalles)) {
                foreach ($this->detalles as $detalle) {
                    // Validación de datos del detalle
                    if (empty($detalle['id_producto']) || empty($detalle['cantidad']) || 
                        empty($detalle['precio_unitario']) || empty($detalle['precio_total'])) {
                        $this->conex->rollBack();
                        return ['respuesta' => 0, 'accion' => 'incluir', 'error' => 'Datos de producto incompletos'];
                    }
                    
                    // Verificamos que el producto exista
                    $producto_existe = $this->verificarProducto($detalle['id_producto']);
                    if (!$producto_existe) {
                        $this->conex->rollBack();
                        return ['respuesta' => 0, 'accion' => 'incluir', 'error' => 'Producto no encontrado'];
                    }
                    
                    $registro_detalle = "INSERT INTO compra_detalles(cantidad, precio_total, precio_unitario, id_compra, id_producto) VALUES (:cantidad, :precio_total, :precio_unitario, :id_compra, :id_producto)";
                    $strExecDetalle = $this->conex->prepare($registro_detalle);
                    $strExecDetalle->bindParam(':cantidad', $detalle['cantidad'], PDO::PARAM_INT);
                    $strExecDetalle->bindParam(':precio_total', $detalle['precio_total'], PDO::PARAM_STR);
                    $strExecDetalle->bindParam(':precio_unitario', $detalle['precio_unitario'], PDO::PARAM_STR);
                    $strExecDetalle->bindParam(':id_compra', $id_compra, PDO::PARAM_INT);
                    $strExecDetalle->bindParam(':id_producto', $detalle['id_producto'], PDO::PARAM_INT);
                    $resulDetalle = $strExecDetalle->execute();
                    
                    if (!$resulDetalle) {
                        $this->conex->rollBack();
                        $error = $strExecDetalle->errorInfo();
                        return ['respuesta' => 0, 'accion' => 'incluir', 'error' => $error[2]];
                    }
                    
                    // Actualizamos el stock del producto
                    $actualizar_stock = "UPDATE productos SET stock_disponible = stock_disponible + :cantidad WHERE id_producto = :id_producto";
                    $strExecStock = $this->conex->prepare($actualizar_stock);
                    $strExecStock->bindParam(':cantidad', $detalle['cantidad'], PDO::PARAM_INT);
                    $strExecStock->bindParam(':id_producto', $detalle['id_producto'], PDO::PARAM_INT);
                    $resulStock = $strExecStock->execute();
                    
                    if (!$resulStock) {
                        $this->conex->rollBack();
                        $error = $strExecStock->errorInfo();
                        return ['respuesta' => 0, 'accion' => 'incluir', 'error' => $error[2]];
                    }
                }
            } else {
                $this->conex->rollBack();
                return ['respuesta' => 0, 'accion' => 'incluir', 'error' => 'No hay productos en la compra'];
            }
            
            $this->conex->commit();
            return ['respuesta' => 1, 'accion' => 'incluir', 'id_compra' => $id_compra];
            
        } catch (Exception $e) {
            if ($this->conex->inTransaction()) {
                $this->conex->rollBack();
            }
            return ['respuesta' => 0, 'accion' => 'incluir', 'error' => $e->getMessage()];
        }
    }

    public function modificar() {
        try {
            $this->conex->beginTransaction();
            
            // Validar que exista la compra
            $compra_existe = $this->consultarCompra($this->id_compra);
            if (empty($compra_existe)) {
                return ['respuesta' => 0, 'accion' => 'actualizar', 'error' => 'La compra no existe'];
            }
            
            // Actualizamos la cabecera de la compra
            $registro = "UPDATE compra SET fecha_entrada = :fecha_entrada, id_proveedor = :id_proveedor WHERE id_compra = :id_compra";
            $strExec = $this->conex->prepare($registro);
            $strExec->bindParam(':fecha_entrada', $this->fecha_entrada);
            $strExec->bindParam(':id_proveedor', $this->id_proveedor, PDO::PARAM_INT);
            $strExec->bindParam(':id_compra', $this->id_compra, PDO::PARAM_INT);
            $resul = $strExec->execute();
            
            if (!$resul) {
                $this->conex->rollBack();
                $error = $strExec->errorInfo();
                return ['respuesta' => 0, 'accion' => 'actualizar', 'error' => $error[2]];
            }
            
            // Primero, recuperamos los detalles actuales para ajustar el stock
            $query = "SELECT id_producto, cantidad FROM compra_detalles WHERE id_compra = :id_compra";
            $strExecConsulta = $this->conex->prepare($query);
            $strExecConsulta->bindParam(':id_compra', $this->id_compra, PDO::PARAM_INT);
            $resultado = $strExecConsulta->execute();
            
            if (!$resultado) {
                $this->conex->rollBack();
                $error = $strExecConsulta->errorInfo();
                return ['respuesta' => 0, 'accion' => 'actualizar', 'error' => $error[2]];
            }
            
            $detalles_actuales = $strExecConsulta->fetchAll(PDO::FETCH_ASSOC);
            
            // Restamos el stock de los productos que ya estaban en el detalle
            foreach ($detalles_actuales as $detalle_actual) {
                $actualizar_stock = "UPDATE productos SET stock_disponible = stock_disponible - :cantidad WHERE id_producto = :id_producto";
                $strExecStock = $this->conex->prepare($actualizar_stock);
                $strExecStock->bindParam(':cantidad', $detalle_actual['cantidad'], PDO::PARAM_INT);
                $strExecStock->bindParam(':id_producto', $detalle_actual['id_producto'], PDO::PARAM_INT);
                $resulStock = $strExecStock->execute();
                
                if (!$resulStock) {
                    $this->conex->rollBack();
                    $error = $strExecStock->errorInfo();
                    return ['respuesta' => 0, 'accion' => 'actualizar', 'error' => $error[2]];
                }
            }
            
            // Eliminamos los detalles actuales
            $eliminar_detalles = "DELETE FROM compra_detalles WHERE id_compra = :id_compra";
            $strExecEliminar = $this->conex->prepare($eliminar_detalles);
            $strExecEliminar->bindParam(':id_compra', $this->id_compra, PDO::PARAM_INT);
            $resulEliminar = $strExecEliminar->execute();
            
            if (!$resulEliminar) {
                $this->conex->rollBack();
                $error = $strExecEliminar->errorInfo();
                return ['respuesta' => 0, 'accion' => 'actualizar', 'error' => $error[2]];
            }
            
            // Insertamos los nuevos detalles
            if (isset($this->detalles) && !empty($this->detalles)) {
                foreach ($this->detalles as $detalle) {
                    // Validación de datos del detalle
                    if (empty($detalle['id_producto']) || empty($detalle['cantidad']) || 
                        empty($detalle['precio_unitario']) || empty($detalle['precio_total'])) {
                        $this->conex->rollBack();
                        return ['respuesta' => 0, 'accion' => 'actualizar', 'error' => 'Datos de producto incompletos'];
                    }
                    
                    // Verificamos que el producto exista
                    $producto_existe = $this->verificarProducto($detalle['id_producto']);
                    if (!$producto_existe) {
                        $this->conex->rollBack();
                        return ['respuesta' => 0, 'accion' => 'actualizar', 'error' => 'Producto no encontrado'];
                    }
                    
                    $registro_detalle = "INSERT INTO compra_detalles(cantidad, precio_total, precio_unitario, id_compra, id_producto) VALUES (:cantidad, :precio_total, :precio_unitario, :id_compra, :id_producto)";
                    $strExecDetalle = $this->conex->prepare($registro_detalle);
                    $strExecDetalle->bindParam(':cantidad', $detalle['cantidad'], PDO::PARAM_INT);
                    $strExecDetalle->bindParam(':precio_total', $detalle['precio_total'], PDO::PARAM_STR);
                    $strExecDetalle->bindParam(':precio_unitario', $detalle['precio_unitario'], PDO::PARAM_STR);
                    $strExecDetalle->bindParam(':id_compra', $this->id_compra, PDO::PARAM_INT);
                    $strExecDetalle->bindParam(':id_producto', $detalle['id_producto'], PDO::PARAM_INT);
                    $resulDetalle = $strExecDetalle->execute();
                    
                    if (!$resulDetalle) {
                        $this->conex->rollBack();
                        $error = $strExecDetalle->errorInfo();
                        return ['respuesta' => 0, 'accion' => 'actualizar', 'error' => $error[2]];
                    }
                    
                    // Actualizamos el stock del producto
                    $actualizar_stock = "UPDATE productos SET stock_disponible = stock_disponible + :cantidad WHERE id_producto = :id_producto";
                    $strExecStock = $this->conex->prepare($actualizar_stock);
                    $strExecStock->bindParam(':cantidad', $detalle['cantidad'], PDO::PARAM_INT);
                    $strExecStock->bindParam(':id_producto', $detalle['id_producto'], PDO::PARAM_INT);
                    $resulStock = $strExecStock->execute();
                    
                    if (!$resulStock) {
                        $this->conex->rollBack();
                        $error = $strExecStock->errorInfo();
                        return ['respuesta' => 0, 'accion' => 'actualizar', 'error' => $error[2]];
                    }
                }
            } else {
                $this->conex->rollBack();
                return ['respuesta' => 0, 'accion' => 'actualizar', 'error' => 'No hay productos en la compra'];
            }
            
            $this->conex->commit();
            return ['respuesta' => 1, 'accion' => 'actualizar'];
            
        } catch (Exception $e) {
            if ($this->conex->inTransaction()) {
                $this->conex->rollBack();
            }
            return ['respuesta' => 0, 'accion' => 'actualizar', 'error' => $e->getMessage()];
        }
    }

    public function eliminar() {
        try {
            $this->conex->beginTransaction();
            
            // Validar que exista la compra
            $compra_existe = $this->consultarCompra($this->id_compra);
            if (empty($compra_existe)) {
                return ['respuesta' => 0, 'accion' => 'eliminar', 'error' => 'La compra no existe'];
            }
            
            // Recuperamos los detalles para ajustar el stock
            $query = "SELECT id_producto, cantidad FROM compra_detalles WHERE id_compra = :id_compra";
            $strExecConsulta = $this->conex->prepare($query);
            $strExecConsulta->bindParam(':id_compra', $this->id_compra, PDO::PARAM_INT);
            $resultado = $strExecConsulta->execute();
            
            if (!$resultado) {
                $this->conex->rollBack();
                $error = $strExecConsulta->errorInfo();
                return ['respuesta' => 0, 'accion' => 'eliminar', 'error' => $error[2]];
            }
            
            $detalles = $strExecConsulta->fetchAll(PDO::FETCH_ASSOC);
            
            // Verificamos si eliminar afectaría negativamente el stock
            foreach ($detalles as $detalle) {
                $consulta_stock = "SELECT stock_disponible FROM productos WHERE id_producto = :id_producto";
                $strExecStock = $this->conex->prepare($consulta_stock);
                $strExecStock->bindParam(':id_producto', $detalle['id_producto'], PDO::PARAM_INT);
                $strExecStock->execute();
                $stock_actual = $strExecStock->fetchColumn();
                
                if ($stock_actual < $detalle['cantidad']) {
                    $this->conex->rollBack();
                    return [
                        'respuesta' => 0, 
                        'accion' => 'eliminar', 
                        'error' => 'No se puede eliminar la compra porque el producto ID: ' . $detalle['id_producto'] . ' no tiene suficiente stock disponible'
                    ];
                }
            }
            
            // Restamos el stock de los productos
            foreach ($detalles as $detalle) {
                $actualizar_stock = "UPDATE productos SET stock_disponible = stock_disponible - :cantidad WHERE id_producto = :id_producto";
                $strExecStock = $this->conex->prepare($actualizar_stock);
                $strExecStock->bindParam(':cantidad', $detalle['cantidad'], PDO::PARAM_INT);
                $strExecStock->bindParam(':id_producto', $detalle['id_producto'], PDO::PARAM_INT);
                $resulStock = $strExecStock->execute();
                
                if (!$resulStock) {
                    $this->conex->rollBack();
                    $error = $strExecStock->errorInfo();
                    return ['respuesta' => 0, 'accion' => 'eliminar', 'error' => $error[2]];
                }
            }
            
            // Eliminamos los detalles
            $eliminar_detalles = "DELETE FROM compra_detalles WHERE id_compra = :id_compra";
            $strExecEliminar = $this->conex->prepare($eliminar_detalles);
            $strExecEliminar->bindParam(':id_compra', $this->id_compra, PDO::PARAM_INT);
            $resulEliminar = $strExecEliminar->execute();
            
            if (!$resulEliminar) {
                $this->conex->rollBack();
                $error = $strExecEliminar->errorInfo();
                return ['respuesta' => 0, 'accion' => 'eliminar', 'error' => $error[2]];
            }
            
            // Eliminamos la cabecera
            $eliminar_cabecera = "DELETE FROM compra WHERE id_compra = :id_compra";
            $strExecEliminarCab = $this->conex->prepare($eliminar_cabecera);
            $strExecEliminarCab->bindParam(':id_compra', $this->id_compra, PDO::PARAM_INT);
            $resulEliminarCab = $strExecEliminarCab->execute();
            
            if (!$resulEliminarCab) {
                $this->conex->rollBack();
                $error = $strExecEliminarCab->errorInfo();
                return ['respuesta' => 0, 'accion' => 'eliminar', 'error' => $error[2]];
            }
            
            $this->conex->commit();
            return ['respuesta' => 1, 'accion' => 'eliminar'];
            
        } catch (Exception $e) {
            if ($this->conex->inTransaction()) {
                $this->conex->rollBack();
            }
            return ['respuesta' => 0, 'accion' => 'eliminar', 'error' => $e->getMessage()];
        }
    }

    public function consultar() {
        try {
            $registro = "SELECT c.id_compra, c.fecha_entrada, p.nombre as proveedor_nombre, p.id_proveedor 
                        FROM compra c 
                        JOIN proveedor p ON c.id_proveedor = p.id_proveedor 
                        ORDER BY c.id_compra DESC";
            $consulta = $this->conex->prepare($registro);
            $resul = $consulta->execute();
            
            if (!$resul) {
                $error = $consulta->errorInfo();
                return [];
            }
            
            return $consulta->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    public function consultarDetalles($id_compra) {
        try {
            $registro = "SELECT cd.id_detalle_compra, cd.cantidad, cd.precio_total, cd.precio_unitario, 
                        p.id_producto, p.nombre as producto_nombre, p.marca 
                        FROM compra_detalles cd 
                        JOIN productos p ON cd.id_producto = p.id_producto 
                        WHERE cd.id_compra = :id_compra";
            $consulta = $this->conex->prepare($registro);
            $consulta->bindParam(':id_compra', $id_compra, PDO::PARAM_INT);
            $resul = $consulta->execute();
            
            if (!$resul) {
                $error = $consulta->errorInfo();
                return [];
            }
            
            return $consulta->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    public function consultarCompra($id_compra) {
        try {
            $registro = "SELECT c.id_compra, c.fecha_entrada, c.id_proveedor, p.nombre as proveedor_nombre 
                        FROM compra c 
                        JOIN proveedor p ON c.id_proveedor = p.id_proveedor 
                        WHERE c.id_compra = :id_compra";
            $consulta = $this->conex->prepare($registro);
            $consulta->bindParam(':id_compra', $id_compra, PDO::PARAM_INT);
            $resul = $consulta->execute();
            
            if (!$resul) {
                $error = $consulta->errorInfo();
                return [];
            }
            
            return $consulta->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    public function consultarProductos() {
        try {
            $registro = "SELECT id_producto, nombre, marca, stock_disponible FROM productos WHERE estatus = 1";
            $consulta = $this->conex->prepare($registro);
            $resul = $consulta->execute();
            
            if (!$resul) {
                $error = $consulta->errorInfo();
                return [];
            }
            
            return $consulta->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    public function consultarProveedores() {
        try {
            $registro = "SELECT id_proveedor, nombre FROM proveedor WHERE estatus = 1";
            $consulta = $this->conex->prepare($registro);
            $resul = $consulta->execute();
            
            if (!$resul) {
                $error = $consulta->errorInfo();
                return [];
            }
            
            return $consulta->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    // Método para verificar si existe un producto
    private function verificarProducto($id_producto) {
        try {
            $query = "SELECT COUNT(*) FROM productos WHERE id_producto = :id_producto AND estatus = 1";
            $consulta = $this->conex->prepare($query);
            $consulta->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
            $consulta->execute();
            
            return $consulta->fetchColumn() > 0;
        } catch (Exception $e) {
            return false;
        }
    }

    // Setters
    public function set_Id_compra($id_compra) {
        $this->id_compra = $id_compra;
    }

    public function set_Fecha_entrada($fecha_entrada) {
        $this->fecha_entrada = $fecha_entrada;
    }

    public function set_Id_proveedor($id_proveedor) {
        $this->id_proveedor = $id_proveedor;
    }

    public function set_Detalles($detalles) {
        $this->detalles = $detalles;
    }
}
?>