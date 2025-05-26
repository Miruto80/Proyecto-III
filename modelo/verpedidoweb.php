<?php
require_once 'conexion.php';

class VentaWeb {
    private $conex;
    private $id_pedido;
    private $datos;
    private $detalles;
    private $estado;

    public function __construct() {
        $this->conex = (new Conexion())->Conex();
    }

    // Setters
    public function set_Id_pedido($id) {
        $this->id_pedido = $id;
    }

    public function set_Datos($datos) {
        $this->datos = $datos;
    }

    public function set_Detalles($detalles) {
        $this->detalles = $detalles;
    }

    public function set_Estado($estado) {
        $this->estado = $estado;
    }

    // Obtener todos los registros de metodo de pago y de entrega
    public function listar() {
        try {
            $stmt = $this->conex->prepare("
                SELECT p.*, mp.nombre AS metodo_pago, me.nombre AS metodo_entrega 
                FROM pedido p
                LEFT JOIN metodo_pago mp ON p.id_metodopago = mp.id_metodopago
                LEFT JOIN metodo_entrega me ON p.id_entrega = me.id_entrega
                ORDER BY p.fecha DESC
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function obtenerMetodosPago() {
        $stmt = $this->conex->prepare("SELECT * FROM metodo_pago WHERE estatus = 1");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerMetodosEntrega() {
        $stmt = $this->conex->prepare("SELECT * FROM metodo_entrega WHERE estatus = 1");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function registrarPedido() {
        try {
            // Eliminar id_pedido si existe
            if (isset($this->datos['id_pedido'])) {
                unset($this->datos['id_pedido']);
            }
    
            $sql = "INSERT INTO pedido (referencia_bancaria, telefono_emisor, banco,banco_destino, id_metodopago, id_entrega, direccion , id_persona, estado, precio_total, tipo)
                    VALUES (:referencia_bancaria, :telefono_emisor, :banco,:banco_destino, :id_metodopago, :id_entrega,:direccion, :id_persona, :estado, :precio_total, :tipo)";
            $stmt = $this->conex->prepare($sql);
            $stmt->execute($this->datos);
            return $this->conex->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Error al insertar pedido: " . $e->getMessage());
        }
    }

    public function registrarDetalle() {
        try {
            $sql = "INSERT INTO pedido_detalles (id_pedido, id_producto, cantidad, precio_unitario)
                    VALUES (:id_pedido, :id_producto, :cantidad, :precio_unitario)";
            $stmt = $this->conex->prepare($sql);
             $stmt->execute($this->detalles);

             $idDetalle = $this->conex->lastInsertId();

               $sqlUpdateStock ="UPDATE productos
                                SET stock_disponible = stock_disponible - :cantidad
                                WHERE id_producto = :id_producto";
                    $stmtUpdate = $this->conex->prepare($sqlUpdateStock);
                    $stmtUpdate->execute([
                        ':cantidad' => $this->detalles['cantidad'],
                        ':id_producto' => $this->detalles['id_producto']
                    ]);  

                return $idDetalle;

           


        } catch (PDOException $e) {
            throw new Exception("Error al insertar pedido: " . $e->getMessage());
            
        }
    }

    public function vaciarCarrito() {
        session_start();
        $_SESSION['carrito'] = []; // Vaciar el carrito
    }


    public function registrarPreliminar() {
        try {
            $stmt = $this->conex->prepare("
                INSERT INTO preliminar (id_detalle, condicion)
                VALUES (:id_detalle, :condicion)
            ");
    
            $stmt->bindParam(':id_detalle', $this->datos['id_detalle'], PDO::PARAM_INT);
            $stmt->bindParam(':condicion', $this->datos['condicion'], PDO::PARAM_STR);
            $stmt->execute();
    
            return $this->conex->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Error al insertar preliminar: " . $e->getMessage());
        }
    }

   
}
