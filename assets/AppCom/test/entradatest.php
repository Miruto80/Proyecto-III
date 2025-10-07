<?php
use PHPUnit\Framework\TestCase;

// Crear una copia temporal del archivo entrada.php sin las dependencias de DomPDF
$entradaOriginal = __DIR__ . '/../../../modelo/entrada.php';
$entradaContent = file_get_contents($entradaOriginal);

// Remover las líneas problemáticas de DomPDF
$entradaContent = str_replace("require_once('assets/dompdf/vendor/autoload.php');", "// require_once('assets/dompdf/vendor/autoload.php'); // Comentado para tests", $entradaContent);
$entradaContent = str_replace("use Dompdf\\Dompdf;", "// use Dompdf\\Dompdf; // Comentado para tests", $entradaContent);
$entradaContent = str_replace("use Dompdf\\Options;", "// use Dompdf\\Options; // Comentado para tests", $entradaContent);

// Corregir la ruta de conexion.php para que funcione desde el directorio temporal
$conexionPath = realpath(__DIR__ . '/../../../modelo/conexion.php');
$entradaContent = str_replace("require_once 'conexion.php';", "require_once '$conexionPath';", $entradaContent);

// Cambiar métodos privados a protegidos para que puedan ser accedidos por la clase hija
$entradaContent = str_replace("private function ejecutarRegistro", "protected function ejecutarRegistro", $entradaContent);
$entradaContent = str_replace("private function ejecutarActualizacion", "protected function ejecutarActualizacion", $entradaContent);
$entradaContent = str_replace("private function ejecutarEliminacion", "protected function ejecutarEliminacion", $entradaContent);
$entradaContent = str_replace("private function ejecutarConsulta", "protected function ejecutarConsulta", $entradaContent);
$entradaContent = str_replace("private function ejecutarConsultaDetalles", "protected function ejecutarConsultaDetalles", $entradaContent);
$entradaContent = str_replace("private function ejecutarConsultaProductos", "protected function ejecutarConsultaProductos", $entradaContent);
$entradaContent = str_replace("private function ejecutarConsultaProveedores", "protected function ejecutarConsultaProveedores", $entradaContent);

// Crear archivo temporal
$tempFile = tempnam(sys_get_temp_dir(), 'entrada_test_') . '.php';
file_put_contents($tempFile, $entradaContent);

// Incluir el archivo temporal
require_once $tempFile;

/*|||||||||||||||||||||||||| INSTANCIA DE LA CLASE Y METODOS  |||||||||||||||||||||| */
class EntradaTestable extends Entrada {
    
    public function testEjecutarRegistro($datos) {  /*||| 1 ||| */
        return $this->ejecutarRegistro($datos);
    }

    public function testEjecutarActualizacion($datos) {  /*||| 2 ||| */
        return $this->ejecutarActualizacion($datos);
    }

    public function testEjecutarEliminacion($datos) {  /*||| 3 ||| */
        return $this->ejecutarEliminacion($datos);
    }

    public function testEjecutarConsulta() {  /*||| 4 ||| */
        return $this->ejecutarConsulta();
    }

    public function testEjecutarConsultaDetalles($datos) {  /*||| 5 ||| */
        return $this->ejecutarConsultaDetalles($datos);
    }

    public function testEjecutarConsultaProductos() {  /*||| 6 ||| */
        return $this->ejecutarConsultaProductos();
    }

    public function testEjecutarConsultaProveedores() {  /*||| 7 ||| */
        return $this->ejecutarConsultaProveedores();
    }
}

/*||||||||||||||||||||||||||||||| CLASE DE TEST  |||||||||||||||||||||||||||||| */
class EntradaTest extends TestCase {
    private EntradaTestable $entrada;

    protected function setUp(): void {
        $this->entrada = new EntradaTestable();
    }

    public function testOperacionInvalida() { /*|||||| OPERACIONES |||| 1 || */
        $entradaDirecto = new Entrada(); 
        $json = json_encode([
            'operacion' => 'desconocido',
            'datos' => []
        ]);

        $resultado = $entradaDirecto->procesarCompra($json);
        $this->assertEquals(0, $resultado['respuesta']);
        $this->assertEquals('Operación no válida', $resultado['mensaje']);
    }

