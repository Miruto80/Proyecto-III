<?php
require_once('modelo/datos.php');
class proveedores extends datos{
    // Propiedades de la clase	
	private $tipo_documento; 
	private $numero_documento;
	private $nombre;
	private $direccion;
	private $correo;
	private $telefono;
	private $catalogo;
	private $imagen;
	private $catalogo_archivo;

	// Métodos para establecer los valores de las propiedades
	function set_tipo_documento($valor){
		$this->tipo_documento = $valor; 
	}
	function set_numero_documento($valor){
		$this->numero_documento = $valor;
	}
	function set_nombre($valor){
		$this->nombre = $valor;
	}
	function set_direccion($valor){
		$this->direccion = $valor;
	}
	function set_correo($valor){
		$this->correo = $valor;
	}	
	function set_telefono($valor){
		$this->telefono = $valor;
	}	
	function set_catalogo($valor){
		$this->catalogo = $valor;
	}
	function set_imagen($valor){
		$this->imagen = $valor;
	}
	function set_catalogo_archivo($valor){
		$this->catalogo_archivo = $valor;
	}

	// Métodos para obtener los valores de las propiedades	
	function get_tipo_documento(){
		return $this->tipo_documento;
	}
	function get_numero_documento(){
		return $this->numero_documento;
	}
	function get_nombre(){
		return $this->nombre;
	}	
	function get_direccion(){
		return $this->direccion;
	}
	function get_correo(){
		return $this->correo;
	}	
	function get_telefono(){
		return $this->telefono;
	}	
	function get_catalogo(){
		return $this->catalogo;
	}
	function get_imagen(){
		return $this->imagen;
	}
	function get_catalogo_archivo(){
		return $this->catalogo_archivo;
	}

