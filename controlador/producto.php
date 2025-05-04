<?php
session_start();
if (empty($_SESSION["id"])) {
    header("location:?pagina=login");
    exit;
}

require_once 'modelo/producto.php';

$objproducto = new producto();

$registro = $objproducto->consultar();
$categoria = $objproducto->obtenerCategoria();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = isset($_POST['accion']) ? $_POST['accion'] : null;

    // Para eliminar puedes seguir pendiente de tu variable 'eliminar' si quieres
    if ($accion === 'registrar') {
        if (!empty($_POST['nombre'])) {
            $objproducto->set_nombre($_POST['nombre']);
            $objproducto->set_descripcion($_POST['descripcion']);
            $objproducto->set_marca($_POST['marca']);
            $objproducto->set_cantidad_mayor($_POST['cantidad_mayor']);
            $objproducto->set_precio_mayor($_POST['precio_mayor']);
            $objproducto->set_precio_detal($_POST['precio_detal']);
            $objproducto->set_stock_disponible($_POST['stock_disponible']);
            $objproducto->set_stock_maximo($_POST['stock_maximo']);
            $objproducto->set_stock_minimo($_POST['stock_minimo']);
            $objproducto->set_Categoria($_POST['categoria']);

            if (isset($_FILES['imagenarchivo']) && $_FILES['imagenarchivo']['error'] == 0) {
                $nombreArchivo = $_FILES['imagenarchivo']['name'];
                $rutaTemporal = $_FILES['imagenarchivo']['tmp_name'];
                $rutaDestino = 'assets/img/Imgproductos/' . $nombreArchivo;

                move_uploaded_file($rutaTemporal, $rutaDestino);

                $objproducto->set_imagen($rutaDestino);
            } else {
                $objproducto->set_imagen('assets/img/logo.PNG');
            }

            $result = $objproducto->registrar();
            echo json_encode($result);
            exit;
        }
    } elseif ($accion === 'modificar') {
        if (!empty($_POST['id_producto']) && !empty($_POST['nombre'])) {
            $objproducto->set_id_producto($_POST['id_producto']);
            $objproducto->set_nombre($_POST['nombre']);
            $objproducto->set_descripcion($_POST['descripcion']);
            $objproducto->set_marca($_POST['marca']);
            $objproducto->set_cantidad_mayor($_POST['cantidad_mayor']);
            $objproducto->set_precio_mayor($_POST['precio_mayor']);
            $objproducto->set_precio_detal($_POST['precio_detal']);
            $objproducto->set_stock_disponible($_POST['stock_disponible']);
            $objproducto->set_stock_maximo($_POST['stock_maximo']);
            $objproducto->set_stock_minimo($_POST['stock_minimo']);
            if (isset($_POST['categoria']) && !empty($_POST['categoria'])) {

                $objproducto->set_Categoria($_POST['categoria']);

            } else {

                echo json_encode(['respuesta' => 0, 'accion' => 'actualizar', 'error' => 'Categoria no seleccionada']);

                exit;

            }

            if (isset($_FILES['imagenarchivo']) && $_FILES['imagenarchivo']['error'] == 0) {

                $nombreArchivo = $_FILES['imagenarchivo']['name'];

                $rutaTemporal = $_FILES['imagenarchivo']['tmp_name'];

                $rutaDestino = 'assets/img/Imgproductos/' . $nombreArchivo;


                move_uploaded_file($rutaTemporal, $rutaDestino);

                $objproducto->set_imagen($rutaDestino);

            } else {

                // No se sube imagen nueva, entonces mantener la imagen actual pasada desde un campo oculto

                // o consultar la imagen actual de la BD para no perderla

                if (isset($_POST['imagenActual']) && !empty($_POST['imagenActual'])) {

                    $objproducto->set_imagen($_POST['imagenActual']);

                } else {

                    // Si no viene imagen actual, poner la predeterminada

                    $objproducto->set_imagen('assets/img/logo.PNG');

                }
            }
            $result = $objproducto->modificar();
            echo json_encode($result);
            exit;
        }
    } elseif (isset($_POST['eliminar'])) {
        if (!empty($_POST['id_producto'])) {
            $objproducto->set_id_producto($_POST['id_producto']);
            $result = $objproducto->eliminar();
            echo json_encode($result);
            exit;
        }
    }
}

// Por defecto carga la vista (GET u otra petición)
require_once 'vista/producto.php';