    public function testConsultarCompras() { /*|||||| CONSULTAR COMPRAS ||||| 2 | */
        $resultado = $this->entrada->testEjecutarConsulta();
        $this->assertIsArray($resultado);
        $this->assertEquals(1, $resultado['respuesta']);
        $this->assertArrayHasKey('datos', $resultado);

        if (!empty($resultado['datos'])) {
            $this->assertArrayHasKey('id_compra', $resultado['datos'][0]);
            $this->assertArrayHasKey('fecha_entrada', $resultado['datos'][0]);
            $this->assertArrayHasKey('proveedor_nombre', $resultado['datos'][0]);
            $this->assertArrayHasKey('proveedor_telefono', $resultado['datos'][0]);
            $this->assertArrayHasKey('id_proveedor', $resultado['datos'][0]);
        }
    }

    public function testConsultarProductos() { /*|||||| CONSULTAR PRODUCTOS ||||| 3 | */
        $resultado = $this->entrada->testEjecutarConsultaProductos();
        $this->assertIsArray($resultado);
        $this->assertEquals(1, $resultado['respuesta']);
        $this->assertArrayHasKey('datos', $resultado);

        if (!empty($resultado['datos'])) {
            $this->assertArrayHasKey('id_producto', $resultado['datos'][0]);
            $this->assertArrayHasKey('nombre', $resultado['datos'][0]);
            $this->assertArrayHasKey('marca', $resultado['datos'][0]);
            $this->assertArrayHasKey('stock_disponible', $resultado['datos'][0]);
        }
    }

    public function testConsultarProveedores() { /*|||||| CONSULTAR PROVEEDORES ||||| 4 | */
        $resultado = $this->entrada->testEjecutarConsultaProveedores();
        $this->assertIsArray($resultado);
        $this->assertEquals(1, $resultado['respuesta']);
        $this->assertArrayHasKey('datos', $resultado);

        if (!empty($resultado['datos'])) {
            $this->assertArrayHasKey('id_proveedor', $resultado['datos'][0]);
            $this->assertArrayHasKey('nombre', $resultado['datos'][0]);
        }
    }

    public function testConsultarDetallesCompra() { /*|||||| CONSULTAR DETALLES COMPRA ||||| 5 | */
        $datos = ['id_compra' => 1];
        $resultado = $this->entrada->testEjecutarConsultaDetalles($datos);
        $this->assertIsArray($resultado);
        $this->assertEquals(1, $resultado['respuesta']);
        $this->assertArrayHasKey('datos', $resultado);

        if (!empty($resultado['datos'])) {
            $this->assertArrayHasKey('id_detalle_compra', $resultado['datos'][0]);
            $this->assertArrayHasKey('cantidad', $resultado['datos'][0]);
            $this->assertArrayHasKey('precio_total', $resultado['datos'][0]);
            $this->assertArrayHasKey('precio_unitario', $resultado['datos'][0]);
            $this->assertArrayHasKey('id_producto', $resultado['datos'][0]);
            $this->assertArrayHasKey('producto_nombre', $resultado['datos'][0]);
            $this->assertArrayHasKey('marca', $resultado['datos'][0]);
        }
    }

    public function testRegistrarCompraValida() { /*|||||| REGISTRAR COMPRA VÁLIDA ||||| 6 | */
        $datos = [
            'fecha_entrada' => '2024-01-15',
            'id_proveedor' => 1,
            'productos' => [
                [
                    'id_producto' => 1,
                    'cantidad' => 10,
                    'precio_unitario' => 25.50,
                    'precio_total' => 255.00
                ]
            ]
        ];

        $resultado = $this->entrada->testEjecutarRegistro($datos);
        $this->assertIsArray($resultado);
        $this->assertEquals(1, $resultado['respuesta']);
        $this->assertEquals('Compra registrada exitosamente', $resultado['mensaje']);
        $this->assertArrayHasKey('id_compra', $resultado);
    }

    public function testRegistrarCompraDatosIncompletos() { /*|||||| REGISTRAR COMPRA DATOS INCOMPLETOS ||||| 7 | */
        $datos = [
            'fecha_entrada' => '2024-01-15',
            'id_proveedor' => 1,
            'productos' => [] // Array vacío
        ];

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Datos incompletos');
        $this->entrada->testEjecutarRegistro($datos);
    }

