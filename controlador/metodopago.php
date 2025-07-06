<?php  
session_start();
if (empty($_SESSION["id"])) {
    header("location:?pagina=login");
    exit;
}

require_once __DIR__ . '/../modelo/metodopago.php';
 require_once 'permiso.php';

$objMetodoPago = new MetodoPago();

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

            $respuesta = $objMetodoPago->procesarMetodoPago(json_encode($datosPeticion));
            echo json_encode($respuesta);
        } else {
            echo json_encode(['respuesta' => 0, 'mensaje' => 'Faltan datos para registrar']);
        }

    } else if (isset($_POST['modificar'])) {
        if (!empty($_POST['id_metodopago']) && !empty($_POST['nombre']) && !empty($_POST['descripcion'])) {
            $datosPeticion = [
                'operacion' => 'modificar',
                'datos' => [
                    'id_metodopago' => $_POST['id_metodopago'],
                    'nombre' => ucfirst(strtolower($_POST['nombre'])),
                    'descripcion' => $_POST['descripcion']
                ]
            ];

            $respuesta = $objMetodoPago->procesarMetodoPago(json_encode($datosPeticion));
            echo json_encode($respuesta);
        } else {
            echo json_encode(['respuesta' => 0, 'mensaje' => 'Faltan datos para actualizar']);
        }

    } else if (isset($_POST['eliminar'])) {
        if (!empty($_POST['id_metodopago'])) {
            $datosPeticion = [
                'operacion' => 'eliminar',
                'datos' => [
                    'id_metodopago' => $_POST['id_metodopago']
                ]
            ];

            $respuesta = $objMetodoPago->procesarMetodoPago(json_encode($datosPeticion));
            echo json_encode($respuesta);
        } else {
            echo json_encode(['respuesta' => 0, 'mensaje' => 'Falta ID para eliminar']);
        }
    }

    exit;
} else if ($_SESSION["nivel_rol"] == 3 && tieneAcceso(10, 'ver')) {
     /* $bitacora = [
            'id_persona' => $_SESSION["id"],
            'accion' => 'Acceso a Módulo',
            'descripcion' => 'módulo de Metodo Pago'
        ];
        $objMetodoPago->registrarBitacora(json_encode($bitacora));*/
       
        $metodos = $objMetodoPago->consultar();
        require_once __DIR__ . '/../vista/metodopago.php';
} else {
        require_once 'vista/seguridad/privilegio.php';

} if ($_SESSION["nivel_rol"] == 1) {
    header("Location: ?pagina=catalogo");
    exit();
}