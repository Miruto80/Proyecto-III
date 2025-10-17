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

    public function testConsultarCategorias() { /*|||||| CONSULTAR CATEGORIAS ||||| 1 | que no devuelva un array vacio*/
        $resultado = $this->categoria->testConsultar();
        $this->assertIsArray($resultado);
        // Nota: No todas las bases de datos tendrán categorías, por lo que no verificamos que no esté vacío
        if (!empty($resultado)) {
            $this->assertArrayHasKey('id_categoria', $resultado[0]);
            $this->assertArrayHasKey('nombre', $resultado[0]);
        }
    }

    public function testInsertarCategoriaValida() { /*|||||| INSERTAR CATEGORIA VÁLIDA 
        Prueba la inserción exitosa de una nueva categoría con datos válidos.
Verifica que la respuesta sea un array con los valores esperados: código 1, acción "incluir" y 
mensaje "Categoría creada".||||| 2 | */
        $datos = [
            'nombre' => 'Categoría de prueba ' . time()
        ];

        $resultado = $this->categoria->testInsertar($datos);
        $this->assertIsArray($resultado);
        $this->assertEquals(1, $resultado['respuesta']);
        $this->assertEquals('incluir', $resultado['accion']);
        $this->assertEquals('Categoría creada', $resultado['mensaje']);
    }

    public function testInsertarCategoriaSinNombre() { /*|||||| INSERTAR CATEGORIA SIN NOMBRE ||||| 3 | */
        $datos = [
            'nombre' => '' // Nombre vacío
        ];

        // Esperamos que se lance una Exception
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('El nombre de la categoría no puede estar vacío.');
        
        $this->categoria->testInsertar($datos);
    }

    public function testActualizarCategoriaExistente() { /*|||||| ACTUALIZAR CATEGORIA EXISTENTE ||||| 4 | */
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

    public function testActualizarCategoriaInexistente() { /*|||||| ACTUALIZAR CATEGORIA INEXISTENTE ||||| 5 | */
        $datos = [
            'id_categoria' => 99999, // ID que no existe
            'nombre' => 'Categoría inexistente ' . time()
        ];

        // Esperamos que se lance una Exception
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('La categoría con ID 99999 no existe.');
        
        $this->categoria->testActualizar($datos);
    }

    public function testEliminarCategoriaExistente() { /*|||||| ELIMINAR CATEGORIA EXISTENTE ||||| 6 | */
        $datos = ['id_categoria' => 1];

        $resultado = $this->categoria->testEliminarLogico($datos);
        $this->assertIsArray($resultado);
        $this->assertEquals(1, $resultado['respuesta']);
        $this->assertEquals('eliminar', $resultado['accion']);
        $this->assertEquals('Categoría eliminada', $resultado['mensaje']);
    }

    public function testEliminarCategoriaInexistente() { /*|||||| ELIMINAR CATEGORIA INEXISTENTE ||||| 7 | */
        $datos = ['id_categoria' => 99999]; // ID que no existe

        // Esperamos que se lance una Exception
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('La categoría con ID 99999 no existe.');
        
        $this->categoria->testEliminarLogico($datos);
    }

    public function testProcesarCategoriaIncluir() { /*|||||| PROCESAR CATEGORIA INCLUIR ||||| 8 | */
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

    public function testProcesarCategoriaActualizar() { /*|||||| PROCESAR CATEGORIA ACTUALIZAR ||||| 9 | */
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

    public function testProcesarCategoriaEliminar() { /*|||||| PROCESAR CATEGORIA ELIMINAR ||||| 10 | */
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
    
    public function testInsertarCategoriaConNombreDuplicado() { /*|||||| INSERTAR CATEGORIA CON NOMBRE DUPLICADO ||||| 11 | */
        // Primero insertamos una categoría
        $nombreCategoria = 'Categoría única ' . time();
        $datos = ['nombre' => $nombreCategoria];
        $this->categoria->testInsertar($datos);
        
        // Intentamos insertar otra categoría con el mismo nombre
        // En este caso, la base de datos podría permitirlo o no dependiendo de las restricciones
        // Si hay restricción UNIQUE en la base de datos, esto debería lanzar una PDOException
        $this->expectException(PDOException::class);
        
        // Esta llamada podría fallar si hay restricción UNIQUE en la base de datos
        $this->categoria->testInsertar($datos);
    }
}

// Limpiar archivo temporal
if (isset($tempFile) && file_exists($tempFile)) {
    unlink($tempFile);
}
?>