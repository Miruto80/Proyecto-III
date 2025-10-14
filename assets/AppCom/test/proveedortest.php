<?php
use PHPUnit\Framework\TestCase;

// Crear una copia temporal del archivo proveedor.php sin las dependencias problemáticas
$proveedorOriginal = __DIR__ . '/../../../modelo/proveedor.php';
$proveedorContent = file_get_contents($proveedorOriginal);

// Remover las líneas problemáticas de DomPDF
$proveedorContent = str_replace("require_once __DIR__ . '/../assets/dompdf/vendor/autoload.php';", "// require_once __DIR__ . '/../assets/dompdf/vendor/autoload.php'; // Comentado para tests", $proveedorContent);
$proveedorContent = str_replace("use Dompdf\\Dompdf;", "// use Dompdf\\Dompdf; // Comentado para tests", $proveedorContent);

// Corregir la ruta de conexion.php para que funcione desde el directorio temporal
$conexionPath = realpath(__DIR__ . '/../../../modelo/conexion.php');
$proveedorContent = str_replace("require_once __DIR__ . '/conexion.php';", "require_once '$conexionPath';", $proveedorContent);

// Cambiar métodos privados a protegidos para que puedan ser accedidos por la clase hija
$proveedorContent = str_replace("private function ejecutarRegistro", "protected function ejecutarRegistro", $proveedorContent);
$proveedorContent = str_replace("private function ejecutarActualizacion", "protected function ejecutarActualizacion", $proveedorContent);
$proveedorContent = str_replace("private function ejecutarEliminacion", "protected function ejecutarEliminacion", $proveedorContent);

// Crear archivo temporal
$tempFile = tempnam(sys_get_temp_dir(), 'proveedor_test_') . '.php';
file_put_contents($tempFile, $proveedorContent);

// Incluir el archivo temporal
require_once $tempFile;

/*|||||||||||||||||||||||||| INSTANCIA DE LA CLASE Y METODOS  |||||||||||||||||||||| */
class ProveedorTestable extends proveedor {
    
    public function testEjecutarRegistro($datos) {  /*||| 1 ||| */
        return $this->ejecutarRegistro($datos);
    }

    public function testEjecutarActualizacion($datos) {  /*||| 2 ||| */
        return $this->ejecutarActualizacion($datos);
    }

    public function testEjecutarEliminacion($datos) {  /*||| 3 ||| */
        return $this->ejecutarEliminacion($datos);
    }

    public function testConsultar() {  /*||| 4 ||| */
        return $this->consultar();
    }

    public function testConsultarPorId($id) {  /*||| 5 ||| */
        return $this->consultarPorId($id);
    }

    public function testProcesarProveedor($jsonDatos) {
        return $this->procesarProveedor($jsonDatos);
    }
}

/*||||||||||||||||||||||||||||||| CLASE DE TEST  |||||||||||||||||||||||||||||| */
class ProveedorTest extends TestCase {
    private ProveedorTestable $proveedor;

    protected function setUp(): void {
        $this->proveedor = new ProveedorTestable();
    }

    protected function tearDown(): void {
        // Limpiar archivo temporal
        global $tempFile;
        if (isset($tempFile) && file_exists($tempFile)) {
            unlink($tempFile);
        }
    }

    public function testOperacionInvalida() { /*|||||| OPERACIONES |||| 1 || */
        $json = json_encode([
            'operacion' => 'desconocido',
            'datos' => []
        ]);

        $resultado = $this->proveedor->testProcesarProveedor($json);
        $this->assertEquals(0, $resultado['respuesta']);
        $this->assertEquals('Operación inválida', $resultado['mensaje']);
    }

    public function testConsultarProveedores() { /*|||||| CONSULTAR PROVEEDORES ||||| 2 | */
        $resultado = $this->proveedor->testConsultar();
        $this->assertIsArray($resultado);
        $this->assertNotEmpty($resultado);
    }

    public function testRegistrarProveedorValido() { /*|||||| REGISTRAR PROVEEDOR VÁLIDO ||||| 3 | */
        $datos = [
            'numero_documento' => 'J-12345678-' . time(),
            'tipo_documento' => 'J',
            'nombre' => 'Proveedor de prueba ' . time(),
            'correo' => 'proveedor' . time() . '@example.com',
            'telefono' => '0212-1234567',
            'direccion' => 'Dirección de prueba ' . time()
        ];

        $resultado = $this->proveedor->testEjecutarRegistro($datos);
        $this->assertIsArray($resultado);
        $this->assertEquals(1, $resultado['respuesta']);
        $this->assertEquals('incluir', $resultado['accion']);
        $this->assertEquals('Proveedor registrado', $resultado['mensaje']);
    }

    public function testRegistrarProveedorDatosIncompletos() { /*|||||| REGISTRAR PROVEEDOR DATOS INCOMPLETOS ||||| 4 | */
        $datos = [
            'numero_documento' => 'J-12345678-' . time(),
            'tipo_documento' => 'J',
            'nombre' => '', // Nombre vacío
            'correo' => 'proveedor' . time() . '@example.com',
            'telefono' => '0212-1234567',
            'direccion' => 'Dirección de prueba ' . time()
        ];

        $this->expectException(Exception::class);
        $this->proveedor->testEjecutarRegistro($datos);
    }

