<?php
use PHPUnit\Framework\TestCase;

// Crear una copia temporal del archivo tipousuario.php
$tipousuarioOriginal = __DIR__ . '/../../../modelo/tipousuario.php';
$tipousuarioContent = file_get_contents($tipousuarioOriginal);

// Corregir la ruta de conexion.php para que funcione desde el directorio temporal
$conexionPath = realpath(__DIR__ . '/../../../modelo/conexion.php');
$tipousuarioContent = str_replace("require_once __DIR__ . '/conexion.php';", "require_once '$conexionPath';", $tipousuarioContent);

// Cambiar métodos privados a protegidos para que puedan ser accedidos por la clase hija
$tipousuarioContent = str_replace("private function registro", "protected function registro", $tipousuarioContent);
$tipousuarioContent = str_replace("private function actualizacion", "protected function actualizacion", $tipousuarioContent);
$tipousuarioContent = str_replace("private function eliminacion", "protected function eliminacion", $tipousuarioContent);

// Crear archivo temporal
$tempFile = tempnam(sys_get_temp_dir(), 'tipousuario_test_') . '.php';
file_put_contents($tempFile, $tipousuarioContent);

// Incluir el archivo temporal
require_once $tempFile;

/*|||||||||||||||||||||||||| INSTANCIA DE LA CLASE Y METODOS  |||||||||||||||||||||| */
class TipousuarioTestable extends tipousuario {
    
    public function testRegistro($datos) {  /*||| 1 ||| */
        return $this->registro($datos);
    }

    public function testActualizacion($datos) {  /*||| 2 ||| */
        return $this->actualizacion($datos);
    }

    public function testEliminacion($datos) {  /*||| 3 ||| */
        return $this->eliminacion($datos);
    }

    public function testConsultar() {  /*||| 4 ||| */
        return $this->consultar();
    }

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
        
        $resultado = $this->tipousuario->testConsultar();
        $this->assertIsArray($resultado);
        
        // Mostrar cantidad de resultados
        fwrite(STDERR, "Consulta de tipos de usuario completada. Resultados: " . count($resultado) . "\n");
    }

    public function testRegistrarTipousuario() {   /*||||||  REGISTRO NUEVO TEST ||||| 3 | */
        $datosTipousuario = [
            'nombre' => 'Tipo usuario de prueba ' . time(),
            'nivel' => rand(2, 3) // Nivel aleatorio entre 2 y 3
        ];
        
        $resultado = $this->tipousuario->testRegistro($datosTipousuario);
        $this->assertIsArray($resultado);
        $this->assertEquals(1, $resultado['respuesta']);
        $this->assertEquals('incluir', $resultado['accion']);
    }

    public function testRegistroMasivoTipousuario() { /*||||||  REGISTRO MASIVO TEST ||||| 4 | */
        for ($i = 1; $i <= 80; $i++) {
            $datosTipousuario = [
                'nombre' => 'Tipo usuario masivo ' . time() . '_' . $i,
                'nivel' => rand(2, 3) // Nivel aleatorio entre 2 y 3
            ];
            
            $resultado = $this->tipousuario->testRegistro($datosTipousuario);

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
        
        $resultadoInsertar = $this->tipousuario->testRegistro($datosTipousuario);

        // Para esta prueba unitaria, usamos un ID fijo
        $datosActualizar = array_merge($datosTipousuario, [
            'id_tipo' => 2, // ID de ejemplo (1 está reservado)
            'estatus' => 1
        ]);
        
        $resultado = $this->tipousuario->testActualizacion($datosActualizar);
        $this->assertIsArray($resultado);
        $this->assertEquals(1, $resultado['respuesta']);
        $this->assertEquals('actualizar', $resultado['accion']);
    }

    public function testEliminarTipousuario() { /*||||||  ELIMINAR  ||||| 6 | */
        // Primero insertamos un tipo de usuario para tener un ID válido
        $datosTipousuario = [
            'nombre' => 'Tipo usuario para eliminar ' . time(),
            'nivel' => rand(1, 2)
        ];
        
        $resultadoInsertar = $this->tipousuario->testRegistro($datosTipousuario);

        // Para esta prueba unitaria, usamos un ID fijo
        $datosEliminar = [
            'id_tipo' => 2 // ID de ejemplo (1 está reservado)
        ];

        $resultado = $this->tipousuario->testEliminacion($datosEliminar);
        $this->assertIsArray($resultado);
        $this->assertEquals(1, $resultado['respuesta']);
        $this->assertEquals('eliminar', $resultado['accion']);
    }
    
    public function testRegistrarTipousuarioConDatosInvalidos() { /*|||||| REGISTRO CON DATOS INVÁLIDOS ||||| 7 | */
        // Datos inválidos que deberían causar un error
        $datosTipousuarioInvalidos = [
            'nombre' => '', // Nombre vacío
            'nivel' => null   // Nivel nulo
        ];
        
        // Llamar al método y verificar que devuelve un error
        $resultado = $this->tipousuario->testRegistro($datosTipousuarioInvalidos);
        
        // Verificar que la respuesta indica un error
        $this->assertIsArray($resultado);
        $this->assertEquals(0, $resultado['respuesta']);
        $this->assertEquals('incluir', $resultado['accion']);
        // El mensaje puede variar dependiendo del motor de base de datos
        // pero debería contener información sobre el error
        $this->assertNotEmpty($resultado['mensaje']);
    }
}

// Limpiar archivo temporal
if (isset($tempFile) && file_exists($tempFile)) {
    unlink($tempFile);
}
?>