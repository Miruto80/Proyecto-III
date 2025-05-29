<?php
session_start();

require_once __DIR__ . '/../modelo/ListaDeseo.php';

// Verificar sesión
$sesion_activa = isset($_SESSION["id"]) && !empty($_SESSION["id"]);
$id_persona = $_SESSION["id"] ?? null;

if (!$sesion_activa) {
    header('Location: ?pagina=catalogo');
    exit;
}

// Instanciar el modelo
$listaDeseo = new ListaDeseo();

$accion = $_POST['accion'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $accion) {
    header('Content-Type: application/json');

    switch ($accion) {
        case 'agregar':
            $id_producto = $_POST['id_producto'] ?? null;
            if (!$id_producto) {
                echo json_encode(['status' => 'error', 'message' => 'Producto no válido']);
                exit;
            }

            // Verificar si ya está en la lista
            if ($listaDeseo->estaEnLista($id_persona, $id_producto)) {
                echo json_encode(['status' => 'exists', 'message' => 'Ya está en la lista']);
                exit;
            }

            // Agregar producto
            if ($listaDeseo->agregarProductoLista($id_persona, $id_producto)) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error al agregar']);
            }
            exit;

        case 'eliminar':
            $id_lista = $_POST['id_lista'] ?? null;
            if ($id_lista && $listaDeseo->eliminarProductoLista($id_lista)) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No se pudo eliminar']);
            }
            exit;

        case 'vaciar':
            if ($listaDeseo->vaciarListaDeseo($id_persona)) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No se pudo vaciar la lista']);
            }
            exit;

        default:
            echo json_encode(['status' => 'error', 'message' => 'Acción no válida']);
            exit;
    }
}

// Si no es POST, cargamos la vista con la lista actual
$lista = $listaDeseo->obtenerListaDeseo($id_persona);
require_once __DIR__ . '/../vista/tienda/listadeseo.php';
