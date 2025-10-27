<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../../../modelo/tipousuario.php';

// Ruta al archivo original
$UsuarioOriginal = __DIR__ . '/../../../modelo/usuario.php';
$UsuarioContenido = file_get_contents($UsuarioOriginal);

// Corregir la ruta de conexion.php si es necesario
$conexionPath = realpath(__DIR__ . '/../../../modelo/conexion.php');
$UsuarioContenido = str_replace("require_once 'conexion.php';", "require_once '$conexionPath';", $UsuarioContenido);

// Cambiar métodos privados a protegidos para permitir acceso desde LoginTestable
$UsuarioContenido = str_replace("private function encryptClave", "protected function encryptClave", $UsuarioContenido);
$UsuarioContenido = str_replace("private function decryptClave", "protected function decryptClave", $UsuarioContenido);
$UsuarioContenido = str_replace("private function ejecutarEliminacion", "protected function ejecutarEliminacion", $UsuarioContenido);
$UsuarioContenido = str_replace("private function registrarCliente", "protected function registrarCliente", $UsuarioContenido);
$UsuarioContenido = str_replace("private function ejecutarRegistro", "protected function ejecutarRegistro", $UsuarioContenido);
$UsuarioContenido = str_replace("private function ejecutarActualizacion", "protected function ejecutarActualizacion", $UsuarioContenido);
$UsuarioContenido = str_replace("private function actualizarLotePermisos", "protected function actualizarLotePermisos", $UsuarioContenido);

// Evaluar el contenido modificado directamente
eval('?>' . $UsuarioContenido);


/*|||||||||||||||||||||||||| INSTANCIA DE LA CLASE Y METODOS  |||||||||||||||||||||| */
class UsuarioTestable extends Usuario {
    
    public function testEncrypt($clave) {  /*||| 1 ||| */
        return $this->encryptClave($clave);
    }

    public function testDecrypt($claveEncriptada) {  /*||| 2 ||| */
        return $this->decryptClave($claveEncriptada);
    }

    public function testEjecutarEliminacion($datos) {  /*||| 5 ||| */
        return $this->ejecutarEliminacion($datos);
    }

    public function testVerificarExistencia($campo, $valor) {  /*||| 6 ||| */
        return $this->verificarExistencia(['campo' => $campo, 'valor' => $valor]);
    }

    public function testEjecutarRegistro($datos) {  /*||| 7 ||| */
        return $this->ejecutarRegistro($datos);
    }

    public function testEjecutarActualizacion($datos) {  /*||| 8 ||| */
        return $this->ejecutarActualizacion($datos);
    }

    public function testActualizarLotePermisos($lista) {  /*||| 11 ||| */
        return $this->actualizarLotePermisos($lista);
    }

}

/*||||||||||||||||||||||||||||||| CLASE DE TEST  |||||||||||||||||||||||||||||| */
class UsuarioTest extends TestCase {
    private UsuarioTestable $usuario;

    protected function setUp(): void {
        $this->usuario = new UsuarioTestable();
    }

    public function testEncriptacionYDesencriptacion() {  /*|||||| INCRIPTAR Y DESINCRIPTAR CLAVE  |||  1 | 2   ||| */
        $claveOriginal = 'MiClave123';
        $claveEncriptada = $this->usuario->testEncrypt($claveOriginal);
        $claveDesencriptada = $this->usuario->testDecrypt($claveEncriptada);

        $this->assertEquals($claveOriginal, $claveDesencriptada);
    }

    public function testOperacionInvalida() { /*|||||| OPERACIONES |||| 3 || */
        $usuarioDirecto = new Usuario(); // No 
        $json = json_encode([
            'operacion' => 'desconocido',
            'datos' => []
        ]);

        $resultado = $usuarioDirecto->procesarUsuario($json);
        $this->assertEquals(0, $resultado['respuesta']);
        $this->assertEquals('Operación no válida', $resultado['mensaje']);
    }

   public function testConsultarDevuelveArray() {
    /*|||||| CONSULTAR  ||||| 4 | */
    $resultado = $this->usuario->consultar();

    $this->assertIsArray($resultado, 'El resultado de consultar() no es un array.');

    if (!empty($resultado)) {
        echo "Se encontraron datos en la consulta.\n";
        $this->assertArrayHasKey('id_rol', $resultado[0], 'Falta la clave id_rol en el primer elemento.');
        $this->assertArrayHasKey('nombre_tipo', $resultado[0], 'Falta la clave nombre_tipo en el primer elemento.');
        $this->assertArrayHasKey('nivel', $resultado[0], 'Falta la clave nivel en el primer elemento.');
    } else {
        echo "No se encontraron datos en la consulta.\n";
        $this->assertEmpty($resultado, 'Se esperaba que el resultado estuviera vacío.');
    }
}


