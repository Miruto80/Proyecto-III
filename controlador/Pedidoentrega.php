<?php
session_start();

$nombre = isset($_SESSION["nombre"]) && !empty($_SESSION["nombre"]) ? $_SESSION["nombre"] : "Estimado Cliente";
$apellido = isset($_SESSION["apellido"]) && !empty($_SESSION["apellido"]) ? $_SESSION["apellido"] : ""; 

$nombreCompleto = trim($nombre . " " . $apellido);

$sesion_activa = isset($_SESSION["id"]) && !empty($_SESSION["id"]);
// Si no está logueado, redirige
require_once __DIR__ . '/../modelo/verpedidoweb.php';
$venta = new VentaWeb();

// 3) Si es AJAX de continuar_entrega, procesamos y devolvemos JSON
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['continuar_entrega'])) {
    header('Content-Type: application/json');

    $me = $_POST['metodo_entrega'] ?? null;
    if (!in_array($me, ['1','2','3'])) {
        echo json_encode(['success'=>false,'message'=>'Método de entrega inválido.']);
        exit;
    }

    // Construye array de entrega
    $entrega = ['id_metodoentrega' => $me];
    switch ($me) {
        case '1': // Tienda física
            $entrega['direccion_envio'] = $_POST['direccion_envio'] ?? '';
            $entrega['sucursal_envio']  = null;
            break;
        case '2': // MRW/ZOOM
            if (empty($_POST['empresa_envio']) || empty($_POST['sucursal_envio'])) {
                echo json_encode(['success'=>false,'message'=>'Complete empresa y sucursal.']);
                exit;
            }
            $entrega['empresa_envio']   = $_POST['empresa_envio'];
            $entrega['sucursal_envio']  = $_POST['sucursal_envio'];
            $entrega['direccion_envio'] = $_POST['direccion_envio'];
            break;
            case '3': // Delivery propio
                // Asegúrate de que lleguen todos los campos
                foreach (['zona','parroquia','sector','direccion_envio'] as $f) {
                    if (empty($_POST[$f])) {
                        echo json_encode(['success'=>false,'message'=>"Falta el campo $f."]);
                        exit;
                    }
                }
            
                // Los valores individuales
                $zona      = trim($_POST['zona']);
                $parroquia = trim($_POST['parroquia']);
                $sector    = trim($_POST['sector']);
                $dirDetall = trim($_POST['direccion_envio']);
            
                // Concatenamos en una sola dirección
                $entrega['direccion_envio'] = "Zona: {$zona}, Parroquia: {$parroquia}, Sector: {$sector}, Dirección: {$dirDetall}";
            
                // Para uniformidad, dejamos nulo el campo sucursal_envio
                $entrega['sucursal_envio'] = null;
                break;
    }

    // Guardar en sesión
    $_SESSION['pedido_entrega'] = $entrega;

    // Responder JSON
    echo json_encode([
        'success'  => true,
        'message'  => 'Datos de entrega guardados.',
        'redirect' => '?pagina=Pedidopago'
    ]);
    exit;
}

// 4) Si llegamos aquí, no es AJAX: preparamos la vista
$metodos_entrega = $venta->obtenerMetodosEntrega();

// Incluimos la vista. Dentro de ella tendrás disponible $metodos_entrega
require_once __DIR__ . '/../vista/tienda/Pedidoentrega.php';
