<?php
require_once('modelo/datos.php');
class usuarios extends datos{    
// Propiedades de la clase
	private $cedula;
	private $nombre;
	private $apellido;
	private $correo;
	private $telefono;
	private $nombre_usuario; 
	private $tipo_usuario; 
	private $contraseña;
	private $imagen;    

	// Métodos para establecer los valores de las propiedades
	function set_cedula($valor){
		$this->cedula = $valor; 
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
	function set_nombre_usuario($valor){
		$this->nombre_usuario = $valor;
	}
	function set_tipo_usuario($valor){
		$this->tipo_usuario = $valor;
	}
	function set_contraseña($valor){
		$this->contraseña = $valor;
	}

	function set_imagen($valor){
		$this->imagen = $valor;
	}

	// Métodos para obtener los valores de las propiedades
	function get_cedula(){
		return $this->cedula;
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
	function get_nombre_usuario(){
		return $this->nombre_usuario;
	}
	function get_tipo_usuario(){
		return $this->tipo_usuario;
	}
	function get_contraseña(){
		return $this->contraseña;
	}

	function get_imagen(){
		return $this->imagen;
	}

	// Método para incluir un nuevo usuario en la base de datos
	function incluir(){
		$r = array();
		if(!$this->existe($this->cedula)){
			$co = $this->conecta();
			$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			try {
				$co->query("Insert into usuarios(cedula,nombre,apellido,correo,telefono,nombre_usuario,tipo_usuario,contraseña,imagen)
					Values(
					'$this->cedula',
					'$this->nombre',
					'$this->apellido',
					'$this->correo',
					'$this->telefono',
					'$this->nombre_usuario',
					'$this->tipo_usuario',
					'" . password_hash($this->contraseña, PASSWORD_DEFAULT) . "',
					'$this->imagen')");
				$r['resultado'] = 'incluir';
				$r['mensaje'] =  '¡Registro guardado con exito!';
			} catch(Exception $e) {
				$r['resultado'] = 'error';
				$r['mensaje'] =  'Este nombre de usuario ya esta siendo utilizado, pruebe con otro...';
			}
		}
		else{
			$r['resultado'] = 'incluir';
			$r['mensaje'] =  'Ya existe el numero de documento';
		}
		return $r;
	}

	// Método para modificar un usuario existente
	function modificar(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		if($this->existe($this->cedula)){
			try {
				$resultado = $co->query("SELECT imagen FROM usuarios WHERE cedula = '$this->cedula'");
				$fila = $resultado->fetch(PDO::FETCH_ASSOC);
				$imagen_actual = $fila['imagen'];
				$contraseña_hash = password_hash($this->contraseña, PASSWORD_DEFAULT);
				$co->query("Update usuarios set 
					cedula = '$this->cedula',
					nombre = '$this->nombre',
					apellido = '$this->apellido',
					correo = '$this->correo',
					telefono = '$this->telefono',
					nombre_usuario = '$this->nombre_usuario',
					tipo_usuario = '$this->tipo_usuario',
					contraseña = '$contraseña_hash',
					imagen = '$this->imagen'
					where
					cedula= '$this->cedula'
					");
				$r['resultado'] = 'modificar';
				$r['mensaje'] =  '¡Registro modificado con éxito!';
				if ($imagen_actual && $imagen_actual != 'otros/img/usuarios/default.jpg') {
					if (file_exists($imagen_actual)) {
						unlink($imagen_actual);
					}
				}
			} catch(Exception $e) {
				$r['resultado'] = 'error';
				$r['mensaje'] =  'Este nombre de usuario ya esta siendo utilizado, pruebe con otro...';
			}
		}
		else{
			$r['resultado'] = 'modificar';
			$r['mensaje'] =  'numero de documento no registrado';
		}
		return $r;
	}

	// Método para eliminar un usuario
	function eliminar(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		if($this->existe($this->cedula)){
			try {
				$resultado = $co->query("SELECT imagen FROM usuarios WHERE cedula = '$this->cedula'");
				$fila = $resultado->fetch(PDO::FETCH_ASSOC);
				$imagen = $fila['imagen'];
				$co->query("delete from usuarios 
					where
					cedula = '$this->cedula'
					");
				$r['resultado'] = 'eliminar';
				$r['mensaje'] =  '¡Registro eliminado con exito!';
				if ($imagen && $imagen != 'otros/img/usuarios/default.jpg') {
					if (file_exists($imagen)) {
						unlink($imagen);
					}
				}
			} catch(Exception $e) {
				$r['resultado'] = 'error';
				$r['mensaje'] =  $e->getMessage();
			}
		}
		else{
			$r['resultado'] = 'eliminar';
			$r['mensaje'] =  'No existe el numero de documento';
		}
		return $r;
	}

	// Método para consultar todos los usuarios
	function consultar(){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try{
			$resultado = $co->query("Select * from usuarios  ORDER BY id DESC");
			if($resultado){
				$respuesta = '';
				$n=1;
				foreach($resultado as $r){
					$respuesta = $respuesta."<tr class='text-center'>";
					$respuesta = $respuesta."<td class='align-middle'>$n</td>";
					$respuesta = $respuesta."<td class='align-middle'>".$r['cedula']."</td>";
					$respuesta = $respuesta."<td class='align-middle'>".$r['nombre']."</td>";
					$respuesta = $respuesta."<td class='align-middle'>".$r['apellido']."</td>";
					$respuesta = $respuesta."<td class='align-middle'>".$r['correo']."</td>";
					$respuesta = $respuesta."<td class='align-middle'>".$r['telefono']."</td>";
					$respuesta = $respuesta."<td class='align-middle'>".$r['nombre_usuario']."</td>";
					$respuesta = $respuesta."<td class='align-middle'>".$r['tipo_usuario']."</td>";		
					$respuesta = $respuesta."<td class='align-middle'><a href='".$r['imagen']."' target='_blank'><img src='".$r['imagen']."' alt='Imagen del usuario' class='img'/></a></td>";		
					$respuesta = $respuesta."<td class='align-middle'>";
					$respuesta = $respuesta.
					"<button type='button' class='btn-sm btn-primary w-50 small-width mb-1' onclick='pone(this,0)' title='Modificar usuario'><i class='bi bi-arrow-repeat'></i></button><br/>";
					$respuesta = $respuesta.
					"<button type='button' class='btn-sm btn-danger w-50 small-width mt-1' onclick='pone (this,1)' title='Eliminar usuario'><i class='bi bi-trash'></i></button><br/>";
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

	// Método privado para verificar si un usuario existe
	private function existe($cedula){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try{
			$resultado = $co->query("Select * from usuarios where cedula='$cedula'");
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


	// Método para obtener datos de un usuario específico
	function obtenerDatosUsuario($id){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try{
			$resultado = $co->query("SELECT * FROM usuarios WHERE id = '$id'");
			return $resultado->fetch(PDO::FETCH_ASSOC);
		} catch(Exception $e){
			return false;
		}
	}


	// Método para modificar el perfil de un usuario
	function modificarPerfil($id){
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try {
			$query = "UPDATE usuarios SET 
			nombre = '$this->nombre',
			apellido = '$this->apellido',
			correo = '$this->correo',
			telefono = '$this->telefono',
			nombre_usuario = '$this->nombre_usuario'";
			
			if(!empty($this->contraseña)){
				$contraseña_hash = password_hash($this->contraseña, PASSWORD_DEFAULT);
				$query .= ", contraseña = '$contraseña_hash'";
			}
			
			if(!empty($this->imagen)){
				$query .= ", imagen = '$this->imagen'";
			}
			
			$query .= " WHERE id = '$id'";
			
			$co->query($query);
			
			$r['resultado'] = 'modificar';
			$r['mensaje'] = '¡Perfil actualizado con éxito!';
		} catch(Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] = $e->getMessage();
		}
		return $r;
	}
}
?>