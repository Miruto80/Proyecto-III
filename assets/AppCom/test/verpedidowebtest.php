<?php
use PHPUnit\Framework\TestCase;


require_once __DIR__ . '/../../../modelo/verpedidoweb.php';


class verpedidowebtest extends TestCase {

    /** @var VentaWeb */
    private $venta;

 
    protected function setUp(): void {
      
        $this->venta = new VentaWeb();
    }

   
    public function testOperacionInvalida() {
        echo "\n Ejecutando testOperacionInvalida...\n";

        $json = json_encode([
            'operacion' => 'valor_invalido',
            'datos' => []
        ]);

        echo " Enviando JSON inválido: $json\n";

        $resultado = $this->venta->procesarPedido($json);

        echo " Resultado recibido: " . json_encode($resultado) . "\n";

        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('success', $resultado);
        $this->assertArrayHasKey('message', $resultado);
        $this->assertFalse($resultado['success']);
        $this->assertEquals('Operación no válida.', $resultado['message']);
    }

    public function testObtenerMetodosPago() {
        echo "\n Ejecutando testObtenerMetodosPago...\n";

        $resultado = $this->venta->obtenerMetodosPago();

        echo " Métodos de pago obtenidos: " . json_encode($resultado) . "\n";

        $this->assertIsArray($resultado, 'El resultado debe ser un array');

        if (!empty($resultado)) {
            $this->assertArrayHasKey('id_metodopago', $resultado[0]);
            $this->assertArrayHasKey('nombre', $resultado[0]);
        }
    }

  
    public function testRegistrarPedidoCompletoSimulado() {
        echo "\n Ejecutando testRegistrarPedidoCompletoSimulado...\n";

        $cantidadIteraciones = 2;

        for ($i = 0; $i < $cantidadIteraciones; $i++) {
            echo "[INFO] Iteración #$i\n";

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
                'referencia_bancaria' => '1223',
                'telefono_emisor' => '04245196914',
                'banco_destino' => 'Banco de venezuela',
                'banco' => 'Banco de venezuela',
                'monto' => 2000.00,
                'monto_usd' => 50.00,
                'imagen' => '',
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

            echo " JSON generado: $json\n";

            $this->assertJson($json);
            $decoded = json_decode($json, true);
            $this->assertEquals('registrar_pedido', $decoded['operacion']);
            $this->assertArrayHasKey('datos', $decoded);
            $this->assertArrayHasKey('id_persona', $decoded['datos']);
            $this->assertArrayHasKey('carrito', $decoded['datos']);
            $this->assertNotEmpty($decoded['datos']['carrito']);
        }
    }

    public function testFallaIntencionalParaVerificarAlertas() {
        echo "\n[TEST] Ejecutando testFallaIntencionalParaVerificarAlertas...\n";
        $valorEsperado = 10;
        $valorObtenido = 5;

        echo " Comparando valores esperados ($valorEsperado) vs obtenidos ($valorObtenido)\n";

        // falla intencionalmente
        $this->assertEquals(
            $valorEsperado,
            $valorObtenido,
            " Esta prueba debe fallar "
        );
    }
}
