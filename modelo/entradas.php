<?php
require_once('modelo/datos.php');

class entradas extends datos{
	
	function consultar() {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try {
			$resultado = $co->query("
				SELECT e.id, e.fecha_entrada, e.nota_entrega,
				pv.tipo_documento, pv.numero_documento, pv.nombre AS proveedor_nombre, p.codigo, p.nombre 
				AS producto_nombre, ed.cantidad, ed.precio, (ed.cantidad * ed.precio) AS subtotal,
				SUM(ed.cantidad * ed.precio) OVER (PARTITION BY e.id) AS total
				FROM entradas e
				JOIN proveedores pv ON e.proveedor_id = pv.id
				JOIN entradas_detalles ed ON e.id = ed.entrada_id
				JOIN productos p ON ed.producto_id = p.id
				ORDER BY e.fecha_entrada DESC;
				");
			if ($resultado) {
				$respuesta = '';
				$n = 1;
				$currentSalida = null;
				$prevTotal = 0;
				foreach ($resultado as $r) {
					if ($currentSalida !== $r['id']) {

						if ($currentSalida !== null) {
							$respuesta .= "</tbody></table></td>";
							$respuesta .= "<td class='align-middle'>$" . number_format($prevTotal, 2) . "</td>";
							$respuesta .= "</tr>";
						}

						$prevTotal = $r['total'];
						$respuesta .= "<tr class='text-center'>";
						$respuesta .= "<td class='align-middle'>" . $n . "</td>";
						$respuesta .= "<td class='align-middle'>" . $r['fecha_entrada'] . "</td>";

						$respuesta .= "<td class='align-middle'>" . $r['tipo_documento'] . ": " . $r['numero_documento'] . " " . "<br>" . "Nombre: " . $r['proveedor_nombre'] . "</td>";
						
						$respuesta .= "<td class='align-middle'>";
						if ($r['nota_entrega']) {
							$respuesta .= "<a href='" . $r['nota_entrega'] . "' target='_blank'>Ver nota</a>";
						} else {
							$respuesta .= "No disponible";
						}
						$respuesta .= "</td>";
						
						$respuesta .= "<td class='align-middle'><table class='table table-sm'><thead><tr><th>Código</th><th>Nombre</th><th>Cantidad</th><th>Precio</th><th>Subtotal</th></tr></thead><tbody>";
						$currentSalida = $r['id'];
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
				if ($currentSalida !== null) {
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

	function entrada($proveedor_id, $producto_id, $cantidad, $precio, $imagen = null) {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try {
			$co->beginTransaction();
			date_default_timezone_set('America/Caracas');//zona horaria 
			$fecha = date('Y-m-d H:i:s');
			
			$guarda = $co->prepare("INSERT INTO entradas(fecha_entrada, proveedor_id, nota_entrega) VALUES (?, ?, ?)");
			$guarda->execute([$fecha, $proveedor_id, $imagen]);
			
			$lid = $co->lastInsertId();
			$tamano = count($producto_id);
			for($i = 0; $i < $tamano; $i++) {
				$gd = $co->prepare("INSERT INTO entradas_detalles (entrada_id, producto_id, cantidad, precio) VALUES (?, ?, ?, ?)");
				$gd->execute([$lid, $producto_id[$i], $cantidad[$i], $precio[$i]]);
				
				// Actualizar el stock y el precio de compra del producto
				$actualizaProducto = $co->prepare("UPDATE productos SET stock_total = stock_total + ?, precio_compra = ? WHERE id = ?");
				$actualizaProducto->execute([$cantidad[$i], $precio[$i], $producto_id[$i]]);
			}   
			$co->commit();
			$r['resultado'] = 'entrada';
			$r['mensaje'] = '¡Entrada registrada con éxito!';
		} catch(Exception $e) {
			$co->rollback();
			$r['resultado'] = 'error';
			$r['mensaje'] = $e->getMessage();
		}
		return $r;
	}

	function listadoproveedores(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array(); // en este arreglo // se enviara la respuesta a la solicitud y el // contenido de la respuesta
		try{
			$resultado = $co->query("Select * from proveedores");
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
					$respuesta = $respuesta.$r['direccion'];
					$respuesta = $respuesta."</td>";
					$respuesta = $respuesta."<td>";
					$respuesta = $respuesta.$r['correo'];
					$respuesta = $respuesta."</td>";
					$respuesta = $respuesta."<td>";
					$respuesta = $respuesta.$r['telefono'];
					$respuesta = $respuesta."</td>";												
					$respuesta = $respuesta."<td>";
					$respuesta = $respuesta.$r['catalogo'];
					$respuesta = $respuesta."</td>";
					$respuesta = $respuesta."<td>";
					if ($r['catalogo_archivo']) {
						$respuesta = $respuesta."<a href='".$r['catalogo_archivo']."' target='_blank'>Ver catálogo</a>";
					} else {
						$respuesta = $respuesta."No disponible";
					}
					$respuesta = $respuesta."</td>";
					$respuesta = $respuesta . "<td class='align-middle'><img src='" . $r['imagen'] . "' alt='Imagen del producto' class='img'/></td>";											
					$respuesta = $respuesta."</tr>";
				}
			}
			$r['resultado'] = 'listadoproveedores';
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
			$resultado = $co->query("Select id,codigo,nombre,precio_compra,stock_total,stock_minimo,marca,modelo,tipo_unidad,imagen from productos");
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
					$respuesta = $respuesta.$r['precio_compra'];
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

}
