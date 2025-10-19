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
public function testRegistrarPedidoCompletoSimulado() {
    $cantidadIteraciones = 1;

    for ($i = 0; $i < $cantidadIteraciones; $i++) {
        $datos = [
            'id_persona' => 1,
            'id_metodoentrega' => 1,
            'direccion_envio' => "Av. Prueba #$i",
            'sucursal_envio' => null,
            'tipo' => 2,
            'estado' => 'pendiente',
            'precio_total_usd' => 50.00,
            'precio_total_bs' => 2000.00,
            'id_metodopago' => 1,
            'referencia_bancaria' => '123456',
            'telefono_emisor' => '04120000000',
            'banco_destino' => 'Banco B',
            'banco' => 'Banco A',
            'monto' => 2000.00,
            'monto_usd' => 50.00,
            'imagen' => 'comprobante.png',
            'carrito' => [
                [
                    'id' => 1,
                    'cantidad' => 1,
                    'cantidad_mayor' => 3,
                    'precio_mayor' => 45.00,
                    'precio_detal' => 50.00
                ]
            ]
        ];

        $json = json_encode([
            'operacion' => 'registrar_pedido',
            'datos' => $datos
        ]);

        // Aquí puedes validar la estructura sin insertar
        $this->assertJson($json);
        $decoded = json_decode($json, true);
        $this->assertEquals('registrar_pedido', $decoded['operacion']);
        $this->assertArrayHasKey('datos', $decoded);
    }
}

}
