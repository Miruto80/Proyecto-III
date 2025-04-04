<?php
require_once('modelo/datos.php');

class apartados extends datos{
	
	function consultar() {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try {
			$resultado = $co->query("
				SELECT a.id, a.fecha_apartado, 
				c.tipo_documento, c.numero_documento, c.nombre AS cliente_nombre, c.apellido AS cliente_apellido,
				p.codigo, p.nombre AS producto_nombre, ap.cantidad, ap.precio,
				(ap.cantidad * ap.precio) AS subtotal,
				SUM(ap.cantidad * ap.precio) OVER (PARTITION BY a.id) AS total
				FROM apartados a
				JOIN clientes c ON a.cliente_id = c.id
				JOIN apartados_detalles ap ON a.id = ap.apartado_id
				JOIN productos p ON ap.producto_id = p.id
				ORDER BY a.fecha_apartado DESC;
				");
			if ($resultado) {
				$respuesta = '';
				$n = 1;
				$currentApartado = null;
				$prevTotal = 0;
				foreach ($resultado as $r) {
					if ($currentApartado !== $r['id']) {
						if ($currentApartado !== null) {
							$respuesta .= "</tbody></table></td>";
							$respuesta .= "<td class='align-middle'>$" . number_format($prevTotal, 2) . "</td>";
							$respuesta .= "</tr>";
						}
						$prevTotal = $r['total'];
						$respuesta .= "<tr class='text-center'>";
						$respuesta .= "<td class='align-middle'>" . $n . "</td>";
						$respuesta .= "<td class='align-middle'><button type='button' class='btn btn-primary btn-sm transferir-salida' data-apartado-id='" . $r['id'] . "' title='Transferir a Salida'><i class='bi bi-arrow-right-circle'></i> </button>
						<button type='button' class='btn btn-danger btn-sm cancelar-apartado' data-apartado-id='" . $r['id'] . "' title='Cancelar apartado'><i class='bi bi-x-circle'></i></button></td>";	
						$respuesta .= "<td class='align-middle'>" . $r['fecha_apartado'] . "</td>";
						$respuesta .= "<td class='align-middle'>" . $r['tipo_documento'] . ": " . $r['numero_documento'] . " " . "<br>" . "Nombre: " . $r['cliente_nombre'] . " " . $r['cliente_apellido'] . "</td>";
						$respuesta .= "<td class='align-middle'><table class='table table-sm'><thead><tr><th>Código</th><th>Nombre</th><th>Cantidad</th><th>Precio</th><th>Subtotal</th></tr></thead><tbody>";
						$currentApartado = $r['id'];
						$n++;						
					}
					$respuesta .= "<tr>";
					$respuesta .= "<td>" . $r['codigo'] . "</td>";
					$respuesta .= "<td>" . $r['producto_nombre'] . "</td>";
					$respuesta .= "<td>" . $r['cantidad'] . "</td>";
					$respuesta .= "<td>$" . number_format($r['precio'], 2) . "</td>";
					$respuesta .= "<td>$" . number_format($r['subtotal'], 2) . "</td>";
					$respuesta .= "</tr>";				
				}
				if ($currentApartado !== null) {
					$respuesta .= "</tbody></table></td>";
					$respuesta .= "<td class='align-middle'>$" . number_format($prevTotal, 2) . "</td>";
					$respuesta .= "</tr>";
				}				
				$r['resultado'] = 'consultar';
				$r['mensaje'] = $respuesta;
			} else {
				$r['resultado'] = 'consultar';
				$r['mensaje'] = '';
			}
		} catch (Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] = $e->getMessage();
		}
		return $r;
	}