    public function testEliminarUsuarioExistente() { /*||||||  ELIMINAR  ||||| 5 | */
        $datos = ['id_persona' => 50];
        $resultado = $this->usuario->testEjecutarEliminacion($datos);

        $this->assertIsArray($resultado);
        $this->assertEquals(1, $resultado['respuesta']);
        $this->assertEquals('eliminar', $resultado['accion']);
    }

    public function testCedulaExistente() { /*||||||  VERIFICAR CEDULA Y CORREO ||||| 6 | */
        $cedula = '3071651';
        $existe = $this->usuario->testVerificarExistencia('cedula', $cedula);
        $this->assertFalse($existe);
    }

    public function testCorreoInexistente() {
        $correo = 'danielsanchezcv@gmail.com';
        $existe = $this->usuario->testVerificarExistencia('correo', $correo);
        $this->assertFalse($existe);
    }

    public function testRegistroUsuarioNuevo() {   /*||||||  REGISTRO NUEVO TEST ||||| 7 | */
        $datos = [
            'cedula' => '30716541',
            'nombre' => 'danielsanc',
            'apellido' => 'Unitaria',
            'correo' => 'pruebaunitaria@gmail.com',
            'telefono' => '04149739941',
            'clave' => $this->usuario->testEncrypt('claveSegura123'),
            'id_rol' => 1,
            'nivel' => 3
        ];

        $resultado = $this->usuario->testEjecutarRegistro($datos);
        $this->assertIsArray($resultado);
        $this->assertEquals(1, $resultado['respuesta']);
        $this->assertEquals('incluir', $resultado['accion']);
          if ($resultado['respuesta'] === 1 && $resultado['accion'] === 'incluir') {
            echo "\n Registro con exitosamente. | Usuario nuevo\n";
        } else {
            echo "\n Error al registrar Usuario\n";
        }
    }

        public function testRegistroMasivoUsuarios() {
        for ($i = 1; $i <= 1; $i++) {
            $cedula = 30716541 + $i;
            $correo = "pruebaunitaria{$i}@gmail.com";
            $nombre = "danielsanc{$i}";

            $datos = [
                'cedula' => (string)$cedula,
                'nombre' => $nombre,
                'apellido' => 'Unitaria',
                'correo' => $correo,
                'telefono' => '04149739941',
                'clave' => $this->usuario->testEncrypt('claveSegura123'),
                'id_rol' => 1,
                'nivel' => 3
            ];

            $resultado = $this->usuario->testEjecutarRegistro($datos);

            $this->assertIsArray($resultado, "Falló en la iteración $i: no se recibió un array");
            $this->assertEquals(1, $resultado['respuesta'], "Falló en la iteración $i: respuesta incorrecta");
            $this->assertEquals('incluir', $resultado['accion'], "Falló en la iteración $i: acción incorrecta");
        }
    }

    public function testActualizarUsuarioExistente() { /*||||||  ACTUALIZAR DATOS  TEST ||||| 8 | */
        $datos = [
            'cedula' => '10200300',
            'correo' => 'actualizado@exampleee.com',
            'estatus' => 1,
            'id_rol' => 1,
            'id_persona' => 2,
            'insertar_permisos' => false,
            'nivel' => 3
        ];

        $resultado = $this->usuario->testEjecutarActualizacion($datos);
        $this->assertIsArray($resultado);
        $this->assertEquals(1, $resultado['respuesta']);
        $this->assertEquals('actualizar', $resultado['accion']);
    }

    public function testBuscarPermisosPorId() { /*|||||| OBTENER LOS PERMISOS DE LA USUARIO ||||| 9 | */
        $id_persona = 2;
        $resultado = $this->usuario->buscar($id_persona);
        $this->assertIsArray($resultado);

        if (!empty($resultado)) {
            $this->assertArrayHasKey('id_modulo', $resultado[0]);
            $this->assertArrayHasKey('nombre', $resultado[0]);
            $this->assertArrayHasKey('accion', $resultado[0]);
            $this->assertArrayHasKey('estado', $resultado[0]);
        }
    }

    public function testActualizarPermisosLote() { /*|||||| ACTUALIZAR LOS PERMISOS DEL USUARIO  |||||||| 11  ||| */
        $lista = [
            ['id_permiso' => 1, 'estado' => 0],
            ['id_permiso' => 2, 'estado' => 1]
        ];

        $resultado = $this->usuario->testActualizarLotePermisos($lista);
        $this->assertIsArray($resultado);
        $this->assertEquals(1, $resultado['respuesta']);
        $this->assertEquals('actualizar_permisos', $resultado['accion']);
    }
}

?>
