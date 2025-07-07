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

    public function obtenerMetodosPago() {
        return $this->objmetodopago->obtenerMetodos();
    }

    public function obtenerMetodosEntrega() {
        return $this->objmetodoentrega->consultar();
    }

    public function procesarPedido($jsonDatos) {
        $datos = json_decode($jsonDatos, true);
        if (!isset($datos['operacion']) || $datos['operacion'] !== 'registrar_pedido') {
            return ['success' => false, 'message' => 'Operación no válida.'];
        }
        $d = $datos['datos'];
        // Validar requeridos
    

        try {
            $this->validarStockCarrito($d['carrito']);

            // 1. Registrar dirección
            $idDireccion = $this->registrarDireccion([
                'id_metodoentrega'=>$d['id_metodoentrega'],
                'id_persona'=>$d['id_persona'],
                'direccion_envio'=>$d['direccion_envio'],
                'sucursal_envio'=>$d['sucursal_envio'] ?? null
            ]);

            // 2. Insertar pedido sin id_pago (permitir NULL en DB)
            $idPedido = $this->registrarPedido([
                'tipo'=>$d['tipo'],
                'fecha'=>$d['fecha'] ?? date('Y-m-d H:i:s'),
                'estado'=>$d['estado'] ?? 'pendiente',
                'precio_total_usd'=>$d['precio_total_usd'],
                'precio_total_bs'=>$d['precio_total_bs'],
                'id_direccion'=>$idDireccion,
                'id_pago'=>null,
                'id_persona'=>$d['id_persona']
            ]);

            // 3. Registrar detalle de pago con id_pedido existente
            $idPago = $this->registrarDetallePago([
                'id_pedido'=>$idPedido,
                'id_metodopago'=>$d['id_metodopago'],
                'referencia_bancaria'=>$d['referencia_bancaria'],
                'telefono_emisor'=>$d['telefono_emisor'],
                'banco_destino'=>$d['banco_destino'],
                'banco'=>$d['banco'],
                'monto'=>$d['monto'],
                'monto_usd'=>$d['monto_usd'],
                'imagen' =>$d['imagen']
            ]);

            // 4. Actualizar pedido con id_pago
            $this->actualizarPedidoConIdPago($idPedido, $idPago);

            // 5. Registrar detalles y actualizar stock
            foreach($d['carrito'] as $item) {
                $precio = $item['cantidad'] >= $item['cantidad_mayor'] ? $item['precio_mayor'] : $item['precio_detal'];
                $this->registrarDetalle([
                    'id_pedido'=>$idPedido,
                    'id_producto'=>$item['id'],
                    'cantidad'=>$item['cantidad'],
                    'precio_unitario'=>$precio
                ]);
                $this->actualizarStock($item['id'],$item['cantidad']);
            }

            return ['success'=>true,'id_pedido'=>$idPedido,'message'=>'Pedido registrado correctamente'];
        } catch (Exception $e) {
            return ['success'=>false,'message'=>$e->getMessage()];
        }
    }

    // Auxiliares
    private function actualizarPedidoConIdPago($idPedido,$idPago) {
        $conex = $this->getConex1();
       try{
        $conex->beginTransaction();
        $stmt = $conex->prepare("UPDATE pedido SET id_pago=:id_pago WHERE id_pedido=:id_pedido");
        $stmt->execute(['id_pago'=>$idPago,'id_pedido'=>$idPedido]);

       
        $conex->commit();
         }catch (PDOException $e){
            if($conex){
                $conex->rollBack();
              
            }
            throw $e;
         }

         $conex = null;
    }

    private function validarStockCarrito($carrito) {
        $conex = $this->getConex1();
       try{
        $conex->beginTransaction();
        foreach($carrito as $it) {
            $stmt = $conex->prepare("SELECT stock_disponible,nombre FROM productos WHERE id_producto=:id");
            $stmt->execute(['id'=>$it['id']]);
            $p = $stmt->fetch(PDO::FETCH_ASSOC);
            if(!$p) throw new Exception("Producto {$it['id']} no encontrado");
            if($it['cantidad']>$p['stock_disponible']) throw new Exception("Stock insuficiente para {$p['nombre']}");
            
        }
        $conex->commit();
    }catch(Exception $e){
        if($conex){
            $conex->rollBack();
        }
        throw $e;
    }
    $conex = null;
    }


    private function registrarPedido($d) {
        $conex = $this->getConex1();
       try{ 
        $conex->beginTransaction();
        $sql = "INSERT INTO pedido(tipo,fecha,estado,precio_total_usd,precio_total_bs,id_direccion,id_pago,id_persona)".
               " VALUES(:tipo,:fecha,:estado,:precio_total_usd,:precio_total_bs,:id_direccion,:id_pago,:id_persona)";
        $stmt = $conex->prepare($sql);
        $stmt->execute($d);
        $id = $conex->lastInsertId();
        $conex->commit();
        return $id;
       }catch(Exception $e) {
        if ($conex->inTransaction()) {
            $conex->rollBack();
        }
        throw $e;
    }
    $conex = null;
    }

    private function registrarDetallePago($d) {
        $conex = $this->getConex1();
       try{
        $conex->beginTransaction();
        $sql = "INSERT INTO detalle_pago(id_pedido,id_metodopago,referencia_bancaria,telefono_emisor,banco_destino,banco,monto,monto_usd,imagen)".
               " VALUES(:id_pedido,:id_metodopago,:referencia_bancaria,:telefono_emisor,:banco_destino,:banco,:monto,:monto_usd, :imagen)";
        $stmt = $conex->prepare($sql);
        $stmt->execute($d);
        $id = $conex->lastInsertId();
        $conex->commit();
        return $id;
       }catch (Exception $e) {
        if ($conex->inTransaction()) {
            $conex->rollBack();
        }
        throw $e;
    }
    $conex = null;
    }

    private function registrarDetalle($d) {
        $conex = $this->getConex1();
       try{ 
        $conex->beginTransaction();
        $sql = "INSERT INTO pedido_detalles(id_pedido,id_producto,cantidad,precio_unitario)".
               " VALUES(:id_pedido,:id_producto,:cantidad,:precio_unitario)";
        $stmt = $conex->prepare($sql);
        $stmt->execute($d);
        $conex->commit();
       }catch (Exception $e) {
        if ($conex->inTransaction()) {
            $conex->rollBack();
        }
        throw $e;
    }
    $conex = null;
    }

    private function registrarDireccion($d) {
        $conex = $this->getConex1();
       try{
        $conex->beginTransaction();
        $sql = "INSERT INTO direccion(id_metodoentrega,id_persona,direccion_envio,sucursal_envio)".
               " VALUES(:id_metodoentrega,:id_persona,:direccion_envio,:sucursal_envio)";
        $stmt = $conex->prepare($sql);
        $stmt->execute($d);
        $id = $conex->lastInsertId();
        $conex->commit();
        return $id;
       }catch (Exception $e) {
        if ($conex->inTransaction()) {
            $conex->rollBack();
        }
        throw $e;
    }
    $conex = null;
    }

    private function actualizarStock($id,$cant) {
        $conex = $this->getConex1();
       try{ 
        $conex->beginTransaction();
        $sql = "UPDATE productos SET stock_disponible=stock_disponible-:cant WHERE id_producto=:id";
        $stmt = $conex->prepare($sql);
        $stmt->execute(['cant'=>$cant,'id'=>$id]);
        $conex->commit();
       }catch (Exception $e) {
        if ($conex->inTransaction()) {
            $conex->rollBack();
        }
        throw $e;
    }
    $conex = null;
    }

    public function vaciarCarrito() {
        if(session_status()===PHP_SESSION_NONE) session_start();
        unset($_SESSION['carrito']);
    }
}
?>
