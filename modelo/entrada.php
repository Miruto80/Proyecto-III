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
            
            // Insertamos la cabecera de la compra
            $registro = "INSERT INTO compra(fecha_entrada, id_proveedor) VALUES (:fecha_entrada, :id_proveedor)";
            $strExec = $this->conex->prepare($registro);
            $strExec->bindParam(':fecha_entrada', $this->fecha_entrada);
            $strExec->bindParam(':id_proveedor', $this->id_proveedor);
            $resul = $strExec->execute();
            
            if (!$resul) {
                $this->conex->rollBack();
                return ['respuesta' => 0, 'accion' => 'incluir'];
            }
            
            // Obtenemos el ID de la compra recién insertada
            $id_compra = $this->conex->lastInsertId();
            
            // Insertamos los detalles de la compra
            if (isset($this->detalles) && !empty($this->detalles)) {
                foreach ($this->detalles as $detalle) {
                    $registro_detalle = "INSERT INTO compra_detalles(cantidad, precio_total, precio_unitario, id_compra, id_producto) VALUES (:cantidad, :precio_total, :precio_unitario, :id_compra, :id_producto)";
                    $strExecDetalle = $this->conex->prepare($registro_detalle);
                    $strExecDetalle->bindParam(':cantidad', $detalle['cantidad']);
                    $strExecDetalle->bindParam(':precio_total', $detalle['precio_total']);
                    $strExecDetalle->bindParam(':precio_unitario', $detalle['precio_unitario']);
                    $strExecDetalle->bindParam(':id_compra', $id_compra);
                    $strExecDetalle->bindParam(':id_producto', $detalle['id_producto']);
                    $resulDetalle = $strExecDetalle->execute();
                    
                    if (!$resulDetalle) {
                        $this->conex->rollBack();
                        return ['respuesta' => 0, 'accion' => 'incluir'];
                    }
                    
                    // Actualizamos el stock del producto
                    $actualizar_stock = "UPDATE productos SET stock_disponible = stock_disponible + :cantidad WHERE id_producto = :id_producto";
                    $strExecStock = $this->conex->prepare($actualizar_stock);
                    $strExecStock->bindParam(':cantidad', $detalle['cantidad']);
                    $strExecStock->bindParam(':id_producto', $detalle['id_producto']);
                    $resulStock = $strExecStock->execute();
                    
                    if (!$resulStock) {
                        $this->conex->rollBack();
                        return ['respuesta' => 0, 'accion' => 'incluir'];
                    }
                }
            }
            
            $this->conex->commit();
            return ['respuesta' => 1, 'accion' => 'incluir'];
            
        } catch (Exception $e) {
            $this->conex->rollBack();
            return ['respuesta' => 0, 'accion' => 'incluir', 'error' => $e->getMessage()];
        }
    }

    public function modificar() {
        try {
            $this->conex->beginTransaction();
            
            // Actualizamos la cabecera de la compra
            $registro = "UPDATE compra SET fecha_entrada = :fecha_entrada, id_proveedor = :id_proveedor WHERE id_compra = :id_compra";
            $strExec = $this->conex->prepare($registro);
            $strExec->bindParam(':fecha_entrada', $this->fecha_entrada);
            $strExec->bindParam(':id_proveedor', $this->id_proveedor);
            $strExec->bindParam(':id_compra', $this->id_compra);
            $resul = $strExec->execute();
            
            if (!$resul) {
                $this->conex->rollBack();
                return ['respuesta' => 0, 'accion' => 'actualizar'];
            }
            
            // Primero, recuperamos los detalles actuales para ajustar el stock
            $query = "SELECT id_producto, cantidad FROM compra_detalles WHERE id_compra = :id_compra";
            $strExecConsulta = $this->conex->prepare($query);
            $strExecConsulta->bindParam(':id_compra', $this->id_compra);
            $strExecConsulta->execute();
            $detalles_actuales = $strExecConsulta->fetchAll(PDO::FETCH_ASSOC);
            
            // Restamos el stock de los productos que ya estaban en el detalle
            foreach ($detalles_actuales as $detalle_actual) {
                $actualizar_stock = "UPDATE productos SET stock_disponible = stock_disponible - :cantidad WHERE id_producto = :id_producto";
                $strExecStock = $this->conex->prepare($actualizar_stock);
                $strExecStock->bindParam(':cantidad', $detalle_actual['cantidad']);
                $strExecStock->bindParam(':id_producto', $detalle_actual['id_producto']);
                $resulStock = $strExecStock->execute();
                
                if (!$resulStock) {
                    $this->conex->rollBack();
                    return ['respuesta' => 0, 'accion' => 'actualizar'];
                }
            }
            
            // Eliminamos los detalles actuales
            $eliminar_detalles = "DELETE FROM compra_detalles WHERE id_compra = :id_compra";
            $strExecEliminar = $this->conex->prepare($eliminar_detalles);
            $strExecEliminar->bindParam(':id_compra', $this->id_compra);
            $resulEliminar = $strExecEliminar->execute();
            
            if (!$resulEliminar) {
                $this->conex->rollBack();
                return ['respuesta' => 0, 'accion' => 'actualizar'];
            }
            
            // Insertamos los nuevos detalles
            if (isset($this->detalles) && !empty($this->detalles)) {
                foreach ($this->detalles as $detalle) {
                    $registro_detalle = "INSERT INTO compra_detalles(cantidad, precio_total, precio_unitario, id_compra, id_producto) VALUES (:cantidad, :precio_total, :precio_unitario, :id_compra, :id_producto)";
                    $strExecDetalle = $this->conex->prepare($registro_detalle);
                    $strExecDetalle->bindParam(':cantidad', $detalle['cantidad']);
                    $strExecDetalle->bindParam(':precio_total', $detalle['precio_total']);
                    $strExecDetalle->bindParam(':precio_unitario', $detalle['precio_unitario']);
                    $strExecDetalle->bindParam(':id_compra', $this->id_compra);
                    $strExecDetalle->bindParam(':id_producto', $detalle['id_producto']);
                    $resulDetalle = $strExecDetalle->execute();
                    
                    if (!$resulDetalle) {
                        $this->conex->rollBack();
                        return ['respuesta' => 0, 'accion' => 'actualizar'];
                    }
                    
                    // Actualizamos el stock del producto
                    $actualizar_stock = "UPDATE productos SET stock_disponible = stock_disponible + :cantidad WHERE id_producto = :id_producto";
                    $strExecStock = $this->conex->prepare($actualizar_stock);
                    $strExecStock->bindParam(':cantidad', $detalle['cantidad']);
                    $strExecStock->bindParam(':id_producto', $detalle['id_producto']);
                    $resulStock = $strExecStock->execute();
                    
                    if (!$resulStock) {
                        $this->conex->rollBack();
                        return ['respuesta' => 0, 'accion' => 'actualizar'];
                    }
                }
            }
            
            $this->conex->commit();
            return ['respuesta' => 1, 'accion' => 'actualizar'];
            
        } catch (Exception $e) {
            $this->conex->rollBack();
            return ['respuesta' => 0, 'accion' => 'actualizar', 'error' => $e->getMessage()];
        }
    }

    public function eliminar() {
        try {
            $this->conex->beginTransaction();
            
            // Recuperamos los detalles para ajustar el stock
            $query = "SELECT id_producto, cantidad FROM compra_detalles WHERE id_compra = :id_compra";
            $strExecConsulta = $this->conex->prepare($query);
            $strExecConsulta->bindParam(':id_compra', $this->id_compra);
            $strExecConsulta->execute();
            $detalles = $strExecConsulta->fetchAll(PDO::FETCH_ASSOC);
            
            // Restamos el stock de los productos
            foreach ($detalles as $detalle) {
                $actualizar_stock = "UPDATE productos SET stock_disponible = stock_disponible - :cantidad WHERE id_producto = :id_producto";
                $strExecStock = $this->conex->prepare($actualizar_stock);
                $strExecStock->bindParam(':cantidad', $detalle['cantidad']);
                $strExecStock->bindParam(':id_producto', $detalle['id_producto']);
                $resulStock = $strExecStock->execute();
                
                if (!$resulStock) {
                    $this->conex->rollBack();
                    return ['respuesta' => 0, 'accion' => 'eliminar'];
                }
            }
            
            // Eliminamos los detalles
            $eliminar_detalles = "DELETE FROM compra_detalles WHERE id_compra = :id_compra";
            $strExecEliminar = $this->conex->prepare($eliminar_detalles);
            $strExecEliminar->bindParam(':id_compra', $this->id_compra);
            $resulEliminar = $strExecEliminar->execute();
            
            if (!$resulEliminar) {
                $this->conex->rollBack();
                return ['respuesta' => 0, 'accion' => 'eliminar'];
            }
            
            // Eliminamos la cabecera
            $eliminar_cabecera = "DELETE FROM compra WHERE id_compra = :id_compra";
            $strExecEliminarCab = $this->conex->prepare($eliminar_cabecera);
            $strExecEliminarCab->bindParam(':id_compra', $this->id_compra);
            $resulEliminarCab = $strExecEliminarCab->execute();
            
            if (!$resulEliminarCab) {
                $this->conex->rollBack();
                return ['respuesta' => 0, 'accion' => 'eliminar'];
            }
            
            $this->conex->commit();
            return ['respuesta' => 1, 'accion' => 'eliminar'];
            
        } catch (Exception $e) {
            $this->conex->rollBack();
            return ['respuesta' => 0, 'accion' => 'eliminar', 'error' => $e->getMessage()];
        }
    }

    public function consultar() {
        $registro = "SELECT c.id_compra, c.fecha_entrada, p.nombre as proveedor_nombre, p.id_proveedor 
                    FROM compra c 
                    JOIN proveedor p ON c.id_proveedor = p.id_proveedor 
                    ORDER BY c.id_compra DESC";
        $consulta = $this->conex->prepare($registro);
        $resul = $consulta->execute();
        return $resul ? $consulta->fetchAll(PDO::FETCH_ASSOC) : [];
    }

    public function consultarDetalles($id_compra) {
        $registro = "SELECT cd.id_detalle_compra, cd.cantidad, cd.precio_total, cd.precio_unitario, 
                    p.id_producto, p.nombre as producto_nombre, p.marca 
                    FROM compra_detalles cd 
                    JOIN productos p ON cd.id_producto = p.id_producto 
                    WHERE cd.id_compra = :id_compra";
        $consulta = $this->conex->prepare($registro);
        $consulta->bindParam(':id_compra', $id_compra);
        $resul = $consulta->execute();
        return $resul ? $consulta->fetchAll(PDO::FETCH_ASSOC) : [];
    }

    public function consultarCompra($id_compra) {
        $registro = "SELECT c.id_compra, c.fecha_entrada, c.id_proveedor, p.nombre as proveedor_nombre 
                    FROM compra c 
                    JOIN proveedor p ON c.id_proveedor = p.id_proveedor 
                    WHERE c.id_compra = :id_compra";
        $consulta = $this->conex->prepare($registro);
        $consulta->bindParam(':id_compra', $id_compra);
        $resul = $consulta->execute();
        return $resul ? $consulta->fetch(PDO::FETCH_ASSOC) : [];
    }

    public function consultarProductos() {
        $registro = "SELECT id_producto, nombre, marca, stock_disponible FROM productos WHERE estatus = 1";
        $consulta = $this->conex->prepare($registro);
        $resul = $consulta->execute();
        return $resul ? $consulta->fetchAll(PDO::FETCH_ASSOC) : [];
    }

    public function consultarProveedores() {
        $registro = "SELECT id_proveedor, nombre FROM proveedor WHERE estatus = 1";
        $consulta = $this->conex->prepare($registro);
        $resul = $consulta->execute();
        return $resul ? $consulta->fetchAll(PDO::FETCH_ASSOC) : [];
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