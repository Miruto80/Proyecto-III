<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../../../modelo/metodoentrega.php';

/*|||||||||||||||||||||||||| CLASE TESTEABLE  |||||||||||||||||||||| */
class MetodoEntregaTestable extends metodoentrega {

    // Exponemos los métodos privados para poder probarlos directamente
    public function testRegistrar($nombre, $descripcion) {
        return $this->registrar($nombre, $descripcion);
    }

    public function testModificar($id_entrega, $nombre, $descripcion) {
        return $this->modificar($id_entrega, $nombre, $descripcion);
    }

    public function testEliminar($id_entrega) {
        return $this->eliminar($id_entrega);
    }
}

/*||||||||||||||||||||||||||||||| CLASE DE TEST  |||||||||||||||||||||||||||||| */
class metodoentregatest extends TestCase {
    private MetodoEntregaTestable $metodoEntrega;

    protected function setUp(): void {
        $this->metodoEntrega = new MetodoEntregaTestable();
    }

    /* --- Caso 1: Operación inválida --- */
    public function testOperacionInvalida() {
        $json = json_encode([
            'operacion' => 'desconocida',
            'datos' => []
        ]);

        $resultado = $this->metodoEntrega->procesarMetodoEntrega($json);

        $this->assertEquals(0, $resultado['respuesta']);
        $this->assertEquals('Operación no válida', $resultado['mensaje']);
    }

    /* --- Caso 2: Consultar registros --- */
    public function testConsultar() {
        $resultado = $this->metodoEntrega->consultar();

        $this->assertIsArray($resultado);
        if (!empty($resultado)) {
            $this->assertArrayHasKey('id_entrega', $resultado[0]);
            $this->assertArrayHasKey('nombre', $resultado[0]);
            $this->assertArrayHasKey('descripcion', $resultado[0]);
            $this->assertArrayHasKey('estatus', $resultado[0]);
        }
    }

    /* --- Caso 3: Registrar un nuevo método de entrega --- */
    public function testRegistrarMetodoEntrega() {
        $nombre = 'Prueba Unidad';
        $descripcion = 'Método creado en test unitario';
        $resultado = $this->metodoEntrega->testRegistrar($nombre, $descripcion);

        $this->assertIsArray($resultado);
        $this->assertEquals(1, $resultado['respuesta']);
        $this->assertEquals('incluir', $resultado['accion']);
    }

    /* --- Caso 4: Modificar un método de entrega --- */
    public function testModificarMetodoEntrega() {
        $id_entrega = 1; // Ajustar según un registro existente en BD
        $nombre = 'Entrega Modificada';
        $descripcion = 'Actualización desde PHPUnit';
        $resultado = $this->metodoEntrega->testModificar($id_entrega, $nombre, $descripcion);

        $this->assertIsArray($resultado);
        $this->assertEquals(1, $resultado['respuesta']);
        $this->assertEquals('actualizar', $resultado['accion']);
    }

    /* --- Caso 5: Eliminar (desactivar) un método de entrega --- */
    public function testEliminarMetodoEntrega() {
        $id_entrega = 1; // Ajustar según un registro existente en BD
        $resultado = $this->metodoEntrega->testEliminar($id_entrega);

        $this->assertIsArray($resultado);
        $this->assertEquals(1, $resultado['respuesta']);
        $this->assertEquals('eliminar', $resultado['accion']);
    }
}
