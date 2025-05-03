<?php  
    require_once 'modelo/listadeseos.php';

    // Instanciar el objeto lista de deseos
    $objListaDeseo = new ListaDeseo(); 

    // Consultar todas las listas de deseos
    $registro = $objListaDeseo->consultar();

    // Acción para añadir a pedido
    if (isset($_POST['añadir_a_pedido'])) {
        // Verificar si se ha enviado el ID de la lista y la persona
        if (!empty($_POST['id_lista']) && !empty($_POST['id_persona'])) {
            $objListaDeseo->set_Id_lista($_POST['id_lista']);
            $objListaDeseo->set_Id_persona($_POST['id_persona']);
            
            // Añadir el artículo al pedido (esto se puede personalizar para añadir a la tabla de pedidos)
            $result = $objListaDeseo->añadirAPedido();
            echo json_encode($result);
        }
    }
    // Acción para eliminar de la lista de deseos
    elseif (isset($_POST['eliminar_lista'])) {
        // Verificar si se ha enviado el ID de la lista
        if (!empty($_POST['id_lista'])) {
            $objListaDeseo->set_Id_lista($_POST['id_lista']);
            
            // Eliminar la lista de deseos
            $result = $objListaDeseo->eliminar();
            echo json_encode($result);
        }
    } else {
        // Si no hay ninguna acción, mostrar la vista de lista de deseos
        require_once 'vista/listadeseos.php';
    }

?>
