<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../../../modelo/notificacion.php';

/*|||||||||||||||||||||||||| INSTANCIA DE LA CLASE Y METODOS  |||||||||||||||||||||| */
class NotificacionTestable extends Notificacion {
    // No necesitamos exponer métodos privados ya que todos los métodos del modelo son públicos
}

/*||||||||||||||||||||||||||||||| CLASE DE TEST  |||||||||||||||||||||||||||||| */
class NotificacionTest extends TestCase {
    private NotificacionTestable $notificacion;

    protected function setUp(): void {
        $this->notificacion = new NotificacionTestable();
    }

    public function testConsultarNotificaciones() { /*|||||| CONSULTAR  ||||| 1 | */
        // Agregar mensaje para verificar que se está ejecutando
        fwrite(STDERR, "Ejecutando consulta de notificaciones...\n");
        
        $resultado = $this->notificacion->getAll();
        $this->assertIsArray($resultado);
        
        // Mostrar cantidad de resultados
        fwrite(STDERR, "Consulta de notificaciones completada. Resultados: " . count($resultado) . "\n");
    }

    public function testContarNuevasNotificaciones() { /*|||||| CONTAR NUEVAS  ||||| 2 | */
        $resultado = $this->notificacion->contarNuevas();
        $this->assertIsInt($resultado);
        $this->assertGreaterThanOrEqual(0, $resultado);
        
        fwrite(STDERR, "Conteo de notificaciones nuevas: " . $resultado . "\n");
    }

    public function testContarParaAsesora() { /*|||||| CONTAR PARA ASESORA  ||||| 3 | */
        $resultado = $this->notificacion->contarParaAsesora();
        $this->assertIsInt($resultado);
        $this->assertGreaterThanOrEqual(0, $resultado);
        
        fwrite(STDERR, "Conteo de notificaciones para asesora: " . $resultado . "\n");
    }

    public function testContarParaAdmin() { /*|||||| CONTAR PARA ADMIN  ||||| 4 | */
        $resultado = $this->notificacion->contarParaAdmin();
        $this->assertIsInt($resultado);
        $this->assertGreaterThanOrEqual(0, $resultado);
        
        fwrite(STDERR, "Conteo de notificaciones para admin: " . $resultado . "\n");
    }

    public function testGenerarDePedidos() { /*|||||| GENERAR DE PEDIDOS  ||||| 5 | */
        // Este método puede no generar resultados si no hay pedidos nuevos
        $resultado = $this->notificacion->generarDePedidos();
        $this->assertIsInt($resultado);
        $this->assertGreaterThanOrEqual(0, $resultado);
        
        fwrite(STDERR, "Notificaciones generadas de pedidos: " . $resultado . "\n");
    }

    public function testGetNuevosPedidos() { /*|||||| OBTENER NUEVOS PEDIDOS  ||||| 6 | */
        // Este método requiere un ID de pedido para comparar
        // Usamos 0 como valor inicial para obtener todos los pedidos
        $resultado = $this->notificacion->getNuevosPedidos(0);
        $this->assertIsArray($resultado);
        
        fwrite(STDERR, "Nuevos pedidos obtenidos: " . count($resultado) . "\n");
    }
}
?>