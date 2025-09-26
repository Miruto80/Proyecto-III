<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../../../modelo/producto.php';

/*|||||||||||||||||||||||||| INSTANCIA DE LA CLASE Y METODOS  |||||||||||||||||||||| */
class ProductoTestable extends producto {

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

/*||||||||||||||||||||||||||||||| CLASE DE TEST  |||||||||||||||||||||||||||||| */
class ProductosTest extends TestCase {
    private ProductoTestable $producto;

    protected function setUp(): void {
        $this->producto = new ProductoTestable();
    }

    /* ----------- PRUEBAS DE PROCESAR ----------- */

    public function testOperacionInvalida() {
        $json = json_encode([
            'operacion' => 'desconocido',
            'datos' => []
        ]);

        $resultado = $this->producto->procesarProducto($json);
        $this->assertEquals(0, $resultado['respuesta']);
        $this->assertEquals('Operación no válida', $resultado['mensaje']);
    }

    public function testRegistrarProductoExistente() {
        $json = json_encode([
            'operacion' => 'registrar',
            'datos' => [
                'nombre' => 'Cable #12',
                'marca' => 'Prysmian',
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
    }

    /* ----------- PRUEBAS DE CONSULTAR ----------- */

    public function testConsultarProductos() {
        $resultado = $this->producto->consultar();
        $this->assertIsArray($resultado);

        if (!empty($resultado)) {
            $this->assertArrayHasKey('id_producto', $resultado[0]);
            $this->assertArrayHasKey('nombre', $resultado[0]);
            $this->assertArrayHasKey('estatus', $resultado[0]);
        }
    }

    public function testProductosActivos() {
        $resultado = $this->producto->ProductosActivos();
        $this->assertIsArray($resultado);

        foreach ($resultado as $prod) {
            $this->assertEquals(1, $prod['estatus']);
        }
    }

    public function testMasVendidos() {
        $resultado = $this->producto->MasVendidos();
        $this->assertIsArray($resultado);
    }

    public function testObtenerCategoria() {
        $resultado = $this->producto->obtenerCategoria();
        $this->assertIsArray($resultado);
    }

    /* ----------- PRUEBAS DE MÉTODOS PRIVADOS EXPUESTOS ----------- */

    public function testVerificarProductoInexistente() {
        $existe = $this->producto->testVerificarProductoExistente('ProductoInexistenteXYZ', 'MarcaX');
        $this->assertFalse($existe);
    }

    public function testCambioEstatus() {
        $datos = [
            'id_producto' => 1,
            'estatus_actual' => 1
        ];

        $resultado = $this->producto->testEjecutarCambioEstatus($datos);
        $this->assertIsArray($resultado);
        $this->assertEquals('cambiarEstatus', $resultado['accion']);
    }
}
