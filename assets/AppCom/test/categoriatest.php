<?php
use PHPUnit\Framework\TestCase;

// Crear una copia temporal del archivo categoria.php sin las dependencias problemáticas
$categoriaOriginal = __DIR__ . '/../../../modelo/categoria.php';
$categoriaContent = file_get_contents($categoriaOriginal);

// Corregir la ruta de conexion.php para que funcione desde el directorio temporal
$conexionPath = realpath(__DIR__ . '/../../../modelo/conexion.php');
$bitacoraPath = realpath(__DIR__ . '/../../../modelo/bitacora.php');
$categoriaContent = str_replace("require_once __DIR__ . '/conexion.php';", "require_once '$conexionPath';", $categoriaContent);
$categoriaContent = str_replace("require_once __DIR__ . '/bitacora.php';", "require_once '$bitacoraPath';", $categoriaContent);

// Cambiar métodos privados a protegidos para que puedan ser accedidos por la clase hija
$categoriaContent = str_replace("private function insertar", "protected function insertar", $categoriaContent);
$categoriaContent = str_replace("private function actualizar", "protected function actualizar", $categoriaContent);
$categoriaContent = str_replace("private function eliminarLogico", "protected function eliminarLogico", $categoriaContent);

// Crear archivo temporal
$tempFile = tempnam(sys_get_temp_dir(), 'categoria_test_') . '.php';
file_put_contents($tempFile, $categoriaContent);

// Incluir el archivo temporal
require_once $tempFile;

/*|||||||||||||||||||||||||| INSTANCIA DE LA CLASE Y METODOS  |||||||||||||||||||||| */
class CategoriaTestable extends Categoria {
    
    public function testInsertar($datos) {  /*||| 1 ||| */
        return $this->insertar($datos);
    }

    public function testActualizar($datos) {  /*||| 2 ||| */
        return $this->actualizar($datos);
    }

    public function testEliminarLogico($datos) {  /*||| 3 ||| */
        return $this->eliminarLogico($datos);
    }

    public function testConsultar() {  /*||| 4 ||| */
        return $this->consultar();
    }

    public function testProcesarCategoria($jsonDatos) {
        return $this->procesarCategoria($jsonDatos);
    }
}

/*||||||||||||||||||||||||||||||| CLASE DE TEST  |||||||||||||||||||||||||||||| */
class CategoriaTest extends TestCase {
    private CategoriaTestable $categoria;

    protected function setUp(): void {
        $this->categoria = new CategoriaTestable();
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

        $resultado = $this->categoria->testProcesarCategoria($json);
        $this->assertEquals(0, $resultado['respuesta']);
        $this->assertEquals('Operación no válida', $resultado['mensaje']);
    }

    public function testConsultarCategorias() { /*|||||| CONSULTAR CATEGORIAS ||||| 2 | */
        $resultado = $this->categoria->testConsultar();
        $this->assertIsArray($resultado);
        $this->assertNotEmpty($resultado);
        $this->assertArrayHasKey('id_categoria', $resultado[0]);
        $this->assertArrayHasKey('nombre', $resultado[0]);
    }

    public function testInsertarCategoriaValida() { /*|||||| INSERTAR CATEGORIA VÁLIDA ||||| 3 | */
        $datos = [
            'nombre' => 'Categoría de prueba ' . time()
        ];

        $resultado = $this->categoria->testInsertar($datos);
        $this->assertIsArray($resultado);
        $this->assertEquals(1, $resultado['respuesta']);
        $this->assertEquals('incluir', $resultado['accion']);
        $this->assertEquals('Categoría creada', $resultado['mensaje']);
    }

    public function testInsertarCategoriaSinNombre() { /*|||||| INSERTAR CATEGORIA SIN NOMBRE ||||| 4 | */
        $datos = [
            'nombre' => '' // Nombre vacío
        ];

        $this->expectException(Exception::class);
        $this->categoria->testInsertar($datos);
    }

    public function testActualizarCategoriaExistente() { /*|||||| ACTUALIZAR CATEGORIA EXISTENTE ||||| 5 | */
        $datos = [
            'id_categoria' => 1,
            'nombre' => 'Categoría actualizada ' . time()
        ];

        $resultado = $this->categoria->testActualizar($datos);
        $this->assertIsArray($resultado);
        $this->assertEquals(1, $resultado['respuesta']);
        $this->assertEquals('actualizar', $resultado['accion']);
        $this->assertEquals('Categoría modificada', $resultado['mensaje']);
    }

    public function testActualizarCategoriaInexistente() { /*|||||| ACTUALIZAR CATEGORIA INEXISTENTE ||||| 6 | */
        $datos = [
            'id_categoria' => 99999, // Categoría que no existe
            'nombre' => 'Categoría actualizada ' . time()
        ];

        $this->expectException(Exception::class);
        $this->categoria->testActualizar($datos);
    }

    public function testEliminarCategoriaExistente() { /*|||||| ELIMINAR CATEGORIA EXISTENTE ||||| 7 | */
        $datos = ['id_categoria' => 1];

        $resultado = $this->categoria->testEliminarLogico($datos);
        $this->assertIsArray($resultado);
        $this->assertEquals(1, $resultado['respuesta']);
        $this->assertEquals('eliminar', $resultado['accion']);
        $this->assertEquals('Categoría eliminada', $resultado['mensaje']);
    }

    public function testEliminarCategoriaInexistente() { /*|||||| ELIMINAR CATEGORIA INEXISTENTE ||||| 8 | */
        $datos = ['id_categoria' => 99999]; // Categoría que no existe

        $this->expectException(Exception::class);
        $this->categoria->testEliminarLogico($datos);
    }

    public function testProcesarCategoriaIncluir() { /*|||||| PROCESAR CATEGORIA INCLUIR ||||| 9 | */
        $categoriaDirecto = new Categoria();
        $json = json_encode([
            'operacion' => 'incluir',
            'datos' => [
                'nombre' => 'Categoría de prueba procesar ' . time()
            ]
        ]);

        $resultado = $categoriaDirecto->procesarCategoria($json);
        $this->assertIsArray($resultado);
        $this->assertEquals(1, $resultado['respuesta']);
        $this->assertEquals('incluir', $resultado['accion']);
    }

    public function testProcesarCategoriaActualizar() { /*|||||| PROCESAR CATEGORIA ACTUALIZAR ||||| 10 | */
        $categoriaDirecto = new Categoria();
        $json = json_encode([
            'operacion' => 'actualizar',
            'datos' => [
                'id_categoria' => 1,
                'nombre' => 'Categoría actualizada procesar ' . time()
            ]
        ]);

        $resultado = $categoriaDirecto->procesarCategoria($json);
        $this->assertIsArray($resultado);
        $this->assertEquals(1, $resultado['respuesta']);
        $this->assertEquals('actualizar', $resultado['accion']);
    }

    public function testProcesarCategoriaEliminar() { /*|||||| PROCESAR CATEGORIA ELIMINAR ||||| 11 | */
        $categoriaDirecto = new Categoria();
        $json = json_encode([
            'operacion' => 'eliminar',
            'datos' => [
                'id_categoria' => 1
            ]
        ]);

        $resultado = $categoriaDirecto->procesarCategoria($json);
        $this->assertIsArray($resultado);
        $this->assertEquals(1, $resultado['respuesta']);
        $this->assertEquals('eliminar', $resultado['accion']);
    }
}
?>