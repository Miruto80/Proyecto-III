<?php  
session_start();
$nombre = isset($_SESSION["nombre"]) && !empty($_SESSION["nombre"]) ? $_SESSION["nombre"] : "Estimado Cliente";
$apellido = isset($_SESSION["apellido"]) && !empty($_SESSION["apellido"]) ? $_SESSION["apellido"] : ""; 

$nombreCompleto = trim($nombre . " " . $apellido);

$sesion_activa = isset($_SESSION["id"]) && !empty($_SESSION["id"]);


if (empty($_SESSION['id'])) {
    header('Location:?pagina=login');
    exit;
}

require_once __DIR__ . '/../modelo/verpedidoweb.php';
$venta = new VentaWeb();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['continuar_pago'])) {
    header('Content-Type: application/json');

    // Asegurar existencia de entrega y carrito
    if (empty($_SESSION['pedido_entrega']) || empty($_SESSION['carrito'])) {
        echo json_encode(['success'=>false,'message'=>'Falta información de envío o carrito vacío.']);
        exit;
    }

    // Construir payload para procesarPedido
    $datos = [
        'operacion' => 'registrar_pedido',
        'datos' => [
            // datos básicos
            'id_persona'       => $_SESSION['id'],
            'tipo'              => '2',
            'fecha'             => $_POST['fecha'] ?? date('Y-m-d h:i A'),
            'estado'            => '1',
            // totales
            'precio_total_usd'  => $_POST['precio_total_usd'],
            'precio_total_bs'   => $_POST['precio_total_bs'],
            // entrega
            'id_metodoentrega'  => $_POST['id_metodoentrega'],
            'direccion_envio'   => $_POST['direccion_envio'] ?? '',
            'sucursal_envio'    => $_POST['sucursal_envio'] ?? '',
            // pago
            'id_metodopago'       => $_POST['id_metodopago'] ?? '',
            'referencia_bancaria' => $_POST['referencia_bancaria'] ?? '',
            'telefono_emisor'     => $_POST['telefono_emisor'] ?? '',
            'banco_destino'       => $_POST['banco_destino'] ?? '',
            'banco'               => $_POST['banco'] ?? '',
            'monto'               => $_POST['precio_total_bs'],
            'monto_usd'           => $_POST['precio_total_usd'],
            'imagen'              => '' // se setea abajo
        ]
    ];

    // Manejo de imagen
    if (!empty($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
        $name = uniqid('img_').".$ext";
        $dest = __DIR__ . '/../assets/img/captures/' . $name;
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $dest)) {
            $datos['datos']['imagen'] = 'assets/img/captures/'.$name;
        }
    }

    // Carrito
    $datos['datos']['carrito'] = $_SESSION['carrito'];

    // Procesar
    $res = $venta->procesarPedido(json_encode($datos));

    if ($res['success']) {
        // Limpiar sesión
        unset($_SESSION['carrito'], $_SESSION['pedido_entrega']);
        echo json_encode([
            'success'  => true,
            'message'  => 'Su pedido ha sido registrado.',
            'redirect' => '?pagina=confirmacion&id='.$res['id_pedido']
        ]);
    } else {
        echo json_encode(['success'=>false,'message'=>$res['message']]);
    }
    exit;
}

// Si no es POST AJAX, redirigir al carrito
require_once __DIR__ . '/../vista/tienda/Pedidopago.php';

?>