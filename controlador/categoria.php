<?php
session_start();
if (empty($_SESSION['id'])) {
    header('Location:?pagina=login');
    exit;
}

require_once 'modelo/categoria.php';
$Cat = new Categoria();

// ————————————————————————————
// Abrir conexiones explícitas
// ————————————————————————————
$db1 = $Cat->getConex1();
$db2 = $Cat->getConex2();

// 0) Registrar acceso (GET)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $Cat->registrarBitacora(json_encode([
        'id_persona'  => $_SESSION['id'],
        'accion'      => 'Acceso a Categorías',
        'descripcion' => 'Usuario accedió al módulo Categoría'
    ]));
}

// 1) Registrar nueva categoría
if (isset($_POST['registrar'])) {
    $datos = ['nombre' => $_POST['nombre']];
    $res   = $Cat->procesarCategoria(
        json_encode(['operacion'=>'incluir','datos'=>$datos])
    );
    if ($res['respuesta'] == 1) {
        $Cat->registrarBitacora(json_encode([
            'id_persona'  => $_SESSION['id'],
            'accion'      => 'Incluir Categoría',
            'descripcion' => "Registró categoría “{$datos['nombre']}”"
        ]));
    }
    echo json_encode($res);
    exit;
}

// 2) Modificar categoría
if (isset($_POST['modificar'])) {
    $datos = [
        'id_categoria' => $_POST['id_categoria'],
        'nombre'       => $_POST['nombre']
    ];
    $res = $Cat->procesarCategoria(
        json_encode(['operacion'=>'actualizar','datos'=>$datos])
    );
    if ($res['respuesta'] == 1) {
        $Cat->registrarBitacora(json_encode([
            'id_persona'  => $_SESSION['id'],
            'accion'      => 'Actualizar Categoría',
            'descripcion' => "Actualizó categoría ID {$datos['id_categoria']} → “{$datos['nombre']}”"
        ]));
    }
    echo json_encode($res);
    exit;
}

// 3) Eliminar categoría (estatus = 0)
if (isset($_POST['eliminar'])) {
    $id = (int) $_POST['id_categoria'];

    // obtener nombre antes de eliminar
    try {
        $stmt = $db1->prepare("SELECT nombre FROM categoria WHERE id_categoria = :id");
        $stmt->execute(['id' => $id]);
        $nombre = $stmt->fetchColumn() ?: "ID $id";
    } catch (PDOException $e) {
        $nombre = "ID $id";
    }

    $res = $Cat->procesarCategoria(
        json_encode(['operacion'=>'eliminar','datos'=>['id_categoria'=>$id]])
    );
    if ($res['respuesta'] == 1) {
        $Cat->registrarBitacora(json_encode([
            'id_persona'  => $_SESSION['id'],
            'accion'      => 'Eliminar Categoría',
            'descripcion' => "Eliminó categoría “{$nombre}”"
        ]));
    }

    echo json_encode($res);
    exit;
}

// 4) Vista normal
$categorias = $Cat->consultar();

// ————————————————————————————
// Cerrar conexiones explícitas
// ————————————————————————————
$db1 = null;
$db2 = null;

require_once 'vista/categoria.php';
