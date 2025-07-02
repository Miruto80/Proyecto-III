<?php
// controlador/tipousuario.php

session_start();
if (empty($_SESSION['id'])) {
    header('Location:?pagina=login');
    exit;
}

require_once 'modelo/tipousuario.php';
$obj = new tipousuario();

// 0) Bitácora de acceso al módulo (GET)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $bit = [
        'id_persona' => $_SESSION['id'],
        'accion'     => 'Acceso a módulo',
        'descripcion'=> 'Ingreso al módulo Tipo Usuario'
    ];
    $obj->registrarBitacora(json_encode($bit));
}

// 1) CRUD JSON‐driven
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    // —— Registrar nuevo rol ——  
    if (isset($_POST['registrar'])) {
        $nombre = trim($_POST['nombre'] ?? '');
        $nivel  = (int)($_POST['nivel'] ?? 0);

        $payload = [
            'operacion'=>'registrar',
            'datos'    => ['nombre'=>$nombre,'nivel'=>$nivel]
        ];
        $res = $obj->procesarTipousuario(json_encode($payload));

        if ($res['respuesta'] == 1) {
            $bit = [
                'id_persona' => $_SESSION['id'],
                'accion'     => 'Registrar rol',
                'descripcion'=> sprintf(
                    'Registró rol "%s" con nivel %d',
                    $nombre, $nivel
                )
            ];
            $obj->registrarBitacora(json_encode($bit));
        }

        echo json_encode($res);
        exit;
    }

    // —— Modificar rol ——  
    if (isset($_POST['modificar'])) {
        $idTipo = (int)($_POST['id_tipo'] ?? 0);
        $nombre = trim($_POST['nombre'] ?? '');
        $nivel  = (int)($_POST['nivel'] ?? 0);
        $estatus= (int)($_POST['estatus'] ?? 1);

        $payload = [
            'operacion'=>'actualizar',
            'datos'=>[
                'id_tipo'=>$idTipo,
                'nombre' =>$nombre,
                'nivel'  =>$nivel,
                'estatus'=>$estatus
            ]
        ];
        $res = $obj->procesarTipousuario(json_encode($payload));

        if ($res['respuesta'] == 1) {
            $estatusText = $estatus == 1 ? 'Activo' : 'Inactivo';
            $bit = [
                'id_persona' => $_SESSION['id'],
                'accion'     => 'Modificar rol',
                'descripcion'=> sprintf(
                    'Modificó rol "%s": nivel %d, estatus %s',
                    $nombre, $nivel, $estatusText
                )
            ];
            $obj->registrarBitacora(json_encode($bit));
        }

        echo json_encode($res);
        exit;
    }

    // —— Eliminar (desactivar) rol ——  
    if (isset($_POST['eliminar'])) {
        $idTipo = (int)($_POST['id_tipo'] ?? 0);

        // Obtiene nombre del rol
        $todos   = $obj->consultar();
        $rolNom  = 'ID '.$idTipo;
        foreach ($todos as $r) {
            if ((int)$r['id_rol'] === $idTipo) {
                $rolNom = $r['nombre'];
                break;
            }
        }

        $payload = [
            'operacion'=>'eliminar',
            'datos'=>['id_tipo'=>$idTipo]
        ];
        $res = $obj->procesarTipousuario(json_encode($payload));

        if ($res['respuesta'] == 1) {
            $bit = [
                'id_persona' => $_SESSION['id'],
                'accion'     => 'Eliminar rol',
                'descripcion'=> sprintf(
                    'Eliminó rol "%s"',
                    $rolNom
                )
            ];
            $obj->registrarBitacora(json_encode($bit));
        }

        echo json_encode($res);
        exit;
    }
}

// 2) Vista normal
$registro = $obj->consultar();
require_once 'vista/tipousuario.php';
