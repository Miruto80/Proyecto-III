<?php  
     session_start();
     if (empty($_SESSION["id"])){
       header("location:?pagina=login");
     } /*  Validacion URL  */
    
    
    require_once 'modelo/categoria.php';

     $objcategoria = new Categoria(); 

        $registro = $objcategoria->consultar();


        
        
if(isset($_POST['registrar'])){
   if( !empty($_POST['nombre'])){ 

       $objcategoria->set_Nombre($_POST['nombre']);
   
       $result=$objcategoria->registrar();
       
       echo json_encode($result);
       
   }
} elseif (isset($_POST['modificar'])) {
    if (!empty($_POST['id_categoria']) && !empty($_POST['nombre'])) {
        $objcategoria->set_Id_categoria($_POST['id_categoria']);
        $objcategoria->set_Nombre($_POST['nombre']);
        $result = $objcategoria->modificar();
        echo json_encode($result);
    }
} elseif (isset($_POST['eliminar'])) {
    if (!empty($_POST['id_categoria'])) {
        $objcategoria->set_Id_categoria($_POST['id_categoria']);
        $result = $objcategoria->eliminar();
        echo json_encode($result);
    }
}   
else if($_SESSION["nivel_rol"] == 3) { // Validacion si es administrador entra
    $id_persona = $_SESSION["id"];
    $accion = 'Acceso a Módulo';
    $descripcion = 'módulo de Categoria';
    $objcategoria->registrarBitacora($id_persona, $accion, $descripcion);
    require_once 'vista/categoria.php';
}else{
    require_once 'vista/seguridad/privilegio.php';
}

?>