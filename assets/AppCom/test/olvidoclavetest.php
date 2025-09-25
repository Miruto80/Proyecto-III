<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../../../modelo/olvidoclave.php';

/*|||||||||||||||||||||||||| INSTANCIA DE LA CLASE Y METODOS  |||||||||||||||||||||| */
class OlvidoTestable extends Olvido {
   
     public function testEncryptClave($clave) { /*||| 01 ||| */
        return $this->encryptClave(['clave' => $clave]);
    }

    public function testDecryptClave($claveEncriptada) { /*||| 02 ||| */
        return $this->decryptClave(['clave_encriptada' => $claveEncriptada]);
    }

    public function testEjecutarActualizacionCliente($datos) { /*||| 04 ||| */
        return $this->ejecutarActualizacionCliente($datos);
    }

    public function testEjecutarActualizacionUsuario($datos) { /*||| 05 ||| */
        return $this->ejecutarActualizacionUsuario($datos);
    }

    public function testEjecutarActualizacionPorOrigen($datos) { /*||| 06 ||| */
        return $this->ejecutarActualizacionPorOrigen($datos);
    }


}

/*||||||||||||||||||||||||||||||| CLASE DE TEST  |||||||||||||||||||||||||||||| */
class OlvidoclaveTest extends TestCase {
    private OlvidoTestable $olvido;

    protected function setUp(): void {
        $this->olvido = new OlvidoTestable();
    }
    
    /*|||||| 01 y 02 - ENCRIPTAR Y DESENCRIPTAR CLAVE ||||*/
    public function testEncriptacionYDesencriptacion() {
        $claveOriginal = 'LaraVenezuela123';
        $claveEncriptada = $this->olvido->testEncryptClave($claveOriginal);
        $claveDesencriptada = $this->olvido->testDecryptClave($claveEncriptada);

        $this->assertEquals($claveOriginal, $claveDesencriptada);
    }

    /*|||||| 03 - OPERACIÓN INVÁLIDA ||||*/
    public function testOperacionInvalida() {
        $json = json_encode([
            'operacion' => 'desconocida',
            'datos' => []
        ]);

        $resultado = $this->olvido->procesarOlvido($json);
        $this->assertEquals(0, $resultado['respuesta']);
        $this->assertEquals('Operación no válida', $resultado['mensaje']);
    }

    /*|||||| 04 - ACTUALIZAR CLAVE CLIENTE ||||*/
    public function testActualizarClaveCliente() {
        $datos = [
            'id_persona' => 3, 
            'clave' => 'NuevaClaveCliente123'
        ];

        $resultado = $this->olvido->testEjecutarActualizacionCliente($datos);
        $this->assertIsArray($resultado);
        $this->assertEquals(1, $resultado['respuesta']);
        $this->assertEquals('actualizar', $resultado['accion']);
    }

    /*|||||| 05 - ACTUALIZAR CLAVE USUARIO ||||*/
    public function testActualizarClaveUsuario() {
        $datos = [
            'id_persona' => 2,
            'clave' => 'NuevaClaveUsuario456'
        ];

        $resultado = $this->olvido->testEjecutarActualizacionUsuario($datos);
        $this->assertIsArray($resultado);
        $this->assertEquals(1, $resultado['respuesta']);
        $this->assertEquals('actualizar', $resultado['accion']);
    }

    /*|||||| 06 - ACTUALIZAR SEGÚN ORIGEN ||||*/
    public function testActualizarPorOrigenCliente() {
        $datos = [
            'id_persona' => 3,
            'clave' => 'ClaveClienteOrigen',
            'tabla_origen' => 1
        ];

        $resultado = $this->olvido->testEjecutarActualizacionPorOrigen($datos);
        $this->assertEquals('actualizar', $resultado['accion']);
    }

    public function testActualizarPorOrigenUsuario() {
        $datos = [
            'id_persona' => 4,
            'clave' => 'ClaveUsuarioOrigen',
            'tabla_origen' => 2
        ];

        $resultado = $this->olvido->testEjecutarActualizacionPorOrigen($datos);
        $this->assertEquals('actualizar', $resultado['accion']);
    }
  
   
}

?>
