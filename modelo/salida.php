<?php
require_once 'conexion.php';

class Salida extends Conexion {
    
    function __construct() {
        parent::__construct();
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
        
        if (!isset($datos['precio_total']) || $datos['precio_total'] <= 0) {
            throw new Exception('Precio total no válido');
        }
        
        if (!isset($datos['detalles']) || empty($datos['detalles'])) {
            throw new Exception('No hay productos en la venta');
        }

        $conex = $this->getConex1();
        try {
            $conex->beginTransaction();
            // Insertar cabecera del pedido con los campos correctos según la estructura de la BD
            $sql = "INSERT INTO pedido(tipo, fecha, estado, precio_total_usd, precio_total_bs, id_persona) 
                    VALUES ('2', NOW(), '1', :precio_total_usd, :precio_total_bs, :id_persona)";
            $stmt = $conex->prepare($sql);
            $stmt->execute([
                'precio_total_usd' => $datos['precio_total'],
                'precio_total_bs' => $datos['precio_total_bs'] ?? 0.00, // Usar el valor calculado desde el frontend
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
                
                // Insertar detalle del pedido
                $sql_detalle = "INSERT INTO pedido_detalles(id_pedido, id_producto, cantidad, precio_unitario) 
                                   VALUES (:id_pedido, :id_producto, :cantidad, :precio_unitario)";
                $stmt_detalle = $conex->prepare($sql_detalle);
                $stmt_detalle->execute([
                    'id_pedido' => $id_pedido,
                    'id_producto' => $detalle['id_producto'],
                    'cantidad' => $detalle['cantidad'],
                    'precio_unitario' => $detalle['precio_unitario']
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
            
            if ($datos['estado'] == '2') {
                $sql .= ", tipo = '2'";
            }
            
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
                    p.fecha, p.estado, p.precio_total_usd as precio_total
                    FROM pedido p 
                    JOIN cliente per ON p.id_persona = per.id_persona 
                    WHERE p.tipo = '2' 
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

        // Verificar si la cédula ya existe
        if ($this->existeCedula($datos)) {
            throw new Exception('La cédula ya está registrada');
        }

        $conex = $this->getConex1();
        try {
            $conex->beginTransaction();
            
            // Corregir la consulta para que coincida con la estructura de la tabla cliente
            $sql = "INSERT INTO cliente (cedula, nombre, apellido, telefono, correo, estatus) 
                    VALUES (:cedula, :nombre, :apellido, :telefono, :correo, 1)";
            
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

    public function consultarClienteDetalle($id_pedido) {
        if (!$id_pedido || $id_pedido <= 0) {
            throw new Exception('ID de pedido no válido');
        }

        $conex = $this->getConex1();
        try {
            $sql = "SELECT c.cedula, c.nombre, c.apellido, c.telefono, c.correo 
                     FROM cliente c 
                     JOIN pedido p ON c.id_persona = p.id_persona 
                     WHERE p.id_pedido = :id_pedido";
            
            $stmt = $conex->prepare($sql);
            $stmt->execute(['id_pedido' => $id_pedido]);
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

    public function consultarMetodosPagoVenta($id_pedido) {
        if (!$id_pedido || $id_pedido <= 0) {
            throw new Exception('ID de pedido no válido');
        }

        $conex = $this->getConex1();
        try {
            // Consulta para obtener métodos de pago de la venta usando la tabla detalle_pago existente
            $sql = "SELECT mp.nombre as nombre_metodo, dp.monto_usd, dp.monto as monto_bs, 
                           dp.referencia_bancaria as referencia, dp.banco as banco_emisor, 
                           dp.banco_destino as banco_receptor, dp.telefono_emisor
                    FROM detalle_pago dp 
                    JOIN metodo_pago mp ON dp.id_metodopago = mp.id_metodopago 
                    WHERE dp.id_pedido = :id_pedido";
            
            $stmt = $conex->prepare($sql);
            $stmt->execute(['id_pedido' => $id_pedido]);
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $conex = null;
            return $resultado;
        } catch (PDOException $e) {
            if ($conex) {
                $conex = null;
            }
            return [];
        }
    }

    // NUEVOS MÉTODOS PÚBLICOS PARA CONTROLADOR
    public function registrarVentaPublico($datos) {
        return $this->registrarVentaPrivado($datos);
    }
    private function registrarVentaPrivado($datos) {
        // Log de depuración
        error_log("Iniciando registro de venta con datos: " . json_encode($datos));
        
        // Validaciones previas
        if (!isset($datos['id_persona']) || $datos['id_persona'] <= 0) {
            throw new Exception('ID de persona no válido');
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
            
            // Verificar que el cliente existe
            $sql_verificar_cliente = "SELECT id_persona FROM cliente WHERE id_persona = ? AND estatus = 1";
            $stmt_verificar_cliente = $conex->prepare($sql_verificar_cliente);
            $stmt_verificar_cliente->execute([$datos['id_persona']]);
            
            if (!$stmt_verificar_cliente->fetch()) {
                throw new Exception('El cliente no existe o está inactivo');
            }

            // Insertar cabecera del pedido con los campos correctos según la estructura de la BD
            $sql = "INSERT INTO pedido(tipo, fecha, estado, precio_total_usd, precio_total_bs, id_persona) VALUES ('2', NOW(), '1', ?, ?, ?)";
            $params = [
                $datos['precio_total'],
                $datos['precio_total_bs'] ?? 0.00, // Usar el valor calculado desde el frontend
                $datos['id_persona']
            ];
            
            error_log("SQL para insertar pedido: " . $sql);
            error_log("Parámetros del pedido: " . json_encode($params));
            
            $stmt = $conex->prepare($sql);
            $stmt->execute($params);
            $id_pedido = $conex->lastInsertId();
            
            error_log("ID del pedido insertado: " . $id_pedido);

            // Procesar detalles de productos
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

                // Verificar que el producto existe y tiene stock
                $sql_verificar_producto = "SELECT stock_disponible, nombre FROM productos WHERE id_producto = ? AND estatus = 1";
                $stmt_verificar_producto = $conex->prepare($sql_verificar_producto);
                $stmt_verificar_producto->execute([$detalle['id_producto']]);
                $producto = $stmt_verificar_producto->fetch(PDO::FETCH_ASSOC);
                
                if (!$producto) {
                    throw new Exception('El producto no existe o está inactivo');
                }
                
                if ($producto['stock_disponible'] < $detalle['cantidad']) {
                    throw new Exception('Stock insuficiente para el producto: ' . $producto['nombre']);
                }

                // Insertar detalle del pedido
                $sql_det = "INSERT INTO pedido_detalles(id_pedido, id_producto, cantidad, precio_unitario) VALUES (?, ?, ?, ?)";
                $params_det = [
                    $id_pedido,
                    $detalle['id_producto'],
                    $detalle['cantidad'],
                    $detalle['precio_unitario']
                ];
                
                error_log("SQL para insertar detalle: " . $sql_det);
                error_log("Parámetros del detalle: " . json_encode($params_det));
                
                $stmt_det = $conex->prepare($sql_det);
                $stmt_det->execute($params_det);
                
                error_log("Detalle insertado para producto ID: " . $detalle['id_producto']);

                // Actualizar stock
                $sql_stock = "UPDATE productos SET stock_disponible = stock_disponible - ? WHERE id_producto = ?";
                $stmt_stock = $conex->prepare($sql_stock);
                $stmt_stock->execute([$detalle['cantidad'], $detalle['id_producto']]);
            }

            // Registrar métodos de pago si existen
            if (isset($datos['metodos_pago']) && !empty($datos['metodos_pago'])) {
                $this->registrarMetodosPagoVenta($id_pedido, $datos['metodos_pago'], $conex);
            }
            
            // Actualizar el pedido con el ID del pago si se registraron métodos de pago
            if (!empty($datos['metodos_pago'])) {
                // Obtener el ID del primer detalle de pago insertado
                $sql_get_pago = "SELECT id_pago FROM detalle_pago WHERE id_pedido = ? ORDER BY id_pago ASC LIMIT 1";
                $stmt_get_pago = $conex->prepare($sql_get_pago);
                $stmt_get_pago->execute([$id_pedido]);
                $id_pago = $stmt_get_pago->fetchColumn();
                
                if ($id_pago) {
                    $sql_update_pedido = "UPDATE pedido SET id_pago = ? WHERE id_pedido = ?";
                    $stmt_update = $conex->prepare($sql_update_pedido);
                    $stmt_update->execute([$id_pago, $id_pedido]);
                }
            }

            // Bitácora
            $bitacora = [
                'id_persona' => $datos['id_persona'],
                'accion' => 'Registro de venta',
                'descripcion' => 'Se registró una nueva venta con ID: ' . $id_pedido
            ];
            $this->registrarBitacora(json_encode($bitacora));
            
            error_log("Commit de la transacción exitoso");
            $conex->commit();
            $conex = null;
            
            $respuesta_final = ['respuesta' => 1, 'id_pedido' => $id_pedido];
            error_log("Respuesta final del modelo: " . json_encode($respuesta_final));
            
            return $respuesta_final;
        } catch (Exception $e) {
            error_log("Error en registro de venta: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            
            if ($conex) {
                $conex->rollBack();
                $conex = null;
            }
            
            $respuesta_error = ['respuesta' => 0, 'mensaje' => $e->getMessage()];
            error_log("Respuesta de error: " . json_encode($respuesta_error));
            
            return $respuesta_error;
        }
    }

    // Método para registrar los métodos de pago de una venta
    private function registrarMetodosPagoVenta($id_pedido, $metodos_pago, $conex) {
        foreach ($metodos_pago as $metodo) {
            // Validar datos del método de pago
            if (!isset($metodo['id_metodopago']) || $metodo['id_metodopago'] <= 0) {
                throw new Exception('ID de método de pago no válido');
            }
            
            if (!isset($metodo['monto_usd']) || $metodo['monto_usd'] <= 0) {
                throw new Exception('Monto USD no válido para método de pago');
            }

            // Insertar método de pago usando la tabla detalle_pago existente
            $sql = "INSERT INTO detalle_pago (
                id_pedido, id_metodopago, monto_usd, monto, 
                referencia_bancaria, telefono_emisor, banco_destino, banco
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            
            $params = [
                $id_pedido,
                $metodo['id_metodopago'],
                $metodo['monto_usd'],
                $metodo['monto_bs'] ?? 0.00,
                $metodo['referencia'] ?? null,
                $metodo['telefono_emisor'] ?? null,
                $metodo['banco_receptor'] ?? null,
                $metodo['banco_emisor'] ?? null
            ];
            
            $stmt = $conex->prepare($sql);
            $stmt->execute($params);
        }
    }

    public function actualizarVentaPublico($datos) {
        return $this->actualizarVentaPrivado($datos);
    }
    private function actualizarVentaPrivado($datos) {
        $conex = $this->getConex1();
        try {
            $conex->beginTransaction();
            
            // Si el estado es 2 (vendido), cambiar el tipo a 2 también
            if ($datos['estado'] == '2') {
                $sql = "UPDATE pedido SET estado = ?, tipo = '2' WHERE id_pedido = ?";
            } else {
                $sql = "UPDATE pedido SET estado = ? WHERE id_pedido = ?";
            }
            
            $params = [$datos['estado'], $datos['id_pedido']];
            $stmt = $conex->prepare($sql);
            $stmt->execute($params);
            
            // Bitácora
            $bitacora = [
                'id_persona' => $_SESSION['id'] ?? null,
                'accion' => 'Actualización de venta',
                'descripcion' => 'Se actualizó la venta con ID: ' . $datos['id_pedido'] . ' - Estado: ' . $datos['estado']
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
            
            // Verificar si la cédula ya existe
            $sql_verificar = "SELECT cedula FROM cliente WHERE cedula = ?";
            $stmt_verificar = $conex->prepare($sql_verificar);
            $stmt_verificar->execute([$datos['cedula']]);
            
            if ($stmt_verificar->fetch()) {
                throw new Exception('La cédula ya está registrada');
            }
            
            $sql = "INSERT INTO cliente (cedula, nombre, apellido, telefono, correo, estatus) VALUES (?, ?, ?, ?, ?, 1)";
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
    
    // Método para registrar en bitácora
    private function registrarBitacora($datos) {
        try {
            $conex = $this->getConex1();
            $datos_array = json_decode($datos, true);
            
            $sql = "INSERT INTO bitacora (id_persona, accion, descripcion, fecha_hora) 
                    VALUES (?, ?, ?, NOW())";
            $stmt = $conex->prepare($sql);
            $stmt->execute([
                $datos_array['id_persona'],
                $datos_array['accion'],
                $datos_array['descripcion']
            ]);
            
            $conex = null;
            return true;
        } catch (Exception $e) {
            // Si falla la bitácora, no afectar la operación principal
            return false;
        }
    }
}
?>