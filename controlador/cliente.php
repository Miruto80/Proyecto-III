<?php  
session_start();
if (empty($_SESSION["id"])){
  header("location:?pagina=login");
} /*  Validacion URL  */
if (!empty($_SESSION['id'])) {
    require_once 'verificarsession.php';
} 

    if ($_SESSION["nivel_rol"] == 1) {
        header("Location: ?pagina=catalogo");
        exit();
    }/*  Validacion cliente  */

require_once 'modelo/cliente.php';
require_once 'modelo/bitacora.php';
require_once 'permiso.php';
$objcliente = new Cliente();


$registro = $objcliente->consultar();


if(isset($_POST['actualizar'])){
     $datosCliente = [
        'operacion' => 'actualizar',
        'datos' => [
            'id_persona' => $_POST['id_persona'],
            'cedula' => $_POST['cedula'],
            'correo' => strtolower($_POST['correo']),
            'estatus' => $_POST['estatus'],
            'cedula_actual' => $_POST['cedulaactual'],
            'correo_actual' => $_POST['correoactual']
        ]
    ]; 
  

    $resultado = $objcliente->procesarCliente(json_encode($datosCliente));
    
     if ($resultado['respuesta'] == 1) {
        $bitacora = [
            'id_persona' => $_SESSION["id"],
            'accion' => 'Modificación de cliente',
            'descripcion' => 'Se modificó el cliente con ID: ' . $datosCliente['datos']['id_persona'] . 
                           ' Cédula: ' . $datosCliente['datos']['cedula'] . 
                           ' Correo: ' . $datosCliente['datos']['correo']
        ];
        $bitacoraObj = new Bitacora();
        $bitacoraObj->registrarOperacion($bitacora['accion'], 'cliente', $bitacora);
    }

    echo json_encode($resultado);

      
    } else if ($_SESSION["nivel_rol"] == 3 && tieneAcceso(8, 'ver')) {
         $bitacora = [
            'id_persona' => $_SESSION["id"],
            'accion' => 'Acceso a Módulo',
            'descripcion' => 'módulo de Cliente'
        ];
        $bitacoraObj = new Bitacora();
        $bitacoraObj->registrarOperacion($bitacora['accion'], 'cliente', $bitacora);
        $pagina_actual = isset($_GET['pagina']) ? $_GET['pagina'] : 'cliente';
        require_once 'vista/cliente.php';
} else {
        require_once 'vista/seguridad/privilegio.php';

}

?>