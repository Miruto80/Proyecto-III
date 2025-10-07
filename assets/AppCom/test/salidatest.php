<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../../../modelo/salida.php';

/*|||||||||||||||||||||||||| INSTANCIA DE LA CLASE Y METODOS  |||||||||||||||||||||| */
class SalidaTestable extends Salida {
    
    public function testEjecutarRegistro($datos) {  /*||| 1 ||| */
        return $this->ejecutarRegistro($datos);
    }

    public function testEjecutarActualizacion($datos) {  /*||| 2 ||| */
        return $this->ejecutarActualizacion($datos);
    }

    public function testEjecutarEliminacion($datos) {  /*||| 3 ||| */
        return $this->ejecutarEliminacion($datos);
    }

    public function testVerificarStock($id_producto) {  /*||| 4 ||| */
        return $this->verificarStock($id_producto);
    }

    public function testConsultarVentas() {  /*||| 5 ||| */
        return $this->consultarVentas();
    }

    public function testConsultarCliente($datos) {  /*||| 6 ||| */
        return $this->consultarCliente($datos);
    }

    public function testRegistrarCliente($datos) {  /*||| 7 ||| */
        return $this->registrarCliente($datos);
    }

    public function testConsultarProductos() {  /*||| 8 ||| */
        return $this->consultarProductos();
    }

    public function testConsultarMetodosPago() {  /*||| 9 ||| */
        return $this->consultarMetodosPago();
    }

    public function testConsultarDetallesPedido($id_pedido) {  /*||| 10 ||| */
        return $this->consultarDetallesPedido($id_pedido);
    }

    public function testConsultarClienteDetalle($id_pedido) {  /*||| 11 ||| */
        return $this->consultarClienteDetalle($id_pedido);
    }

    public function testConsultarMetodosPagoVenta($id_pedido) {  /*||| 12 ||| */
        return $this->consultarMetodosPagoVenta($id_pedido);
    }

    public function testRegistrarVentaPublico($datos) {  /*||| 13 ||| */
        return $this->registrarVentaPublico($datos);
    }

    public function testActualizarVentaPublico($datos) {  /*||| 14 ||| */
        return $this->actualizarVentaPublico($datos);
    }

    public function testEliminarVentaPublico($datos) {  /*||| 15 ||| */
        return $this->eliminarVentaPublico($datos);
    }

    public function testConsultarClientePublico($datos) {  /*||| 16 ||| */
        return $this->consultarClientePublico($datos);
    }

    public function testRegistrarClientePublico($datos) {  /*||| 17 ||| */
        return $this->registrarClientePublico($datos);
    }
}

/*||||||||||||||||||||||||||||||| CLASE DE TEST  |||||||||||||||||||||||||||||| */
class SalidaTest extends TestCase {
    private SalidaTestable $salida;

    protected function setUp(): void {
        $this->salida = new SalidaTestable();
    }

    public function testOperacionInvalida() { /*|||||| OPERACIONES |||| 1 || */
        $salidaDirecto = new Salida(); 
        $json = json_encode([
            'operacion' => 'desconocido',
            'datos' => []
        ]);

        $resultado = $salidaDirecto->procesarVenta($json);
        $this->assertEquals(0, $resultado['respuesta']);
        $this->assertEquals('Operación no válida', $resultado['mensaje']);
    }

    public function testConsultarVentas() { /*|||||| CONSULTAR VENTAS ||||| 2 | */
        $resultado = $this->salida->testConsultarVentas();
        $this->assertIsArray($resultado);

        if (!empty($resultado)) {
            $this->assertArrayHasKey('id_pedido', $resultado[0]);
            $this->assertArrayHasKey('cliente', $resultado[0]);
            $this->assertArrayHasKey('fecha', $resultado[0]);
            $this->assertArrayHasKey('estado', $resultado[0]);
            $this->assertArrayHasKey('precio_total', $resultado[0]);
        }
    }

    public function testConsultarProductos() { /*|||||| CONSULTAR PRODUCTOS ||||| 3 | */
        $resultado = $this->salida->testConsultarProductos();
        $this->assertIsArray($resultado);

        if (!empty($resultado)) {
            $this->assertArrayHasKey('id_producto', $resultado[0]);
            $this->assertArrayHasKey('nombre', $resultado[0]);
            $this->assertArrayHasKey('descripcion', $resultado[0]);
            $this->assertArrayHasKey('marca', $resultado[0]);
            $this->assertArrayHasKey('precio_detal', $resultado[0]);
            $this->assertArrayHasKey('stock_disponible', $resultado[0]);
        }
    }

    public function testConsultarMetodosPago() { /*|||||| CONSULTAR MÉTODOS DE PAGO ||||| 4 | */
        $resultado = $this->salida->testConsultarMetodosPago();
        $this->assertIsArray($resultado);

        if (!empty($resultado)) {
            $this->assertArrayHasKey('id_metodopago', $resultado[0]);
            $this->assertArrayHasKey('nombre', $resultado[0]);
            $this->assertArrayHasKey('descripcion', $resultado[0]);
        }
    }

