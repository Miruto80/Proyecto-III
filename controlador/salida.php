<?php
session_start();
if (empty($_SESSION["id"])) {
    header("location:?pagina=login");
    exit;
} /* Validacion URL */

require_once 'modelo/salida.php';

$salida = new Salida();

// Generar o verificar el token CSRF
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        if (esAjax()) {
            header('Content-Type: application/json');
            echo json_encode([
                'error' => true,
                'title' => '¡Error!',
                'text' => 'Error de validación del formulario',
                'icon' => 'error'
            ]);
            exit;
        } else {
            echo "<script>
                Swal.fire({
                    title: '¡Error!',
                    text: 'Error de validación del formulario',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                }).then((result) => {
                    window.location.href = '?pagina=salida';
                });
            </script>";
            exit;
        }
    }
}

// Detectar si la solicitud es AJAX
function esAjax() {
    return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
}

// Función para sanitizar datos de entrada
function sanitizar($dato) {
    return htmlspecialchars(trim($dato), ENT_QUOTES, 'UTF-8');
}

// Procesar la búsqueda de cliente por cédula (AJAX)
if (isset($_POST['buscar_cliente'])) {
    if (isset($_POST['cedula'])) {
        $cedula = sanitizar($_POST['cedula']);
        $cliente = $salida->consultarCliente($cedula);
        
        if ($cliente) {
            $_SESSION['cliente_encontrado'] = true;
            $_SESSION['datos_cliente'] = $cliente;
        } else {
            $_SESSION['cliente_encontrado'] = false;
        }
        
        if (esAjax()) {
            header('Content-Type: application/json');
            echo json_encode([
                'respuesta' => $cliente ? 1 : 0,
                'cliente' => $cliente
            ]);
            exit;
        } else {
            header("Location: ?pagina=salida");
            exit;
        }
    }
}

// Procesar el registro de nuevo cliente
if (isset($_POST['registrar_cliente'])) {
    header('Content-Type: application/json; charset=utf-8');
    
    try {
        // Validar datos del cliente
        $cedula = sanitizar($_POST['cedula']);
        $nombre = sanitizar($_POST['nombre']);
        $apellido = sanitizar($_POST['apellido']);
        $telefono = sanitizar($_POST['telefono']);
        $correo = sanitizar($_POST['correo']);

        // Verificar si la cédula ya existe
        if ($salida->existeCedula($cedula)) {
            echo json_encode([
                'success' => false,
                'message' => 'La cédula ya está registrada'
            ]);
            exit;
        }

        // Registrar el nuevo cliente
        $id_cliente = $salida->registrarCliente([
            'cedula' => $cedula,
            'nombre' => $nombre,
            'apellido' => $apellido,
            'telefono' => $telefono,
            'correo' => $correo
        ]);

        if ($id_cliente) {
            echo json_encode([
                'success' => true,
                'id_cliente' => $id_cliente,
                'message' => 'Cliente registrado exitosamente'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Error al registrar el cliente'
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
    exit;
}

// Procesar el registro de una nueva venta
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registrar_venta'])) {
    // Asegurarnos de que no haya salida previa
    ob_clean();
    
    try {
        // Validar que existan los datos necesarios
        if (!isset($_POST['id_persona']) || !isset($_POST['id_metodopago']) || !isset($_POST['id_entrega'])) {
            throw new Exception('Faltan datos requeridos para la venta');
        }

        // Configurar datos básicos de la venta
        $salida->set_Id_persona(intval($_POST['id_persona']));
        $salida->set_Id_metodopago(intval($_POST['id_metodopago']));
        $salida->set_Id_entrega(intval($_POST['id_entrega']));
        $salida->set_Precio_total(floatval($_POST['precio_total']));
        
        // Configurar datos de pago móvil si el método es pago móvil (id = 1)
        if ($_POST['id_metodopago'] == '1') {
            if (!isset($_POST['referencia_bancaria']) || !isset($_POST['telefono_emisor']) || !isset($_POST['banco'])) {
                throw new Exception('Faltan datos del pago móvil');
            }
            
            // Validar que el banco destino sea uno de los permitidos
            if (!isset($_POST['banco_destino']) || 
                !in_array($_POST['banco_destino'], ['0102-Banco De Venezuela', '0105-Banco Mercantil'])) {
                throw new Exception('Banco receptor no válido');
            }
            
            $salida->set_Referencia_bancaria(sanitizar($_POST['referencia_bancaria']));
            $salida->set_Telefono_emisor(sanitizar($_POST['telefono_emisor']));
            $salida->set_Banco(sanitizar($_POST['banco']));
            $salida->set_Banco_destino(sanitizar($_POST['banco_destino']));
        }

        // Configurar dirección si existe
        if (isset($_POST['direccion'])) {
            $salida->set_Direccion(sanitizar($_POST['direccion']));
        }
        
        // Procesar detalles de productos
        $detalles = [];
        if (!isset($_POST['id_producto']) || !is_array($_POST['id_producto']) || empty($_POST['id_producto'])) {
            throw new Exception('No hay productos seleccionados');
        }

        for ($i = 0; $i < count($_POST['id_producto']); $i++) {
            if (!empty($_POST['id_producto'][$i]) && isset($_POST['cantidad'][$i]) && $_POST['cantidad'][$i] > 0) {
                $detalle = [
                    'id_producto' => intval($_POST['id_producto'][$i]),
                    'cantidad' => intval($_POST['cantidad'][$i]),
                    'precio_unitario' => floatval($_POST['precio_unitario'][$i])
                ];
                $detalles[] = $detalle;
            }
        }

        if (empty($detalles)) {
            throw new Exception('No hay productos válidos para registrar');
        }
        
        $salida->set_Detalles($detalles);
        
        // Registrar la venta
        $respuesta = $salida->registrar();
        
        header('Content-Type: application/json; charset=utf-8');
        if ($respuesta['respuesta'] == 1) {
            echo json_encode([
                'respuesta' => 1,
                'mensaje' => 'Venta registrada exitosamente',
                'id_pedido' => $respuesta['id_pedido']
            ]);
        } else {
            echo json_encode([
                'respuesta' => 0,
                'error' => isset($respuesta['error']) ? $respuesta['error'] : 'Error desconocido al registrar la venta'
            ]);
        }
        exit;

    } catch (Exception $e) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'respuesta' => 0,
            'error' => $e->getMessage()
        ]);
        exit;
    }
}

