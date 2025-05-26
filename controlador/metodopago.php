<?php  
session_start();
if (empty($_SESSION["id"])) {
    header("location:?pagina=login");
}

require_once 'modelo/metodopago.php';

$objMetodoPago = new MetodoPago();

$registro = $objMetodoPago->consultar();

if (isset($_POST['registrar'])) {
    if (!empty($_POST['nombre']) && !empty($_POST['descripcion'])) {
        $objMetodoPago->set_Nombre($_POST['nombre']);
        $objMetodoPago->set_Descripcion($_POST['descripcion']);
        $result = $objMetodoPago->registrar();
        echo json_encode($result);
    }
} elseif (isset($_POST['modificar'])) {
    if (!empty($_POST['id_metodopago']) && !empty($_POST['nombre']) && !empty($_POST['descripcion'])) {
        $objMetodoPago->set_Id_metodopago($_POST['id_metodopago']);
        $objMetodoPago->set_Nombre($_POST['nombre']);
        $objMetodoPago->set_Descripcion($_POST['descripcion']);
        $result = $objMetodoPago->modificar();
        echo json_encode($result);
    }
} elseif (isset($_POST['eliminar'])) {
    if (!empty($_POST['id_metodopago'])) {
        $objMetodoPago->set_Id_metodopago($_POST['id_metodopago']);
        $result = $objMetodoPago->eliminar();
        echo json_encode($result);
    }
} else if($_SESSION["nivel_rol"] == 3) { // Validacion si es administrador entra
    $id_persona = $_SESSION["id"];
    $accion = 'Acceso a Módulo';
    $descripcion = 'módulo de Metodo Pago';
    $objMetodoPago->registrarBitacora($id_persona, $accion, $descripcion);
    require_once 'vista/metodopago.php';
}else{
    require_once 'vista/seguridad/privilegio.php';
}
?>