    public function testConsultarClientePorCedula() { /*|||||| CONSULTAR CLIENTE ||||| 5 | */
        $datos = ['cedula' => '12345678'];
        $resultado = $this->salida->testConsultarCliente($datos);
        $this->assertIsArray($resultado);

        if (!empty($resultado)) {
            $this->assertArrayHasKey('id_persona', $resultado);
            $this->assertArrayHasKey('cedula', $resultado);
            $this->assertArrayHasKey('nombre', $resultado);
            $this->assertArrayHasKey('apellido', $resultado);
            $this->assertArrayHasKey('correo', $resultado);
            $this->assertArrayHasKey('telefono', $resultado);
        }
    }

    public function testConsultarDetallesPedido() { /*|||||| CONSULTAR DETALLES PEDIDO ||||| 6 | */
        $id_pedido = 1;
        $resultado = $this->salida->testConsultarDetallesPedido($id_pedido);
        $this->assertIsArray($resultado);

        if (!empty($resultado)) {
            $this->assertArrayHasKey('cantidad', $resultado[0]);
            $this->assertArrayHasKey('precio_unitario', $resultado[0]);
            $this->assertArrayHasKey('nombre_producto', $resultado[0]);
        }
    }

    public function testConsultarClienteDetalle() { /*|||||| CONSULTAR CLIENTE DETALLE ||||| 7 | */
        $id_pedido = 1;
        $resultado = $this->salida->testConsultarClienteDetalle($id_pedido);
        $this->assertIsArray($resultado);

        if (!empty($resultado)) {
            $this->assertArrayHasKey('cedula', $resultado);
            $this->assertArrayHasKey('nombre', $resultado);
            $this->assertArrayHasKey('apellido', $resultado);
            $this->assertArrayHasKey('telefono', $resultado);
            $this->assertArrayHasKey('correo', $resultado);
        }
    }

    public function testConsultarMetodosPagoVenta() { /*|||||| CONSULTAR MÉTODOS PAGO VENTA ||||| 8 | */
        $id_pedido = 1;
        $resultado = $this->salida->testConsultarMetodosPagoVenta($id_pedido);
        $this->assertIsArray($resultado);

        if (!empty($resultado)) {
            $this->assertArrayHasKey('nombre_metodo', $resultado[0]);
            $this->assertArrayHasKey('monto_usd', $resultado[0]);
            $this->assertArrayHasKey('monto_bs', $resultado[0]);
        }
    }

    public function testVerificarStockProducto() { /*|||||| VERIFICAR STOCK ||||| 9 | */
        $id_producto = 1;
        $resultado = $this->salida->testVerificarStock($id_producto);
        $this->assertIsInt($resultado);
        $this->assertGreaterThanOrEqual(0, $resultado);
    }

    public function testConsultarClientePublico() { /*|||||| CONSULTAR CLIENTE PÚBLICO ||||| 10 | */
        $datos = ['cedula' => '12345678'];
        $resultado = $this->salida->testConsultarClientePublico($datos);
        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('respuesta', $resultado);
        $this->assertArrayHasKey('cliente', $resultado);
    }

    public function testRegistrarClientePublico() { /*|||||| REGISTRAR CLIENTE PÚBLICO ||||| 11 | */
        $datos = [
            'cedula' => '12345678',
            'nombre' => 'Test',
            'apellido' => 'Usuario',
            'telefono' => '04141234567',
            'correo' => 'test@example.com'
        ];
        $resultado = $this->salida->testRegistrarClientePublico($datos);
        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('success', $resultado);
        $this->assertArrayHasKey('message', $resultado);
    }

    public function testRegistrarVentaPublico() { /*|||||| REGISTRAR VENTA PÚBLICO ||||| 12 | */
        $datos = [
            'id_persona' => 1,
            'precio_total' => 100.00,
            'precio_total_bs' => 2500.00,
            'detalles' => [
                [
                    'id_producto' => 1,
                    'cantidad' => 2,
                    'precio_unitario' => 50.00
                ]
            ]
        ];
        $resultado = $this->salida->testRegistrarVentaPublico($datos);
        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('respuesta', $resultado);
    }

    public function testActualizarVentaPublico() { /*|||||| ACTUALIZAR VENTA PÚBLICO ||||| 13 | */
        $datos = [
            'id_pedido' => 1,
            'estado' => '2'
        ];
        $resultado = $this->salida->testActualizarVentaPublico($datos);
        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('respuesta', $resultado);
    }

    public function testEliminarVentaPublico() { /*|||||| ELIMINAR VENTA PÚBLICO ||||| 14 | */
        $datos = ['id_pedido' => 1];
        $resultado = $this->salida->testEliminarVentaPublico($datos);
        $this->assertIsArray($resultado);
        $this->assertArrayHasKey('respuesta', $resultado);
    }
}

?>