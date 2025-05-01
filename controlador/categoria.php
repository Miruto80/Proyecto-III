<?php  
     require_once 'modelo/categoria.php';

     $objcategoria = new Categoria(); 

        $registro = $objcategoria->consultar();


        
        
if(isset($_POST['registrar'])){
   if( !empty($_POST['nombre'])){ 

       $objcategoria->set_Nombre($_POST['nombre']);
   
       $result=$objcategoria->registrar();
       echo json_encode($result);
       
   }
}else{
    require_once 'vista/categoria.php';
}

?>