<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../../../modelo/categoria.php';

/*|||||||||||||||||||||||||| INSTANCIA DE LA CLASE Y METODOS  |||||||||||||||||||||| */
class CategoriaTestable extends Categoria {
   
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

    public function testOperacionInvalida() { /*|||||| OPERACIONES |||| 1 || */
        $json = json_encode([
            'operacion' => 'desconocido',
            'datos' => []
        ]);

        $resultado = $this->categoria->testProcesarCategoria($json);
        $this->assertEquals(0, $resultado['respuesta']);
        $this->assertEquals('Operación no válida', $resultado['mensaje']);
    }

    public function testConsultarDevuelveArray() { /*|||||| CONSULTAR  ||||| 2 | */
        $resultado = $this->categoria->consultar();
        $this->assertIsArray($resultado);

        if (!empty($resultado)) {
            $this->assertArrayHasKey('id_categoria', $resultado[0]);
            $this->assertArrayHasKey('nombre', $resultado[0]);
        }
    }

    public function testInsertarCategoriaNueva() {   /*||||||  REGISTRO NUEVO TEST ||||| 3 | */
        $nombreCategoria = 'Categoría de prueba ' . time();
        $json = json_encode([
            'operacion' => 'incluir',
            'datos' => [
                'nombre' => $nombreCategoria
            ]
        ]);

        $resultado = $this->categoria->testProcesarCategoria($json);
        $this->assertIsArray($resultado);
        $this->assertEquals(1, $resultado['respuesta']);
        $this->assertEquals('incluir', $resultado['accion']);
    }

    public function testRegistroMasivoCategorias() { /*||||||  REGISTRO MASIVO TEST ||||| 4 | */
        for ($i = 1; $i <= 2; $i++) {
            $nombreCategoria = 'Categoría masiva ' . time() . '_' . $i;
            $json = json_encode([
                'operacion' => 'incluir',
                'datos' => [
                    'nombre' => $nombreCategoria
                ]
            ]);

            $resultado = $this->categoria->testProcesarCategoria($json);

            $this->assertIsArray($resultado, "Falló en la iteración $i: no se recibió un array");
            $this->assertEquals(1, $resultado['respuesta'], "Falló en la iteración $i: respuesta incorrecta");
            $this->assertEquals('incluir', $resultado['accion'], "Falló en la iteración $i: acción incorrecta");
        }
    }

    public function testActualizarCategoriaExistente() { /*||||||  ACTUALIZAR DATOS  TEST ||||| 5 | */
        // Primero insertamos una categoría para tener un ID válido
        $nombreCategoria = 'Categoría para actualizar ' . time();
        $jsonInsertar = json_encode([
            'operacion' => 'incluir',
            'datos' => [
                'nombre' => $nombreCategoria
            ]
        ]);
        $resultadoInsertar = $this->categoria->testProcesarCategoria($jsonInsertar);


        // Pero para esta prueba unitaria, usamos un ID fijo
        $jsonActualizar = json_encode([
            'operacion' => 'actualizar',
            'datos' => [
                'id_categoria' => 1, // ID 
                'nombre' => 'Categoría actualizada ' . time()
            ]
        ]);

        $resultado = $this->categoria->testProcesarCategoria($jsonActualizar);
        $this->assertIsArray($resultado);
        $this->assertEquals(1, $resultado['respuesta']);
        $this->assertEquals('actualizar', $resultado['accion']);
    }

    public function testEliminarCategoriaExistente() { /*||||||  ELIMINAR  ||||| 6 | */
        // Primero insertamos una categoría para tener un ID válido
        $nombreCategoria = 'Categoría para eliminar ' . time();
        $jsonInsertar = json_encode([
            'operacion' => 'incluir',
            'datos' => [
                'nombre' => $nombreCategoria
            ]
        ]);
        $resultadoInsertar = $this->categoria->testProcesarCategoria($jsonInsertar);

        //para esta prueba unitaria, usamos un ID fijo
        $jsonEliminar = json_encode([
            'operacion' => 'eliminar',
            'datos' => [
                'id_categoria' => 1 // ID 
            ]
        ]);

        $resultado = $this->categoria->testProcesarCategoria($jsonEliminar);
        $this->assertIsArray($resultado);
        $this->assertEquals(1, $resultado['respuesta']);
        $this->assertEquals('eliminar', $resultado['accion']);
    }

    public function testProcesarIncluirCategoria() { /*|||||| PROCESAR INCLUIR ||||| 7 | */
        $nombreCategoria = 'Categoría procesar incluir ' . time();
        $json = json_encode([
            'operacion' => 'incluir',
            'datos' => [
                'nombre' => $nombreCategoria
            ]
        ]);

        $resultado = $this->categoria->testProcesarCategoria($json);
        $this->assertIsArray($resultado);
        $this->assertEquals(1, $resultado['respuesta']);
        $this->assertEquals('incluir', $resultado['accion']);
    }

    public function testProcesarActualizarCategoria() { /*|||||| PROCESAR ACTUALIZAR ||||| 8 | */
        // Primero insertamos una categoría para tener un ID válido
        $nombreCategoria = 'Categoría para procesar actualizar ' . time();
        $jsonInsertar = json_encode([
            'operacion' => 'incluir',
            'datos' => [
                'nombre' => $nombreCategoria
            ]
        ]);
        $resultadoInsertar = $this->categoria->testProcesarCategoria($jsonInsertar);

        // para esta prueba unitaria, usamos un ID fijo
        $json = json_encode([
            'operacion' => 'actualizar',
            'datos' => [
                'id_categoria' => 1, // ID 
                'nombre' => 'Categoría procesar actualizada ' . time()
            ]
        ]);

        $resultado = $this->categoria->testProcesarCategoria($json);
        $this->assertIsArray($resultado);
        $this->assertEquals(1, $resultado['respuesta']);
        $this->assertEquals('actualizar', $resultado['accion']);
    }

    public function testProcesarEliminarCategoria() { /*|||||| PROCESAR ELIMINAR ||||| 9 | */
        // Primero insertamos una categoría para tener un ID válido
        $nombreCategoria = 'Categoría para procesar eliminar ' . time();
        $jsonInsertar = json_encode([
            'operacion' => 'incluir',
            'datos' => [
                'nombre' => $nombreCategoria
            ]
        ]);
        $resultadoInsertar = $this->categoria->testProcesarCategoria($jsonInsertar);

        // Para esta prueba unitaria, usamos un ID fijo
        $json = json_encode([
            'operacion' => 'eliminar',
            'datos' => [
                'id_categoria' => 1 // ID 
            ]
        ]);

        $resultado = $this->categoria->testProcesarCategoria($json);
        $this->assertIsArray($resultado);
        $this->assertEquals(1, $resultado['respuesta']);
        $this->assertEquals('eliminar', $resultado['accion']);
    }
}
?>    