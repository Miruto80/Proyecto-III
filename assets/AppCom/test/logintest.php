<?php
use PHPUnit\Framework\TestCase;
// Ruta 
$loginOriginal = __DIR__ . '/../../../modelo/login.php';
$loginContent = file_get_contents($loginOriginal);

// Corregir la ruta de conexion.php si es necesario
$conexionPath = realpath(__DIR__ . '/../../../modelo/conexion.php');
$loginContent = str_replace("require_once 'conexion.php';", "require_once '$conexionPath';", $loginContent);

// Cambiar métodos privados a protegidos 
$loginContent = str_replace("private function encryptClave", "protected function encryptClave", $loginContent);
$loginContent = str_replace("private function decryptClave", "protected function decryptClave", $loginContent);
$loginContent = str_replace("private function verificarCredenciales", "protected function verificarCredenciales", $loginContent);
$loginContent = str_replace("private function registrarCliente", "protected function registrarCliente", $loginContent);
$loginContent = str_replace("private function verificarExistencia", "protected function verificarExistencia", $loginContent);
$loginContent = str_replace("private function obtenerPersonaPorCedula", "protected function obtenerPersonaPorCedula", $loginContent);
$loginContent = str_replace("private function consultar", "protected function consultar", $loginContent);

// Evaluar el contenido modificado directamente
eval('?>' . $loginContent);

/*|||||||||||||||||||||||||| INSTANCIA DE LA CLASE Y METODOS  |||||||||||||||||||||| */
class LoginTestable extends Login {
   
    public function testEncrypt($clave) {  /*||| 1 ||| */
        return $this->encryptClave(['clave' => $clave]);
    }

    public function testDecrypt($claveEncriptada) {  /*||| 2 ||| */
        return $this->decryptClave(['clave_encriptada' => $claveEncriptada]);
    }

    public function testVerificarCredenciales($cedula, $clavePlano) {  /*||| 3 ||| */
        $resultado = $this->verificarCredenciales([
            'cedula' => $cedula,
            'clave' => $clavePlano
        ]);

        return $resultado;
    }

    public function testRegistrarCliente($datos) {  /*||| 4 ||| */
        return $this->registrarCliente($datos); 
    }

    public function testVerificarExistencia($campo, $valor) {  /*||| 5 ||| */
        return $this->verificarExistencia(['campo' => $campo, 'valor' => $valor]);
    }

    public function testObtenerPersonaPorCedula($cedula) {  /*||| 6 ||| */
        return $this->obtenerPersonaPorCedula(['cedula' => $cedula]);
    }

}

/*||||||||||||||||||||||||||||||| CLASE DE TEST  |||||||||||||||||||||||||||||| */
class LoginTest extends TestCase {
    private LoginTestable $login;

    protected function setUp(): void {
        $this->login = new LoginTestable();
    }
    
    public function testEncriptacionYDesencriptacion() {    /*|||||| 01 Y 02 - ENCRIPTAR Y DESENCRIPTAR CLAVE ||||*/
        $claveOriginal = 'MiClaveSegura123';
        $claveEncriptada = $this->login->testEncrypt($claveOriginal);
        $claveDesencriptada = $this->login->testDecrypt($claveEncriptada);

        $this->assertEquals($claveOriginal, $claveDesencriptada);
    }

  public function testVerificarCredenciales() { /*|||||| 03 - VERIFICAR CREDENCIALES CORRECTAS ||||*/
    $cedula = '20152522'; 
    $clavePlano = 'love1234';

    $resultado = $this->login->testVerificarCredenciales($cedula, $clavePlano);

    $this->assertNotNull($resultado, 'Las credenciales son incorrectas o el usuario no existe.');

    if ($resultado !== null) {
        echo "Las credenciales son correctas. Usuario autenticado.\n";
        $this->assertObjectHasProperty('cedula', $resultado, 'Falta la propiedad cedula en el objeto resultado.');
        $this->assertObjectHasProperty('estatus', $resultado, 'Falta la propiedad estatus en el objeto resultado.');
    }
}

    public function testRegistrarClienteNuevo() {   /*|||||| 04 - REGISTRAR CLIENTE NUEVO ||||*/
        $datos = [
            'cedula' => '30800123',
            'nombre' => 'Daniel',
            'apellido' => 'Sánchez',
            'correo' => 'daniel.sanchez.test@gmail.com',
            'telefono' => '04141234567',
            'clave' => 'ClaveSegura123'
        ];

        $resultado = $this->login->testRegistrarCliente($datos);

        $this->assertIsArray($resultado);
        $this->assertEquals(1, $resultado['respuesta'], 'No se pudo registrar el cliente');
        $this->assertEquals('incluir', $resultado['accion']);
          if ($resultado['respuesta'] === 1 && $resultado['accion'] === 'incluir') {
            echo "\n Registro con exitosamente. | Cliente nuevo\n";
        } else {
            echo "\n Error al registrar Cliente\n";
        }
    }

    public function testCedulaExistente() { /*|||||| 05 - VERIFICAR CEDULA Y CORREO |||||| */
        $cedula = '3071651';
        $existe = $this->login->testVerificarExistencia('cedula', $cedula);
        $this->assertFalse($existe);
    }

    public function testCorreoInexistente() {
        $correo = 'danielsanchezcv@gmail.com';
        $existe = $this->login->testVerificarExistencia('correo', $correo);
        $this->assertFalse($existe);
    }
  
    public function testObtenerPersonaPorCedula() {     /*|||||| 06 - OBTENER CEDULA PARA EL OLVIDOCLAVE |||||| */
        $cedula = '30716541'; 

        $resultado = $this->login->testObtenerPersonaPorCedula($cedula);

        $this->assertNotNull($resultado, "No se encontró ninguna persona con la cédula $cedula");

        $this->assertObjectHasProperty('cedula', $resultado);
        $this->assertObjectHasProperty('origen', $resultado); 
    }

    public function testConsultarPermisosPorId() {     /*|||||| 07 - OBTENER PERMISOS DEL ID |||||| */
        $id_persona = 1; 

        $resultado = $this->login->consultar($id_persona);

        $this->assertIsArray($resultado, 'El resultado no es un array');

        if (!empty($resultado)) {
            $this->assertArrayHasKey('id_permiso', $resultado[0]);
            $this->assertArrayHasKey('id_modulo', $resultado[0]);
            $this->assertArrayHasKey('accion', $resultado[0]);
            $this->assertArrayHasKey('estado', $resultado[0]);
            $this->assertArrayHasKey('id_rol', $resultado[0]);
        } else {
            $this->fail("No se encontraron permisos para el id_persona $id_persona");
        }
    }

}

?>
