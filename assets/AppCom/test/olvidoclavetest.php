<?php
use PHPUnit\Framework\TestCase;

// Ruta al archivo original
$OlvidoOriginal = __DIR__ . '/../../../modelo/olvidoclave.php';
$OlvidoContenido = file_get_contents($OlvidoOriginal);

// Corregir la ruta de conexion.php si es necesario
$conexionPath = realpath(__DIR__ . '/../../../modelo/conexion.php');
$OlvidoContenido = str_replace("require_once 'conexion.php';", "require_once '$conexionPath';", $OlvidoContenido);

// Cambiar métodos privados a protegidos para permitir acceso desde LoginTestable
$OlvidoContenido = str_replace("private function encryptClave", "protected function encryptClave", $OlvidoContenido);
$OlvidoContenido = str_replace("private function decryptClave", "protected function decryptClave", $OlvidoContenido);
$OlvidoContenido = str_replace("private function ejecutarActualizacionCliente", "protected function ejecutarActualizacionCliente", $OlvidoContenido);
$OlvidoContenido = str_replace("private function ejecutarActualizacionUsuario", "protected function ejecutarActualizacionUsuario", $OlvidoContenido);
$OlvidoContenido = str_replace("private function ejecutarActualizacionPorOrigen", "protected function ejecutarActualizacionPorOrigen", $OlvidoContenido);

// Evaluar el contenido modificado directamente
eval('?>' . $OlvidoContenido);

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
              // Mensaje de confirmación
        if ($resultado['respuesta'] === 1 && $resultado['accion'] === 'actualizar') {
            echo "\n Clave de Cliente actualizada exitosamente.\n";
        } else {
            echo "\n Error al actualizar la clave del usuario.\n";
        }
    }

    /*|||||| 05 - ACTUALIZAR CLAVE USUARIO ||||*/
    public function testActualizarClaveUsuario() {
        $datos = [
            'id_persona' => 2,
            'clave' => 'love1234'
        ];

        $resultado = $this->olvido->testEjecutarActualizacionUsuario($datos);
        $this->assertIsArray($resultado);
        $this->assertEquals(1, $resultado['respuesta']);
        $this->assertEquals('actualizar', $resultado['accion']);
          // Mensaje de confirmación
        if ($resultado['respuesta'] === 1 && $resultado['accion'] === 'actualizar') {
            echo "\n Clave de usuario actualizada exitosamente.\n";
        } else {
            echo "\n Error al actualizar la clave del usuario.\n";
        }
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
