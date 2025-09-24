<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../../../modelo/usuario.php';

class UsuarioTestable extends Usuario {
    public function testEncrypt($clave) {
        return $this->encryptClave($clave);
    }

    public function testDecrypt($claveEncriptada) {
        return $this->decryptClave($claveEncriptada);
    }
}

class UsuarioTest extends TestCase {
    public function testEncriptacionYDesencriptacion() {
        $usuario = new UsuarioTestable();
        $claveOriginal = 'MiClave123';
        $claveEncriptada = $usuario->testEncrypt($claveOriginal);
        $claveDesencriptada = $usuario->testDecrypt($claveEncriptada);

        $this->assertEquals($claveOriginal, $claveDesencriptada);
    }

    public function testOperacionInvalida() {
        $usuario = new Usuario();
        $json = json_encode([
            'operacion' => 'desconocida',
            'datos' => []
        ]);

        $resultado = $usuario->procesarUsuario($json);
        $this->assertEquals(0, $resultado['respuesta']);
        $this->assertEquals('Operación no válida', $resultado['mensaje']);
    }
}


?>
