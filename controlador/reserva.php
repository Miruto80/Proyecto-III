<?php  
session_start();
if (empty($_SESSION["id"])) {
    header("location:?pagina=login");
    exit;
}

require_once __DIR__ . '/../modelo/reserva.php';
require_once 'permiso.php';

$objReservas = new Reservas();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Confirmar / cambiar estado
    if (isset($_POST['confirmar']) && !empty($_POST['id_pedido'])) {
        $datosPeticion = [
            'operacion' => 'cambiar_estado',
            'datos' => [
                'id_pedido' => $_POST['id_pedido'],
                'estado' => 2 // 2 = Confirmado
            ]
        ];

        $respuesta = $objReservas->procesarReserva(json_encode($datosPeticion));
        echo json_encode($respuesta);
    
    // Eliminar reserva
    } elseif (isset($_POST['eliminar']) && !empty($_POST['id_pedido'])) {
        $datosPeticion = [
            'operacion' => 'eliminar',
            'datos' => $_POST['id_pedido']
        ];

        $respuesta = $objReservas->procesarReserva(json_encode($datosPeticion));
        echo json_encode($respuesta);

    } else {
        echo json_encode(['respuesta' => 0, 'mensaje' => 'Datos incompletos para procesar solicitud']);
    }

    exit;
}


// GET: Consultar reservas con detalles
$reservas = $objReservas->consultarReservasCompletas();

foreach ($reservas as &$reserva) {
    $reserva['detalles'] = $objReservas->consultarDetallesReserva($reserva['id_pedido']);
}

// Verificación de privilegios
if ($_SESSION["nivel_rol"] >= 2 && tieneAcceso(9, 'ver')) {
    require_once 'vista/reserva.php'; // Asegúrate de tener esta vista
} else {
    require_once 'vista/seguridad/privilegio.php';
}

// Redirección si es cliente
if ($_SESSION["nivel_rol"] == 1) {
    header("Location: ?pagina=catalogo");
    exit();
}
