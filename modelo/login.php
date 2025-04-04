<?php
require_once('modelo/usuarios.php');

// Clase 'login' que extiende de 'usuarios'
class login extends usuarios{

	private $nombre_usuario; // Almacena el nombre de usuario
	private $tipo_usuario; // Almacena el tipo de usuario
	private $contraseña; // Almacena la contraseña del usuario

	// Método para establecer el nombre de usuario
	function set_nombre_usuario($valor){
		$this->nombre_usuario = $valor;
	}

	// Método para establecer el tipo de usuario
	function set_tipo_usuario($valor){
		$this->tipo_usuario = $valor;
	}

	// Método para establecer la contraseña
	function set_contraseña($valor){
		$this->contraseña = $valor;
	}	

	// Método para obtener el nombre de usuario
	function get_nombre_usuario(){
		return $this->nombre_usuario;
	}

	// Método para obtener el tipo de usuario
	function get_tipo_usuario(){
		return $this->tipo_usuario;
	}

	// Método para obtener la contraseña
	function get_contraseña(){
		return $this->contraseña;
	}

	// Método para autenticar al usuario
	function autenticar($nombre_usuario, $contraseña){
		$co = $this->conecta(); // Conectar a la base de datos
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Configurar el modo de error
		$r = array(); // Array para almacenar el resultado

		try{
			// Preparar la consulta para buscar el usuario
			$stmt = $co->prepare("SELECT * FROM usuarios WHERE nombre_usuario = :nombre_usuario");
			$stmt->bindParam(':nombre_usuario', $nombre_usuario); // Vincular el parámetro
			$stmt->execute(); // Ejecutar la consulta
			$usuario = $stmt->fetch(PDO::FETCH_ASSOC); // Obtener el usuario

			// Verificar si el usuario existe y si la contraseña es correcta
			if($usuario && password_verify($contraseña, $usuario['contraseña'])){
				// Almacenar información del usuario en la sesión
				$_SESSION['usuario_id'] = $usuario['id'];
				$_SESSION['nombre_usuario'] = $usuario['nombre_usuario'];
				$_SESSION['tipo_usuario'] = $usuario['tipo_usuario'];
				$_SESSION['imagen_usuario'] = $usuario['imagen'];
				$r['resultado'] = 'login_success'; // Resultado exitoso
				$r['mensaje'] = 'Inicio de sesión exitoso'; // Mensaje de éxito
			} else {
				$r['resultado'] = 'login_error'; // Resultado de error
				$r['mensaje'] = 'Nombre de usuario o contraseña incorrectos'; // Mensaje de error
			}
		} catch(Exception $e){
			$r['resultado'] = 'error'; // Resultado de error
			$r['mensaje'] = $e->getMessage(); // Mensaje de excepción
		}
		return $r; // Retornar el resultado
	}
}
?>