<?php
require_once('modelo/datos.php');

class ubicaciones extends datos {
	private $nombre; 
	private $descripcion; 
	private $imagen;

	// Método para establecer el nombre
	function set_nombre($valor) {
		$this->nombre = $valor;
	}	

	// Método para establecer la descripción
	function set_descripcion($valor) {
		$this->descripcion = $valor;
	}

	// Método para establecer la imagen
	function set_imagen($valor) {
		$this->imagen = $valor;
	}	

	// Método para obtener el nombre
	function get_nombre() {
		return $this->nombre;
	}	

	// Método para obtener la descripción
	function get_descripcion() {
		return $this->descripcion;
	}	

	// Método para obtener la imagen
	function get_imagen() {
		return $this->imagen;
	}

	// Método para incluir una nueva ubicación
	function incluir() {
		$r = array();
		if (!$this->existe($this->nombre)) { // Verifica si la ubicación ya existe
			$co = $this->conecta(); // Conecta a la base de datos
			$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			try {
				// Inserta la nueva ubicación en la base de datos
				$co->query("Insert into ubicaciones(nombre, descripcion, imagen)
					Values(
					'$this->nombre',
					'$this->descripcion',
					'$this->imagen')");
				$r['resultado'] = 'incluir';
				$r['mensaje'] =  '¡Registro guardado con éxito!';
			} catch(Exception $e) {
				$r['resultado'] = 'error';
				$r['mensaje'] =  $e->getMessage(); // Captura errores
			}
		} else {
			$r['resultado'] = 'incluir';
			$r['mensaje'] =  'Ya existe el nombre de documento'; // Mensaje si ya existe
		}
		return $r; // Retorna el resultado
	}

	// Método para modificar una ubicación existente
	function modificar() {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		if ($this->existe($this->nombre)) { // Verifica si la ubicación existe
			try {
				// Obtiene la imagen actual de la ubicación
				$resultado = $co->query("SELECT imagen FROM ubicaciones WHERE nombre = '$this->nombre'");
				$fila = $resultado->fetch(PDO::FETCH_ASSOC);
				$imagen_actual = $fila['imagen'];
				// Actualiza la ubicación en la base de datos
				$co->query("UPDATE ubicaciones SET 
					descripcion = '$this->descripcion',
					imagen = '$this->imagen'
					WHERE
					nombre = '$this->nombre'
					");
				$r['resultado'] = 'modificar';
				$r['mensaje'] = '¡Registro actualizado con éxito!';
				// Elimina la imagen anterior si es necesario
				if ($imagen_actual && $imagen_actual != 'otros/img/ubicaciones/default.png') {
					if (file_exists($imagen_actual)) {
						unlink($imagen_actual);
					}
				}
			} catch(Exception $e) {
				$r['resultado'] = 'error';
				$r['mensaje'] = $e->getMessage(); // Captura errores
			}
		} else {
			$r['resultado'] = 'modificar';
			$r['mensaje'] = 'nombre de documento no registrado'; // Mensaje si no existe
		}
		return $r; // Retorna el resultado
	}

	// Método para eliminar una ubicación
	function eliminar() {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		if ($this->existe($this->nombre)) { // Verifica si la ubicación existe
			try {
				// Obtiene la imagen de la ubicación a eliminar
				$resultado = $co->query("SELECT imagen FROM ubicaciones WHERE nombre = '$this->nombre'");
				$fila = $resultado->fetch(PDO::FETCH_ASSOC);
				$imagen = $fila['imagen'];
				// Elimina la ubicación de la base de datos
				$co->query("DELETE FROM ubicaciones WHERE nombre = '$this->nombre'");
				$r['resultado'] = 'eliminar';
				$r['mensaje'] = '¡Registro eliminado con éxito!';
				// Elimina la imagen si es necesario
				if ($imagen && $imagen != 'otros/img/ubicaciones/default.png') {
					if (file_exists($imagen)) {
						unlink($imagen);
					}
				}

			} catch (Exception $e) {
				$r['resultado'] = 'error';
				if ($e->getCode() == 23000) {
					$r['mensaje'] = 'No se puede eliminar esta ubicación porque tiene productos asociados';
				} else {
					$r['mensaje'] = $e->getMessage();
				}
			}

		} else {
			$r['resultado'] = 'eliminar';
			$r['mensaje'] = 'No existe el nombre de documento'; // Mensaje si no existe
		}
		return $r; // Retorna el resultado
	}

	// Método para consultar todas las ubicaciones
	function consultar() {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$r = array();
		try {
			$resultado = $co->query("Select * from ubicaciones  ORDER BY id DESC");
			if ($resultado) {
				$respuesta = '';		
				$n = 1; // Contador para las filas
				foreach ($resultado as $r) {
					$respuesta .= "<tr class='text-center'>"; // Crea una fila de tabla
					$respuesta .= "<td class='align-middle'>$n</td>"; // Número de fila
					$respuesta .= "<td class='align-middle'>".$r['nombre']."</td>"; // Nombre
					$respuesta .= "<td class='align-middle'>".$r['descripcion']."</td>"; // Descripción
					$respuesta .= "<td class='align-middle'><a href='".$r['imagen']."' target='_blank'><img src='".$r['imagen']."' alt='Imagen de la ubicación' class='img'/></a></td>"; // Imagen
					$respuesta .= "<td class='align-middle'>";
					$respuesta .= "<button type='button' class='btn-sm btn-primary w-50 small-width mb-1' onclick='pone(this,0)' title='Modificar ubicación'><i class='bi bi-arrow-repeat'></i></button><br/>";
					$respuesta .= "<button type='button' class='btn-sm btn-danger w-50 small-width mt-1' onclick='pone(this,1)' title='Eliminar ubicación'><i class='bi bi-trash'></i></button><br/>";
					$respuesta .= "</td>";
					$respuesta .= "</tr>";
					$n++; // Incrementa el contador
				}
				$r['resultado'] = 'consultar';
				$r['mensaje'] =  $respuesta; // Retorna la tabla de resultados
			} else {
				$r['resultado'] = 'consultar';
				$r['mensaje'] =  ''; // Sin resultados
			}	
		} catch(Exception $e) {
			$r['resultado'] = 'error';
			$r['mensaje'] =  $e->getMessage(); // Captura errores
		}
		return $r; // Retorna el resultado
	}	

	// Método privado para verificar si una ubicación existe
	private function existe($nombre) {
		$co = $this->conecta();
		$co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		try {
			$resultado = $co->query("Select * from ubicaciones where nombre='$nombre'");
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