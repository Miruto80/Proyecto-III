<?php
require_once 'conexion.php';

class Salida {
    private $conex;
    private $id_pedido;
    private $datosPedido;
    private $detallesPedido;
    private $estado;
    
    function __construct() {
        $this->conex = new Conexion();
        $this->conex = $this->conex->Conex();
    }
    
    // Setters
    public function set_Id_pedido($id_pedido) {
        $this->id_pedido = $id_pedido;
    }
    
    public function set_DatosPedido($datosPedido) {
        $this->datosPedido = $datosPedido;
    }
    
    public function set_DetallesPedido($detallesPedido) {
        $this->detallesPedido = $detallesPedido;
    }
    
    public function set_Estado($estado) {
        $this->estado = $estado;
    }
    
    /**
     * Obtiene todos los pedidos con información relacionada
     */
    public function listarPedidos() {
        try {
            $stmt = $this->conex->prepare("
                SELECT p.*, mp.nombre as metodo_pago, me.nombre as metodo_entrega 
                FROM pedido p
                LEFT JOIN metodo_pago mp ON p.id_metodopago = mp.id_metodopago
                LEFT JOIN metodo_entrega me ON p.id_entrega = me.id_entrega
                ORDER BY p.fecha DESC
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error al listar pedidos: " . $e->getMessage();
            return false;
        }
    }
    
    /**
     * Obtiene los detalles de un pedido específico
     */
    public function obtenerDetallesPedido($id_pedido = null) {
        try {
            if ($id_pedido === null) {
                $id_pedido = $this->id_pedido;
            }
            
            $stmt = $this->conex->prepare("
                SELECT pd.*, pr.nombre as nombre_producto, pr.marca 
                FROM pedido_detalles pd
                JOIN productos pr ON pd.id_producto = pr.id_producto
                WHERE pd.id_pedido = :id_pedido
            ");
            $stmt->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error al obtener detalles del pedido: " . $e->getMessage();
            return false;
        }
    }
    
    /**
     * Obtiene un pedido específico por su ID
     */
    public function obtenerPedido($id_pedido = null) {
        try {
            if ($id_pedido === null) {
                $id_pedido = $this->id_pedido;
            }
            
            $stmt = $this->conex->prepare("
                SELECT p.*, mp.nombre as metodo_pago, me.nombre as metodo_entrega 
                FROM pedido p
                LEFT JOIN metodo_pago mp ON p.id_metodopago = mp.id_metodopago
                LEFT JOIN metodo_entrega me ON p.id_entrega = me.id_entrega
                WHERE p.id_pedido = :id_pedido
            ");
            $stmt->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error al obtener pedido: " . $e->getMessage();
            return false;
        }
    }
    
    /**
     * Registra un nuevo pedido y sus detalles
     */
    public function registrarPedido() {
        try {
            $this->conex->beginTransaction();
            
            // Insertar el pedido principal
            $stmt = $this->conex->prepare("
                INSERT INTO pedido (tipo, fecha, estado, precio_total, referencia_bancaria, telefono_emisor, banco, id_entrega, id_metodopago) 
                VALUES (:tipo, NOW(), :estado, :precio_total, :referencia_bancaria, :telefono_emisor, :banco, :id_entrega, :id_metodopago)
            ");
            
            $stmt->bindParam(':tipo', $this->datosPedido['tipo'], PDO::PARAM_STR);
            $stmt->bindParam(':estado', $this->datosPedido['estado'], PDO::PARAM_STR);
            $stmt->bindParam(':precio_total', $this->datosPedido['precio_total'], PDO::PARAM_STR);
            $stmt->bindParam(':referencia_bancaria', $this->datosPedido['referencia_bancaria'], PDO::PARAM_STR);
            $stmt->bindParam(':telefono_emisor', $this->datosPedido['telefono_emisor'], PDO::PARAM_STR);
            $stmt->bindParam(':banco', $this->datosPedido['banco'], PDO::PARAM_STR);
            $stmt->bindParam(':id_entrega', $this->datosPedido['id_entrega'], PDO::PARAM_INT);
            $stmt->bindParam(':id_metodopago', $this->datosPedido['id_metodopago'], PDO::PARAM_INT);
            
            $stmt->execute();
            $id_pedido = $this->conex->lastInsertId();
            
            // Insertar los detalles del pedido
            foreach ($this->detallesPedido as $detalle) {
                // Validar que exista el producto y que tenga stock suficiente
                $stmt_stock = $this->conex->prepare("
                    SELECT stock_disponible FROM productos WHERE id_producto = :id_producto
                ");
                $stmt_stock->bindParam(':id_producto', $detalle['id_producto'], PDO::PARAM_INT);
                $stmt_stock->execute();
                $producto = $stmt_stock->fetch(PDO::FETCH_ASSOC);
                
                if (!$producto) {
                    throw new Exception("El producto con ID {$detalle['id_producto']} no existe.");
                }
                
                $cantidad = $detalle['cantidad'];
                if ($producto['stock_disponible'] < $cantidad) {
                    throw new Exception("Stock insuficiente para el producto ID {$detalle['id_producto']}.");
                }
                
                // Registrar detalle del pedido
                $stmt_detalle = $this->conex->prepare("
                    INSERT INTO pedido_detalles (cantidad, precio_unitario, id_pedido, id_producto)
                    VALUES (:cantidad, :precio_unitario, :id_pedido, :id_producto)
                ");
                
                $stmt_detalle->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
                $stmt_detalle->bindParam(':precio_unitario', $detalle['precio_unitario'], PDO::PARAM_STR);
                $stmt_detalle->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);
                $stmt_detalle->bindParam(':id_producto', $detalle['id_producto'], PDO::PARAM_INT);
                $stmt_detalle->execute();
                
                // Actualizar stock del producto
                $stmt_update = $this->conex->prepare("
                    UPDATE productos 
                    SET stock_disponible = stock_disponible - :cantidad
                    WHERE id_producto = :id_producto
                ");
                
                $stmt_update->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
                $stmt_update->bindParam(':id_producto', $detalle['id_producto'], PDO::PARAM_INT);
                $stmt_update->execute();
            }
            
            // Crear notificación para el pedido
            $stmt_notif = $this->conex->prepare("
                INSERT INTO notificaciones (titulo, mensaje, fecha, estado, id_pedido)
                VALUES (:titulo, :mensaje, CURDATE(), 'sin_leer', :id_pedido)
            ");
            
            $titulo = "Nuevo pedido registrado";
            $mensaje = "Se ha registrado un nuevo pedido con ID: {$id_pedido}";
            
            $stmt_notif->bindParam(':titulo', $titulo, PDO::PARAM_STR);
            $stmt_notif->bindParam(':mensaje', $mensaje, PDO::PARAM_STR);
            $stmt_notif->bindParam(':id_pedido', $id_pedido, PDO::PARAM_INT);
            $stmt_notif->execute();
            
            $this->conex->commit();
            return array('respuesta' => 1, 'id_pedido' => $id_pedido);
        } catch (Exception $e) {
            $this->conex->rollBack();
            return array('respuesta' => 0, 'error' => $e->getMessage());
        }
    }
    
    /**
     * Actualiza un pedido existente
     */
    public function actualizarPedido() {
        try {
            $stmt = $this->conex->prepare("
                UPDATE pedido 
                SET estado = :estado, 
                    referencia_bancaria = :referencia_bancaria,
                    telefono_emisor = :telefono_emisor,
                    banco = :banco,
                    id_entrega = :id_entrega,
                    id_metodopago = :id_metodopago
                WHERE id_pedido = :id_pedido
            ");
            
            $stmt->bindParam(':estado', $this->datosPedido['estado'], PDO::PARAM_STR);
            $stmt->bindParam(':referencia_bancaria', $this->datosPedido['referencia_bancaria'], PDO::PARAM_STR);
            $stmt->bindParam(':telefono_emisor', $this->datosPedido['telefono_emisor'], PDO::PARAM_STR);
            $stmt->bindParam(':banco', $this->datosPedido['banco'], PDO::PARAM_STR);
            $stmt->bindParam(':id_entrega', $this->datosPedido['id_entrega'], PDO::PARAM_INT);
            $stmt->bindParam(':id_metodopago', $this->datosPedido['id_metodopago'], PDO::PARAM_INT);
            $stmt->bindParam(':id_pedido', $this->id_pedido, PDO::PARAM_INT);
            
            $stmt->execute();
            
            // Crear notificación para la actualización
            $stmt_notif = $this->conex->prepare("
                INSERT INTO notificaciones (titulo, mensaje, fecha, estado, id_pedido)
                VALUES (:titulo, :mensaje, CURDATE(), 'sin_leer', :id_pedido)
            ");
            
            $titulo = "Pedido actualizado";
            $mensaje = "Se ha actualizado el pedido con ID: {$this->id_pedido}";
            
            $stmt_notif->bindParam(':titulo', $titulo, PDO::PARAM_STR);
            $stmt_notif->bindParam(':mensaje', $mensaje, PDO::PARAM_STR);
            $stmt_notif->bindParam(':id_pedido', $this->id_pedido, PDO::PARAM_INT);
            $stmt_notif->execute();
            
            return array('respuesta' => 1);
        } catch (PDOException $e) {
            return array('respuesta' => 0, 'error' => $e->getMessage());
        }
    }
    
    /**
     * Elimina un pedido y sus detalles
     */
    public function eliminarPedido() {
        try {
            $this->conex->beginTransaction();
            
            // Primero recuperamos los detalles para restaurar stock
            $detalles = $this->obtenerDetallesPedido();
            
            // Restaurar stock de productos
            foreach ($detalles as $detalle) {
                $stmt_update = $this->conex->prepare("
                    UPDATE productos 
                    SET stock_disponible = stock_disponible + :cantidad
                    WHERE id_producto = :id_producto
                ");
                
                $stmt_update->bindParam(':cantidad', $detalle['cantidad'], PDO::PARAM_INT);
                $stmt_update->bindParam(':id_producto', $detalle['id_producto'], PDO::PARAM_INT);
                $stmt_update->execute();
            }
            
            // Eliminar notificaciones relacionadas
            $stmt_notif = $this->conex->prepare("
                DELETE FROM notificaciones WHERE id_pedido = :id_pedido
            ");
            $stmt_notif->bindParam(':id_pedido', $this->id_pedido, PDO::PARAM_INT);
            $stmt_notif->execute();
            
            // Eliminar detalles del pedido
            $stmt_detalles = $this->conex->prepare("
                DELETE FROM pedido_detalles WHERE id_pedido = :id_pedido
            ");
            $stmt_detalles->bindParam(':id_pedido', $this->id_pedido, PDO::PARAM_INT);
            $stmt_detalles->execute();
            
            // Finalmente eliminar el pedido
            $stmt_pedido = $this->conex->prepare("
                DELETE FROM pedido WHERE id_pedido = :id_pedido
            ");
            $stmt_pedido->bindParam(':id_pedido', $this->id_pedido, PDO::PARAM_INT);
            $stmt_pedido->execute();
            
            $this->conex->commit();
            return array('respuesta' => 1);
        } catch (PDOException $e) {
            $this->conex->rollBack();
            return array('respuesta' => 0, 'error' => $e->getMessage());
        }
    }
    
    /**
     * Actualiza el estado de un pedido
     */
    public function actualizarEstadoPedido() {
        try {
            $stmt = $this->conex->prepare("
                UPDATE pedido SET estado = :estado WHERE id_pedido = :id_pedido
            ");
            
            $stmt->bindParam(':estado', $this->estado, PDO::PARAM_STR);
            $stmt->bindParam(':id_pedido', $this->id_pedido, PDO::PARAM_INT);
            
            $stmt->execute();
            
            // Crear notificación para el cambio de estado
            $stmt_notif = $this->conex->prepare("
                INSERT INTO notificaciones (titulo, mensaje, fecha, estado, id_pedido)
                VALUES (:titulo, :mensaje, CURDATE(), 'sin_leer', :id_pedido)
            ");
            
            $titulo = "Estado de pedido actualizado";
            $mensaje = "El pedido {$this->id_pedido} ha sido actualizado a: {$this->estado}";
            
            $stmt_notif->bindParam(':titulo', $titulo, PDO::PARAM_STR);
            $stmt_notif->bindParam(':mensaje', $mensaje, PDO::PARAM_STR);
            $stmt_notif->bindParam(':id_pedido', $this->id_pedido, PDO::PARAM_INT);
            $stmt_notif->execute();
            
            return array('respuesta' => 1);
        } catch (PDOException $e) {
            return array('respuesta' => 0, 'error' => $e->getMessage());
        }
    }
    
    /**
     * Obtiene los métodos de pago disponibles
     */
    public function obtenerMetodosPago() {
        try {
            $stmt = $this->conex->prepare("
                SELECT * FROM metodo_pago WHERE estatus = 1
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error al obtener métodos de pago: " . $e->getMessage();
            return false;
        }
    }
    
    /**
     * Obtiene los métodos de entrega disponibles
     */
    public function obtenerMetodosEntrega() {
        try {
            $stmt = $this->conex->prepare("
                SELECT * FROM metodo_entrega WHERE estatus = 1
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error al obtener métodos de entrega: " . $e->getMessage();
            return false;
        }
    }
    
    /**
     * Obtiene los productos disponibles en inventario
     */
    public function obtenerProductos() {
        try {
            $stmt = $this->conex->prepare("
                SELECT * FROM productos WHERE estatus = 1 AND stock_disponible > 0
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error al obtener productos: " . $e->getMessage();
            return false;
        }
    }
}
?>