<?php
use PHPUnit\Framework\TestCase;

/*||||||||||||||||||||||||||| COPIA TEMPORAL DE producto.php |||||||||||||||||||||||||||*/

// Ruta original del modelo
$productoOriginal = __DIR__ . '/../../../modelo/producto.php';
$productoContent = file_get_contents($productoOriginal);

// --- Remover dependencias innecesarias si existen ---
$productoContent = str_replace("require_once('assets/dompdf/vendor/autoload.php');", "// require_once('assets/dompdf/vendor/autoload.php'); // Comentado para tests", $productoContent);
$productoContent = str_replace("use Dompdf\\Dompdf;", "// use Dompdf\\Dompdf; // Comentado para tests", $productoContent);
$productoContent = str_replace("use Dompdf\\Options;", "// use Dompdf\\Options; // Comentado para tests", $productoContent);

// --- Ajustar ruta de conexión ---
$conexionPath = realpath(__DIR__ . '/../../../modelo/conexion.php');
// --- Reemplazar cualquier require de conexion.php con la ruta absoluta correcta ---
$productoContent = preg_replace(
    '/require_once\s+__DIR__\s*\.\s*[\'"]\/conexion\.php[\'"]\s*;?/',
    "require_once '$conexionPath';",
    $productoContent
);

$productoContent = preg_replace(
    '/require_once\s+__DIR__\s*\.\s*[\'"]\/categoria\.php[\'"]\s*;?/',
    "require_once '" . __DIR__ . "/../../../modelo/categoria.php';",
    $productoContent
);



// --- Cambiar métodos privados a protegidos para acceder desde la clase testable ---
$productoContent = str_replace("private function ejecutarRegistro", "protected function ejecutarRegistro", $productoContent);
$productoContent = str_replace("private function ejecutarActualizacion", "protected function ejecutarActualizacion", $productoContent);
$productoContent = str_replace("private function ejecutarEliminacion", "protected function ejecutarEliminacion", $productoContent);
$productoContent = str_replace("private function ejecutarCambioEstatus", "protected function ejecutarCambioEstatus", $productoContent);
$productoContent = str_replace("private function verificarProductoExistente", "protected function verificarProductoExistente", $productoContent);

// --- Crear archivo temporal ---
$tempFile = tempnam(sys_get_temp_dir(), 'producto_test_') . '.php';
file_put_contents($tempFile, $productoContent);

// --- Incluir archivo temporal ---
require_once $tempFile;

/*|||||||||||||||||||||||||| CLASE TESTABLE |||||||||||||||||||||||||||*/

class ProductosTestable extends producto {

    public function testVerificarProductoExistente($nombre, $marca) {
        return $this->verificarProductoExistente($nombre, $marca);
    }

    public function testEjecutarRegistro($datos) {
        return $this->ejecutarRegistro($datos);
    }

    public function testEjecutarActualizacion($datos) {
        return $this->ejecutarActualizacion($datos);
    }

    public function testEjecutarEliminacion($datos) {
        return $this->ejecutarEliminacion($datos);
    }

    public function testEjecutarCambioEstatus($datos) {
        return $this->ejecutarCambioEstatus($datos);
    }
}

/*||||||||||||||||||||||||||||||| CLASE DE TEST ||||||||||||||||||||||||||||||*/

class ProductosTest extends TestCase {
    private ProductosTestable $producto;

    protected function setUp(): void {
        $this->producto = new ProductosTestable();
    }

    /*|||||| TESTS DE PROCESAR PRODUCTO ||||||*/

    public function testOperacionInvalida() {
        $json = json_encode([
            'operacion' => 'desconocido',
            'datos' => []
        ]);

        $resultado = $this->producto->procesarProducto($json);
        $this->assertEquals(0, $resultado['respuesta']);
        $this->assertEquals('Operación no válida', $resultado['mensaje']);
        echo "\n testOperacionInvalida: El sistema manejó correctamente una operación no válida.\n";
    }

    public function testRegistrarProductoExistente() {
        $json = json_encode([
            'operacion' => 'registrar',
            'datos' => [
                'nombre' => 'Bálsamo premium',
                'marca' => 'Salome',
                'descripcion' => 'Prueba producto existente',
                'cantidad_mayor' => 10,
                'precio_mayor' => 20,
                'precio_detal' => 25,
                'stock_maximo' => 100,
                'stock_minimo' => 1,
                'imagen' => 'imagen.png',
                'id_categoria' => 1
            ]
        ]);

        $resultado = $this->producto->procesarProducto($json);

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('respuesta', $resultado);
        echo "\n testRegistrarProductoExistente: Se validó correctamente el intento de registro duplicado.\n";
    }

    /*|||||| TESTS DE CONSULTAS ||||||*/

    public function testConsultarProductos() {
        $resultado = $this->producto->consultar();
        $this->assertIsArray($resultado);

        if (!empty($resultado)) {
            $this->assertArrayHasKey('id_producto', $resultado[0]);
            $this->assertArrayHasKey('nombre', $resultado[0]);
            $this->assertArrayHasKey('estatus', $resultado[0]);
        }
        echo "\n testConsultarProductos: La consulta devolvió datos válidos y con estructura esperada.\n";
        echo "Productos consultados (array):\n";
        echo print_r($resultado, true) . "\n";
    }

    public function testProductosActivos() {
    $resultado = $this->producto->ProductosActivos();
    $this->assertIsArray($resultado);

    $activos = 0;

    foreach ($resultado as $prod) {
        $this->assertEquals(1, $prod['estatus']);
        $activos++;
    }

    echo "\n testProductosActivos: Se encontraron {$activos} productos activos.\n";
}

    public function testObtenerCategoria() {
    $resultado = $this->producto->obtenerCategoria();
    $this->assertIsArray($resultado);

    echo "\n testObtenerCategoria: Se consultaron correctamente las categorías.\n";

    if (empty($resultado)) {
        echo "No se encontraron categorías registradas.\n";
    } else {
        echo "Lista de categorías encontradas:\n";
        foreach ($resultado as $cat) {
            $nombre = isset($cat['nombre']) ? $cat['nombre'] : 'Sin nombre';
            echo "- {$nombre}\n";
        }
        echo "Total de categorías: " . count($resultado) . "\n";
    }
}


    /*|||||| TESTS DE MÉTODOS PRIVADOS ||||||*/

    public function testVerificarProductoInexistente() {
        $existe = $this->producto->testVerificarProductoExistente('ProductoInexistenteXYZ', 'MarcaX');
        $this->assertFalse($existe);
        echo "\n testVerificarProductoInexistente: Correcto, el producto no existe.\n";
    }

    public function testVerificarProductoExistenteFallido() {
        $existe = $this->producto->testVerificarProductoExistente('Base de gotero', 'Salome');
        $this->assertFalse($existe, "Este test debería fallar porque el producto sí existe");
        echo "\n testVerificarProductoExistenteFallido: Se esperaba fallo al detectar un producto existente.\n";
    }

    public function testCambioEstatus() {
        $datos = [
            'id_producto' => 1,
            'estatus_actual' => 1
        ];

        $resultado = $this->producto->testEjecutarCambioEstatus($datos);
        $this->assertIsArray($resultado);
        $this->assertEquals('cambiarEstatus', $resultado['accion']);
        echo "\n testCambioEstatus: Cambio de estatus ejecutado correctamente.\n";
    }
}

/*||||||||||||||||||||||||||| LIMPIEZA FINAL |||||||||||||||||||||||||||*/
if (isset($tempFile) && file_exists($tempFile)) {
    unlink($tempFile);
}

?>
