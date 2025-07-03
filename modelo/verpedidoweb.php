<?php
require_once 'conexion.php';
require_once 'metodoentrega.php';
require_once 'metodopago.php';

class VentaWeb extends Conexion {
    private $objmetodoentrega;
    private $objmetodopago;

    public function __construct() {
        parent::__construct();
        $this->objmetodoentrega = new metodoentrega();
        $this->objmetodopago = new MetodoPago();
    }

    // Métodos públicos para obtener datos
    public function obtenerMetodosPago() {
        return $this->objmetodopago->obtenerMetodos();
    }

    public function obtenerMetodosEntrega() {
        return $this->objmetodoentrega->consultar();
    }

    // Método principal para procesar el pedido, sin transacciones en este nivel
    public function procesarPedido($jsonDatos) {
        $datos = json_decode($jsonDatos, true);

        if (!isset($datos['operacion']) || $datos['operacion'] !== 'registrar_pedido') {
            return ['success' => false, 'message' => 'Operación no válida.'];
        }

        $datosPedido = $datos['datos'];

        try {
            // 1. Validar stock
            $this->validarStockCarrito($datosPedido['carrito']);

            // 2. Registrar pedido
            $idPedido = $this->registrarPedido($datosPedido);
            if (!$idPedido) {
                throw new Exception("Error al registrar el pedido");
            }

            // 3. Registrar detalles, preliminar y actualizar stock
            foreach ($datosPedido['carrito'] as $item) {
                $precioUnitario = (
                    $item['cantidad'] >= $item['cantidad_mayor']
                    ? $item['precio_mayor']
                    : $item['precio_detal']
                );

                $detalle = [
                    'id_pedido' => $idPedido,
                    'id_producto' => $item['id'],
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $precioUnitario
                ];

                $idDetalle = $this->registrarDetalle($detalle);
                if (!$idDetalle) {
                    throw new Exception("Error al registrar detalle del producto: {$item['nombre']}");
                }

                $this->registrarPreliminar(['id_detalle' => $idDetalle, 'condicion' => 'pedido']);
                $this->actualizarStock($item['id'], $item['cantidad']);
            }

            return ['success' => true, 'id_pedido' => $idPedido, 'message' => 'Pedido registrado correctamente'];

        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // Funciones privadas: cada una con su propia transacción
    private function validarStockCarrito($carrito) {
        $conex = $this->getConex1();
        try {
            $conex->beginTransaction();
            foreach ($carrito as $item) {
                $stmt = $conex->prepare(
                    "SELECT stock_disponible, nombre FROM productos WHERE id_producto = :id_producto"
                );
                $stmt->execute(['id_producto' => $item['id']]);
                $producto = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$producto) {
                    throw new Exception("Producto con ID {$item['id']} no encontrado.");
                }
                if ($item['cantidad'] > $producto['stock_disponible']) {
                    throw new Exception(
                        "Stock insuficiente para {$producto['nombre']} (Disponible: {$producto['stock_disponible']}, Solicitado: {$item['cantidad']})"
                    );
                }
            }
            $conex->commit();
            return true;
        } catch (Exception $e) {
            $conex->rollBack();
            throw $e;
        }
    }

    private function registrarPedido($datos) {
        $conex = $this->getConex1();
        try {
            $conex->beginTransaction();

            $sql = "INSERT INTO pedido (
                referencia_bancaria, telefono_emisor, banco, banco_destino, direccion,
                id_metodopago, id_entrega, id_persona, estado, precio_total, tipo
            ) VALUES (
                :referencia_bancaria, :telefono_emisor, :banco, :banco_destino, :direccion,
                :id_metodopago, :id_entrega, :id_persona, :estado, :precio_total, :tipo
            )";

            $stmt = $conex->prepare($sql);
            $stmt->execute([
                'referencia_bancaria' => $datos['referencia_bancaria'],
                'telefono_emisor'      => $datos['telefono_emisor'],
                'banco'                => $datos['banco'],
                'banco_destino'        => $datos['banco_destino'],
                'direccion'            => $datos['direccion'],
                'id_metodopago'        => $datos['id_metodopago'],
                'id_entrega'           => $datos['id_entrega'],
                'id_persona'           => $datos['id_persona'],
                'estado'               => $datos['estado'],
                'precio_total'         => $datos['precio_total'],
                'tipo'                 => $datos['tipo']
            ]);
            $id = $conex->lastInsertId();
            $conex->commit();
            return $id;
        } catch (Exception $e) {
            $conex->rollBack();
            throw $e;
        }
    }

    private function registrarDetalle($detalle) {
        $conex = $this->getConex1();
        try {
            $conex->beginTransaction();
            $sql = "INSERT INTO pedido_detalles (id_pedido, id_producto, cantidad, precio_unitario)
                    VALUES (:id_pedido, :id_producto, :cantidad, :precio_unitario)";
            $stmt = $conex->prepare($sql);
            $stmt->execute($detalle);
            $id = $conex->lastInsertId();
            $conex->commit();
            return $id;
        } catch (Exception $e) {
            $conex->rollBack();
            throw $e;
        }
    }

    private function registrarPreliminar($datos) {
        $conex = $this->getConex1();
        try {
            $conex->beginTransaction();
            $sql = "INSERT INTO preliminar (id_detalle, condicion)
                    VALUES (:id_detalle, :condicion)";
            $stmt = $conex->prepare($sql);
            $res = $stmt->execute($datos);
            $conex->commit();
            return $res;
        } catch (Exception $e) {
            $conex->rollBack();
            throw $e;
        }
    }

    private function actualizarStock($idProducto, $cantidad) {
        $conex = $this->getConex1();
        try {
            $conex->beginTransaction();
            $sql = "UPDATE productos SET stock_disponible = stock_disponible - :cantidad
                    WHERE id_producto = :id_producto";
            $stmt = $conex->prepare($sql);
            $stmt->execute(['id_producto' => $idProducto, 'cantidad' => $cantidad]);
            $conex->commit();
            return true;
        } catch (Exception $e) {
            $conex->rollBack();
            throw $e;
        }
    }

    public function vaciarCarrito() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        unset($_SESSION['carrito']);
    }
}
?>
