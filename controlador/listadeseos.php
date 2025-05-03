<?php
session_start();

// Validar que el usuario esté autenticado
if (!isset($_SESSION['id_persona'])) {
    die('Usuario no autenticado');
}

require_once 'modelo/listadeseos.php';
require_once 'conexion.php';

$accion = $_GET['accion'] ?? '';
$id_persona = $_SESSION['id_persona'];

if ($accion === 'listar') {
    $lista = obtenerListaDeseos($id_persona, $conexion);
    echo json_encode($lista);
} elseif ($accion === 'eliminar' && isset($_POST['id_lista'])) {
    $resultado = eliminarDeListaDeseos($_POST['id_lista'], $conexion);
    echo json_encode(['success' => $resultado]);
} elseif ($accion === 'agregar_pedido' && isset($_POST['id_producto'])) {
    $resultado = agregarAPedido($id_persona, $_POST['id_producto'], $conexion);
    echo json_encode(['success' => $resultado]);
} else {
    require_once 'vista/listadeseos.php';
}
?>
