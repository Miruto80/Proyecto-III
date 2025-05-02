<?php  
      session_start();
      if (empty($_SESSION["id"])){
        header("location:?pagina=login");
      } /*  Validacion URL  */
     
     require_once 'modelo/producto.php';

    $objproducto = new producto(); 

        $registro = $objproducto->consultar();
        $categoria = $objproducto->obtenerCategoria();  // Metodo para obtener la categoria

        
        
        if(isset($_POST['registrar'])){
            if(!empty($_POST['nombre'])){ 
         
                $objproducto->set_nombre($_POST['nombre']);
                $objproducto->set_descripcion($_POST['descripcion']);
                $objproducto->set_marca($_POST['marca']);
                $objproducto->set_cantidad_mayor($_POST['cantidad_mayor']);
                $objproducto->set_precio_mayor($_POST['precio_mayor']);
                $objproducto->set_precio_detal($_POST['precio_detal']);
                $objproducto->set_stock_disponible($_POST['stock_disponible']);
                $objproducto->set_stock_maximo($_POST['stock_maximo']);
                $objproducto->set_stock_minimo($_POST['stock_minimo']);
                $objproducto->set_Categoria($_POST['categoria']);
         
                if (isset($_FILES['imagenarchivo']) && $_FILES['imagenarchivo']['error'] == 0) {
                    $nombreArchivo = $_FILES['imagenarchivo']['name'];
                    $rutaTemporal = $_FILES['imagenarchivo']['tmp_name'];
                    $rutaDestino = 'assets/img/Imgproductos/' . $nombreArchivo;
                
                    move_uploaded_file($rutaTemporal, $rutaDestino);
                
                    $objproducto->set_imagen($rutaDestino); // Guarda la ruta en la base de datos
                } else {
                    $objproducto->set_imagen(''); // O puedes poner una imagen por defecto
                }
                
         
                $result = $objproducto->registrar();
                echo json_encode($result);
            }
}else{
    require_once 'vista/producto.php';
}


?>