    public function testRegistrarCompraSinFecha() { /*|||||| REGISTRAR COMPRA SIN FECHA ||||| 8 | */
        $datos = [
            'fecha_entrada' => '', // Fecha vacía
            'id_proveedor' => 1,
            'productos' => [
                [
                    'id_producto' => 1,
                    'cantidad' => 10,
                    'precio_unitario' => 25.50,
                    'precio_total' => 255.00
                ]
            ]
        ];

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Datos incompletos');
        $this->entrada->testEjecutarRegistro($datos);
    }

    public function testRegistrarCompraSinProveedor() { /*|||||| REGISTRAR COMPRA SIN PROVEEDOR ||||| 9 | */
        $datos = [
            'fecha_entrada' => '2024-01-15',
            'id_proveedor' => null, // Proveedor nulo
            'productos' => [
                [
                    'id_producto' => 1,
                    'cantidad' => 10,
                    'precio_unitario' => 25.50,
                    'precio_total' => 255.00
                ]
            ]
        ];

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Datos incompletos');
        $this->entrada->testEjecutarRegistro($datos);
    }

    public function testRegistrarCompraProductoInexistente() { /*|||||| REGISTRAR COMPRA PRODUCTO INEXISTENTE ||||| 10 | */
        $datos = [
            'fecha_entrada' => '2024-01-15',
            'id_proveedor' => 1,
            'productos' => [
                [
                    'id_producto' => 99999, // Producto que no existe
                    'cantidad' => 10,
                    'precio_unitario' => 25.50,
                    'precio_total' => 255.00
                ]
            ]
        ];

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Producto no encontrado: 99999');
        $this->entrada->testEjecutarRegistro($datos);
    }

    public function testActualizarCompraExistente() { /*|||||| ACTUALIZAR COMPRA EXISTENTE ||||| 11 | */
        $datos = [
            'id_compra' => 1,
            'fecha_entrada' => '2024-01-16',
            'id_proveedor' => 1,
            'productos' => [
                [
                    'id_producto' => 1,
                    'cantidad' => 15,
                    'precio_unitario' => 30.00,
                    'precio_total' => 450.00
                ]
            ]
        ];

        $resultado = $this->entrada->testEjecutarActualizacion($datos);
        $this->assertIsArray($resultado);
        $this->assertEquals(1, $resultado['respuesta']);
        $this->assertEquals('Compra actualizada exitosamente', $resultado['mensaje']);
    }

    public function testActualizarCompraInexistente() { /*|||||| ACTUALIZAR COMPRA INEXISTENTE ||||| 12 | */
        $datos = [
            'id_compra' => 99999, // Compra que no existe
            'fecha_entrada' => '2024-01-16',
            'id_proveedor' => 1,
            'productos' => [
                [
                    'id_producto' => 1,
                    'cantidad' => 15,
                    'precio_unitario' => 30.00,
                    'precio_total' => 450.00
                ]
            ]
        ];

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('La compra no existe');
        $this->entrada->testEjecutarActualizacion($datos);
    }

    public function testEliminarCompraExistente() { /*|||||| ELIMINAR COMPRA EXISTENTE ||||| 13 | */
        $datos = ['id_compra' => 1];

        $resultado = $this->entrada->testEjecutarEliminacion($datos);
        $this->assertIsArray($resultado);
        $this->assertEquals(1, $resultado['respuesta']);
        $this->assertEquals('Compra eliminada exitosamente', $resultado['mensaje']);
    }

    public function testEliminarCompraInexistente() { /*|||||| ELIMINAR COMPRA INEXISTENTE ||||| 14 | */
        $datos = ['id_compra' => 99999]; // Compra que no existe

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('La compra no existe');
        $this->entrada->testEjecutarEliminacion($datos);
    }

