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
    if (isset($_POST['registrar'])) {
        if (!empty($_POST['nombre']) && !empty($_POST['descripcion']) && !empty($_POST['marca']) && !empty($_POST['cantidad_mayor']) && !empty($_POST['precio_mayor']) && !empty($_POST['precio_detal']) && !empty($_POST['stock_maximo']) && !empty($_POST['stock_minimo']) && !empty($_POST['categoria'])) {
            $rutaImagen = 'assets/img/logo.PNG';
            
            if (isset($_FILES['imagenarchivo']) && $_FILES['imagenarchivo']['error'] == 0) {
                $nombreArchivo = $_FILES['imagenarchivo']['name'];
                $rutaTemporal = $_FILES['imagenarchivo']['tmp_name'];
                $rutaDestino = 'assets/img/Imgproductos/' . $nombreArchivo;
                move_uploaded_file($rutaTemporal, $rutaDestino);
                $rutaImagen = $rutaDestino;
            }

            $datosProducto = [
                'operacion' => 'registrar',
                'datos' => [
                    'nombre' => ucfirst(strtolower($_POST['nombre'])),
                    'descripcion' => $_POST['descripcion'],
                    'marca' => ucfirst(strtolower($_POST['marca'])),
                    'cantidad_mayor' => $_POST['cantidad_mayor'],
                    'precio_mayor' => $_POST['precio_mayor'],
                    'precio_detal' => $_POST['precio_detal'],
                    'stock_maximo' => $_POST['stock_maximo'],
                    'stock_minimo' => $_POST['stock_minimo'],
                    'imagen' => $rutaImagen,
                    'id_categoria' => $_POST['categoria']
                ]
            ];

            $resultadoRegistro = $objproducto->procesarProducto(json_encode($datosProducto));

            if ($resultadoRegistro['respuesta'] == 1) {
                $bitacora = [
                    'id_persona' => $_SESSION["id"],
                    'accion' => 'Registro de producto',
                    'descripcion' => 'Se registró el producto: ' . $datosProducto['datos']['nombre'] . ' ' . 
                                    $datosProducto['datos']['marca']
                ];
                $objproducto->registrarBitacora(json_encode($bitacora));
            }

            echo json_encode($resultadoRegistro);
        }
    } else if(isset($_POST['actualizar'])) {
        $rutaImagen = $_POST['imagenActual'];
            
        if (isset($_FILES['imagenarchivo']) && $_FILES['imagenarchivo']['error'] == 0) {
            $nombreArchivo = $_FILES['imagenarchivo']['name'];
            $rutaTemporal = $_FILES['imagenarchivo']['tmp_name'];
            $rutaDestino = 'assets/img/Imgproductos/' . $nombreArchivo;
            move_uploaded_file($rutaTemporal, $rutaDestino);
            $rutaImagen = $rutaDestino;
        }

        $datosProducto = [
            'operacion' => 'actualizar',
            'datos' => [
                'id_producto' => $_POST['id_producto'],
                'nombre' => ucfirst(strtolower($_POST['nombre'])),
                'descripcion' => $_POST['descripcion'],
                'marca' => ucfirst(strtolower($_POST['marca'])),
                'cantidad_mayor' => $_POST['cantidad_mayor'],
                'precio_mayor' => $_POST['precio_mayor'],
                'precio_detal' => $_POST['precio_detal'],
                'stock_maximo' => $_POST['stock_maximo'],
                'stock_minimo' => $_POST['stock_minimo'],
                'imagen' => $rutaImagen,
                'id_categoria' => $_POST['categoria']
            ]
        ];

        $resultado = $objproducto->procesarProducto(json_encode($datosProducto));

        if ($resultado['respuesta'] == 1) {
            $bitacora = [
                'id_persona' => $_SESSION["id"],
                'accion' => 'Modificación de producto',
                'descripcion' => 'Se modificó el producto: ' . $datosProducto['datos']['nombre'] . ' ' . 
                                $datosProducto['datos']['marca']
            ];
            $objproducto->registrarBitacora(json_encode($bitacora));
        }

        echo json_encode($resultado);

    } else if(isset($_POST['eliminar'])) {
        $datosProducto = [
            'operacion' => 'eliminar',
            'datos' => [
                'id_producto' => $_POST['id_producto']
            ]
        ];

        $resultado = $objproducto->procesarProducto(json_encode($datosProducto));

        if ($resultado['respuesta'] == 1) {
            $bitacora = [
                'id_persona' => $_SESSION["id"],
                'accion' => 'Eliminación de producto',
                'descripcion' => 'Se eliminó el producto con ID: ' . $datosProducto['datos']['id_producto']
            ];
            $objproducto->registrarBitacora(json_encode($bitacora));
        }

        echo json_encode($resultado);
    } else if(isset($_POST['accion']) && $_POST['accion'] == 'cambiarEstatus') {
        $datosProducto = [
            'operacion' => 'cambiarEstatus',
            'datos' => [
                'id_producto' => $_POST['id_producto'],
                'estatus_actual' => $_POST['estatus_actual']
            ]
        ];

        $resultado = $objproducto->procesarProducto(json_encode($datosProducto));

        if ($resultado['respuesta'] == 1) {
            $bitacora = [
                'id_persona' => $_SESSION["id"],
                'accion' => 'Cambio de estatus de producto',
                'descripcion' => 'Se cambió el estatus del producto con ID: ' . $datosProducto['datos']['id_producto']
            ];
            $objproducto->registrarBitacora(json_encode($bitacora));
        }

        echo json_encode($resultado);
    }
} else  {
    if ($_SESSION["nivel_rol"] != 2 && $_SESSION["nivel_rol"] != 3) {
    header("Location: ?pagina=catalogo");
    exit();
    }

    $bitacora = [
        'id_persona' => $_SESSION["id"],
        'accion' => 'Acceso a Módulo',
        'descripcion' => 'módulo de Producto'
    ];
    $objproducto->registrarBitacora(json_encode($bitacora));

    

    require_once 'vista/producto.php';
}
?>