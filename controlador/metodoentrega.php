<?php  

session_start();
if (empty($_SESSION["id"])) {
    header("location:?pagina=login");
    exit;
}

require_once __DIR__ . '/../modelo/metodoentrega.php';

$objEntrega = new metodoentrega();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['registrar'])) {
        if (!empty($_POST['nombre']) && !empty($_POST['descripcion'])) {
            $datosPeticion = [
                'operacion' => 'incluir',
                'datos' => [
                    'nombre' => ucfirst(strtolower($_POST['nombre'])),
                    'descripcion' => $_POST['descripcion']
                ]
            ];

            $respuesta = $objEntrega->procesarMetodoEntrega(json_encode($datosPeticion));

            echo json_encode($respuesta);
        } else {
            echo json_encode(['respuesta' => 0, 'mensaje' => 'Faltan datos para registrar']);
        }

    } else if (isset($_POST['actualizar'])) {
        if (!empty($_POST['id_entrega']) && !empty($_POST['nombre']) && !empty($_POST['descripcion'])) {
            $datosPeticion = [
                'operacion' => 'modificar',
                'datos' => [
                    'id_entrega' => $_POST['id_entrega'],
                    'nombre' => ucfirst(strtolower($_POST['nombre'])),
                    'descripcion' => $_POST['descripcion']
                ]
            ];

            $respuesta = $objEntrega->procesarMetodoEntrega(json_encode($datosPeticion));

            echo json_encode($respuesta);
        } else {
            echo json_encode(['respuesta' => 0, 'mensaje' => 'Faltan datos para actualizar']);
        }

    } else if (isset($_POST['eliminar'])) {
        if (!empty($_POST['id_entrega'])) {
            $datosPeticion = [
                'operacion' => 'eliminar',
                'datos' => [
                    'id_entrega' => $_POST['id_entrega']
                ]
            ];

            $respuesta = $objEntrega->procesarMetodoEntrega(json_encode($datosPeticion));

            echo json_encode($respuesta);
        } else {
            echo json_encode(['respuesta' => 0, 'mensaje' => 'Falta ID para eliminar']);
        }
    }

    exit;
} else if($_SESSION["nivel_rol"] == 3) { // Validacion si es administrador entra
       /* $bitacora = [
            'id_persona' => $_SESSION["id"],
            'accion' => 'Acceso a Módulo',
            'descripcion' => 'módulo de Metodo Entrega'
        ];
        $objEntrega->registrarBitacora(json_encode($bitacora));*/
      
            $metodos = $objEntrega->consultar();
            require_once __DIR__ . '/../vista/metodoentrega.php';

        } else if ($_SESSION["nivel_rol"] == 1) {

            header("Location: ?pagina=catalogo");
            exit();

        } else {
        require_once 'vista/seguridad/privilegio.php';
    }


?>
