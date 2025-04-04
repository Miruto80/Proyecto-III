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
		$o = new proveedores();   // Crea una instancia de la clase proveedores
		$accion = $_POST['accion']; // Obtiene la acción a realizar

		// Acción para consultar datos
		if($accion=='consultar'){
			echo  json_encode($o->consultar());  
		}
		// Acción para eliminar un registro
		elseif($accion=='eliminar'){
			$o->set_numero_documento($_POST['numero_documento']); // Establece el número de documento
			echo  json_encode($o->eliminar()); // Elimina y devuelve el resultado en formato JSON
		}
		else{		  
			// Manejo de la imagen subida
			if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
				$imagen_nombre = uniqid() . '_' . $_FILES['imagen']['name']; // Genera un nombre único para la imagen
				$imagen_ruta = 'otros/img/proveedores/' . $imagen_nombre; // Define la ruta de la imagen
				move_uploaded_file($_FILES['imagen']['tmp_name'], $imagen_ruta); // Mueve la imagen a la ruta definida
				$o->set_imagen($imagen_ruta); // Establece la ruta de la imagen en el objeto
			} else {
				$o->set_imagen('otros/img/proveedores/default.png'); // Establece una imagen por defecto si no se subió ninguna
			}

			// Manejo del archivo del catálogo
			if (isset($_FILES['catalogo_archivo']) && $_FILES['catalogo_archivo']['error'] == 0) {
				$catalogo_nombre = uniqid() . '_' . $_FILES['catalogo_archivo']['name']; // Genera un nombre único para el archivo del catálogo
				$catalogo_ruta = 'otros/archivos/' . $catalogo_nombre; // Define la ruta del archivo del catálogo
				move_uploaded_file($_FILES['catalogo_archivo']['tmp_name'], $catalogo_ruta); // Mueve el archivo a la ruta definida
				$o->set_catalogo_archivo($catalogo_ruta); // Establece la ruta del archivo en el objeto
			} else if (isset($_POST['catalogo_archivo_actual']) && !empty($_POST['catalogo_archivo_actual'])) {
				$o->set_catalogo_archivo($_POST['catalogo_archivo_actual']); // Mantiene el archivo actual si no se subió uno nuevo
			} else {
				$o->set_catalogo_archivo(null); // Establece el catálogo como nulo si no hay archivo
			}

			// Establece otros atributos del objeto
			$o->set_tipo_documento($_POST['tipo_documento']);
			$o->set_numero_documento($_POST['numero_documento']);
			$o->set_nombre($_POST['nombre']);
			$o->set_direccion($_POST['direccion']);			  
			$o->set_correo($_POST['correo']);
			$o->set_telefono($_POST['telefono']);
			$o->set_catalogo($_POST['catalogo']);

			// Acciones para incluir o modificar registros
			if($accion=='incluir'){
				echo  json_encode($o->incluir()); // Incluye un nuevo registro y devuelve el resultado
			}
			elseif($accion=='modificar'){
				echo  json_encode($o->modificar()); // Modifica un registro existente y devuelve el resultado
			}
		}
		exit; // Termina la ejecución después de procesar la acción
	}	  
	require_once("vista/".$pagina.".php"); // Incluye la vista correspondiente
}
else{
	echo "ERROR 404"; // Mensaje de error si no se encuentra la vista
}
?>