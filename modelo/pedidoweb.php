<?php

require_once 'conexion.php';


class pedidoWeb extends Conexion {
   

     function __construct() {
        parent::__construct();
      
    }

    public function procesarPedidoweb($jsonDatos){
        $datos = json_decode($jsonDatos, true);
        $operacion = $datos['operacion'];
        $datosProcesar = $datos['datos'];

        try {
            switch($operacion){
                case 'confirmar':
                    return $this->confirmarPedido($datosProcesar);

                case 'eliminar':
                    return $this->eliminarPedido($datosProcesar);
                
                    

                case 'delivery':
                        return $this->actualizarDelivery($datosProcesar); 
                           
                 
                case 'tracking':
                         return $this->actualizarTracking($datosProcesar);  
                
                default:
                    return    ['respuesta' => 0, 'mensaje' => 'Operación no válida'];
            }
        } catch (Exception $e){
                return ['respuesta' => 0, 'mensaje' => 'Operación no válida'];
        }
    }

   public function consultarPedidosCompletos() {
    $sql = "SELECT 
                p.id_pedido,
                p.tipo,
                p.fecha,
                p.estado,
                p.precio_total_bs,
                p.precio_total_usd,
                p.tracking,
                p.id_pago,
                p.id_persona,
                
                cli.nombre AS nombre,
                cli.apellido AS apellido,
                 cli.correo AS correo,   
                d.direccion_envio AS direccion,
                
                me.nombre AS metodo_entrega,
                me.descripcion AS descripcion_entrega,
                
                dp.referencia_bancaria,
                dp.telefono_emisor,
                dp.banco_destino,
                dp.banco,
                dp.monto,
                dp.monto_usd,
                dp.imagen,
                
                mp.nombre AS metodo_pago,
                mp.descripcion AS descripcion_pago

            FROM pedido p
            LEFT JOIN cliente cli ON p.id_persona = cli.id_persona
            LEFT JOIN direccion d ON p.id_direccion = d.id_direccion
            LEFT JOIN metodo_entrega me ON d.id_metodoentrega = me.id_entrega
            LEFT JOIN detalle_pago dp ON p.id_pago = dp.id_pago
            LEFT JOIN metodo_pago mp ON dp.id_metodopago = mp.id_metodopago

            WHERE p.tipo = 2
            ORDER BY p.fecha DESC";

    $stmt = $this->getconex1()->prepare($sql);  
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function consultarDetallesPedido($id_pedido) {
    $sql = "SELECT 
                pd.id_producto,
                pr.nombre AS nombre,
                pr.descripcion,
                pd.cantidad,
                pd.precio_unitario,
                (pd.cantidad * pd.precio_unitario) AS subtotal
            FROM pedido_detalles pd
            JOIN productos pr ON pd.id_producto = pr.id_producto
            WHERE pd.id_pedido = ?";

    $stmt = $this->getconex1()->prepare($sql);
    $stmt->execute([$id_pedido]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
    private function eliminarPedido($id_pedido) {
        try {
            $conex = $this->getconex1();
            $conex->beginTransaction();

            $sqlDetalles = "SELECT id_producto, cantidad FROM pedido_detalles WHERE id_pedido = ?";
            $stmtDetalles = $conex->prepare($sqlDetalles);
            $stmtDetalles->execute([$id_pedido]);
            $detalles = $stmtDetalles->fetchAll(PDO::FETCH_ASSOC);

            foreach ($detalles as $detalle) {
                $sqlUpdateStock = "UPDATE productos SET stock_disponible = stock_disponible + ? WHERE id_producto = ?";
                $stmtStock = $conex->prepare($sqlUpdateStock);
                $stmtStock->execute([$detalle['cantidad'], $detalle['id_producto']]);
            }

            $sqlEliminar = "UPDATE pedido SET estado = 0 WHERE id_pedido = ?";
            $stmtEliminar = $conex->prepare($sqlEliminar);
            $stmtEliminar->execute([$id_pedido]);

         
            $conex = null;
            return ['respuesta' => 1, 'msg' => 'Pedido eliminado correctamente'];
        } catch (Exception $e) {
            $conex->rollBack();
            error_log("Error al eliminar pedido: " . $e->getMessage());
            return ['respuesta' => 0, 'msg' => 'Error al eliminar el pedido'];
            $conex = null;
        
        }
    }

    private function confirmarPedido($id_pedido) {
        $conex = $this->getConex1();
        try {
            $conex->beginTransaction();
            $sql = "UPDATE pedido SET estado = 2 WHERE id_pedido = ?";
            $stmt = $conex->prepare($sql);
            if ($stmt->execute([$id_pedido])) {
                $conex->commit();  // <-- Aquí debes confirmar la transacción
                $conex = null;
                return ['respuesta' => 1, 'msg' => 'Pedido confirmado'];
            } else {
                $conex->rollBack();
                $conex = null;
                return ['respuesta' => 'error', 'msg' => 'No se pudo confirmar el pedido'];
            }
        } catch (PDOException $e) {
            if ($conex) {
                $conex->rollBack();
                $conex = null;
            }
            throw $e;
        }
    }
    




    private function actualizarDelivery($datos) {
        $conex = $this->getConex1();
        try {
            $conex->beginTransaction();
    
            $sql = "UPDATE pedido SET estado = ?, direccion = ? WHERE id_pedido = ?";
            $stmt = $conex->prepare($sql);
            $stmt->execute([$datos['estado_delivery'], $datos['direccion'], $datos['id_pedido']]);
    
            $conex->commit();
            return ['respuesta' => 1, 'msg' => 'Estado actualizado correctamente'];
        } catch (Exception $e) {
            $conex->rollBack();
            error_log("Error al actualizar delivery: " . $e->getMessage());
            return ['respuesta' => 0, 'msg' => 'Error al actualizar estado'];
        }
    }
    

    private function actualizarTracking($datos) {
        $conex = $this->getConex1();
        try {
            $conex->beginTransaction();
    
            $sql = "UPDATE pedido SET tracking = ? WHERE id_pedido = ?";
            $stmt = $conex->prepare($sql);
            $stmt->execute([$datos['tracking'], $datos['id_pedido']]);
    
            $conex->commit();
    
            // Enviar correo al cliente
            $this->enviarCorreoTracking($datos['correo_cliente'], $datos['tracking'], $datos['nombre_cliente']);
    
            return ['respuesta' => 1, 'msg' => 'Tracking actualizado y correo enviado'];
        } catch (Exception $e) {
            $conex->rollBack();
            error_log("Error al actualizar tracking: " . $e->getMessage());
            return ['respuesta' => 0, 'msg' => 'Error al actualizar el tracking'];
        }
    }


    private function enviarCorreoTracking($correo, $tracking, $nombre_cliente) {
       
    
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'help.lovemakeupca@gmail.com';
            $mail->Password = 'uoteptddjgljeukw';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
    
            $mail->setFrom('help.lovemakeupca@gmail.com', 'Love Makeup');
            $mail->addAddress($correo, $nombre_cliente);
            $mail->Subject = 'Información de Envío: Número de Tracking';
            $mail->isHTML(true);


            $mail->Body = "
            <html>
            <body style='font-family: Arial, sans-serif;'>
                <h2 style='color:#df059d;'>¡Tu pedido ya fue enviado!</h2>
                <p>Hola <strong>$nombre_cliente</strong>,</p>
                <p>Gracias por tu compra en <strong>LoveMakeup C.A</strong>.</p>
                <p>Tu número de tracking es: <strong style='font-size: 18px;'>$tracking</strong></p>
                <p>Podrás utilizarlo para rastrear tu pedido.</p>
                <hr>
                <p>Si tienes alguna duda, contáctanos:</p>
                 <p><strong>LoveMakeup C.A</strong> es tu mejor aliado en productos de belleza y maquillaje. ¡Descubre tu mejor versión con nosotros!</p>

      <p>Telf.: +58 424 5115414<br> Correo: <a href='mailto:help.lovemakeupca@gmail.com'>help.lovemakeupca@gmail.com</a></p>

<!-- Redes Sociales -->
<div style='text-align: center; margin-top: 30px;'>
  <a href='https://www.instagram.com/lovemakeupyk/' target='_blank' style='margin: 0 10px;'>
    <img src='https://cdn-icons-png.flaticon.com/24/1384/1384031.png' alt='Instagram' style='vertical-align: middle;'>
  </a>
  <a href='https://www.facebook.com/lovemakeupyk/' target='_blank' style='margin: 0 10px;'>
    <img src='https://cdn-icons-png.flaticon.com/24/1384/1384005.png' alt='Facebook' style='vertical-align: middle;'>
  </a>
  <a href='https://wa.me/584245115414' target='_blank' style='margin: 0 10px;'>
    <img src='https://cdn-icons-png.flaticon.com/24/733/733585.png' alt='WhatsApp' style='vertical-align: middle;'>
  </a>
</div>
                <p style='font-size: 12px; color: #888;'>© 2025 LoveMakeup C.A. Todos los derechos reservados.</p>
            </body>
            </html>";
    
            $mail->send();
        } catch (Exception $e) {
            error_log("Error al enviar correo tracking: " . $mail->ErrorInfo);
        }
    }

   
}

?>
