<?php
require_once('modelo/datos.php');
class clientes extends datos{
    // Propiedades del cliente	
	private $tipo_documento; 
	private $numero_documento;
	private $nombre;
	private $apellido;
	private $correo;
	private $telefono;
	private $direccion;

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
	function set_apellido($valor){
		$this->apellido = $valor;
	}	
	function set_correo($valor){
		$this->correo = $valor;
	}	
	function set_telefono($valor){
		$this->telefono = $valor;
	}	
	function set_direccion($valor){
		$this->direccion = $valor;
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
	function get_apellido(){
		return $this->apellido;
	}
	function get_correo(){
		return $this->correo;
	}	
	function get_telefono(){
		return $this->telefono;
	}	
	function get_direccion(){
		return $this->direccion;
	}

    // Método para incluir un nuevo cliente en la base de datos
	function incluir(){
		$r = array();
        // Verifica si el número de documento ya existe		
		if(!$this->existe($this->numero_documento)){
			$co = $this->conecta();
			$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			try {
                // Inserta el nuevo cliente			
				$co->query("Insert into clientes(tipo_documento, numero_documento,nombre,apellido,correo,telefono,direccion)
					Values(
					'$this->tipo_documento',
					'$this->numero_documento',
					'$this->nombre',
					'$this->apellido',
					'$this->correo',
					'$this->telefono',
					'$this->direccion')");
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

    // Método para modificar un cliente existente	
	function modificar(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
        // Verifica si el número de documento existe		
		if($this->existe($this->numero_documento)){
			try {
                // Actualiza los datos del cliente				
				$co->query("Update clientes set 
					tipo_documento = '$this->tipo_documento',
					numero_documento = '$this->numero_documento',
					nombre = '$this->nombre',
					apellido = '$this->apellido',
					correo = '$this->correo',
					telefono = '$this->telefono',
					direccion = '$this->direccion'
					where
					numero_documento = '$this->numero_documento'
					");
				$r['resultado'] = 'modificar';
				$r['mensaje'] =  '¡Registro modificado con éxito!';
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

    // Método para eliminar un cliente	
	function eliminar(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
        // Verifica si el número de documento existe		
		if($this->existe($this->numero_documento)){
			try {
				// Elimina el cliente de la base de datos
				$co->query("delete from clientes 
					where
					numero_documento = '$this->numero_documento'
					");
				$r['resultado'] = 'eliminar';
				$r['mensaje'] =  '¡Registro eliminado con exito!';
			} catch (Exception $e) {
				$r['resultado'] = 'error';
				if ($e->getCode() == 23000) {
					$r['mensaje'] = 'No se puede eliminar este cliente porque tiene movimientos asociados';
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

    // Método para consultar todos los clientes	
	function consultar(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try{		
			$resultado = $co->query("Select * from clientes  ORDER BY id DESC");
			if($resultado){	
				$respuesta = '';
				$n=1;
				// Recorre los resultados y genera la tabla HTML
				foreach($resultado as $r){
					$respuesta = $respuesta."<tr class='text-center'>";
					$respuesta = $respuesta."<td class='align-middle'>$n</td>";
					$respuesta = $respuesta."<td class='align-middle'>".$r['tipo_documento'].":".$r['numero_documento']."</td>";					
					$respuesta = $respuesta."<td class='align-middle'>".$r['nombre']."</td>";
					$respuesta = $respuesta."<td class='align-middle'>".$r['apellido']."</td>";
					$respuesta = $respuesta."<td class='align-middle'>".$r['correo']."</td>";
					$respuesta = $respuesta."<td class='align-middle'>".$r['telefono']."</td>";
					$respuesta = $respuesta."<td class='align-middle'>".$r['direccion']."</td>";									
					$respuesta = $respuesta."<td class='align-middle'>";
					$respuesta = $respuesta.
					"<button type='button' class='btn-sm btn-primary w-50 small-width mb-1' onclick='pone(this,0)' title='Modificar cliente'><i class='bi bi-arrow-repeat'></i></button><br/>";
					$respuesta = $respuesta."<button type='button'
					class='btn-sm btn-danger w-50 small-width mt-1' 
					onclick='pone(this,1)'
					title='Eliminar cliente'
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

	// Método privado para verificar si un número de documento ya existe
	private function existe($numero_documento){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try{
			$resultado = $co->query("Select * from clientes where numero_documento='$numero_documento'");
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