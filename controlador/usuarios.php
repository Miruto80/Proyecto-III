<?php
// Verifica si el archivo del modelo existe
if (!is_file("modelo/".$pagina.".php")){
	echo "Falta definir la clase ".$pagina; // Mensaje de error si falta la clase
	exit;
}  
require_once("modelo/".$pagina.".php"); // Incluye el archivo del modelo

// Verifica si el usuario es un administrador
if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'administrador') {
	header("Location: ?pagina=principal"); // Redirige si no es administrador
	exit;
}

// Verifica si la vista existe
if(is_file("vista/".$pagina.".php")){  
	if(!empty($_POST)){ // Comprueba si se han enviado datos por POST
		$o = new usuarios(); // Crea una instancia de la clase usuarios
		$accion = $_POST['accion']; // Obtiene la acción a realizar

		// Acción para consultar usuarios
		if($accion=='consultar'){
			echo  json_encode($o->consultar());  
		}
		// Acción para eliminar un usuario
		elseif($accion=='eliminar'){
			$o->set_cedula($_POST['cedula']); // Establece la cédula del usuario a eliminar
			echo  json_encode($o->eliminar());
		}
		else{		  
			// Establece los datos del usuario
			$o->set_cedula($_POST['cedula']);
			$o->set_nombre($_POST['nombre']);
			$o->set_apellido($_POST['apellido']);
			$o->set_correo($_POST['correo']);
			$o->set_telefono($_POST['telefono']);
			$o->set_nombre_usuario($_POST['nombre_usuario']);
			$o->set_tipo_usuario($_POST['tipo_usuario']);
			
			// Establece la contraseña si no está vacía
			if (!empty($_POST['contraseña'])) {
				$o->set_contraseña($_POST['contraseña']);
			}
			
			// Manejo de la imagen del usuario
			if(isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0){
				$imagen_nombre = uniqid() . '_' . $_FILES['imagen']['name']; // Genera un nombre único para la imagen
				$imagen_ruta = 'otros/img/usuarios/' . $imagen_nombre; // Define la ruta de la imagen
				move_uploaded_file($_FILES['imagen']['tmp_name'], $imagen_ruta); // Mueve la imagen a la ruta definida
				$o->set_imagen($imagen_ruta); // Establece la ruta de la imagen en el objeto
			} else {
				$o->set_imagen('otros/img/usuarios/default.jpg'); // Establece una imagen por defecto si no se sube ninguna
			}
			
			// Acción para incluir un nuevo usuario
			if($accion=='incluir'){
				echo  json_encode($o->incluir());
			}
			// Acción para modificar un usuario existente
			elseif($accion=='modificar'){
				echo  json_encode($o->modificar());
			}
		}
		exit; // Finaliza la ejecución después de procesar la acción
	}	  
	require_once("vista/".$pagina.".php"); // Incluye la vista correspondiente
}
else{
	echo "ERROR 404"; // Mensaje de error si la vista no existe
}
?>