<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../../modelo/verpedidoweb.php';

class verpedidowebtest extends TestCase {

    private $venta;

    protected function setUp(): void {
        $this->venta = new VentaWeb();
    }

    /** @test */
    public function testOperacionInvalida() {
        $json = json_encode([
            'operacion' => 'algo_raro',
            'datos' => []
        ]);

        $resultado = $this->venta->procesarPedido($json);

        $this->assertIsArray($resultado);
        $this->assertFalse($resultado['success']);
        $this->assertEquals('Operación no válida.', $resultado['message']);
    }

    /** @test */
    public function testObtenerMetodosPago() {
        $resultado = $this->venta->obtenerMetodosPago();
        $this->assertIsArray($resultado);
    }

    /** @test */
    public function testObtenerMetodosEntrega() {
        $resultado = $this->venta->obtenerMetodosEntrega();
        $this->assertIsArray($resultado);
    }

    /** @test */
    public function testRegistrarPedidoCompleto() {
        //  Necesitas datos válidos en tu BD para que esto funcione
        $json = json_encode([
            'operacion' => 'registrar_pedido',
            'datos' => [
                'id_persona' => 1,             // <-- cambia según tu BD
                'id_metodoentrega' => 1,       // <-- debe existir
                'direccion_envio' => 'Av. Prueba #123',
                'sucursal_envio' => null,
                'tipo' => 2,
                'estado' => 'pendiente',
                'precio_total_usd' => 50.00,
                'precio_total_bs' => 2000.00,
                'id_metodopago' => 1,          // <-- debe existir
                'referencia_bancaria' => '123456',
                'telefono_emisor' => '04120000000',
                'banco_destino' => 'Banco B',
                'banco' => 'Banco A',
                'monto' => 2000.00,
                'monto_usd' => 50.00,
                'imagen' => 'comprobante.png',
                'carrito' => [
                    [
                        'id' => 1,              // <-- id_producto existente
                        'cantidad' => 1,
                        'cantidad_mayor' => 3,
                        'precio_mayor' => 45.00,
                        'precio_detal' => 50.00
                    ]
                ]
            ]
        ]);

        $resultado = $this->venta->procesarPedido($json);

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('success', $resultado);

        if ($resultado['success']) {
            $this->assertArrayHasKey('id_pedido', $resultado);
        } else {
            // Nos aseguramos que devuelva un mensaje de error
            $this->assertArrayHasKey('message', $resultado);
        }
    }
}
