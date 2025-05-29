<?php
require_once 'conexion.php';
require_once 'metodoentrega.php';
require_once 'metodopago.php';
class VentaWeb extends Conexion {
    private $conex1;
    private $conex2;
    private $id_pedido;
    private $datos;
    private $detalles;
    private $estado;
    private $objmetodoentrega;
    private $objmetodopago;

    public function __construct() {
        parent::__construct(); // Llama al constructor de la clase padre

        // Obtener las conexiones de la clase padre
        $this->conex1 = $this->getConex1();
        $this->conex2 = $this->getConex2();
    
        $this->objmetodoentrega = new metodoentrega();
        $this->objmetodopago = new MetodoPago();
    
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

    // Obtener  los registros de metodo de pago y de entrega
 

    public function obtenerMetodosPago() {
          return $this->objmetodopago->obtenerMetodos();
    }

    public function obtenerMetodosEntrega() {
        return $this->objmetodoentrega->consultar();
    }


    public function registrarPedido() {
        try {
            // Eliminar id_pedido si existe
            if (isset($this->datos['id_pedido'])) {
                unset($this->datos['id_pedido']);
            }
    
            $sql = "INSERT INTO pedido (referencia_bancaria, telefono_emisor, banco,banco_destino,direccion, id_metodopago, id_entrega, id_persona, estado, precio_total, tipo)
                    VALUES (:referencia_bancaria, :telefono_emisor, :banco,:banco_destino,:direccion, :id_metodopago, :id_entrega, :id_persona, :estado, :precio_total, :tipo)";
            $stmt = $this->conex1->prepare($sql);
            $stmt->execute($this->datos);
            return $this->conex1->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Error al insertar pedido: " . $e->getMessage());
        }
    }

    public function validarStockCarrito($carrito) {
        foreach ($carrito as $item) {
            $stmt = $this->conex1->prepare("SELECT stock_disponible FROM productos WHERE id_producto = :id_producto");
            $stmt->bindParam(':id_producto', $item['id'], PDO::PARAM_INT);
            $stmt->execute();
            $stock = $stmt->fetchColumn();
    
            if ($stock === false) {
                throw new Exception("Producto con ID {$item['id']} no encontrado.");
            }
    
            if ($item['cantidad'] > $stock) {
                throw new Exception("No hay suficiente stock para el producto: {$item['nombre']} (Disponible: $stock, Solicitado: {$item['cantidad']})");
            }
        }
    
        return true; 
    }

    public function registrarDetalle() {
        try {
            $sql = "INSERT INTO pedido_detalles (id_pedido, id_producto, cantidad, precio_unitario)
                    VALUES (:id_pedido, :id_producto, :cantidad, :precio_unitario)";
            $stmt = $this->conex1->prepare($sql);
             $stmt->execute($this->detalles);

             $idDetalle = $this->conex1->lastInsertId();

               $sqlUpdateStock ="UPDATE productos
                                SET stock_disponible = stock_disponible - :cantidad
                                WHERE id_producto = :id_producto";
                    $stmtUpdate = $this->conex1->prepare($sqlUpdateStock);
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
      
        $_SESSION['carrito'] = []; // Vaciar el carrito
    }


    public function registrarPreliminar() {
        try {
            $stmt = $this->conex1->prepare("
                INSERT INTO preliminar (id_detalle, condicion)
                VALUES (:id_detalle, :condicion)
            ");
    
            $stmt->bindParam(':id_detalle', $this->datos['id_detalle'], PDO::PARAM_INT);
            $stmt->bindParam(':condicion', $this->datos['condicion'], PDO::PARAM_STR);
            $stmt->execute();
    
            return $this->conex1->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Error al insertar preliminar: " . $e->getMessage());
        }
    }

   
}