	// Método para incluir un nuevo proveedor		
	function incluir(){
		$r = array();
		if(!$this->existe($this->numero_documento)){
			$co = $this->conecta();
			$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			try {
				$co->query("Insert into proveedores(tipo_documento, numero_documento,nombre,direccion,correo,telefono,catalogo,imagen, catalogo_archivo)
					Values(
					'$this->tipo_documento',
					'$this->numero_documento',
					'$this->nombre',
					'$this->direccion',
					'$this->correo',
					'$this->telefono',
					'$this->catalogo',
					'$this->imagen',
					'$this->catalogo_archivo')");
				$r['resultado'] = 'incluir';
				$r['mensaje'] =  '¡Registro guardado con exito!';
			} catch(Exception $e) {
				$r['resultado'] = 'error';
				$r['mensaje'] =  $e->getMessage();
			}
		}
		else{
			$r['resultado'] = 'incluir';
			$r['mensaje'] =  'Ya existe el numero de documento';
		}
		return $r;
	}

	// Método para modificar un proveedor existente
	function modificar(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		if($this->existe($this->numero_documento)){
			try {
				$resultado = $co->query("SELECT imagen, catalogo_archivo FROM proveedores WHERE numero_documento = '$this->numero_documento'");
				$fila = $resultado->fetch(PDO::FETCH_ASSOC);
				$imagen_actual = $fila['imagen'];
				$catalogo_archivo_actual = $fila['catalogo_archivo'];

				$co->query("Update proveedores set 
					tipo_documento = '$this->tipo_documento',
					numero_documento = '$this->numero_documento',
					nombre = '$this->nombre',
					direccion = '$this->direccion',
					correo = '$this->correo',
					telefono = '$this->telefono',
					catalogo = '$this->catalogo',
					imagen = '$this->imagen',
					catalogo_archivo = '$this->catalogo_archivo'
					where
					numero_documento = '$this->numero_documento'
					");

				if ($imagen_actual && $imagen_actual != 'otros/img/proveedores/default.png') {
					if (file_exists($imagen_actual)) {
						unlink($imagen_actual);
					}
				}

				if ($this->catalogo_archivo != $catalogo_archivo_actual) {
					if ($catalogo_archivo_actual && file_exists($catalogo_archivo_actual)) {
						unlink($catalogo_archivo_actual);
					}
				}

				$r['resultado'] = 'modificar';
				$r['mensaje'] =  '¡Registro actualizado con éxito!';
			} catch(Exception $e) {
				$r['resultado'] = 'error';
				$r['mensaje'] =  $e->getMessage();
			}
		}
		else{
			$r['resultado'] = 'modificar';
			$r['mensaje'] =  'numero de documento no registrado';
		}
		return $r;
	}

	// Método para eliminar un proveedor
	function eliminar(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		if($this->existe($this->numero_documento)){
			try {
				$resultado = $co->query("SELECT imagen, catalogo_archivo FROM proveedores WHERE numero_documento = '$this->numero_documento'");
				$fila = $resultado->fetch(PDO::FETCH_ASSOC);
				$imagen = $fila['imagen'];
				$catalogo_archivo = $fila['catalogo_archivo'];

				$co->query("delete from proveedores 
					where
					numero_documento = '$this->numero_documento'
					");

				if ($imagen && $imagen != 'otros/img/proveedores/default.png') {
					if (file_exists($imagen)) {
						unlink($imagen);
					}
				}

				if ($catalogo_archivo && file_exists($catalogo_archivo)) {
					unlink($catalogo_archivo);
				}

				$r['resultado'] = 'eliminar';
				$r['mensaje'] =  '¡Registro eliminado con exito!';

			} catch (Exception $e) {
				$r['resultado'] = 'error';
				if ($e->getCode() == 23000) {
					$r['mensaje'] = 'No se puede eliminar este proveedor porque tiene entradas de productos asociadas';
				} else {
					$r['mensaje'] = $e->getMessage();
				}
			}
		}		
		else{
			$r['resultado'] = 'eliminar';
			$r['mensaje'] =  'No existe el numero de documento';
		}
		return $r;
	}

	// Método para consultar todos los proveedores
	function consultar(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try{		
			$resultado = $co->query("Select * from proveedores  ORDER BY id DESC");
			if($resultado){
				$respuesta = '';
				$n=1;
				foreach($resultado as $r){
					$respuesta = $respuesta."<tr class='text-center'>";
					$respuesta = $respuesta."<td class='align-middle'>$n</td>";
					$respuesta = $respuesta."<td class='align-middle'>".$r['tipo_documento'].":".$r['numero_documento']."</td>";
					$respuesta = $respuesta."<td class='align-middle'>".$r['nombre']."</td>";
					$respuesta = $respuesta."<td class='align-middle'>".$r['direccion']."</td>";
					$respuesta = $respuesta."<td class='align-middle'>".$r['correo']."</td>";
					$respuesta = $respuesta."<td class='align-middle'>".$r['telefono']."</td>";
					$respuesta = $respuesta."<td class='align-middle'>".$r['catalogo']."</td>";
					$respuesta = $respuesta."<td class='align-middle'>";
					if ($r['catalogo_archivo']) {
						$respuesta = $respuesta."<a href='".$r['catalogo_archivo']."' target='_blank'>Ver catálogo</a>";
					} else {
						$respuesta = $respuesta."No disponible";
					}
					$respuesta = $respuesta."</td>";
					$respuesta = $respuesta."<td class='align-middle'><a href='".$r['imagen']."' target='_blank'><img src='".$r['imagen']."' alt='Imagen de la ubicación' class='img'/></a></td>";
					$respuesta = $respuesta."<td class='align-middle'>";
					$respuesta = $respuesta.
					"<button type='button' class='btn-sm btn-primary w-50 small-width mb-1' onclick='pone(this,0)' title='Modificar Proveedor'><i class='bi bi-arrow-repeat'></i></button><br/>";
					$respuesta = $respuesta."<button type='button'
					class='btn-sm btn-danger w-50 small-width mt-1' 
					onclick='pone(this,1)'
					title='Eliminar Proveedor'
					><i class='bi bi-trash'></i></button><br/>";
					$respuesta = $respuesta."</td>";
					$respuesta = $respuesta."</tr>";
					$n++;
				}
				$r['resultado'] = 'consultar';
				$r['mensaje'] =  $respuesta;
			}
			else{
				$r['resultado'] = 'consultar';
				$r['mensaje'] =  '';
			}	
		}catch(Exception $e){
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage();
		}
		return $r;
	}

	// Método privado para verificar si un número de documento existe
	private function existe($numero_documento){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try{		
			$resultado = $co->query("Select * from proveedores where numero_documento='$numero_documento'");	
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
}
?>