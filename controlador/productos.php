<?php
// Verifica si el archivo del modelo existe
if (!is_file("modelo/".$pagina.".php")){
    echo "Falta definir la clase ".$pagina; // Mensaje de error si no se encuentra el modelo
    exit;
}  
require_once("modelo/".$pagina.".php");  // Incluye el archivo del modelo

// Verifica si el archivo de vista existe
if(is_file("vista/".$pagina.".php")){ 
    // Comprueba si hay datos enviados por POST
    if(!empty($_POST)){
        $o = new productos();   // Crea una nueva instancia de la clase productos
        $accion = $_POST['accion']; // Obtiene la acción a realizar

        // Acción para consultar productos
        if($accion=='consultar'){
            echo json_encode($o->consultar());  
        }
        // Acción para eliminar un producto
        elseif($accion=='eliminar'){
            $o->set_codigo($_POST['codigo']); // Establece el código del producto
            echo json_encode($o->eliminar()); // Elimina el producto y devuelve el resultado
        }
        else{		  
            // Acciones para incluir o modificar un producto
            if($accion=='incluir' || $accion=='modificar'){
                // Establece los atributos del producto
                $o->set_codigo($_POST['codigo']);
                $o->set_nombre($_POST['nombre']);
                $o->set_precio_compra($_POST['precio_compra']);
                $o->set_precio_venta($_POST['precio_venta']);
                $o->set_stock_total($_POST['stock_total']);
                $o->set_stock_minimo($_POST['stock_minimo']);
                $o->set_marca($_POST['marca']);
                $o->set_modelo($_POST['modelo']);
                $o->set_tipo_unidad($_POST['tipo_unidad']);       
                $o->set_categoria_id($_POST['categoria_id']);
                $o->set_ubicacion_id($_POST['ubicacion_id']);


                // Manejo de la imagen del producto
                if(isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0){
                    $imagen_nombre = uniqid() . '_' . $_FILES['imagen']['name']; // Genera un nombre único para la imagen
                    $imagen_ruta = 'otros/img/productos/' . $imagen_nombre; // Define la ruta de la imagen
                    move_uploaded_file($_FILES['imagen']['tmp_name'], $imagen_ruta); // Mueve la imagen a la ruta definida
                    $o->set_imagen($imagen_ruta); // Establece la ruta de la imagen en el objeto
                } else {
                    $o->set_imagen('otros/img/productos/default.png'); // Establece una imagen por defecto si no se sube ninguna
                }

                // Ejecuta la acción de incluir o modificar
                if($accion == 'incluir'){
                    echo json_encode($o->incluir()); // Incluye el producto y devuelve el resultado
                } elseif($accion == 'modificar'){
                    echo json_encode($o->modificar()); // Modifica el producto y devuelve el resultado
                }
            }
        }

        // Acción para obtener notificaciones de productos
        if($accion=='obtenerNotificaciones'){
            echo json_encode($o->obtenerProductosNotificacion());
        }        
        // Acción para cargar opciones de categorías, ubicaciones y proveedores
        elseif($accion=='cargarOpciones'){
            $categorias = $o->obtenerCategorias();
            $ubicaciones = $o->obtenerUbicaciones();
            echo json_encode([
                'categorias' => $categorias,
                'ubicaciones' => $ubicaciones
            ]);
        }
        exit; // Termina la ejecución del script
    }	  
    require_once("vista/".$pagina.".php"); // Incluye el archivo de vista
}
else{
    echo "ERROR 404"; // Mensaje de error si no se encuentra la vista
}
?>