    public function testProcesarCompraRegistrar() { /*|||||| PROCESAR COMPRA REGISTRAR ||||| 15 | */
        $entradaDirecto = new Entrada();
        $json = json_encode([
            'operacion' => 'registrar',
            'datos' => [
                'fecha_entrada' => '2024-01-15',
                'id_proveedor' => 1,
                'productos' => [
                    [
                        'id_producto' => 1,
                        'cantidad' => 5,
                        'precio_unitario' => 20.00,
                        'precio_total' => 100.00
                    ]
                ]
            ]
        ]);

        $resultado = $entradaDirecto->procesarCompra($json);
        $this->assertIsArray($resultado);
        $this->assertEquals(1, $resultado['respuesta']);
        $this->assertEquals('Compra registrada exitosamente', $resultado['mensaje']);
    }

    public function testProcesarCompraConsultar() { /*|||||| PROCESAR COMPRA CONSULTAR ||||| 16 | */
        $entradaDirecto = new Entrada();
        $json = json_encode([
            'operacion' => 'consultar',
            'datos' => null
        ]);

        $resultado = $entradaDirecto->procesarCompra($json);
        $this->assertIsArray($resultado);
        $this->assertEquals(1, $resultado['respuesta']);
        $this->assertArrayHasKey('datos', $resultado);
    }

    public function testProcesarCompraConsultarProductos() { /*|||||| PROCESAR COMPRA CONSULTAR PRODUCTOS ||||| 17 | */
        $entradaDirecto = new Entrada();
        $json = json_encode([
            'operacion' => 'consultarProductos',
            'datos' => null
        ]);

        $resultado = $entradaDirecto->procesarCompra($json);
        $this->assertIsArray($resultado);
        $this->assertEquals(1, $resultado['respuesta']);
        $this->assertArrayHasKey('datos', $resultado);
    }

    public function testProcesarCompraConsultarProveedores() { /*|||||| PROCESAR COMPRA CONSULTAR PROVEEDORES ||||| 18 | */
        $entradaDirecto = new Entrada();
        $json = json_encode([
            'operacion' => 'consultarProveedores',
            'datos' => null
        ]);

        $resultado = $entradaDirecto->procesarCompra($json);
        $this->assertIsArray($resultado);
        $this->assertEquals(1, $resultado['respuesta']);
        $this->assertArrayHasKey('datos', $resultado);
    }

    public function testProcesarCompraConsultarDetalles() { /*|||||| PROCESAR COMPRA CONSULTAR DETALLES ||||| 19 | */
        $entradaDirecto = new Entrada();
        $json = json_encode([
            'operacion' => 'consultarDetalles',
            'datos' => ['id_compra' => 1]
        ]);

        $resultado = $entradaDirecto->procesarCompra($json);
        $this->assertIsArray($resultado);
        $this->assertEquals(1, $resultado['respuesta']);
        $this->assertArrayHasKey('datos', $resultado);
    }

    public function testProcesarCompraActualizar() { /*|||||| PROCESAR COMPRA ACTUALIZAR ||||| 20 | */
        $entradaDirecto = new Entrada();
        $json = json_encode([
            'operacion' => 'actualizar',
            'datos' => [
                'id_compra' => 1,
                'fecha_entrada' => '2024-01-17',
                'id_proveedor' => 1,
                'productos' => [
                    [
                        'id_producto' => 1,
                        'cantidad' => 8,
                        'precio_unitario' => 22.00,
                        'precio_total' => 176.00
                    ]
                ]
            ]
        ]);

        $resultado = $entradaDirecto->procesarCompra($json);
        $this->assertIsArray($resultado);
        $this->assertEquals(1, $resultado['respuesta']);
        $this->assertEquals('Compra actualizada exitosamente', $resultado['mensaje']);
    }

    public function testProcesarCompraEliminar() { /*|||||| PROCESAR COMPRA ELIMINAR ||||| 21 | */
        $entradaDirecto = new Entrada();
        $json = json_encode([
            'operacion' => 'eliminar',
            'datos' => ['id_compra' => 1]
        ]);

        $resultado = $entradaDirecto->procesarCompra($json);
        $this->assertIsArray($resultado);
        $this->assertEquals(1, $resultado['respuesta']);
        $this->assertEquals('Compra eliminada exitosamente', $resultado['mensaje']);
    }
}

// Limpiar archivo temporal
if (isset($tempFile) && file_exists($tempFile)) {
    unlink($tempFile);
}

?>
