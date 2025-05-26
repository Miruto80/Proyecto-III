<?php  
session_start();
if (empty($_SESSION["id"])) {
    header("location:?pagina=login");
} // Validación de sesión

require_once 'modelo/metodoentrega.php';

$objEntrega = new metodoentrega();

// Consultar registros para usarlos en la vista
$registro = $objEntrega->consultar();

// Registrar
if (isset($_POST['registrar'])) {
    if (!empty($_POST['nombre']) && !empty($_POST['descripcion'])) {
        $objEntrega->set_Nombre($_POST['nombre']);
        $objEntrega->set_Descripcion($_POST['descripcion']);
        $result = $objEntrega->registrar();
        echo json_encode($result);
    }
} 
// Modificar
elseif (isset($_POST['modificar'])) {
    if (!empty($_POST['id_entrega']) && !empty($_POST['nombre']) && !empty($_POST['descripcion'])) {
        $objEntrega->set_Id_entrega($_POST['id_entrega']);
        $objEntrega->set_Nombre($_POST['nombre']);
        $objEntrega->set_Descripcion($_POST['descripcion']);
        $result = $objEntrega->modificar();
        echo json_encode($result);
    }
} 
// Eliminar
elseif (isset($_POST['eliminar'])) {
    if (!empty($_POST['id_entrega'])) {
        $objEntrega->set_Id_entrega($_POST['id_entrega']);
        $result = $objEntrega->eliminar();
        echo json_encode($result);
    }
} 
// Mostrar vista si no hay POST
else if($_SESSION["nivel_rol"] == 3) { // Validacion si es administrador entra
    $id_persona = $_SESSION["id"];
    $accion = 'Acceso a Módulo';
    $descripcion = 'módulo de Metodo Entrega';
    $objEntrega->registrarBitacora($id_persona, $accion, $descripcion);
    require_once 'vista/metodoentrega.php';
}else{
    require_once 'vista/seguridad/privilegio.php';
}
?>