// Procesar la modificación del estado de una venta
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modificar_venta'])) {
    if (isset($_POST['id_pedido']) && isset($_POST['estado_pedido'])) {
        $salida->set_Id_pedido(intval($_POST['id_pedido']));
        $salida->set_Estado(sanitizar($_POST['estado_pedido']));
        
        // Modificar el estado de la venta
        $respuesta = $salida->modificar();
        
        if (esAjax()) {
            header('Content-Type: application/json');
            echo json_encode($respuesta);
            exit;
        } else {
            // Regenerar token CSRF
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            header("Location: ?pagina=salida");
            exit;
        }
    }
}

// Procesar la eliminación de una venta
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_venta'])) {
    if (!empty($_POST['eliminar_venta'])) {
        $salida->set_Id_pedido(intval($_POST['eliminar_venta']));
        
        // Eliminar la venta
        $respuesta = $salida->eliminar();
        
        if (esAjax()) {
            header('Content-Type: application/json');
            echo json_encode($respuesta);
            exit;
        } else {
            if ($respuesta['respuesta'] == 1) {
                $_SESSION['mensaje'] = "Venta eliminada exitosamente";
                $_SESSION['tipo_mensaje'] = "success";
            } else {
                $_SESSION['mensaje'] = "Error al eliminar la venta: " . (isset($respuesta['error']) ? $respuesta['error'] : "");
                $_SESSION['tipo_mensaje'] = "danger";
            }
            
            // Regenerar token CSRF
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            header("Location: ?pagina=salida");
            exit;
        }
    }
}

