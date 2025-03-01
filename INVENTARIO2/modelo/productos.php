<?php
require_once('modelo/datos.php');
class productos extends datos{
    // Propiedades de la clase	
	private $codigo; 
	private $nombre;
	private $precio_compra;
	private $precio_venta;
	private $stock_total;
	private $stock_minimo;
	private $marca;
	private $modelo;
	private $tipo_unidad;
	private $imagen;
	private $categoria_id;
	private $ubicacion_id;


    // Métodos para establecer valores de las propiedades	
	function set_codigo($valor){
		$this->codigo = $valor; 
	}
	function set_nombre($valor){
		$this->nombre = $valor;
	}
	function set_precio_compra($valor){
		$this->precio_compra = $valor;
	}			
	function set_precio_venta($valor){
		$this->precio_venta = $valor;
	}	
	function set_stock_total($valor){
		$this->stock_total = $valor;
	}	
	function set_stock_minimo($valor){
		$this->stock_minimo = $valor;
	}
	function set_marca($valor){
		$this->marca = $valor;
	}	
	function set_modelo($valor){
		$this->modelo = $valor;
	}	
	function set_tipo_unidad($valor){
		$this->tipo_unidad = $valor;
	}
	function set_categoria_id($valor){
		$this->categoria_id = $valor;
	}
	function set_ubicacion_id($valor){
		$this->ubicacion_id = $valor;
	}

	function set_imagen($valor){
		$this->imagen = $valor;
	}
	function get_imagen(){
		return $this->imagen;
	}	
	function get_codigo(){
		return $this->codigo;
	}	
	function get_nombre(){
		return $this->nombre;
	}
	function get_precio_compra(){
		return $this->precio_compra;
	}	
	function get_precio_venta(){
		return $this->precio_venta;
	}
	function get_stock_total(){
		return $this->stock_total;
	}	
	function get_stock_minimo(){
		return $this->stock_minimo;
	}
	function get_marca(){
		return $this->marca;
	}
	function get_modelo(){
		return $this->modelo;
	}	
	function get_tipo_unidad(){
		return $this->tipo_unidad;
	}

