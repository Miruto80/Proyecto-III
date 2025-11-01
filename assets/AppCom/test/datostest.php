<?php
use PHPUnit\Framework\TestCase;
// Ruta 
$Original = __DIR__ . '/../../../modelo/datos.php';
$UsuariosDatosContent = file_get_contents($Original);

// Corregir la ruta de conexion.php si es necesario
$conexionPath = realpath(__DIR__ . '/../../../modelo/conexion.php');
$UsuariosDatosContent = str_replace("require_once 'conexion.php';", "require_once '$conexionPath';", $UsuariosDatosContent);

// Cambiar métodos privados a protegidos 
$UsuariosDatosContent = str_replace("private function encryptClave", "protected function encryptClave", $UsuariosDatosContent);
$UsuariosDatosContent = str_replace("private function decryptClave", "protected function decryptClave", $UsuariosDatosContent);
$UsuariosDatosContent = str_replace("private function verificarExistencia", "protected function verificarExistencia", $UsuariosDatosContent);
$UsuariosDatosContent = str_replace("private function ejecutarActualizacion", "protected function ejecutarActualizacion", $UsuariosDatosContent);
$UsuariosDatosContent = str_replace("private function validarClaveActual", "protected function validarClaveActual", $UsuariosDatosContent);
$UsuariosDatosContent = str_replace("private function ejecutarActualizacionClave", "protected function ejecutarActualizacionClave", $UsuariosDatosContent);

// Evaluar el contenido modificado directamente
eval('?>' . $UsuariosDatosContent);


/*|||||||||||||||||||||||||| INSTANCIA DE LA CLASE Y METODOS  |||||||||||||||||||||| */
class DatosTestable extends Datos {
   
    public function testEncrypt($clave) {  /*||| 1 ||| */
        return $this->encryptClave(['clave' => $clave]);
    }

    public function testDecrypt($claveEncriptada) {  /*||| 2 ||| */
        return $this->decryptClave(['clave_encriptada' => $claveEncriptada]);
    }

    public function testEjecutarActualizacion($datos) {
        return $this->ejecutarActualizacion($datos);
    }

    public function testValidarClaveActual($id_persona, $clave_actual) {
        return $this->validarClaveActual([
            'id_persona' => $id_persona,
            'clave_actual' => $clave_actual
        ]);
    }

    public function testEjecutarActualizacionClave($id_persona, $clavePlano) {
        return $this->ejecutarActualizacionClave([
            'id_persona' => $id_persona,
            'clave' => $clavePlano
        ]);
    }

    public function testVerificarExistencia($campo, $valor) {  /*||| 6 ||| */
        return $this->verificarExistencia(['campo' => $campo, 'valor' => $valor]);
    }

}

/*||||||||||||||||||||||||||||||| CLASE DE TEST  |||||||||||||||||||||||||||||| */
class DatosTest extends TestCase {
    private DatosTestable $datos;

    protected function setUp(): void {
        $this->datos = new DatosTestable();
    }

    public function testOperacionInvalida() { /*|||||| OPERACIONES |||| 3 || */
        $datosDirecto = new DatosTestable(); // No 
        $json = json_encode([
            'operacion' => 'desconocido',
            'datos' => []
        ]);

        $resultado = $datosDirecto->procesarUsuario($json);
        $this->assertEquals(0, $resultado['respuesta']);
        $this->assertEquals('Operación no válida', $resultado['mensaje']);
    }
    
    public function testEncriptacionYDesencriptacion() {    /*|||||| 01 Y 02 - ENCRIPTAR Y DESENCRIPTAR CLAVE ||||*/
        $claveOriginal = 'MiClaveSegura123';
        $claveEncriptada = $this->datos->testEncrypt($claveOriginal);
        $claveDesencriptada = $this->datos->testDecrypt($claveEncriptada);

        $this->assertEquals($claveOriginal, $claveDesencriptada);
    }

    public function testActualizarDatosUsuario() {
        $datos = [
            'cedula' => '10200300',
            'correo' => 'actualizado@example.com',
            'nombre' => 'Daniel',
            'apellido' => 'Sánchez',
            'telefono' => '04141234567',
            'id_persona' => 2 
        ];

        $resultado = $this->datos->testEjecutarActualizacion($datos);

        $this->assertIsArray($resultado, 'No se recibió un array como respuesta');
        $this->assertEquals(1, $resultado['respuesta'], 'La actualización falló');
        $this->assertEquals('actualizar', $resultado['accion']);
    }

    public function testValidarClaveActual() {
        $id_persona = 2; 
        $clavePlano = 'love1234'; 

        $resultado = $this->datos->testValidarClaveActual($id_persona, $clavePlano);

        $this->assertTrue($resultado, "La clave actual no coincide con la registrada para el usuario $id_persona");
    }

    public function testActualizarClaveUsuario() {
        $id_persona = 2; 
        $claveNueva = 'love1234'; 

        $resultado = $this->datos->testEjecutarActualizacionClave($id_persona, $claveNueva);

        $this->assertIsArray($resultado, 'No se recibió un array como respuesta');
        $this->assertEquals(1, $resultado['respuesta'], 'La actualización de clave falló');
        $this->assertEquals('clave', $resultado['accion']);
    }

    public function testCedulaExistente() { /*||||||  VERIFICAR CEDULA Y CORREO ||||| 6 | */
        $cedula = '3071651';
        $existe = $this->datos->testVerificarExistencia('cedula', $cedula);
        $this->assertFalse($existe);
    }

    public function testCorreoInexistente() {
        $correo = 'danielsanchezcv@gmail.com';
        $existe = $this->datos->testVerificarExistencia('correo', $correo);
        $this->assertFalse($existe);
    }

    public function testConsultarDatosPorId() {
        $id_persona = 2;

        $resultado = $this->datos->consultardatos($id_persona);

        $this->assertIsArray($resultado, 'resultado no es un array');

        if (!empty($resultado)) {
            $this->assertArrayHasKey('cedula', $resultado[0]);
            $this->assertArrayHasKey('correo', $resultado[0]);
            $this->assertArrayHasKey('nombre', $resultado[0]);
            $this->assertArrayHasKey('apellido', $resultado[0]);
        } else {
            $this->fail("No se encontraron datos para el id_persona $id_persona");
        }
    }


}

?>
