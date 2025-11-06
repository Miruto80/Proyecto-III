<?php
use PHPUnit\Framework\TestCase;
// Ruta 
$clienteOriginal = __DIR__ . '/../../../modelo/cliente.php';
$clienteContent = file_get_contents($clienteOriginal);

// Corregir la ruta de conexion.php si es necesario
$conexionPath = realpath(__DIR__ . '/../../../modelo/conexion.php');
$clienteContent = str_replace("require_once 'conexion.php';", "require_once '$conexionPath';", $clienteContent);

// Cambiar métodos privados a protegidos 
$clienteContent = str_replace("private function verificarExistencia", "protected function verificarExistencia", $clienteContent);
$clienteContent = str_replace("private function ejecutarActualizacion", "protected function ejecutarActualizacion", $clienteContent);

// Evaluar el contenido modificado directamente
eval('?>' . $clienteContent);

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
              echo "\n testConsultar |  Si Consulta todo los datos correctamente \n";
        }else{
              echo "\n testConsultar |  Error/Vacios la consulta\n";
        }
         
    }

    public function testCedulaExistente() { /*||||||  VERIFICAR CEDULA Y CORREO ||||| 3 | */
        $cedula = '3071651';
        $existe = $this->cliente->testVerificarExistencia('cedula', $cedula);
        
        if ($existe) {
            echo "\n testCedulaExistente |  El cedula '$cedula' existe. \n";
        } else {
            echo "\n testCedulaExistente |  El cedula '$cedula' NO existe. \n";
        }

        $this->assertFalse($existe);
    }

    public function testCorreoInexistente() {
        $correo = 'loves@gmail.com';
        $existe = $this->cliente->testVerificarExistencia('correo', $correo);

        if ($existe) {
            echo "\n testCorreoInexistente |  El correo '$correo' existe. \n";
        } else {
            echo "\n testCorreoInexistente |  El correo '$correo' NO existe. \n";
        }

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

         if ($resultado['respuesta'] === 1 && $resultado['accion'] === 'actualizar') {
            echo "\n testActualizarcliente | Actualizacion con exitosamente. \n";
        } else {
            echo "\n testActualizarcliente | Error al Actualizar \n";
        }
    }
   
}

?>
