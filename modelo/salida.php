<?php
require_once 'conexion.php';

class Salida extends Conexion {
    
    function __construct() {
        parent::__construct();
    }

    public function registrarBitacora($jsonDatos) {
        $datos = json_decode($jsonDatos, true);
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

    public function procesarVenta($jsonDatos) {
        $datos = json_decode($jsonDatos, true);
        $operacion = $datos['operacion'];
        $datosProcesar = $datos['datos'];
        
        try {
            switch ($operacion) {
                case 'registrar':
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
        // Validaciones previas
        if (!isset($datos['id_persona']) || $datos['id_persona'] <= 0) {
            throw new Exception('ID de persona no válido');
        }
        
        if (!isset($datos['id_metodopago']) || $datos['id_metodopago'] <= 0) {
            throw new Exception('Método de pago no válido');
        }
        
        if (!isset($datos['id_entrega']) || $datos['id_entrega'] <= 0) {
            throw new Exception('Método de entrega no válido');
        }
        
        if (!isset($datos['precio_total']) || $datos['precio_total'] <= 0) {
            throw new Exception('Precio total no válido');
        }
        
        if (!isset($datos['detalles']) || empty($datos['detalles'])) {
            throw new Exception('No hay productos en la venta');
        }

        $conex = $this->getConex1();
        try {
            $conex->beginTransaction();
            
            // Insertar cabecera del pedido
            $sql = "INSERT INTO pedido(tipo, fecha, estado, precio_total, referencia_bancaria, 
                        telefono_emisor, banco, banco_destino, direccion, id_entrega, id_metodopago, id_persona) 
                        VALUES ('1', NOW(), '1', :precio_total, :referencia_bancaria, 
                        :telefono_emisor, :banco, :banco_destino, :direccion, :id_entrega, :id_metodopago, :id_persona)";
            
            $stmt = $conex->prepare($sql);
            $stmt->execute([
                'precio_total' => $datos['precio_total'],
                'referencia_bancaria' => $datos['referencia_bancaria'] ?? null,
                'telefono_emisor' => $datos['telefono_emisor'] ?? null,
                'banco' => $datos['banco'] ?? null,
                'banco_destino' => $datos['banco_destino'] ?? null,
                'direccion' => $datos['direccion'] ?? null,
                'id_entrega' => $datos['id_entrega'],
                'id_metodopago' => $datos['id_metodopago'],
                'id_persona' => $datos['id_persona']
            ]);
            
            $id_pedido = $conex->lastInsertId();
            
            // Insertar detalles
            foreach ($datos['detalles'] as $detalle) {
                // Validar datos del detalle
                if (!isset($detalle['id_producto']) || $detalle['id_producto'] <= 0) {
                    throw new Exception('ID de producto no válido en detalle');
                }
                
                if (!isset($detalle['cantidad']) || $detalle['cantidad'] <= 0) {
                    throw new Exception('Cantidad no válida en detalle');
                }
                
                if (!isset($detalle['precio_unitario']) || $detalle['precio_unitario'] <= 0) {
                    throw new Exception('Precio unitario no válido en detalle');
                }
                
                // Verificar stock
                $stock = $this->verificarStock($detalle['id_producto']);
                if ($stock < $detalle['cantidad']) {
                    throw new Exception('Stock insuficiente para el producto ID: ' . $detalle['id_producto']);
                }
                
                $sql_detalle = "INSERT INTO pedido_detalles(cantidad, precio_unitario, id_pedido, id_producto) 
                                   VALUES (:cantidad, :precio_unitario, :id_pedido, :id_producto)";
                $stmt_detalle = $conex->prepare($sql_detalle);
                $stmt_detalle->execute([
                    'cantidad' => $detalle['cantidad'],
                    'precio_unitario' => $detalle['precio_unitario'],
                    'id_pedido' => $id_pedido,
                    'id_producto' => $detalle['id_producto']
                ]);
                
                // Actualizar stock
                $sql_stock = "UPDATE productos SET stock_disponible = stock_disponible - :cantidad 
                                   WHERE id_producto = :id_producto";
                $stmt_stock = $conex->prepare($sql_stock);
                $stmt_stock->execute([
                    'cantidad' => $detalle['cantidad'],
                    'id_producto' => $detalle['id_producto']
                ]);
            }
            
            $conex->commit();
            $conex = null;
            return ['respuesta' => 1, 'accion' => 'incluir', 'id_pedido' => $id_pedido];
            
        } catch (Exception $e) {
            if ($conex) {
                $conex->rollBack();
                $conex = null;
            }
            throw $e;
        }
    }

    private function ejecutarActualizacion($datos) {
        // Validaciones previas
        if (!isset($datos['id_pedido']) || $datos['id_pedido'] <= 0) {
            throw new Exception('ID de pedido no válido');
        }
        
        if (!isset($datos['estado']) || !in_array($datos['estado'], ['0', '1', '2', '3', '4', '5'])) {
            throw new Exception('Estado no válido');
        }

        $conex = $this->getConex1();
        try {
            $conex->beginTransaction();
            
            $sql = "UPDATE pedido SET estado = :estado";
            $params = ['estado' => $datos['estado']];
            
            if (!empty($datos['direccion'])) {
                $sql .= ", direccion = :direccion";
                $params['direccion'] = $datos['direccion'];
            }
            
            $sql .= " WHERE id_pedido = :id_pedido";
            $params['id_pedido'] = $datos['id_pedido'];
            
            $stmt = $conex->prepare($sql);
            $resultado = $stmt->execute($params);
            
            if ($resultado) {
                $conex->commit();
                $conex = null;
                return ['respuesta' => 1, 'accion' => 'actualizar'];
            }
            
            $conex->rollBack();
            $conex = null;
            return ['respuesta' => 0, 'mensaje' => 'Error al actualizar la venta'];
            
        } catch (Exception $e) {
            if ($conex) {
                $conex->rollBack();
                $conex = null;
            }
            throw $e;
        }
    }

    private function ejecutarEliminacion($datos) {
        // Validaciones previas
        if (!isset($datos['id_pedido']) || $datos['id_pedido'] <= 0) {
            throw new Exception('ID de pedido no válido');
        }

        $conex = $this->getConex1();
        try {
            $conex->beginTransaction();
            
            // Verificar que el pedido existe
            $sql_verificar = "SELECT id_pedido FROM pedido WHERE id_pedido = :id_pedido";
            $stmt_verificar = $conex->prepare($sql_verificar);
            $stmt_verificar->execute(['id_pedido' => $datos['id_pedido']]);
            
            if (!$stmt_verificar->fetch()) {
                throw new Exception('El pedido no existe');
            }
            
            // Recuperar detalles para devolver stock
            $sql_detalles = "SELECT id_producto, cantidad FROM pedido_detalles WHERE id_pedido = :id_pedido";
            $stmt_detalles = $conex->prepare($sql_detalles);
            $stmt_detalles->execute(['id_pedido' => $datos['id_pedido']]);
            $detalles = $stmt_detalles->fetchAll(PDO::FETCH_ASSOC);
            
            // Devolver stock
            foreach ($detalles as $detalle) {
                $sql_stock = "UPDATE productos SET stock_disponible = stock_disponible + :cantidad 
                                   WHERE id_producto = :id_producto";
                $stmt_stock = $conex->prepare($sql_stock);
                $stmt_stock->execute([
                    'cantidad' => $detalle['cantidad'],
                    'id_producto' => $detalle['id_producto']
                ]);
            }
            
            // Eliminar detalles
            $sql_eliminar_detalles = "DELETE FROM pedido_detalles WHERE id_pedido = :id_pedido";
            $stmt_eliminar_detalles = $conex->prepare($sql_eliminar_detalles);
            $stmt_eliminar_detalles->execute(['id_pedido' => $datos['id_pedido']]);
            
            // Eliminar cabecera
            $sql_eliminar = "DELETE FROM pedido WHERE id_pedido = :id_pedido";
            $stmt_eliminar = $conex->prepare($sql_eliminar);
            $stmt_eliminar->execute(['id_pedido' => $datos['id_pedido']]);
            
            $conex->commit();
            $conex = null;
            return ['respuesta' => 1, 'accion' => 'eliminar'];
            
        } catch (Exception $e) {
            if ($conex) {
                $conex->rollBack();
                $conex = null;
            }
            throw $e;
        }
    }

    public function consultarVentas() {
        $conex = $this->getConex1();
        try {
            $sql = "SELECT p.id_pedido, CONCAT(per.nombre, ' ', per.apellido) as cliente, 
                    p.fecha, p.estado, p.precio_total, mp.nombre as metodo_pago, 
                    me.nombre as metodo_entrega, p.banco, p.banco_destino, 
                    p.referencia_bancaria, p.direccion 
                        FROM pedido p 
                        JOIN cliente per ON p.id_persona = per.id_persona 
                        JOIN metodo_pago mp ON p.id_metodopago = mp.id_metodopago 
                        JOIN metodo_entrega me ON p.id_entrega = me.id_entrega 
                        WHERE p.tipo = '1' 
                        ORDER BY p.id_pedido DESC";
            
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

    public function consultarCliente($datos) {
        // Validar datos de entrada
        if (!isset($datos['cedula']) || empty($datos['cedula'])) {
            throw new Exception('Cédula no proporcionada');
        }

        $conex = $this->getConex1();
        try {
            $sql = "SELECT id_persona, cedula, nombre, apellido, correo, telefono 
                    FROM cliente 
                    WHERE cedula = :cedula AND estatus = 1";
            
            $stmt = $conex->prepare($sql);
            $stmt->execute(['cedula' => $datos['cedula']]);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            $conex = null;
            return $resultado;
        } catch (PDOException $e) {
            if ($conex) {
                $conex = null;
            }
            throw $e;
        }
    }

    public function registrarCliente($datos) {
        // Validar datos de entrada
        $campos_requeridos = ['cedula', 'nombre', 'apellido', 'telefono', 'correo'];
        foreach ($campos_requeridos as $campo) {
            if (!isset($datos[$campo]) || empty($datos[$campo])) {
                throw new Exception("Campo {$campo} es obligatorio");
            }
        }

        // Validar formato de cédula
        if (!preg_match('/^[0-9]{7,8}$/', $datos['cedula'])) {
            throw new Exception('Formato de cédula no válido');
        }

        // Validar formato de teléfono
        if (!preg_match('/^0[0-9]{10}$/', $datos['telefono'])) {
            throw new Exception('Formato de teléfono no válido');
        }

        // Validar formato de correo
        if (!filter_var($datos['correo'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Formato de correo no válido');
        }

        $conex = $this->getConex1();
        try {
            $conex->beginTransaction();
            
            $sql = "INSERT INTO cliente (cedula, nombre, apellido, telefono, correo, id_tipo, estatus) 
                    VALUES (:cedula, :nombre, :apellido, :telefono, :correo, 2, 1)";
            
            $stmt = $conex->prepare($sql);
            $stmt->execute($datos);
            
            $id_cliente = $conex->lastInsertId();
            $conex->commit();
            $conex = null;
            return $id_cliente;
        } catch (PDOException $e) {
            if ($conex) {
                $conex->rollBack();
                $conex = null;
            }
            throw $e;
        }
    }

    public function existeCedula($datos) {
        if (!isset($datos['cedula']) || empty($datos['cedula'])) {
            throw new Exception('Cédula no proporcionada');
        }

        $conex = $this->getConex1();
        try {
            $sql = "SELECT cedula FROM cliente WHERE cedula = :cedula";
            $stmt = $conex->prepare($sql);
            $stmt->execute(['cedula' => $datos['cedula']]);
            $resultado = $stmt->rowCount() > 0;
            $conex = null;
            return $resultado;
        } catch (PDOException $e) {
            if ($conex) {
                $conex = null;
            }
            throw $e;
        }
    }

    private function verificarStock($id_producto) {
        if (!$id_producto || $id_producto <= 0) {
            throw new Exception('ID de producto no válido');
        }

        $conex = $this->getConex1();
        try {
            $sql = "SELECT stock_disponible FROM productos WHERE id_producto = :id_producto AND estatus = 1";
            $stmt = $conex->prepare($sql);
            $stmt->execute(['id_producto' => $id_producto]);
            $resultado = $stmt->fetchColumn();
            $conex = null;
            return $resultado ? intval($resultado) : 0;
        } catch (PDOException $e) {
            if ($conex) {
                $conex = null;
            }
            throw $e;
        }
    }

    public function consultarProductos() {
        $conex = $this->getConex1();
        try {
            $sql = "SELECT id_producto, nombre, descripcion, marca, precio_detal, stock_disponible 
                     FROM productos 
                     WHERE estatus = 1 AND stock_disponible > 0
                     ORDER BY nombre ASC";
            
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

    public function consultarMetodosPago() {
        $conex = $this->getConex1();
        try {
            $sql = "SELECT id_metodopago, nombre, descripcion 
                     FROM metodo_pago 
                     WHERE estatus = 1
                     ORDER BY nombre ASC";
            
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

    public function consultarMetodosEntrega() {
        $conex = $this->getConex1();
        try {
            $sql = "SELECT id_entrega, nombre, descripcion 
                     FROM metodo_entrega 
                     WHERE estatus = 1
                     ORDER BY nombre ASC";
            
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

    public function consultarDetallesPedido($id_pedido) {
        if (!$id_pedido || $id_pedido <= 0) {
            throw new Exception('ID de pedido no válido');
        }

        $conex = $this->getConex1();
        try {
            $sql = "SELECT pd.cantidad, pd.precio_unitario, p.nombre as nombre_producto 
                     FROM pedido_detalles pd 
                     JOIN productos p ON pd.id_producto = p.id_producto 
                     WHERE pd.id_pedido = :id_pedido";
            
            $stmt = $conex->prepare($sql);
            $stmt->execute(['id_pedido' => $id_pedido]);
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

    // NUEVOS MÉTODOS PÚBLICOS PARA CONTROLADOR
    public function registrarVentaPublico($datos) {
        return $this->registrarVentaPrivado($datos);
    }
    private function registrarVentaPrivado($datos) {
        $conex = $this->getConex1();
        try {
            $conex->beginTransaction();
            $sql = "INSERT INTO pedido(tipo, fecha, estado, precio_total, referencia_bancaria, telefono_emisor, banco, banco_destino, direccion, id_entrega, id_metodopago, id_persona) VALUES ('1', NOW(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $params = [
                '1', // estado inicial
                $datos['precio_total'],
                $datos['referencia_bancaria'],
                $datos['telefono_emisor'],
                $datos['banco'],
                $datos['banco_destino'],
                $datos['direccion'],
                $datos['id_entrega'],
                $datos['id_metodopago'],
                $datos['id_persona']
            ];
            $stmt = $conex->prepare($sql);
            $stmt->execute($params);
            $id_pedido = $conex->lastInsertId();
            foreach ($datos['detalles'] as $detalle) {
                $sql_det = "INSERT INTO pedido_detalles(cantidad, precio_unitario, id_pedido, id_producto) VALUES (?, ?, ?, ?)";
                $params_det = [
                    $detalle['cantidad'],
                    $detalle['precio_unitario'],
                    $id_pedido,
                    $detalle['id_producto']
                ];
                $stmt_det = $conex->prepare($sql_det);
                $stmt_det->execute($params_det);
                $sql_stock = "UPDATE productos SET stock_disponible = stock_disponible - ? WHERE id_producto = ?";
                $stmt_stock = $conex->prepare($sql_stock);
                $stmt_stock->execute([$detalle['cantidad'], $detalle['id_producto']]);
            }
            // Bitácora
            $bitacora = [
                'id_persona' => $datos['id_persona'],
                'accion' => 'Registro de venta',
                'descripcion' => 'Se registró una nueva venta con ID: ' . $id_pedido
            ];
            $this->registrarBitacora(json_encode($bitacora));
            $conex->commit();
            $conex = null;
            return ['respuesta' => 1, 'id_pedido' => $id_pedido];
        } catch (Exception $e) {
            if ($conex) {
                $conex->rollBack();
                $conex = null;
            }
            return ['respuesta' => 0, 'mensaje' => $e->getMessage()];
        }
    }
    public function actualizarVentaPublico($datos) {
        return $this->actualizarVentaPrivado($datos);
    }
    private function actualizarVentaPrivado($datos) {
        $conex = $this->getConex1();
        try {
            $conex->beginTransaction();
            $sql = "UPDATE pedido SET estado = ?";
            $params = [$datos['estado']];
            if (!empty($datos['direccion'])) {
                $sql .= ", direccion = ?";
                $params[] = $datos['direccion'];
            }
            $sql .= " WHERE id_pedido = ?";
            $params[] = $datos['id_pedido'];
            $stmt = $conex->prepare($sql);
            $stmt->execute($params);
            // Bitácora
            $bitacora = [
                'id_persona' => $_SESSION['id'] ?? null,
                'accion' => 'Actualización de venta',
                'descripcion' => 'Se actualizó la venta con ID: ' . $datos['id_pedido']
            ];
            $this->registrarBitacora(json_encode($bitacora));
            $conex->commit();
            $conex = null;
            return ['respuesta' => 1];
        } catch (Exception $e) {
            if ($conex) {
                $conex->rollBack();
                $conex = null;
            }
            return ['respuesta' => 0, 'mensaje' => $e->getMessage()];
        }
    }
    public function eliminarVentaPublico($datos) {
        return $this->eliminarVentaPrivado($datos);
    }
    private function eliminarVentaPrivado($datos) {
        $conex = $this->getConex1();
        try {
            $conex->beginTransaction();
            $sql_verificar = "SELECT id_pedido FROM pedido WHERE id_pedido = ?";
            $stmt_verificar = $conex->prepare($sql_verificar);
            $stmt_verificar->execute([$datos['id_pedido']]);
            if (!$stmt_verificar->fetch()) {
                throw new Exception('El pedido no existe');
            }
            $sql_detalles = "SELECT id_producto, cantidad FROM pedido_detalles WHERE id_pedido = ?";
            $stmt_detalles = $conex->prepare($sql_detalles);
            $stmt_detalles->execute([$datos['id_pedido']]);
            $detalles = $stmt_detalles->fetchAll(PDO::FETCH_ASSOC);
            foreach ($detalles as $detalle) {
                $sql_stock = "UPDATE productos SET stock_disponible = stock_disponible + ? WHERE id_producto = ?";
                $stmt_stock = $conex->prepare($sql_stock);
                $stmt_stock->execute([$detalle['cantidad'], $detalle['id_producto']]);
            }
            $sql_eliminar_detalles = "DELETE FROM pedido_detalles WHERE id_pedido = ?";
            $stmt_eliminar_detalles = $conex->prepare($sql_eliminar_detalles);
            $stmt_eliminar_detalles->execute([$datos['id_pedido']]);
            $sql_eliminar = "DELETE FROM pedido WHERE id_pedido = ?";
            $stmt_eliminar = $conex->prepare($sql_eliminar);
            $stmt_eliminar->execute([$datos['id_pedido']]);
            // Bitácora
            $bitacora = [
                'id_persona' => $_SESSION['id'] ?? null,
                'accion' => 'Eliminación de venta',
                'descripcion' => 'Se eliminó la venta con ID: ' . $datos['id_pedido']
            ];
            $this->registrarBitacora(json_encode($bitacora));
            $conex->commit();
            $conex = null;
            return ['respuesta' => 1];
        } catch (Exception $e) {
            if ($conex) {
                $conex->rollBack();
                $conex = null;
            }
            return ['respuesta' => 0, 'mensaje' => $e->getMessage()];
        }
    }
    public function consultarClientePublico($datos) {
        return $this->consultarClientePrivado($datos);
    }
    private function consultarClientePrivado($datos) {
        $conex = $this->getConex1();
        try {
            $sql = "SELECT id_persona, cedula, nombre, apellido, correo, telefono FROM cliente WHERE cedula = ? AND estatus = 1";
            $stmt = $conex->prepare($sql);
            $stmt->execute([$datos['cedula']]);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            $conex = null;
            return [
                'respuesta' => $resultado ? 1 : 0,
                'cliente' => $resultado
            ];
        } catch (PDOException $e) {
            if ($conex) {
                $conex = null;
            }
            throw $e;
        }
    }
    public function registrarClientePublico($datos) {
        return $this->registrarClientePrivado($datos);
    }
    private function registrarClientePrivado($datos) {
        $conex = $this->getConex1();
        try {
            $conex->beginTransaction();
            $sql = "INSERT INTO cliente (cedula, nombre, apellido, telefono, correo, id_tipo, estatus) VALUES (?, ?, ?, ?, ?, 2, 1)";
            $params = [
                $datos['cedula'],
                $datos['nombre'],
                $datos['apellido'],
                $datos['telefono'],
                $datos['correo']
            ];
            $stmt = $conex->prepare($sql);
            $stmt->execute($params);
            $id_cliente = $conex->lastInsertId();
            // Bitácora
            $bitacora = [
                'id_persona' => $_SESSION['id'] ?? null,
                'accion' => 'Registro de cliente',
                'descripcion' => 'Se registró un nuevo cliente con cédula: ' . $datos['cedula']
            ];
            $this->registrarBitacora(json_encode($bitacora));
            $conex->commit();
            $conex = null;
            return [
                'success' => true,
                'id_cliente' => $id_cliente,
                'message' => 'Cliente registrado exitosamente'
            ];
        } catch (PDOException $e) {
            if ($conex) {
                $conex->rollBack();
                $conex = null;
            }
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
?>