<?php
 session_start();
 if (empty($_SESSION["id"])){
   header("location:?pagina=login");
 } /*  Validacion URL  */
 
require_once 'modelo/tipousuario.php';

$objtipousuario = new Tipousuario();

// Si se desea obtener la lista de tipos de usuario al cargar la página o realizar alguna acción inicial
$registro = $objtipousuario->consultar(); // Aquí obtenemos todos los tipos de usuario

if (isset($_POST['registrar'])) {
    // Si se presiona el botón para registrar un nuevo tipo de usuario
    if (!empty($_POST['nombre']) && !empty($_POST['nivel'])) {
        $objtipousuario->set_Nombre($_POST['nombre']);
        $objtipousuario->set_Nivel($_POST['nivel']);
        $objtipousuario->set_Estatus(1); // Estatus activo por defecto
        $result = $objtipousuario->registrar();
        echo json_encode($result);
    }
} elseif (isset($_POST['modificar'])) {
    // Si se presiona el botón para modificar un tipo de usuario
    if (!empty($_POST['id_tipo']) && !empty($_POST['nombre']) && !empty($_POST['nivel'])) {
        $objtipousuario->set_Id_tipo($_POST['id_tipo']);
        $objtipousuario->set_Nombre($_POST['nombre']);
        $objtipousuario->set_Nivel($_POST['nivel']);
        $objtipousuario->set_Estatus($_POST['estatus']);
        $result = $objtipousuario->modificar();
        echo json_encode($result);
    }
} elseif (isset($_POST['eliminar'])) {
    // Si se presiona el botón para eliminar un tipo de usuario
    if (!empty($_POST['id_tipo'])) {
        $objtipousuario->set_Id_tipo($_POST['id_tipo']);
        $result = $objtipousuario->eliminar();
        echo json_encode($result);
    }
} else {
    // Si no hay una acción específica, se muestra la vista por defecto
    require_once 'vista/tipousuario.php';
}
?>
