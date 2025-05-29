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

    if ($accion === 'registrar') {
        if (!empty($_POST['nombre'])) {
            $objproducto->set_nombre($_POST['nombre']);
            $objproducto->set_descripcion($_POST['descripcion']);
            $objproducto->set_marca($_POST['marca']);
            $objproducto->set_cantidad_mayor($_POST['cantidad_mayor']);
            $objproducto->set_precio_mayor($_POST['precio_mayor']);
            $objproducto->set_precio_detal($_POST['precio_detal']);
            $objproducto->set_stock_maximo($_POST['stock_maximo']);
            $objproducto->set_stock_minimo($_POST['stock_minimo']);
            if (isset($_POST['categoria']) && !empty($_POST['categoria'])) {

                $objproducto->set_Categoria($_POST['categoria']);

            } else {

                echo json_encode(['respuesta' => 0, 'accion' => 'registrar', 'error' => 'Categoria no seleccionada']);

                exit;

            }

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

             /* BITACORA */
            if ($result['respuesta'] == 1) {
                $id_persona = $_SESSION["id"]; 
            // Registrar en la bitácora
                $accion = 'Registro de Producto';
                $descripcion = 'Se registró el producto: ' . $_POST['nombre'] . ' ' . $_POST['marca'];
                $objproducto->registrarBitacora($id_persona, $accion, $descripcion);
            } /* FIN BITACORA */


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
                if (isset($_POST['imagenActual']) && !empty($_POST['imagenActual'])) {

                    $objproducto->set_imagen($_POST['imagenActual']);

                } else {
                    $objproducto->set_imagen('assets/img/logo.PNG');
                }
            }
            $result = $objproducto->modificar();
              /* BITACORA */
            if ($result['respuesta'] == 1) {
                $id_persona = $_SESSION["id"]; 
            // Registrar en la bitácora
                $accion = 'Modificación de Producto';
                $descripcion = 'Se Modifico el producto: ' . $_POST['nombre'] . ' ' . $_POST['marca'];
                $objproducto->registrarBitacora($id_persona, $accion, $descripcion);
            } /* FIN BITACORA */

            echo json_encode($result);
            exit;
        }
    } elseif (isset($_POST['eliminar'])) {
        if (!empty($_POST['id_producto'])) {
            $objproducto->set_id_producto($_POST['id_producto']);
            $result = $objproducto->eliminar();
            
            /* BITACORA */
            if ($result['respuesta'] == 1) {
                $id_persona = $_SESSION["id"]; 
            // Registrar en la bitácora
                $accion = 'Eliminación  de Producto';
                $descripcion = 'Se Elimino el producto: ' . $_POST['id_producto'];
                $objproducto->registrarBitacora($id_persona, $accion, $descripcion);
            } /* FIN BITACORA */

            echo json_encode($result);
            exit;
        }
    }elseif ($accion === 'cambiarEstatus') {
        if (!empty($_POST['id_producto']) && isset($_POST['estatus_actual'])) {
            $id_producto = $_POST['id_producto'];
            $estatus_actual = $_POST['estatus_actual']; 
    
            
            $result = $objproducto->cambiarEstatusProducto($id_producto, $estatus_actual);

              
            if ($result['respuesta'] == 1) {
                $id_persona = $_SESSION["id"]; 
            // Registrar en la bitácora
                $accion = 'Cambiar Estatus de Producto';
                $descripcion = 'Se Cambio Estatus del producto: ' . $_POST['id_producto'] . ' ' . $_POST['estatus_actual'];
                $objproducto->registrarBitacora($id_persona, $accion, $descripcion);
            } /* FIN BITACORA */

            echo json_encode($result);
            exit;
        }
    }
}

if(isset($_POST['generar'])){
    $objproducto->generarPDF();
    exit; // Evitar que se cargue la vista después del PDF
}

// Generar gráfico antes de cargar la vista

function generarGrafico() {
    require_once('assets/js/jpgraph/src/jpgraph.php');
    require_once('assets/js/jpgraph/src/jpgraph_pie.php');
    require_once('assets/js/jpgraph/src/jpgraph_pie3d.php');

    $db = new Conexion();
    $conex1 = $db->getConex1(); // Cambia al nombre correcto

    // Obtener los productos con mayor stock
    $SQL = "SELECT nombre, stock_disponible 
    FROM productos 
    WHERE estatus IN (1, 2)
    ORDER BY stock_disponible DESC 
    LIMIT 10";

    $stmt = $conex1->prepare($SQL);
    $stmt->execute();

    $data = [];
    $labels = [];

    while ($resultado = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $labels[] = $resultado['nombre'];
        $data[] = $resultado['stock_disponible'];
    }

    // Crear el gráfico
    $graph = new PieGraph(900, 500);
    $p1 = new PiePlot3D($data);
    $p1->SetLegends($labels);
    $p1->SetCenter(0.5, 0.5);
    $p1->ShowBorder();
    $p1->SetColor('black');
    $p1->ExplodeSlice(1);
    $graph->Add($p1);

    // Ruta de almacenamiento
    $imgDir = __DIR__ . "/../assets/img/grafica_reportes/";
    $imagePath = $imgDir . "grafico_productos.png";

    // Crear carpeta si no existe
    if (!file_exists($imgDir)) {
        mkdir($imgDir, 0777, true);
    }

    // Eliminar imagen anterior
    if (file_exists($imagePath)) {
        unlink($imagePath);
    }

    // Verificar si hay datos antes de generar la imagen
    if (empty($data) || array_sum($data) == 0) {
        echo "No hay datos suficientes para generar un gráfico.";
        return;
    }

    $graph->Stroke($imagePath);
}


// Llamar la función para generar la gráfica ANTES de cargar la vista
generarGrafico();


// Por defecto carga la vista (GET u otra petición)
$id_persona = $_SESSION["id"];
$accion = 'Acceso a Módulo';
$descripcion = 'módulo de Producto.';
$objproducto->registrarBitacora($id_persona, $accion, $descripcion);
require_once 'vista/producto.php';