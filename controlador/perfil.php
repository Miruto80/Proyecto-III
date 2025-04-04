<?php
// Verifica si el archivo del modelo existe
if (!is_file("modelo/".$pagina.".php")){
    echo "Falta definir la clase ".$pagina; // Mensaje de error si no se encuentra el modelo
    exit; // Termina la ejecución
}

// Incluye el archivo del modelo
require_once("modelo/".$pagina.".php");

// Verifica si el archivo de vista existe
if(is_file("vista/".$pagina.".php")){
    $o = new perfil(); // Crea una instancia de la clase perfil

    // Verifica si se ha enviado algún dato por POST
    if(!empty($_POST)){
        $accion = $_POST['accion']; // Obtiene la acción a realizar

        // Acción para cargar el perfil
        if($accion == 'cargar'){
            echo json_encode($o->cargarPerfil($_SESSION['usuario_id'])); // Devuelve el perfil en formato JSON
        }
        // Acción para modificar el perfil
        elseif($accion == 'modificar'){
            // Establece los nuevos valores del perfil
            $o->set_nombre($_POST['nombre']);
            $o->set_apellido($_POST['apellido']);
            $o->set_correo($_POST['correo']);
            $o->set_telefono($_POST['telefono']);
            $o->set_nombre_usuario($_POST['nombre_usuario']);

            // Si se proporciona una nueva contraseña, se establece
            if(!empty($_POST['contraseña'])){
                $o->set_contraseña($_POST['contraseña']);
            }

            // Manejo de la imagen de perfil
            if(isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0){
                $imagen_nombre = uniqid() . '_' . $_FILES['imagen']['name']; // Genera un nombre único para la imagen
                $imagen_ruta = 'otros/img/usuarios/' . $imagen_nombre; // Define la ruta donde se guardará la imagen
                move_uploaded_file($_FILES['imagen']['tmp_name'], $imagen_ruta); // Mueve la imagen a la ruta definida
                $o->set_imagen($imagen_ruta); // Establece la ruta de la imagen en el perfil
            }

            // Modifica el perfil y obtiene la respuesta
            $respuesta = $o->modificarPerfil($_SESSION['usuario_id']);
            if($respuesta['resultado'] === 'ok') {
                $_SESSION['imagen_usuario'] = $respuesta['nueva_imagen'];
                echo json_encode([
                    'resultado' => 'ok',
                    'mensaje' => $respuesta['mensaje'],
                    'nueva_imagen' => $respuesta['nueva_imagen']
                ]);
            } else {
                echo json_encode($respuesta);
            }
            exit; // Termina la ejecución
        }
        exit; // Termina la ejecución
    }

    // Incluye el archivo de vista
    require_once("vista/".$pagina.".php");
}
else{
    echo "ERROR 404"; // Mensaje de error si no se encuentra la vista
}
?>
