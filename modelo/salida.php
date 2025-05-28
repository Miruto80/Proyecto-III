<?php
require_once 'conexion.php';

class Salida extends Conexion {
    private $conex;
    private $id_pedido;
    private $tipo;
    private $fecha;
    private $estado;
    private $precio_total;
    private $referencia_bancaria;
    private $telefono_emisor;
    private $banco;
    private $banco_destino;
    private $direccion;
    private $id_entrega;
    private $id_metodopago;
    private $id_persona;
    private $detalles; // Para almacenar los detalles del pedido (productos)

    function __construct() {
        $this->conex = new Conexion();
        $this->conex = $this->conex->Conex();
    }

    public function registrar() {
        try {
            $this->conex->beginTransaction();
            
            // Validación de datos antes de insertar
            if (empty($this->id_persona) || empty($this->id_metodopago) || empty($this->id_entrega) || empty($this->detalles)) {
                throw new Exception('Datos incompletos para el registro de la venta');
            }
            
            // Insertamos la cabecera del pedido
            $registro = "INSERT INTO pedido(tipo, fecha, estado, precio_total, referencia_bancaria, 
                        telefono_emisor, banco, banco_destino, direccion, id_entrega, id_metodopago, id_persona) 
                        VALUES ('2', NOW(), 'Pendiente', :precio_total, :referencia_bancaria, 
                        :telefono_emisor, :banco, :banco_destino, :direccion, :id_entrega, :id_metodopago, :id_persona)";
            
            $strExec = $this->conex->prepare($registro);
            $strExec->bindValue(':precio_total', $this->precio_total);
            $strExec->bindValue(':referencia_bancaria', $this->referencia_bancaria);
            $strExec->bindValue(':telefono_emisor', $this->telefono_emisor);
            $strExec->bindValue(':banco', $this->banco);
            $strExec->bindValue(':banco_destino', $this->banco_destino);
            $strExec->bindValue(':direccion', $this->direccion);
            $strExec->bindValue(':id_entrega', $this->id_entrega);
            $strExec->bindValue(':id_metodopago', $this->id_metodopago);
            $strExec->bindValue(':id_persona', $this->id_persona);
            
            if (!$strExec->execute()) {
                $error = $strExec->errorInfo();
                throw new Exception('Error al registrar la venta: ' . $error[2]);
            }
            
            // Obtenemos el ID del pedido recién insertado
            $id_pedido = $this->conex->lastInsertId();
            
            // Insertamos los detalles del pedido
            foreach ($this->detalles as $detalle) {
                // Validación de datos del detalle
                if (empty($detalle['id_producto']) || empty($detalle['cantidad']) || empty($detalle['precio_unitario'])) {
                    throw new Exception('Datos de producto incompletos');
                }
                
                // Verificamos que el producto exista y tenga stock suficiente
                $stock_disponible = $this->verificarStock($detalle['id_producto']);
                if ($stock_disponible === false) {
                    throw new Exception('Producto no encontrado');
                }
                
                if ($stock_disponible < $detalle['cantidad']) {
                    throw new Exception('Stock insuficiente para el producto');
                }
                
                $registro_detalle = "INSERT INTO pedido_detalles(cantidad, precio_unitario, id_pedido, id_producto) 
                                   VALUES (:cantidad, :precio_unitario, :id_pedido, :id_producto)";
                $strExecDetalle = $this->conex->prepare($registro_detalle);
                $strExecDetalle->bindValue(':cantidad', $detalle['cantidad']);
                $strExecDetalle->bindValue(':precio_unitario', $detalle['precio_unitario']);
                $strExecDetalle->bindValue(':id_pedido', $id_pedido);
                $strExecDetalle->bindValue(':id_producto', $detalle['id_producto']);
                
                if (!$strExecDetalle->execute()) {
                    $error = $strExecDetalle->errorInfo();
                    throw new Exception('Error al registrar el detalle de la venta: ' . $error[2]);
                }
                
                // Actualizamos el stock del producto
                $actualizar_stock = "UPDATE productos SET stock_disponible = stock_disponible - :cantidad 
                                   WHERE id_producto = :id_producto";
                $strExecStock = $this->conex->prepare($actualizar_stock);
                $strExecStock->bindValue(':cantidad', $detalle['cantidad']);
                $strExecStock->bindValue(':id_producto', $detalle['id_producto']);
                
                if (!$strExecStock->execute()) {
                    $error = $strExecStock->errorInfo();
                    throw new Exception('Error al actualizar el stock: ' . $error[2]);
                }
            }
            
            $this->conex->commit();
            return ['respuesta' => 1, 'accion' => 'incluir', 'id_pedido' => $id_pedido];
            
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
            
            // Validar que exista el pedido
            $pedido_existe = $this->consultarPedido($this->id_pedido);
            if (empty($pedido_existe)) {
                return ['respuesta' => 0, 'accion' => 'actualizar', 'error' => 'El pedido no existe'];
            }
            
            // Actualizamos el estado del pedido
            $registro = "UPDATE pedido SET estado = :estado WHERE id_pedido = :id_pedido";
            $strExec = $this->conex->prepare($registro);
            $strExec->bindParam(':estado', $this->estado);
            $strExec->bindParam(':id_pedido', $this->id_pedido);
            $resul = $strExec->execute();
            
            if (!$resul) {
                $this->conex->rollBack();
                $error = $strExec->errorInfo();
                return ['respuesta' => 0, 'accion' => 'actualizar', 'error' => $error[2]];
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
            
            // Validar que exista el pedido
            $pedido_existe = $this->consultarPedido($this->id_pedido);
            if (empty($pedido_existe)) {
                return ['respuesta' => 0, 'accion' => 'eliminar', 'error' => 'El pedido no existe'];
            }
            
            // Recuperamos los detalles para devolver el stock
            $detalles = $this->consultarDetallesPedido($this->id_pedido);
            
            // Devolvemos el stock de los productos
            foreach ($detalles as $detalle) {
                $actualizar_stock = "UPDATE productos SET stock_disponible = stock_disponible + :cantidad 
                                   WHERE id_producto = :id_producto";
                $strExecStock = $this->conex->prepare($actualizar_stock);
                $strExecStock->bindParam(':cantidad', $detalle['cantidad']);
                $strExecStock->bindParam(':id_producto', $detalle['id_producto']);
                $resulStock = $strExecStock->execute();
                
                if (!$resulStock) {
                    $this->conex->rollBack();
                    $error = $strExecStock->errorInfo();
                    return ['respuesta' => 0, 'accion' => 'eliminar', 'error' => $error[2]];
                }
            }
            
            // Eliminamos los detalles
            $eliminar_detalles = "DELETE FROM pedido_detalles WHERE id_pedido = :id_pedido";
            $strExecEliminar = $this->conex->prepare($eliminar_detalles);
            $strExecEliminar->bindParam(':id_pedido', $this->id_pedido);
            $resulEliminar = $strExecEliminar->execute();
            
            if (!$resulEliminar) {
                $this->conex->rollBack();
                $error = $strExecEliminar->errorInfo();
                return ['respuesta' => 0, 'accion' => 'eliminar', 'error' => $error[2]];
            }
            
            // Eliminamos la cabecera
            $eliminar_cabecera = "DELETE FROM pedido WHERE id_pedido = :id_pedido";
            $strExecEliminarCab = $this->conex->prepare($eliminar_cabecera);
            $strExecEliminarCab->bindParam(':id_pedido', $this->id_pedido);
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
            $registro = "SELECT p.id_pedido, CONCAT(per.nombre, ' ', per.apellido) as cliente, p.fecha, 
                        p.estado, p.precio_total, mp.nombre as metodo_pago, me.nombre as metodo_entrega,
                        p.banco, p.banco_destino 
                        FROM pedido p 
                        JOIN personas per ON p.id_persona = per.id_persona 
                        JOIN metodo_pago mp ON p.id_metodopago = mp.id_metodopago 
                        JOIN metodo_entrega me ON p.id_entrega = me.id_entrega 
                        WHERE p.tipo = '2' 
                        ORDER BY p.id_pedido DESC";
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

    public function consultarDetallesPedido($id_pedido) {
        try {
            $registro = "SELECT pd.id_detalle, pd.cantidad, pd.precio_unitario, 
                        p.id_producto, p.nombre as nombre_producto 
                        FROM pedido_detalles pd 
                        JOIN productos p ON pd.id_producto = p.id_producto 
                        WHERE pd.id_pedido = :id_pedido";
            $consulta = $this->conex->prepare($registro);
            $consulta->bindParam(':id_pedido', $id_pedido);
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

    public function consultarPedido($id_pedido) {
        try {
            $registro = "SELECT p.*, CONCAT(per.nombre, ' ', per.apellido) as cliente 
                        FROM pedido p 
                        JOIN personas per ON p.id_persona = per.id_persona 
                        WHERE p.id_pedido = :id_pedido";
            $consulta = $this->conex->prepare($registro);
            $consulta->bindParam(':id_pedido', $id_pedido);
            $resul = $consulta->execute();
            
            if (!$resul) {
                $error = $consulta->errorInfo();
                return null;
            }
            
            return $consulta->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return null;
        }
    }

    public function consultarCliente($cedula) {
        try {
            $registro = "SELECT id_persona, cedula, nombre, apellido, correo, telefono 
                        FROM personas 
                        WHERE cedula = :cedula AND estatus = 1";
            $consulta = $this->conex->prepare($registro);
            $consulta->bindParam(':cedula', $cedula);
            $resul = $consulta->execute();
            
            if (!$resul) {
                $error = $consulta->errorInfo();
                return null;
            }
            
            return $consulta->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return null;
        }
    }

    private function verificarStock($id_producto) {
        try {
            $query = "SELECT stock_disponible FROM productos WHERE id_producto = :id_producto AND estatus = 1";
            $consulta = $this->conex->prepare($query);
            $consulta->bindParam(':id_producto', $id_producto);
            $consulta->execute();
            
            return $consulta->fetchColumn();
        } catch (Exception $e) {
            return false;
        }
    }

    // Setters
    public function set_Id_pedido($id_pedido) {
        $this->id_pedido = $id_pedido;
    }

    public function set_Estado($estado) {
        $this->estado = $estado;
    }

    public function set_Precio_total($precio_total) {
        $this->precio_total = $precio_total;
    }

    public function set_Referencia_bancaria($referencia_bancaria) {
        $this->referencia_bancaria = $referencia_bancaria;
    }

    public function set_Telefono_emisor($telefono_emisor) {
        $this->telefono_emisor = $telefono_emisor;
    }

    public function set_Banco($banco) {
        $this->banco = $banco;
    }

    public function set_Banco_destino($banco_destino) {
        $this->banco_destino = $banco_destino;
    }

    public function set_Direccion($direccion) {
        $this->direccion = $direccion;
    }

    public function set_Id_entrega($id_entrega) {
        $this->id_entrega = $id_entrega;
    }

    public function set_Id_metodopago($id_metodopago) {
        $this->id_metodopago = $id_metodopago;
    }

    public function set_Id_persona($id_persona) {
        $this->id_persona = $id_persona;
    }

    public function set_Detalles($detalles) {
        $this->detalles = $detalles;
    }

    public function consultarProductos() {
        try {
            $query = "SELECT id_producto, nombre, descripcion, marca, precio_detal, stock_disponible 
                     FROM productos 
                     WHERE estatus = 1 AND stock_disponible > 0";
            $consulta = $this->conex->prepare($query);
            $consulta->execute();
            
            return $consulta->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    public function consultarMetodosPago() {
        try {
            $query = "SELECT id_metodopago, nombre, descripcion 
                     FROM metodo_pago 
                     WHERE estatus = 1";
            $consulta = $this->conex->prepare($query);
            $consulta->execute();
            
            return $consulta->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    public function consultarMetodosEntrega() {
        try {
            $query = "SELECT id_entrega, nombre, descripcion 
                     FROM metodo_entrega 
                     WHERE estatus = 1";
            $consulta = $this->conex->prepare($query);
            $consulta->execute();
            
            return $consulta->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    public function existeCedula($cedula) {
        try {
            $consulta = "SELECT cedula FROM personas WHERE cedula = :cedula";
            $stmt = $this->conex->prepare($consulta);
            $stmt->bindParam(':cedula', $cedula);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            return false;
        }
    }

    public function registrarCliente($datos) {
        try {
            $consulta = "INSERT INTO personas (cedula, nombre, apellido, telefono, correo, id_tipo, estatus) 
                         VALUES (:cedula, :nombre, :apellido, :telefono, :correo, 2, 1)";
            
            $stmt = $this->conex->prepare($consulta);
            $stmt->bindParam(':cedula', $datos['cedula']);
            $stmt->bindParam(':nombre', $datos['nombre']);
            $stmt->bindParam(':apellido', $datos['apellido']);
            $stmt->bindParam(':telefono', $datos['telefono']);
            $stmt->bindParam(':correo', $datos['correo']);
            
            if ($stmt->execute()) {
                return $this->conex->lastInsertId();
            }
            return false;
        } catch (Exception $e) {
            return false;
        }
    }
}
?>