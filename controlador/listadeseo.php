<?php
session_start();

require_once __DIR__ . '/../modelo/ListaDeseo.php';

// Verificar sesión
$sesion_activa = isset($_SESSION["id"]) && !empty($_SESSION["id"]);
$id_persona = $_SESSION["id"] ?? null;

if (empty($_SESSION["id"])) {
    header('Location: ?pagina=catalogo');
    exit;
}

$id_persona = $_SESSION["id"];
$objListaDeseo = new ListaDeseo();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    $accion = $_POST['accion'] ?? '';

    switch ($accion) {
        case 'agregar':
            if (!empty($_POST['id_producto'])) {
                $datos = [
                    'operacion' => 'agregar',
                    'datos' => [
                        'id_persona' => $id_persona,
                        'id_producto' => $_POST['id_producto']
                    ]
                ];

                $resultado = $objListaDeseo->procesarListaDeseo(json_encode($datos));
                echo json_encode($resultado);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Producto no válido']);
            }
            break;

        case 'eliminar':
            if (!empty($_POST['id_lista'])) {
                $datos = [
                    'operacion' => 'eliminar',
                    'datos' => [
                        'id_lista' => $_POST['id_lista']
                    ]
                ];

                $resultado = $objListaDeseo->procesarListaDeseo(json_encode($datos));
                echo json_encode($resultado);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'ID de lista no válido']);
            }
            break;

        case 'vaciar':
            $datos = [
                'operacion' => 'vaciar',
                'datos' => [
                    'id_persona' => $id_persona
                ]
            ];

            $resultado = $objListaDeseo->procesarListaDeseo(json_encode($datos));
            echo json_encode($resultado);
            break;

        default:
            echo json_encode(['status' => 'error', 'message' => 'Acción no válida']);
    }

    exit;
}

// Si no es POST, mostrar la vista con la lista actual
$lista = $objListaDeseo->obtenerListaDeseo($id_persona);
require_once __DIR__ . '/../vista/tienda/listadeseo.php';
