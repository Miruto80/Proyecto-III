<?php
session_start();
if (empty($_SESSION['id'])) {
    header('Location:?pagina=login');
    exit;
}

require_once 'modelo/categoria.php';
$Cat = new Categoria();

// 0) GET → acceso + bitácora
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $Cat->registrarBitacora(json_encode([
        'id_persona'  => $_SESSION['id'],
        'accion'      => 'Acceso a Categorías',
        'descripcion' => 'Usuario accedió al módulo Categoría'
    ]));
}

// 1) Registrar
if (isset($_POST['registrar'])) {
    $datos = ['nombre'=>$_POST['nombre']];
    $res   = $Cat->procesarCategoria(
        json_encode(['operacion'=>'incluir','datos'=>$datos])
    );
    if ($res['respuesta']==1) {
        $Cat->registrarBitacora(json_encode([
            'id_persona'=>$_SESSION['id'],
            'accion'    =>'Incluir Categoría',
            'descripcion'=>"Registró categoría “{$datos['nombre']}”"
        ]));
    }
    echo json_encode($res);
    exit;
}

// 2) Modificar
if (isset($_POST['modificar'])) {
    $datos = [
        'id_categoria'=>$_POST['id_categoria'],
        'nombre'      =>$_POST['nombre']
    ];
    $res = $Cat->procesarCategoria(
        json_encode(['operacion'=>'actualizar','datos'=>$datos])
    );
    if ($res['respuesta']==1) {
        $Cat->registrarBitacora(json_encode([
            'id_persona'=>$_SESSION['id'],
            'accion'    =>'Actualizar Categoría',
            'descripcion'=>"Actualizó categoría ID {$datos['id_categoria']} → “{$datos['nombre']}”"
        ]));
    }
    echo json_encode($res);
    exit;
}

// 3) Eliminar
if (isset($_POST['eliminar'])) {
    $id = (int) $_POST['id_categoria'];

    // obtener nombre antes de eliminar
    try {
        $db = $Cat->getConex1();
        $stmt = $db->prepare("SELECT nombre FROM categoria WHERE id_categoria=:id");
        $stmt->execute(['id'=>$id]);
        $nombre = $stmt->fetchColumn() ?: "ID $id";
        $db = null;
    } catch (PDOException $e) {
        $nombre = "ID $id";
    }

    $res = $Cat->procesarCategoria(
        json_encode(['operacion'=>'eliminar','datos'=>['id_categoria'=>$id]])
    );
    if ($res['respuesta']==1) {
        $Cat->registrarBitacora(json_encode([
            'id_persona'=>$_SESSION['id'],
            'accion'    =>'Eliminar Categoría',
            'descripcion'=>"Eliminó categoría “{$nombre}”"
        ]));
    }
    echo json_encode($res);
    exit;

} else if($_SESSION["nivel_rol"] == 3) { // Validacion si es administrador entra
        $bitacora = [
            'id_persona' => $_SESSION["id"],
            'accion' => 'Acceso a Módulo',
            'descripcion' => 'módulo de Categoria'
        ];
        $Cat->registrarBitacora(json_encode($bitacora));
        $categorias = $Cat->consultar();
        require_once 'vista/categoria.php';
        } else if ($_SESSION["nivel_rol"] == 1) {

            header("Location: ?pagina=catalogo");
            exit();

        } else {
        require_once 'vista/seguridad/privilegio.php';
    }
