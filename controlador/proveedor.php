<?php

session_start();
if (empty($_SESSION['id'])) {
    header('Location:?pagina=login');
    exit;
}

require_once 'modelo/proveedor.php';
 require_once 'permiso.php';
$obj = new proveedor();

// Fijamos el rol en “Administrador”
$rolText = 'Administrador';

// 0) Registrar acceso al módulo (GET sin AJAX ni operaciones)
if ($_SERVER['REQUEST_METHOD'] === 'GET'
    && !isset($_POST['consultar_proveedor'])
) {
    $obj->registrarBitacora(json_encode([
        'id_persona'  => $_SESSION['id'],
        'accion'      => 'Acceso a Proveedores',
        'descripcion' => "$rolText accedió al módulo Proveedores"
    ]));
}

// 1) AJAX JSON: Consultar, Registrar, Actualizar, Eliminar
if ($_SERVER['REQUEST_METHOD'] === 'POST'
    && !isset($_POST['generar'])
) {
    header('Content-Type: application/json');

    // a) Consultar proveedor para edición
    if (isset($_POST['consultar_proveedor'])) {
        echo json_encode(
            $obj->consultarPorId((int)$_POST['id_proveedor'])
        );
        exit;
    }

    // b) Registrar nuevo proveedor
    if (isset($_POST['registrar'])) {
        $d = [
            'numero_documento' => $_POST['numero_documento'],
            'tipo_documento'   => $_POST['tipo_documento'],
            'nombre'           => ucfirst(strtolower($_POST['nombre'])),
            'correo'           => $_POST['correo'],
            'telefono'         => $_POST['telefono'],
            'direccion'        => $_POST['direccion']
        ];
        $res = $obj->procesarProveedor(
            json_encode(['operacion'=>'registrar','datos'=>$d])
        );
        if ($res['respuesta'] == 1) {
            $obj->registrarBitacora(json_encode([
                'id_persona'  => $_SESSION['id'],
                'accion'      => 'Incluir Proveedor',
                'descripcion' => "$rolText registró proveedor {$d['nombre']}"
            ]));
        }
        echo json_encode($res);
        exit;
    }

    // c) Actualizar proveedor existente
    if (isset($_POST['actualizar'])) {
        $d = [
            'id_proveedor'     => $_POST['id_proveedor'],
            'numero_documento' => $_POST['numero_documento'],
            'tipo_documento'   => $_POST['tipo_documento'],
            'nombre'           => ucfirst(strtolower($_POST['nombre'])),
            'correo'           => $_POST['correo'],
            'telefono'         => $_POST['telefono'],
            'direccion'        => $_POST['direccion']
        ];
        // Obtener nombre actual para bitácora
        $old = $obj->consultarPorId((int)$d['id_proveedor']);
        $res = $obj->procesarProveedor(
            json_encode(['operacion'=>'actualizar','datos'=>$d])
        );
        if ($res['respuesta'] == 1) {
            $obj->registrarBitacora(json_encode([
                'id_persona'  => $_SESSION['id'],
                'accion'      => 'Actualizar Proveedor',
                'descripcion' => "$rolText actualizó proveedor {$old['nombre']}"
            ]));
        }
        echo json_encode($res);
        exit;
    }

    // d) Eliminar (desactivar) proveedor
    if (isset($_POST['eliminar'])) {
        $id   = (int)$_POST['id_proveedor'];
        $prov = $obj->consultarPorId($id);
        $nombre = $prov['nombre'] ?? "ID $id";

        $res = $obj->procesarProveedor(
            json_encode(['operacion'=>'eliminar','datos'=>['id_proveedor'=>$id]])
        );
        if ($res['respuesta'] == 1) {
            $obj->registrarBitacora(json_encode([
                'id_persona'  => $_SESSION['id'],
                'accion'      => 'Eliminar Proveedor',
                'descripcion' => "$rolText eliminó proveedor $nombre"
            ]));
        }
        echo json_encode($res);
        exit;
    }
} else  if ($_SESSION["nivel_rol"] == 3 && tieneAcceso(6, 'ver')) {
        $bitacora = [
            'id_persona' => $_SESSION["id"],
            'accion' => 'Acceso a Módulo',
            'descripcion' => 'módulo de Proveedor'
        ];
        $obj->registrarBitacora(json_encode($bitacora));

       $registro = $obj->consultar();
        require_once 'vista/proveedor.php';
} else {
        require_once 'vista/seguridad/privilegio.php';

} if ($_SESSION["nivel_rol"] == 1) {
    header("Location: ?pagina=catalogo");
    exit();
}
   

// 2) Vista normal: consultar registros y renderizar

