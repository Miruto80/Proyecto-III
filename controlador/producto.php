<?php  
     require_once 'modelo/producto.php';

    $objproducto = new producto(); 

        $registro = $objproducto->consultar();

        
        
if(isset($_POST['registrar'])){
   if( !empty($_POST['nombre'])){ 

       $objproducto->set_nombre($_POST['nombre']);
       $objproducto->set_descripcion($_POST['descripcion']);
       $objproducto->set_marca($_POST['marca']);
       $objproducto->set_cantidad_mayor($_POST['cantidad_mayor']);
       $objproducto->set_precio_mayor($_POST['precio_mayor']);
       $objproducto->set_precio_detal($_POST['precio_detal']);
       $objproducto->set_stock_disponible($_POST['stock_disponible']);
       $objproducto->set_stock_maximo($_POST['stock_maximo']);
       $objproducto->set_stock_minimo($_POST['stock_minimo']);
       $objproducto->set_imagen($_POST['imagen']);
       
   
       $result=$objproducto->registrar();
       echo json_encode($result);
       
   }
}else{
    require_once 'vista/producto.php';
}


?>