<?php
session_start();
require_once __DIR__ . '/../modelo/reserva_cliente.php';

if (empty($_SESSION['id'])) {
  header("Location:?pagina=login");
  exit;
}

$reserva = new ReservaCliente();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  header('Content-Type: application/json');

  if (empty($_SESSION['carrito'])) {
    echo json_encode(['success'=>false,'message'=>'El carrito está vacío.']);
    exit;
  }

  $datos = [
    'operacion' => 'registrar_reserva',
    'datos' => [
      'id_persona'          => $_SESSION['id'],
      'tipo'                => '3',
      'estado'              => '1',
      'precio_total_usd'    => $_POST['precio_total_usd'] ?? '0',
      'precio_total_bs'     => $_POST['precio_total_bs'] ?? '0',
      'id_metodopago'       => $_POST['id_metodopago'] ?? '1',
      'referencia_bancaria' => $_POST['referencia_bancaria'] ?? '',
      'telefono_emisor'     => $_POST['telefono_emisor'] ?? '',
      'banco'               => $_POST['banco'] ?? '',
      'banco_destino'       => $_POST['banco_destino'] ?? '',
      'monto'               => $_POST['precio_total_bs'] ?? null,
      'monto_usd'           => $_POST['precio_total_usd'] ?? null,
      'imagen'              => '',
      'carrito'             => $_SESSION['carrito'],
    ]
  ];

  if (!empty($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
    $name = uniqid('img_') . ".$ext";
    $dest = __DIR__ . '/../assets/img/captures/' . $name;
    if (move_uploaded_file($_FILES['imagen']['tmp_name'], $dest)) {
      $datos['datos']['imagen'] = 'assets/img/captures/' . $name;
    }
  }

  $res = $reserva->procesarReserva(json_encode($datos));

  if ($res['success'] && isset($res['id_pedido'])) {
    unset($_SESSION['carrito']);
    echo json_encode([
      'success'  => true,
      'message'  => 'Reserva realizada en espera de verificación.',
      'redirect' => '?pagina=confirmacion&id=' . $res['id_pedido']
    ]);
  } else {
    echo json_encode(['success'=>false,'message'=>$res['message'] ?? 'Error al procesar reserva.']);
  }
  exit;
}

require_once __DIR__ . '/../vista/tienda/reserva_cliente.php';
