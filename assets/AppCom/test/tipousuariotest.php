<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../../../modelo/tipousuario.php';

/*|||||||||||||||||||||||||| INSTANCIA DE LA CLASE Y METODOS  |||||||||||||||||||||| */
class TipousuarioTestable extends tipousuario {
   
    public function testProcesarTipousuario($jsonDatos) {
        return $this->procesarTipousuario($jsonDatos);
    }
}

/*||||||||||||||||||||||||||||||| CLASE DE TEST  |||||||||||||||||||||||||||||| */
class TipousuarioTest extends TestCase {
    private TipousuarioTestable $tipousuario;

    protected function setUp(): void {
        $this->tipousuario = new TipousuarioTestable();
    }

    public function testOperacionInvalida() { /*|||||| OPERACIONES |||| 1 || */
        $json = json_encode([
            'operacion' => 'desconocido',
            'datos' => []
        ]);

        $resultado = $this->tipousuario->testProcesarTipousuario($json);
        $this->assertEquals(0, $resultado['respuesta']);
        $this->assertEquals('Operación inválida', $resultado['mensaje']);
    }

    public function testConsultar() { /*|||||| CONSULTAR  ||||| 2 | */
        // Agregar mensaje para verificar que se está ejecutando
        fwrite(STDERR, "Ejecutando consulta de tipos de usuario...\n");
        
        $resultado = $this->tipousuario->consultar();
        $this->assertIsArray($resultado);
        
        // Mostrar cantidad de resultados
        fwrite(STDERR, "Consulta de tipos de usuario completada. Resultados: " . count($resultado) . "\n");
    }

    public function testRegistrarTipousuario() {   /*||||||  REGISTRO NUEVO TEST ||||| 3 | */
        $datosTipousuario = [
            'nombre' => 'Tipo usuario de prueba ' . time(),
            'nivel' => rand(2, 3) // Nivel aleatorio entre 2 y 3
        ];
        
        $json = json_encode([
            'operacion' => 'registrar',
            'datos' => $datosTipousuario
        ]);

        $resultado = $this->tipousuario->testProcesarTipousuario($json);
        $this->assertIsArray($resultado);
        $this->assertEquals(1, $resultado['respuesta']);
        $this->assertEquals('incluir', $resultado['accion']);
    }

    public function testRegistroMasivoTipousuario() { /*||||||  REGISTRO MASIVO TEST ||||| 4 | */
        for ($i = 1; $i <= 2; $i++) {
            $datosTipousuario = [
                'nombre' => 'Tipo usuario masivo ' . time() . '_' . $i,
                'nivel' => rand(2, 3) // Nivel aleatorio entre 2 y 3
            ];
            
            $json = json_encode([
                'operacion' => 'registrar',
                'datos' => $datosTipousuario
            ]);

            $resultado = $this->tipousuario->testProcesarTipousuario($json);

            $this->assertIsArray($resultado, "Falló en la iteración $i: no se recibió un array");
            $this->assertEquals(1, $resultado['respuesta'], "Falló en la iteración $i: respuesta incorrecta");
            $this->assertEquals('incluir', $resultado['accion'], "Falló en la iteración $i: acción incorrecta");
        }
    }

    public function testActualizarTipousuario() { /*||||||  ACTUALIZAR DATOS  TEST ||||| 5 | */
        // Primero insertamos un tipo de usuario para tener un ID válido
        $datosTipousuario = [
            'nombre' => 'Tipo usuario para actualizar ' . time(),
            'nivel' => rand(1, 5)
        ];
        
        $jsonInsertar = json_encode([
            'operacion' => 'registrar',
            'datos' => $datosTipousuario
        ]);
        $resultadoInsertar = $this->tipousuario->testProcesarTipousuario($jsonInsertar);

        // Para esta prueba unitaria, usamos un ID fijo
        $datosActualizar = array_merge($datosTipousuario, [
            'id_tipo' => 2, // ID de ejemplo (1 está reservado)
            'estatus' => 1
        ]);
        
        $jsonActualizar = json_encode([
            'operacion' => 'actualizar',
            'datos' => $datosActualizar
        ]);

        $resultado = $this->tipousuario->testProcesarTipousuario($jsonActualizar);
        $this->assertIsArray($resultado);
        $this->assertEquals(1, $resultado['respuesta']);
        $this->assertEquals('actualizar', $resultado['accion']);
    }

    public function testEliminarTipousuario() { /*||||||  ELIMINAR  ||||| 6 | */
        // Primero insertamos un tipo de usuario para tener un ID válido
        $datosTipousuario = [
            'nombre' => 'Tipo usuario para eliminar ' . time(),
            'nivel' => rand(1, 5)
        ];
        
        $jsonInsertar = json_encode([
            'operacion' => 'registrar',
            'datos' => $datosTipousuario
        ]);
        $resultadoInsertar = $this->tipousuario->testProcesarTipousuario($jsonInsertar);

        // Para esta prueba unitaria, usamos un ID fijo
        $jsonEliminar = json_encode([
            'operacion' => 'eliminar',
            'datos' => [
                'id_tipo' => 2 // ID de ejemplo (1 está reservado)
            ]
        ]);

        $resultado = $this->tipousuario->testProcesarTipousuario($jsonEliminar);
        $this->assertIsArray($resultado);
        $this->assertEquals(1, $resultado['respuesta']);
        $this->assertEquals('eliminar', $resultado['accion']);
    }
}
?>