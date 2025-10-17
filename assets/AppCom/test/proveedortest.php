<?php
use PHPUnit\Framework\TestCase;

// Crear una copia temporal del archivo proveedor.php sin las dependencias de DomPDF
$proveedorOriginal = __DIR__ . '/../../../modelo/proveedor.php';
$proveedorContent = file_get_contents($proveedorOriginal);

// Remover las líneas problemáticas de DomPDF
$proveedorContent = str_replace("require_once __DIR__ . '/../assets/dompdf/vendor/autoload.php';", "// require_once __DIR__ . '/../assets/dompdf/vendor/autoload.php'; // Comentado para tests", $proveedorContent);
$proveedorContent = str_replace("use Dompdf\\Dompdf;", "// use Dompdf\\Dompdf; // Comentado para tests", $proveedorContent);

// Corregir la ruta de conexion.php para que funcione desde el directorio temporal
$conexionPath = realpath(__DIR__ . '/../../../modelo/conexion.php');
$proveedorContent = str_replace("require_once __DIR__ . '/conexion.php';", "require_once '$conexionPath';", $proveedorContent);

// Corregir la ruta de bitacora.php en el constructor
$bitacoraPath = realpath(__DIR__ . '/../../../modelo/bitacora.php');
$proveedorContent = str_replace("require_once __DIR__ . '/bitacora.php';", "require_once '$bitacoraPath';", $proveedorContent);

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

    public function testOperacionInvalida() { /*|||||| OPERACIONES |||| 1 || */
        $json = json_encode([
            'operacion' => 'desconocido',
            'datos' => []
        ]);

        $resultado = $this->proveedor->testProcesarProveedor($json);
        $this->assertEquals(0, $resultado['respuesta']);
        $this->assertEquals('Operación inválida', $resultado['mensaje']);
    }

    public function testConsultar() { /*|||||| CONSULTAR  ||||| 2 | */
        // Agregar mensaje para verificar que se está ejecutando la consulta
        fwrite(STDERR, "Ejecutando consulta de proveedores...\n");
        
        $resultado = $this->proveedor->testConsultar();
        $this->assertIsArray($resultado);
        
        // Mostrar cantidad de resultados
        fwrite(STDERR, "Consulta de proveedores completada. Resultados: " . count($resultado) . "\n");
    }

    public function testRegistrarProveedor() {   /*||||||  REGISTRO NUEVO TEST ||||| 3 | */
        $datosProveedor = [
            'numero_documento' => 'J-12345678-' . time(),
            'tipo_documento' => 'J',
            'nombre' => 'Proveedor de prueba ' . time(),
            'correo' => 'proveedor' . time() . '@example.com',
            'telefono' => '0212-1234567',
            'direccion' => 'Dirección de prueba ' . time()
        ];
        
        $resultado = $this->proveedor->testEjecutarRegistro($datosProveedor);
        $this->assertIsArray($resultado);
        $this->assertEquals(1, $resultado['respuesta']);
        $this->assertEquals('incluir', $resultado['accion']);
    }

    public function testRegistroMasivoProveedores() { /*||||||  REGISTRO MASIVO TEST ||||| 4 | */
        for ($i = 1; $i <= 2; $i++) {
            $datosProveedor = [
                'numero_documento' => 'J-87654321-' . time() . $i,
                'tipo_documento' => 'J',
                'nombre' => 'Proveedor masivo ' . time() . '_' . $i,
                'correo' => 'proveedormasivo' . time() . $i . '@example.com',
                'telefono' => '0212-7654321',
                'direccion' => 'Dirección masiva ' . time() . '_' . $i
            ];
            
            $resultado = $this->proveedor->testEjecutarRegistro($datosProveedor);

            $this->assertIsArray($resultado, "Falló en la iteración $i: no se recibió un array");
            $this->assertEquals(1, $resultado['respuesta'], "Falló en la iteración $i: respuesta incorrecta");
            $this->assertEquals('incluir', $resultado['accion'], "Falló en la iteración $i: acción incorrecta");
        }
    }

    public function testActualizarProveedor() { /*||||||  ACTUALIZAR DATOS  TEST ||||| 5 | */
        // Primero insertamos un proveedor para tener un ID válido
        $datosProveedor = [
            'numero_documento' => 'J-11111111-' . time(),
            'tipo_documento' => 'J',
            'nombre' => 'Proveedor para actualizar ' . time(),
            'correo' => 'proveedoractualizar' . time() . '@example.com',
            'telefono' => '0212-1111111',
            'direccion' => 'Dirección para actualizar ' . time()
        ];
        
        $resultadoInsertar = $this->proveedor->testEjecutarRegistro($datosProveedor);

        // Para esta prueba unitaria, usamos un ID fijo
        $datosActualizar = array_merge($datosProveedor, [
            'id_proveedor' => 1 // ID
        ]);
        
        $resultado = $this->proveedor->testEjecutarActualizacion($datosActualizar);
        $this->assertIsArray($resultado);
        $this->assertEquals(1, $resultado['respuesta']);
        $this->assertEquals('actualizar', $resultado['accion']);
    }

    public function testEliminarProveedor() { /*||||||  ELIMINAR  ||||| 6 | */
        // Primero insertamos un proveedor para tener un ID válido
        $datosProveedor = [
            'numero_documento' => 'J-22222222-' . time(),
            'tipo_documento' => 'J',
            'nombre' => 'Proveedor para eliminar ' . time(),
            'correo' => 'proveedoreliminar' . time() . '@example.com',
            'telefono' => '0212-2222222',
            'direccion' => 'Dirección para eliminar ' . time()
        ];
        
        $resultadoInsertar = $this->proveedor->testEjecutarRegistro($datosProveedor);

        // Para esta prueba unitaria, usamos un ID fijo
        $datosEliminar = [
            'id_proveedor' => 1 // ID
        ];

        $resultado = $this->proveedor->testEjecutarEliminacion($datosEliminar);
        $this->assertIsArray($resultado);
        $this->assertEquals(1, $resultado['respuesta']);
        $this->assertEquals('eliminar', $resultado['accion']);
    }

    public function testConsultarPorId() { /*|||||| CONSULTAR POR ID ||||| 7 | */
        // Primero insertamos un proveedor para tener un ID válido
        $datosProveedor = [
            'numero_documento' => 'J-33333333-' . time(),
            'tipo_documento' => 'J',
            'nombre' => 'Proveedor para consultar por ID ' . time(),
            'correo' => 'proveedorconsultar' . time() . '@example.com',
            'telefono' => '0212-3333333',
            'direccion' => 'Dirección para consultar ' . time()
        ];
        
        $resultadoInsertar = $this->proveedor->testEjecutarRegistro($datosProveedor);

        // Agregar mensaje para verificar que se está ejecutando
        fwrite(STDERR, "Ejecutando consulta de proveedor por ID...\n");
        
        // Para esta prueba unitaria, usamos un ID fijo
        $resultado = $this->proveedor->testConsultarPorId(1); // ID
        
        // Mostrar resultado
        fwrite(STDERR, "Consulta de proveedor por ID completada. Resultado: " . (empty($resultado) ? "No encontrado" : "Encontrado") . "\n");
        
        $this->assertIsArray($resultado);
    }
    
    public function testRegistrarProveedorConDatosInvalidos() { /*|||||| REGISTRO CON DATOS INVÁLIDOS ||||| 8 | */
        // Datos inválidos que deberían causar una excepción
        $datosProveedorInvalidos = [
            'numero_documento' => '', // Documento vacío
            'tipo_documento' => '',   // Tipo vacío
            'nombre' => '',           // Nombre vacío
            'correo' => 'correo-invalido', // Correo inválido
            'telefono' => '',         // Teléfono vacío
            'direccion' => ''         // Dirección vacía
        ];
        
        // Esperamos que se lance una excepción PDO
        $this->expectException(PDOException::class);
        
        // Esta llamada debería fallar y lanzar la excepción
        $this->proveedor->testEjecutarRegistro($datosProveedorInvalidos);
    }
}

// Limpiar archivo temporal
if (isset($tempFile) && file_exists($tempFile)) {
    unlink($tempFile);
}
?>