<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../../../modelo/cliente.php';

/*|||||||||||||||||||||||||| INSTANCIA DE LA CLASE Y METODOS  |||||||||||||||||||||| */
class ClienteTestable extends Cliente {
   
   
    public function testVerificarExistencia($campo, $valor) {  /*||| 3 ||| */
        return $this->verificarExistencia(['campo' => $campo, 'valor' => $valor]);
    }

     public function testEjecutarActualizacion($datos) {  /*||| 4 ||| */
        return $this->ejecutarActualizacion($datos);
    }


}

/*||||||||||||||||||||||||||||||| CLASE DE TEST  |||||||||||||||||||||||||||||| */
class ClienteTest extends TestCase {
    private ClienteTestable $cliente;

    protected function setUp(): void {
        $this->cliente = new ClienteTestable();
    }

    public function testOperacionInvalida() { /*|||||| OPERACIONES |||| 1 || */
        $clienteDirecto = new Cliente(); // No 
        $json = json_encode([
            'operacion' => 'desconocido',
            'datos' => []
        ]);

        $resultado = $clienteDirecto->procesarCliente($json);
        $this->assertEquals(0, $resultado['respuesta']);
        $this->assertEquals('Operación no válida', $resultado['mensaje']);
    }

    public function testConsultar() { /*|||||| CONSULTAR  ||||| 2 | */
        $resultado = $this->cliente->consultar();
        $this->assertIsArray($resultado);

        if (!empty($resultado)) {
            $this->assertArrayHasKey('id_persona', $resultado[0]);
            $this->assertArrayHasKey('estatus', $resultado[0]);
            
        }
    }

    public function testCedulaExistente() { /*||||||  VERIFICAR CEDULA Y CORREO ||||| 3 | */
        $cedula = '3071651';
        $existe = $this->cliente->testVerificarExistencia('cedula', $cedula);
        $this->assertFalse($existe);
    }

    public function testCorreoInexistente() {
        $correo = 'danielsanchezcv@gmail.com';
        $existe = $this->cliente->testVerificarExistencia('correo', $correo);
        $this->assertFalse($existe);
    }

    public function testActualizarcliente() { /*||||||  ACTUALIZAR DATOS  TEST ||||| 4 | */
        $datos = [
            'cedula' => '1044',
            'correo' => 'hola@expla.com',
            'estatus' => 1,
            'id_persona' => 100000
        ];

        $resultado = $this->cliente->testEjecutarActualizacion($datos);
        $this->assertIsArray($resultado);
        $this->assertEquals(1, $resultado['respuesta']);
        $this->assertEquals('actualizar', $resultado['accion']);
    }
   
}

?>
