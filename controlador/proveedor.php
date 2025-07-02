<?php
session_start();
if(empty($_SESSION['id'])){ header('Location:?pagina=login'); exit; }
require_once 'modelo/proveedor.php';
$obj = new proveedor();

// AJAX JSON
if($_SERVER['REQUEST_METHOD']==='POST' && !isset($_POST['generar'])){
  header('Content-Type: application/json');
  // consultar para editar
  if(isset($_POST['consultar_proveedor'])){
    echo json_encode($obj->consultarPorId((int)$_POST['id_proveedor']));
    exit;
  }
  // registrar
  if(isset($_POST['registrar'])){
    $d = [
      'numero_documento'=>$_POST['numero_documento'],
      'tipo_documento'=>$_POST['tipo_documento'],
      'nombre'=>ucfirst(strtolower($_POST['nombre'])),
      'correo'=>$_POST['correo'],
      'telefono'=>$_POST['telefono'],
      'direccion'=>$_POST['direccion']
    ];
    $res = $obj->procesarProveedor(json_encode(['operacion'=>'registrar','datos'=>$d]));
    if($res['respuesta']==1){
      $bit=[ 'id_persona'=>$_SESSION['id'],'accion'=>'Incluir Proveedor',
             'descripcion'=>"Se registró proveedor {$d['nombre']}" ];
      $obj->registrarBitacora(json_encode($bit));
    }
    echo json_encode($res); exit;
  }
  // actualizar
  if(isset($_POST['actualizar'])){
    $d = [
      'id_proveedor'=>$_POST['id_proveedor'],
      'numero_documento'=>$_POST['numero_documento'],
      'tipo_documento'=>$_POST['tipo_documento'],
      'nombre'=>ucfirst(strtolower($_POST['nombre'])),
      'correo'=>$_POST['correo'],
      'telefono'=>$_POST['telefono'],
      'direccion'=>$_POST['direccion']
    ];
    $res = $obj->procesarProveedor(json_encode(['operacion'=>'actualizar','datos'=>$d]));
    if($res['respuesta']==1){
      $bit=[ 'id_persona'=>$_SESSION['id'],'accion'=>'Actualizar Proveedor',
             'descripcion'=>"Se modificó proveedor {$d['nombre']}" ];
      $obj->registrarBitacora(json_encode($bit));
    }
    echo json_encode($res); exit;
  }
  // eliminar
  if(isset($_POST['eliminar'])){
    $d=['id_proveedor'=>$_POST['id_proveedor']];
    $res=$obj->procesarProveedor(json_encode(['operacion'=>'eliminar','datos'=>$d]));
    if($res['respuesta']==1){
      $bit=[ 'id_persona'=>$_SESSION['id'],'accion'=>'Eliminar Proveedor',
             'descripcion'=>"Se eliminó proveedor ID {$d['id_proveedor']}" ];
      $obj->registrarBitacora(json_encode($bit));
    }
    echo json_encode($res); exit;
  }
}

// PDF
if(isset($_POST['generar'])){
  $obj->generarPDF();
  exit;
} else if ($_SESSION["nivel_rol"] == 3) {
    
    $bitacora = [
        'id_persona' => $_SESSION["id"],
        'accion' => 'Acceso a Módulo',
        'descripcion' => 'módulo de Proveedor'
    ];
    $objusuario->registrarBitacora(json_encode($bitacora));

    $registro = $obj->consultar();
    require_once 'vista/proveedor.php';

} else if ($_SESSION["nivel_rol"] == 1) {

    header("Location: ?pagina=catalogo");
    exit();

} else {
    require_once 'vista/seguridad/privilegio.php';
}
