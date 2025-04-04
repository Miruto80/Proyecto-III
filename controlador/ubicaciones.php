<?php
// Verifica si el archivo del modelo existe
if (!is_file("modelo/".$pagina.".php")){
	echo "Falta definir la clase ".$pagina; // Mensaje de error si no se encuentra el modelo
	exit;
}  
require_once("modelo/".$pagina.".php");  // Incluye el archivo del modelo

// Verifica si el archivo de vista existe
if(is_file("vista/".$pagina.".php")){	    
	if(!empty($_POST)){ // Comprueba si hay datos enviados por POST
		$o = new ubicaciones();   // Crea una nueva instancia de la clase ubicaciones
		$accion = $_POST['accion']; // Obtiene la acción a realizar

		// Acción para consultar datos
		if($accion=='consultar'){
			echo  json_encode($o->consultar());  // Devuelve los datos en formato JSON
		}
		// Acción para eliminar un registro
		elseif($accion=='eliminar'){
			$o->set_nombre($_POST['nombre']); // Establece el nombre del registro a eliminar
			echo  json_encode($o->eliminar()); // Devuelve el resultado de la eliminación en JSON
		}
		else{		  
			// Acciones para incluir o modificar un registro
			if($accion=='incluir' || $accion=='modificar'){
				$o->set_descripcion($_POST['descripcion']); // Establece la descripción del registro

				// Manejo de la imagen subida
				if(isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0){
					$imagen_nombre = uniqid() . '_' . $_FILES['imagen']['name']; // Genera un nombre único para la imagen
					$imagen_ruta = 'otros/img/ubicaciones/' . $imagen_nombre; // Define la ruta de la imagen
					move_uploaded_file($_FILES['imagen']['tmp_name'], $imagen_ruta); // Mueve la imagen a la ruta definida
					$o->set_imagen($imagen_ruta); // Establece la ruta de la imagen en el objeto
				} else {
					$o->set_imagen('otros/img/ubicaciones/default.png'); // Establece una imagen por defecto si no se sube ninguna
				}

				// Acción para incluir un nuevo registro
				if($accion == 'incluir'){
					$o->set_nombre($_POST['nombre']); // Establece el nombre del nuevo registro
					echo json_encode($o->incluir()); // Devuelve el resultado de la inclusión en JSON
				} 
				// Acción para modificar un registro existente
				elseif($accion == 'modificar'){
					$o->set_nombre($_POST['nombre']); // Establece el nombre del registro a modificar
					echo json_encode($o->modificar()); // Devuelve el resultado de la modificación en JSON
				}
			}
		}
		exit; // Termina la ejecución del script
	}	  
	require_once("vista/".$pagina.".php"); // Incluye el archivo de vista
}
else{
	echo "ERROR 404"; // Mensaje de error si no se encuentra la vista
}
?>