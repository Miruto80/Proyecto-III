<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../../../modelo/reporte.php';

/*|||||||||||||||||||||||||| INSTANCIA DE LA CLASE Y METODOS  |||||||||||||||||||||| */
class ReporteTestable {
    // Como Reporte es una clase con métodos estáticos, no necesitamos heredarla
    // Vamos a probar llamando directamente a sus métodos
}

/*||||||||||||||||||||||||||||||| CLASE DE TEST  |||||||||||||||||||||||||||||| */
class ReporteTest extends TestCase {

    public function testClaseReporteExiste() { /*|||||| VERIFICAR CLASE  ||||| 1 | */
        $this->assertTrue(class_exists('Reporte'));
    }

    public function testMetodoCompraExiste() { /*|||||| VERIFICAR METODO COMPRA  ||||| 2 | */
        $this->assertTrue(method_exists('Reporte', 'compra'));
    }

    public function testMetodoProductoExiste() { /*|||||| VERIFICAR METODO PRODUCTO  ||||| 3 | */
        $this->assertTrue(method_exists('Reporte', 'producto'));
    }

    public function testMetodoVentaExiste() { /*|||||| VERIFICAR METODO VENTA  ||||| 4 | */
        $this->assertTrue(method_exists('Reporte', 'venta'));
    }

    public function testMetodoPedidoWebExiste() { /*|||||| VERIFICAR METODO PEDIDO WEB  ||||| 5 | */
        $this->assertTrue(method_exists('Reporte', 'pedidoWeb'));
    }

    public function testMetodoGraficaVentaTop5Existe() { /*|||||| VERIFICAR METODO GRAFICA VENTA TOP5  ||||| 6 | */
        $this->assertTrue(method_exists('Reporte', 'graficaVentaTop5'));
    }
}
?>