	function apartar($cliente_id, $producto_id, $cantidad, $precio) {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try {
			$co->beginTransaction();
			date_default_timezone_set('America/Caracas');//zona horaria 
			$fecha = date('Y-m-d H:i:s');
			$guarda = $co->prepare("INSERT INTO apartados(fecha_apartado, cliente_id) VALUES (?, ?)");
			$guarda->execute([$fecha, $cliente_id]);
			$lid = $co->lastInsertId();
			$tamano = count($producto_id);
			for($i = 0; $i < $tamano; $i++) {
				$gd = $co->prepare("INSERT INTO apartados_detalles (apartado_id, producto_id, cantidad, precio) VALUES (?, ?, ?, ?)");
				$gd->execute([$lid, $producto_id[$i], $cantidad[$i], $precio[$i]]);
				// Actualizar el stock y el precio de compra del producto
				$actualizaProducto = $co->prepare("UPDATE productos SET stock_total = stock_total - ?, precio_venta = ? WHERE id = ?");
				$actualizaProducto->execute([$cantidad[$i], $precio[$i], $producto_id[$i]]);
			}   
			$co->commit();
			$r['resultado'] = 'apartar';
			$r['mensaje'] = "Venta procesada, número de factura: $lid";
		} catch(Exception $e) {
			$co->rollBack();
			$r['resultado'] = 'error';
			$r['mensaje'] = $e->getMessage();
		}
		return $r;
	}

	function listadoclientes(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array(); 
		try{
			$resultado = $co->query("Select * from clientes");
			$respuesta = '';
			if($resultado){
				foreach($resultado as $r){
					$respuesta = $respuesta."<tr style='cursor:pointer' onclick='colocacliente(this);'>";
					$respuesta = $respuesta."<td style='display:none'>";
					$respuesta = $respuesta.$r['id'];
					$respuesta = $respuesta."</td>";
					$respuesta = $respuesta."<td class='align-middle'>".$r['tipo_documento'].":".$r['numero_documento']."</td>";
					$respuesta = $respuesta."<td>";
					$respuesta = $respuesta.$r['nombre'];
					$respuesta = $respuesta."</td>";
					$respuesta = $respuesta."<td>";
					$respuesta = $respuesta.$r['apellido'];
					$respuesta = $respuesta."</td>";
					$respuesta = $respuesta."<td>";
					$respuesta = $respuesta.$r['correo'];
					$respuesta = $respuesta."</td>";
					$respuesta = $respuesta."<td>";
					$respuesta = $respuesta.$r['telefono'];
					$respuesta = $respuesta."</td>";
					$respuesta = $respuesta."<td>";
					$respuesta = $respuesta.$r['direccion'];
					$respuesta = $respuesta."</td>";											
					$respuesta = $respuesta."</tr>";
				}
			}
			$r['resultado'] = 'listadoclientes';
			$r['mensaje'] =  $respuesta;
		}catch(Exception $e){
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}
		return $r;
	}

	function listadoproductos(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try{
			$resultado = $co->query("Select id,codigo,nombre,precio_venta,stock_total,stock_minimo,marca,modelo,tipo_unidad,imagen from productos");
			if($resultado){
				$respuesta = '';
				foreach($resultado as $r){
					$respuesta = $respuesta."<tr style='cursor:pointer' onclick='colocaproducto(this);'>";
					$respuesta = $respuesta."<td style='display:none'>";
					$respuesta = $respuesta.$r['id'];
					$respuesta = $respuesta."</td>";
					$respuesta = $respuesta."<td>";
					$respuesta = $respuesta.$r['codigo'];
					$respuesta = $respuesta."</td>";
					$respuesta = $respuesta."<td>";
					$respuesta = $respuesta.$r['nombre'];
					$respuesta = $respuesta."</td>";
					$respuesta = $respuesta."<td>";
					$respuesta = $respuesta.$r['precio_venta'];
					$respuesta = $respuesta."</td>";
					$respuesta .= "<td>".$r['stock_total'].
					($r['stock_total'] == 0 ? "<span class='badge bg-danger' title='Este producto esta agotado'>No disponible</span>" : " " . 
						($r['stock_total'] < 0 ? "<br><span class='badge bg-danger' title='Este producto esta en stock negativo'>Stock negativo</span>" : " ".
							($r['stock_total'] <= $r['stock_minimo'] ? "<br><span class='badge bg-warning' title='Este producto llegó al nivel mínimo de stock'>Stock bajo</span>" : " "))) . "</td>";
					$respuesta = $respuesta."<td>";
					$respuesta = $respuesta.$r['marca'];
					$respuesta = $respuesta."</td>";
					$respuesta = $respuesta."<td>";
					$respuesta = $respuesta.$r['modelo'];
					$respuesta = $respuesta."</td>";
					$respuesta = $respuesta."<td>";
					$respuesta = $respuesta.$r['tipo_unidad'];
					$respuesta = $respuesta."</td>";	
					$respuesta = $respuesta . "<td class='align-middle'><img src='" . $r['imagen'] . "' alt='Imagen del producto' class='img'/></td>";									
					$respuesta = $respuesta."</tr>";
				}	  
			}
			$r['resultado'] = 'listadoproductos';
			$r['mensaje'] =  $respuesta;
			
		}catch(Exception $e){
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}
		return $r;
	}	