    // Método para incluir un nuevo producto
	function incluir(){
		$r = array();
        // Verifica si el código ya existe		
		if(!$this->existe($this->codigo)){
			$co = $this->conecta();
			$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			try {
                // Inserta el nuevo producto en la base de datos				
				$co->query("INSERT INTO productos(codigo, nombre, precio_compra,precio_venta, stock_total, stock_minimo, marca, modelo, tipo_unidad, imagen, categoria_id, ubicacion_id)
					VALUES(
						'$this->codigo',
						'$this->nombre',
						'$this->precio_compra',
						'$this->precio_venta',
						'$this->stock_total',
						'$this->stock_minimo',
						'$this->marca',
						'$this->modelo',
						'$this->tipo_unidad',
						'$this->imagen',
						'$this->categoria_id',
						'$this->ubicacion_id'
					)");
				$r['resultado'] = 'incluir';
				$r['mensaje'] =  '¡Registro guardado con éxito!';
			} catch(Exception $e) {
				$r['resultado'] = 'error';
				$r['mensaje'] =  $e->getMessage();
			}
		}
		else{
			$r['resultado'] = 'incluir';
			$r['mensaje'] =  'Ya existe el código de producto';
		}
		return $r;
	}
    // Método para modificar un producto existente
	function modificar(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
        // Verifica si el producto existe
		if($this->existe($this->codigo)){
			try {
	            // Obtiene la imagen actual del producto			
				$resultado = $co->query("SELECT imagen FROM productos WHERE codigo = '$this->codigo'");
				$fila = $resultado->fetch(PDO::FETCH_ASSOC);
				$imagen_actual = $fila['imagen'];                
				// Actualiza los datos del producto	

				$co->query("UPDATE productos set 
					codigo = '$this->codigo',
					nombre = '$this->nombre',
					precio_compra = '$this->precio_compra',
					precio_venta = '$this->precio_venta',
					stock_total = '$this->stock_total',
					stock_minimo = '$this->stock_minimo',
					marca = '$this->marca',
					modelo = '$this->modelo',
					tipo_unidad = '$this->tipo_unidad',
					imagen = '$this->imagen',
					categoria_id = '$this->categoria_id',
					ubicacion_id = '$this->ubicacion_id'
					where
					codigo = '$this->codigo'
					");
				$r['resultado'] = 'modificar';
				$r['mensaje'] =  '¡Registro actualizado con éxito!';                
				// Elimina la imagen anterior si es necesario

				if ($imagen_actual && $imagen_actual != 'otros/img/productos/default.png') {
					if (file_exists($imagen_actual)) {
						unlink($imagen_actual);
					}
				}
			} catch(Exception $e) {
				$r['resultado'] = 'error';
				$r['mensaje'] =  $e->getMessage();
			}
		}
		else{
			$r['resultado'] = 'modificar';
			$r['mensaje'] =  'Codigo no registrado';
		}
		return $r;
	}
    // Método para eliminar un producto	
	function eliminar(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();        
		// Verifica si el producto existe
		if($this->existe($this->codigo)){
			try {                
			// Obtiene la imagen del producto a eliminar
				$resultado = $co->query("SELECT imagen FROM productos WHERE codigo = '$this->codigo'");
				$fila = $resultado->fetch(PDO::FETCH_ASSOC);
				$imagen = $fila['imagen'];
				// Elimina el producto de la base de datos

				$co->query("DELETE from productos 
					where
					codigo = '$this->codigo'
					");
				$r['resultado'] = 'eliminar';
				$r['mensaje'] =  '¡Registro eliminado con exito!';
				// Elimina la imagen del producto si existe
				if ($imagen && $imagen != 'otros/img/productos/default.png') {
					if (file_exists($imagen)) {
						unlink($imagen);
					}
				}
			} catch (Exception $e) {
				$r['resultado'] = 'error';
				if ($e->getCode() == 23000) {
					$r['mensaje'] = 'No se puede eliminar este producto porque tiene movimientos asociados';
				} else {
					$r['mensaje'] = $e->getMessage();
				}
			}
		}
		else{
			$r['resultado'] = 'eliminar';
			$r['mensaje'] =  'No existe el nombre de documento';
		}
		return $r;
	}
    // Método para consultar productos		
	function consultar(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try{
            // Realiza la consulta para obtener productos y sus detalles


			$resultado = $co->query("SELECT p.*, c.nombre as categoria_nombre, u.nombre as ubicacion_nombre
				FROM productos p 
				LEFT JOIN categorias c ON p.categoria_id = c.id 
				LEFT JOIN ubicaciones u ON p.ubicacion_id = u.id 
				ORDER BY p.id DESC
				");
			if ($resultado->rowCount() > 0) {
				$respuesta = "";
				$productos_bajo_stock = array();
				$n = 1;
				while ($row = $resultado->fetch(PDO::FETCH_ASSOC)){                    
				// Genera la respuesta en formato HTML
					$respuesta .= "<tr>";
					$respuesta .= "<td>$n</td>";
					$respuesta .= "<td>".$row['codigo']."</td>";
					$respuesta .= "<td>".$row['nombre']."</td>";

					if ($_SESSION['tipo_usuario'] == 'administrador'): 	
						$respuesta .= "<td>$".$row['precio_compra']."</td>";
					endif; 
					
					$respuesta .= "<td>$".$row['precio_venta']."</td>";

					$respuesta .= "<td>".$row['stock_total'].
					($row['stock_total'] == 0 ? "<span class='badge bg-danger' title='Este producto esta agotado'>No disponible</span>" : " " . 
						($row['stock_total'] < 0 ? "<br><span class='badge bg-danger' title='Este producto esta en stock negativo'>Stock negativo</span>" : " ".
							($row['stock_total'] <= $row['stock_minimo'] ? "<br><span class='badge bg-warning' title='Este producto llegó al nivel mínimo de stock'>Stock bajo</span>" : " "))) . "</td>";
					$respuesta .= "<td>".$row['stock_minimo']."</td>";
					$respuesta .= "<td>".$row['marca']."</td>";
					$respuesta .= "<td>".$row['modelo']."</td>";
					$respuesta .= "<td>".$row['tipo_unidad']."</td>";
					$respuesta .= "<td data-categoria-id='".$row['categoria_id']."'>".$row['categoria_nombre']."</td>";
					$respuesta .= "<td data-ubicacion-id='".$row['ubicacion_id']."'>".$row['ubicacion_nombre']."</td>";
					$respuesta .= "<td><a href='".$row['imagen']."' target='_blank'><img src='".$row['imagen']."' alt='Imagen del producto' class='img'/></a></td>";		
					$respuesta .= "<td>";
					$respuesta .= "<button type='button' class='btn-sm btn-primary w-50 small-width mb-1' onclick='pone(this,0)' title='Modificar producto'><i class='bi bi-arrow-repeat'></i></button><br/>";
					$respuesta .= "<button type='button' class='btn-sm btn-danger w-50 small-width mt-1' onclick='pone(this,1)' title='Eliminar producto'><i class='bi bi-trash'></i></button><br/>";
					$respuesta .= "</td>";
					$respuesta .= "</tr>";
					// Verifica si el stock es bajo
					if ($row['stock_total'] <= $row['stock_minimo']) {
						$productos_bajo_stock[] = array(
							'nombre' => $row['nombre'],
							'stock_total' => $row['stock_total'],
							'stock_minimo' => $row['stock_minimo']
						);
					}
					$n++;
				}
				$r['resultado'] = 'consultar';
				$r['mensaje'] = $respuesta;
				$r['productos_bajo_stock'] = $productos_bajo_stock;
			} else {
				$r['resultado'] = 'consultar';
				$r['mensaje'] = '';
			}
		} catch(Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] = $e->getMessage();
		}
		return $r;
	}

    // Método privado para verificar si un producto existe	
	private function existe($codigo){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try{		
			$resultado = $co->query("Select * from productos where codigo='$codigo'");	
			$fila = $resultado->fetchAll(PDO::FETCH_BOTH);
			if($fila){
				return true;   
			}
			else{	
				return false;;
			}	
		}catch(Exception $e){
			return false;
		}
	}	
    // Método para obtener productos que necesitan notificación		
	function obtenerProductosNotificacion() {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try {
			$resultado = $co->query("SELECT codigo, nombre, stock_total, stock_minimo FROM productos WHERE stock_total <= stock_minimo OR stock_total = 0");
			$productos = $resultado->fetchAll(PDO::FETCH_ASSOC);
			return $productos;
		} catch(Exception $e) {
			return array();
		}
	}

    // Métodos para obtener categorías, ubicaciones y proveedores
	
	function obtenerCategorias() {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try {
			$resultado = $co->query("SELECT id, nombre FROM categorias");
			$categorias = $resultado->fetchAll(PDO::FETCH_ASSOC);
			return $categorias;
		} catch(Exception $e) {
			return array();
		}
	}
	function obtenerUbicaciones() {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try {
			$resultado = $co->query("SELECT id, nombre FROM ubicaciones");
			$ubicaciones = $resultado->fetchAll(PDO::FETCH_ASSOC);
			return $ubicaciones;
		} catch(Exception $e) {
			return array();
		}
	}



}
?>