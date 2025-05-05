<?php  
     session_start();
     if (empty($_SESSION["id"])){
       header("location:?pagina=login");
     } /*  Validacion URL  */
    
    
    require_once 'modelo/bitacora.php';

    $objbitacora = new Bitacora();
    
    $registro = $objbitacora->consultar();


    if (isset($_POST['entrar'])){
         // Verifica si se ha enviado la clave
        if (isset($_POST['clave']) && $_POST['clave'] === '1355') {
              require 'vista/seguridad/bitacora.php';
        } else {
           $_SESSION['message'] = array('title' => 'Clave Invalida', 'text' => 'Por favor, verifica tus datos y vuelve a intentarlo', 'icon' => 'error');
            header('Location: ?pagina=bitacora'); 
            exit;
        }
    
     }else if(isset($_POST['eliminar'])){
         $id_bitacora = $_POST['eliminar'];

         $objbitacora->set_Idbitacora($id_bitacora); 
         $result = $objbitacora->eliminar();
 
         echo json_encode($result);

  }else{
        require_once 'vista/bitacora.php';
     }




?>