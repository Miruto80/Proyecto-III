<?php
use PHPUnit\Framework\TestCase;
// Corregir la ruta para que apunte correctamente al modelo de proveedor
require_once __DIR__ . '/../../../modelo/proveedor.php';

/*|||||||||||||||||||||||||| INSTANCIA DE LA CLASE Y METODOS  |||||||||||||||||||||| */
class ProveedorTestable extends proveedor {
   
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
        
        $resultado = $this->proveedor->consultar();
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
        
        $json = json_encode([
            'operacion' => 'registrar',
            'datos' => $datosProveedor
        ]);

        $resultado = $this->proveedor->testProcesarProveedor($json);
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
            
            $json = json_encode([
                'operacion' => 'registrar',
                'datos' => $datosProveedor
            ]);

            $resultado = $this->proveedor->testProcesarProveedor($json);

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
        
        $jsonInsertar = json_encode([
            'operacion' => 'registrar',
            'datos' => $datosProveedor
        ]);
        $resultadoInsertar = $this->proveedor->testProcesarProveedor($jsonInsertar);

        // Para esta prueba unitaria, usamos un ID fijo
        $datosActualizar = array_merge($datosProveedor, [
            'id_proveedor' => 1 // ID
        ]);
        
        $jsonActualizar = json_encode([
            'operacion' => 'actualizar',
            'datos' => $datosActualizar
        ]);

        $resultado = $this->proveedor->testProcesarProveedor($jsonActualizar);
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
        
        $jsonInsertar = json_encode([
            'operacion' => 'registrar',
            'datos' => $datosProveedor
        ]);
        $resultadoInsertar = $this->proveedor->testProcesarProveedor($jsonInsertar);

        // Para esta prueba unitaria, usamos un ID fijo
        $jsonEliminar = json_encode([
            'operacion' => 'eliminar',
            'datos' => [
                'id_proveedor' => 1 // ID
            ]
        ]);

        $resultado = $this->proveedor->testProcesarProveedor($jsonEliminar);
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
        
        $jsonInsertar = json_encode([
            'operacion' => 'registrar',
            'datos' => $datosProveedor
        ]);
        $resultadoInsertar = $this->proveedor->testProcesarProveedor($jsonInsertar);

        // Agregar mensaje para verificar que se está ejecutando
        fwrite(STDERR, "Ejecutando consulta de proveedor por ID...\n");
        
        // Para esta prueba unitaria, usamos un ID fijo
        $resultado = $this->proveedor->consultarPorId(1); // ID
        
        // Mostrar resultado
        fwrite(STDERR, "Consulta de proveedor por ID completada. Resultado: " . (empty($resultado) ? "No encontrado" : "Encontrado") . "\n");
        
        $this->assertIsArray($resultado);
    }
}
?>