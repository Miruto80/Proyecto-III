<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../../../modelo/metodopago.php';

/*|||||||||||||||||||||||||| CLASE TESTEABLE  |||||||||||||||||||||| */
class MetodoPagoTestable extends MetodoPago {
    // Exponemos los métodos privados para poder probarlos
    public function testRegistrar($nombre, $descripcion) {
        return $this->registrar($nombre, $descripcion);
    }

    public function testModificar($id_metodopago, $nombre, $descripcion) {
        return $this->modificar($id_metodopago, $nombre, $descripcion);
    }

    public function testEliminar($id_metodopago) {
        return $this->eliminar($id_metodopago);
    }
}

/*||||||||||||||||||||||||||||||| CLASE DE TEST  |||||||||||||||||||||||||||||| */
class metodopagotest extends TestCase {
    private MetodoPagoTestable $metodoPago;

    protected function setUp(): void {
        $this->metodoPago = new MetodoPagoTestable();
    }

    /* --- Caso 1: Operación inválida --- */
    public function testOperacionInvalida() {
        $json = json_encode([
            'operacion' => 'desconocida',
            'datos' => []
        ]);

        $resultado = $this->metodoPago->procesarMetodoPago($json);

        $this->assertEquals(0, $resultado['respuesta']);
        $this->assertEquals('Operación no válida', $resultado['mensaje']);
    }

    /* --- Caso 2: Consultar registros --- */
    public function testConsultar() {
        $resultado = $this->metodoPago->consultar();

        $this->assertIsArray($resultado);
        if (!empty($resultado)) {
            $this->assertArrayHasKey('id_metodopago', $resultado[0]);
            $this->assertArrayHasKey('nombre', $resultado[0]);
            $this->assertArrayHasKey('descripcion', $resultado[0]);
            $this->assertArrayHasKey('estatus', $resultado[0]);
        }
    }

    /* --- Caso 3: Registrar un nuevo método de pago --- */
    public function testRegistrarMetodoPago() {
        $nombre = 'PagoPrueba';
        $descripcion = 'Método creado en test unitario';
        $resultado = $this->metodoPago->testRegistrar($nombre, $descripcion);

        $this->assertIsArray($resultado);
        $this->assertEquals(1, $resultado['respuesta']);
        $this->assertEquals('incluir', $resultado['accion']);
    }

    /* --- Caso 4: Modificar un método de pago --- */
    public function testModificarMetodoPago() {
        $id_metodopago = 1; 
        $nombre = 'Pago Modificado';
        $descripcion = 'Actualización desde PHPUnit';
        $resultado = $this->metodoPago->testModificar($id_metodopago, $nombre, $descripcion);

        $this->assertIsArray($resultado);
        $this->assertEquals(1, $resultado['respuesta']);
        $this->assertEquals('actualizar', $resultado['accion']);
    }

    /* --- Caso 5: Eliminar (desactivar) un método de pago --- */
    public function testEliminarMetodoPago() {
        $id_metodopago = 1; //  Ajusta este ID según un registro existente en BD
        $resultado = $this->metodoPago->testEliminar($id_metodopago);

        $this->assertIsArray($resultado);
        $this->assertEquals(1, $resultado['respuesta']);
        $this->assertEquals('eliminar', $resultado['accion']);
    }

    /* --- Caso 6: Obtener métodos (id_metodopago = 1) --- */
    public function testObtenerMetodos() {
        $resultado = $this->metodoPago->obtenerMetodos();

        $this->assertIsArray($resultado);
        if (!empty($resultado)) {
            $this->assertEquals(1, $resultado[0]['id_metodopago']);
            $this->assertArrayHasKey('nombre', $resultado[0]);
        }
    }
}