//actualización del delivery
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_delivery'])) {
    if (isset($_POST['id_pedido']) && isset($_POST['estado_delivery'])) {
        try {
            $salida->set_Id_pedido(intval($_POST['id_pedido']));
            $salida->set_Estado(sanitizar($_POST['estado_delivery']));
            
            // Si hay dirección nueva, actualizarla
            if (isset($_POST['direccion']) && !empty($_POST['direccion'])) {
                $salida->set_Direccion(sanitizar($_POST['direccion']));
            }
            
            // Modificar el estado del delivery
            $respuesta = $salida->modificar();
            
            if ($respuesta['respuesta'] == 1) {
                $_SESSION['mensaje'] = "Estado del delivery actualizado exitosamente";
                $_SESSION['tipo_mensaje'] = "success";
            } else {
                $_SESSION['mensaje'] = "Error al actualizar el estado del delivery";
                $_SESSION['tipo_mensaje'] = "error";
            }
        } catch (Exception $e) {
            $_SESSION['mensaje'] = "Error: " . $e->getMessage();
            $_SESSION['tipo_mensaje'] = "error";
        }
    } else {
        $_SESSION['mensaje'] = "Faltan datos requeridos para actualizar el delivery";
        $_SESSION['tipo_mensaje'] = "error";
    }
    
    // Regenerar token CSRF y redirigir
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    header("Location: ?pagina=salida");
    exit;
}

if(isset($_POST['generar'])){
    try {
        // Generar el gráfico antes del PDF
        generarGrafico();
        
        // Generar el PDF
        $salida->generarPDF();
        exit;
    } catch (Exception $e) {
        error_log("Error al generar el PDF: " . $e->getMessage());
        echo "<script>
            Swal.fire({
                title: '¡Error!',
                text: 'Error al generar el reporte PDF',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            }).then((result) => {
                window.location.href = '?pagina=salida';
            });
        </script>";
        exit;
    }
}

// Generar gráfico antes de cargar la vista
function generarGrafico() {
    try {
        require_once('assets/js/jpgraph/src/jpgraph.php');
        require_once('assets/js/jpgraph/src/jpgraph_pie.php');
        require_once('assets/js/jpgraph/src/jpgraph_pie3d.php');

        $db = new Conexion();
        $conex1 = $db->getConex1();

        // Consulta para obtener los 5 productos más vendidos
        $SQL = "SELECT p.nombre as nombre_producto, 
                       SUM(pd.cantidad) as total_vendido
                FROM pedido_detalles pd
                JOIN productos p ON pd.id_producto = p.id_producto
                JOIN pedido pe ON pd.id_pedido = pe.id_pedido
                WHERE pe.estado = 2 -- Solo pedidos aprobados
                GROUP BY p.id_producto, p.nombre
                ORDER BY total_vendido DESC
                LIMIT 5";

        $stmt = $conex1->prepare($SQL);
        $stmt->execute();

        $data = [];
        $labels = [];

        // Verificar si hay resultados
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (empty($resultados)) {
            $data = [100];
            $labels = ['No hay datos de ventas'];
        } else {
            foreach ($resultados as $resultado) {
                $labels[] = $resultado['nombre_producto'];
                $data[] = (int)$resultado['total_vendido'];
            }
        }

        // Crear el gráfico
        $graph = new PieGraph(900, 500);
        $graph->SetShadow();
        
        $p1 = new PiePlot3D($data);
        $p1->SetLegends($labels);
        $p1->SetCenter(0.5, 0.45);
        $p1->SetSize(0.3);
        
        $p1->ShowBorder();
        $p1->SetSliceColors(['#FF9999','#66B2FF','#99FF99','#FFCC99','#FF99CC']);
        
        $p1->SetLabelType(PIE_VALUE_ABS);
        $p1->value->SetFont(FF_ARIAL, FS_BOLD, 11);
        $p1->value->SetColor("black");
        
        $graph->Add($p1);

        // Guardar el gráfico
        $imgDir = __DIR__ . "/../assets/img/grafica_reportes/";
        if (!file_exists($imgDir)) {
            mkdir($imgDir, 0777, true);
        }

        $imagePath = $imgDir . "grafico_ventas.png";
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        $graph->Stroke($imagePath);
        
    } catch (Exception $e) {
        error_log("Error al generar el gráfico de ventas: " . $e->getMessage());
    }
}

// Llamar la función para generar la gráfica ANTES de cargar la vista
generarGrafico();

// Si es una solicitud GET normal, mostrar la vista
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Consultar datos actualizados para la vista
    $ventas = $salida->consultar();
    $productos_lista = $salida->consultarProductos();
    $metodos_pago = $salida->consultarMetodosPago();
    $metodos_entrega = $salida->consultarMetodosEntrega();

    // Cargar la vista
    if ($_SESSION["nivel_rol"] >= 2) {
        require_once 'vista/salida.php';
    } else {
        require_once 'vista/seguridad/privilegio.php';
    }
}
?>