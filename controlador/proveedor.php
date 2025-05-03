<?php  
     session_start();
     if (empty($_SESSION["id"])){
       header("location:?pagina=login");
     } /*  Validacion URL  */
    
    require_once 'modelo/proveedor.php';

     $objproveedor = new Proveedor(); 

        $registro = $objproveedor->consultar();

if(isset($_POST['registrar'])){
   if( !empty($_POST['nombre'])){ 

       $objproveedor->set_Nombre($_POST['nombre']);
   
       $result=$objproveedor->registrar();
       echo json_encode($result);
       
   }
} elseif (isset($_POST['modificar'])) {
    if (!empty($_POST['id_proveedor']) && !empty($_POST['nombre'])) {
        $objproveedor->set_Id_proveedor($_POST['id_proveedor']);
        $objproveedor->set_Nombre($_POST['nombre']);
        $result = $objproveedor->modificar();
        echo json_encode($result);
    }
} elseif (isset($_POST['eliminar'])) {
    if (!empty($_POST['id_proveedor'])) {
        $objproveedor->set_Id_proveedor($_POST['id_proveedor']);
        $result = $objproveedor->eliminar();
        echo json_encode($result);
    }
}   
else{
    require_once 'vista/proveedor.php';
}

?>