	function transferir_a_salida($apartado_id) {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		
		try {
			$co->beginTransaction();
			
			// Obtener datos del apartado
			$consulta = $co->prepare("SELECT cliente_id, fecha_apartado FROM apartados WHERE id = ?");
			$consulta->execute([$apartado_id]);
			$apartado = $consulta->fetch(PDO::FETCH_ASSOC);
			
			// Insertar en salidas
			$guarda = $co->prepare("INSERT INTO salidas(fecha_salida, cliente_id) VALUES (?, ?)");
			$guarda->execute([date('Y-m-d H:i:s'), $apartado['cliente_id']]);
			$salida_id = $co->lastInsertId();
			
			// Transferir detalles
			$co->prepare("INSERT INTO salidas_detalles (salida_id, producto_id, cantidad, precio) 
				SELECT ?, producto_id, cantidad, precio 
				FROM apartados_detalles 
				WHERE apartado_id = ?")->execute([$salida_id, $apartado_id]);
			
			// Eliminar apartado y sus detalles
			$co->prepare("DELETE FROM apartados_detalles WHERE apartado_id = ?")->execute([$apartado_id]);
			$co->prepare("DELETE FROM apartados WHERE id = ?")->execute([$apartado_id]);
			
			$co->commit();
			$r['resultado'] = 'transferir_a_salida';
			$r['mensaje'] = 'ok';
			
		} catch (Exception $e) {
			$co->rollBack();
			$r['resultado'] = 'error';
			$r['mensaje'] = $e->getMessage();
		}
		
		return $r;
	}

	function cancelar_apartado($apartado_id) {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		
		try {
			$co->beginTransaction();
			
			// Obtener los detalles del apartado antes de eliminarlo
			$consulta = $co->prepare("SELECT producto_id, cantidad FROM apartados_detalles WHERE apartado_id = ?");
			$consulta->execute([$apartado_id]);
			$detalles = $consulta->fetchAll(PDO::FETCH_ASSOC);
			
			// Devolver los productos al inventario
			foreach ($detalles as $detalle) {
				$actualizaProducto = $co->prepare("UPDATE productos SET stock_total = stock_total + ? WHERE id = ?");
				$actualizaProducto->execute([$detalle['cantidad'], $detalle['producto_id']]);
			}
			
			// Eliminar los detalles del apartado
			$co->prepare("DELETE FROM apartados_detalles WHERE apartado_id = ?")->execute([$apartado_id]);
			
			// Eliminar el apartado
			$co->prepare("DELETE FROM apartados WHERE id = ?")->execute([$apartado_id]);
			
			$co->commit();
			$r['resultado'] = 'cancelar_apartado';
			$r['mensaje'] = 'ok';
			
		} catch (Exception $e) {
			$co->rollBack();
			$r['resultado'] = 'error';
			$r['mensaje'] = $e->getMessage();
		}
		
		return $r;
	}
}