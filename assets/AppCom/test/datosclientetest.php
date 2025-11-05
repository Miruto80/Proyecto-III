<?php
use PHPUnit\Framework\TestCase;
// Ruta 
$DatosOriginal = __DIR__ . '/../../../modelo/catalogo_datos.php';
$DatosContent = file_get_contents($DatosOriginal);

// Corregir la ruta de conexion.php si es necesario
$conexionPath = realpath(__DIR__ . '/../../../modelo/conexion.php');
$DatosContent = str_replace("require_once 'conexion.php';", "require_once '$conexionPath';", $DatosContent);

$Metodo = realpath(__DIR__ . '/../../../modelo/metodoentrega.php');
$DatosContent = str_replace("require_once 'metodoentrega.php';", "require_once '$Metodo';", $DatosContent);

// Cambiar métodos privados a protegidos 
$DatosContent = str_replace("private function encryptClave", "protected function encryptClave", $DatosContent);
$DatosContent = str_replace("private function decryptClave", "protected function decryptClave", $DatosContent);
$DatosContent = str_replace("private function verificarExistencia", "protected function verificarExistencia", $DatosContent);
$DatosContent = str_replace("private function ejecutarActualizacion", "protected function ejecutarActualizacion", $DatosContent);
$DatosContent = str_replace("private function validarClaveActual", "protected function validarClaveActual", $DatosContent);
$DatosContent = str_replace("private function ejecutarActualizacionClave", "protected function ejecutarActualizacionClave", $DatosContent);
$DatosContent = str_replace("private function ejecutarEliminacion", "protected function ejecutarEliminacion", $DatosContent);
$DatosContent = str_replace("private function RegistroDireccion", "protected function RegistroDireccion", $DatosContent);
$DatosContent = str_replace("private function ejecutarActualizacionDireccion", "protected function ejecutarActualizacionDireccion", $DatosContent);

// Evaluar el contenido modificado directamente
eval('?>' . $DatosContent);
/*|||||||||||||||||||||||||| INSTANCIA DE LA CLASE Y METODOS  |||||||||||||||||||||| */
class DatosclienteTestable extends Datoscliente {
   
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

    public function testEjecutarEliminacionCliente($id_persona) {
        return $this->ejecutarEliminacion(['id_persona' => $id_persona]);
    }

    public function testRegistroDireccion($datos) {
        return $this->RegistroDireccion($datos);
    }

    public function testEjecutarActualizacionDireccion($datos) {
        return $this->ejecutarActualizacionDireccion($datos);
    }



}

/*||||||||||||||||||||||||||||||| CLASE DE TEST  |||||||||||||||||||||||||||||| */
class DatosclienteTest extends TestCase {
    private DatosclienteTestable $datoscliente;

    protected function setUp(): void {
        $this->datoscliente = new DatosclienteTestable();
    }

    public function testOperacionInvalida() { /*|||||| OPERACIONES |||| 3 || */
        $datosclienteDirecto = new DatosclienteTestable(); // No 
        $json = json_encode([
            'operacion' => 'desconocido',
            'datos' => []
        ]);

        $resultado = $datosclienteDirecto->procesarCliente($json);
        $this->assertEquals(0, $resultado['respuesta']);
        $this->assertEquals('Operación no válida', $resultado['mensaje']);
    }
    
    public function testEncriptacionYDesencriptacion() {    /*|||||| 01 Y 02 - ENCRIPTAR Y DESENCRIPTAR CLAVE ||||*/
        $claveOriginal = 'MiClaveSegura123';
        $claveEncriptada = $this->datoscliente->testEncrypt($claveOriginal);
        $claveDesencriptada = $this->datoscliente->testDecrypt($claveEncriptada);

        $this->assertEquals($claveOriginal, $claveDesencriptada);
    }

    public function testActualizarDatosCliente() {
        $datos = [
            'cedula' => '10200300',
            'correo' => 'actualizado@example.com',
            'nombre' => 'Daniel',
            'apellido' => 'Sánchez',
            'telefono' => '04141234567',
            'id_persona' => 1 
        ];

        $resultado = $this->datoscliente->testEjecutarActualizacion($datos);

        $this->assertIsArray($resultado, 'No se recibió un array como respuesta');
        $this->assertEquals(1, $resultado['respuesta'], 'La actualización falló');
        $this->assertEquals('actualizar', $resultado['accion']);
    }

    public function testValidarClaveActual() {
        $id_persona = 2; 
        $clavePlano = 'lara1234'; 

        $resultado = $this->datoscliente->testValidarClaveActual($id_persona, $clavePlano);

        $this->assertTrue($resultado, "La clave actual no coincide con la registrada para el usuario $id_persona");
    }

    public function testActualizarClaveUsuario() {
        $id_persona = 2; 
        $claveNueva = 'love1234'; 

        $resultado = $this->datoscliente->testEjecutarActualizacionClave($id_persona, $claveNueva);

        $this->assertIsArray($resultado, 'No se recibió un array como respuesta');
        $this->assertEquals(1, $resultado['respuesta'], 'La actualización de clave falló');
        $this->assertEquals('clave', $resultado['accion']);
    }

    public function testCedulaExistente() { /*||||||  VERIFICAR CEDULA Y CORREO ||||| 6 | */
        $cedula = '3071651';
        $existe = $this->datoscliente->testVerificarExistencia('cedula', $cedula);
        $this->assertFalse($existe);
    }

    public function testCorreoInexistente() {
        $correo = 'danielsanchezcv@gmail.com';
        $existe = $this->datoscliente->testVerificarExistencia('correo', $correo);
        $this->assertFalse($existe);
    }

    public function testConsultarDatosPorId() {
        $id_persona = 20;

        $resultado = $this->datoscliente->consultardatos($id_persona);

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

    public function testEliminarClienteExistente() {
        $id_persona = 1; 

        $resultado = $this->datoscliente->testEjecutarEliminacionCliente($id_persona);

        $this->assertIsArray($resultado, 'No se recibió un array como respuesta');
        $this->assertEquals(1, $resultado['respuesta'], "No se pudo eliminar al cliente con id_persona $id_persona");
        $this->assertEquals('eliminar', $resultado['accion']);
    }

    public function testRegistrarDireccionCliente() {
        $datos = [
            'id_metodoentrega' => 1, 
            'id_persona' => 3, 
            'direccion_envio' => 'Av. Lara, Edif. Copilot, Piso 3',
            'sucursal_envio' => 'Sucursal Barquisimeto'
        ];

        $resultado = $this->datoscliente->testRegistroDireccion($datos);

        $this->assertIsArray($resultado, 'No se recibió un array como respuesta');
        $this->assertEquals(1, $resultado['respuesta'], 'No se pudo registrar la dirección');
        $this->assertEquals('incluir', $resultado['accion']);
    }

    public function testActualizarDireccionExistente() {
        $datos = [
            'id_direccion' => 1,
            'direccion_envio' => 'Av. Libertador, Edif. Copilot, Piso 5',
            'sucursal_envio' => 'Sucursal Este'
        ];

        $resultado = $this->datoscliente->testEjecutarActualizacionDireccion($datos);

        $this->assertIsArray($resultado, 'No se recibió un array como respuesta');
        $this->assertEquals(1, $resultado['respuesta'], "No se pudo actualizar la dirección con id_direccion {$datos['id_direccion']}");
        $this->assertEquals('actualizardireccion', $resultado['accion']);
    }


}

?>