    public function testActualizarProveedorExistente() { /*|||||| ACTUALIZAR PROVEEDOR EXISTENTE ||||| 5 | */
        $datos = [
            'id_proveedor' => 1,
            'numero_documento' => 'J-87654321-' . time(),
            'tipo_documento' => 'J',
            'nombre' => 'Proveedor actualizado ' . time(),
            'correo' => 'proveedoractualizado' . time() . '@example.com',
            'telefono' => '0212-7654321',
            'direccion' => 'Dirección actualizada ' . time()
        ];

        $resultado = $this->proveedor->testEjecutarActualizacion($datos);
        $this->assertIsArray($resultado);
        $this->assertEquals(1, $resultado['respuesta']);
        $this->assertEquals('actualizar', $resultado['accion']);
        $this->assertEquals('Proveedor actualizado', $resultado['mensaje']);
    }

    public function testActualizarProveedorInexistente() { /*|||||| ACTUALIZAR PROVEEDOR INEXISTENTE ||||| 6 | */
        $datos = [
            'id_proveedor' => 99999, // Proveedor que no existe
            'numero_documento' => 'J-87654321-' . time(),
            'tipo_documento' => 'J',
            'nombre' => 'Proveedor actualizado ' . time(),
            'correo' => 'proveedoractualizado' . time() . '@example.com',
            'telefono' => '0212-7654321',
            'direccion' => 'Dirección actualizada ' . time()
        ];

        $this->expectException(Exception::class);
        $this->proveedor->testEjecutarActualizacion($datos);
    }

    public function testEliminarProveedorExistente() { /*|||||| ELIMINAR PROVEEDOR EXISTENTE ||||| 7 | */
        $datos = ['id_proveedor' => 1];

        $resultado = $this->proveedor->testEjecutarEliminacion($datos);
        $this->assertIsArray($resultado);
        $this->assertEquals(1, $resultado['respuesta']);
        $this->assertEquals('eliminar', $resultado['accion']);
        $this->assertEquals('Proveedor eliminado', $resultado['mensaje']);
    }

    public function testEliminarProveedorInexistente() { /*|||||| ELIMINAR PROVEEDOR INEXISTENTE ||||| 8 | */
        $datos = ['id_proveedor' => 99999]; // Proveedor que no existe

        $this->expectException(Exception::class);
        $this->proveedor->testEjecutarEliminacion($datos);
    }

    public function testConsultarProveedorPorId() { /*|||||| CONSULTAR PROVEEDOR POR ID ||||| 9 | */
        $resultado = $this->proveedor->testConsultarPorId(1);
        $this->assertIsArray($resultado);
    }

    public function testProcesarProveedorRegistrar() { /*|||||| PROCESAR PROVEEDOR REGISTRAR ||||| 10 | */
        $proveedorDirecto = new proveedor();
        $json = json_encode([
            'operacion' => 'registrar',
            'datos' => [
                'numero_documento' => 'J-11111111-' . time(),
                'tipo_documento' => 'J',
                'nombre' => 'Proveedor de prueba procesar ' . time(),
                'correo' => 'proveedorprocesar' . time() . '@example.com',
                'telefono' => '0212-1111111',
                'direccion' => 'Dirección de prueba procesar ' . time()
            ]
        ]);

        $resultado = $proveedorDirecto->procesarProveedor($json);
        $this->assertIsArray($resultado);
        $this->assertEquals(1, $resultado['respuesta']);
        $this->assertEquals('incluir', $resultado['accion']);
    }

    public function testProcesarProveedorActualizar() { /*|||||| PROCESAR PROVEEDOR ACTUALIZAR ||||| 11 | */
        $proveedorDirecto = new proveedor();
        $json = json_encode([
            'operacion' => 'actualizar',
            'datos' => [
                'id_proveedor' => 1,
                'numero_documento' => 'J-22222222-' . time(),
                'tipo_documento' => 'J',
                'nombre' => 'Proveedor actualizado procesar ' . time(),
                'correo' => 'proveedoractualizarprocesar' . time() . '@example.com',
                'telefono' => '0212-2222222',
                'direccion' => 'Dirección actualizada procesar ' . time()
            ]
        ]);

        $resultado = $proveedorDirecto->procesarProveedor($json);
        $this->assertIsArray($resultado);
        $this->assertEquals(1, $resultado['respuesta']);
        $this->assertEquals('actualizar', $resultado['accion']);
    }

    public function testProcesarProveedorEliminar() { /*|||||| PROCESAR PROVEEDOR ELIMINAR ||||| 12 | */
        $proveedorDirecto = new proveedor();
        $json = json_encode([
            'operacion' => 'eliminar',
            'datos' => [
                'id_proveedor' => 1
            ]
        ]);

        $resultado = $proveedorDirecto->procesarProveedor($json);
        $this->assertIsArray($resultado);
        $this->assertEquals(1, $resultado['respuesta']);
        $this->assertEquals('eliminar', $resultado['accion']);
    }
}
?>