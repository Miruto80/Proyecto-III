<?php
use PHPUnit\Framework\TestCase;

// Ajusta la ruta según tu estructura
require_once __DIR__ . '/../../../modelo/pedidoweb.php';

class pedidowebtest extends TestCase {

    private $pedido;

    protected function setUp(): void {
        $this->pedido = new pedidoWeb();
    }

    /** @test */
    public function testOperacionInvalida() {
        $json = json_encode([
            'operacion' => 'desconocida',
            'datos' => []
        ]);

        $resultado = $this->pedido->procesarPedidoweb($json);

        $this->assertIsArray($resultado);
        $this->assertEquals(0, $resultado['respuesta']);
        $this->assertEquals('Operación no válida', $resultado['mensaje']);
    }

    /** @test */
    public function testConsultarPedidosCompletos() {
        $resultado = $this->pedido->consultarPedidosCompletos();

        $this->assertIsArray($resultado);
        // Incluso si no hay pedidos, fetchAll devuelve array vacío
    }

    /** @test */
    public function testConsultarDetallesPedido() {
        // prueba con un ID que probablemente no exista
        $resultado = $this->pedido->consultarDetallesPedido(99999);

        $this->assertIsArray($resultado);
    }
}
