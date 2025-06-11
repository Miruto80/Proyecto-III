<?php 

require_once('assets/dompdf/vendor/autoload.php');
use Dompdf\Dompdf;
use Dompdf\Options;

require_once('modelo/conexion.php');
require_once('modelo/categoria.php');

class producto extends Conexion {
    private $objcategoria;

    function __construct() {
        parent::__construct();
        $this->objcategoria = new categoria();
    }

    public function registrarBitacora($jsonDatos) {
        $datos = json_decode($jsonDatos, true);
        return $this->ejecutarSentenciaBitacora($datos);
    }

    private function ejecutarSentenciaBitacora($datos) {
        $conex = $this->getConex2();
        try {
            $conex->beginTransaction();
            
            $sql = "INSERT INTO bitacora (accion, fecha_hora, descripcion, id_persona) 
                    VALUES (:accion, NOW(), :descripcion, :id_persona)";
            
            $stmt = $conex->prepare($sql);
            $stmt->execute($datos);
            
            $conex->commit();
            $conex = null;
            return true;
        } catch (PDOException $e) {
            if ($conex) {
                $conex->rollBack();
                $conex = null;
            }
            throw $e;
        }
    }

    public function procesarProducto($jsonDatos) {
        $datos = json_decode($jsonDatos, true);
        $operacion = $datos['operacion'];
        $datosProcesar = $datos['datos'];
        
        try {
            switch ($operacion) {
                case 'registrar':
                    if ($this->verificarProductoExistente($datosProcesar['nombre'], $datosProcesar['marca'])) {
                        return ['respuesta' => 0, 'mensaje' => 'Ya existe un producto con el mismo nombre y marca'];
                    }
                    return $this->ejecutarRegistro($datosProcesar);
                    
                case 'actualizar':
                    return $this->ejecutarActualizacion($datosProcesar);
                    
                case 'eliminar':
                    return $this->ejecutarEliminacion($datosProcesar);
                    
                case 'cambiarEstatus':
                    return $this->ejecutarCambioEstatus($datosProcesar);
                    
                default:
                    return ['respuesta' => 0, 'mensaje' => 'Operación no válida'];
            }
        } catch (Exception $e) {
            return ['respuesta' => 0, 'mensaje' => $e->getMessage()];
        }
    }

    private function verificarProductoExistente($nombre, $marca) {
        $conex = $this->getConex1();
        try {
            $sql = "SELECT COUNT(*) FROM productos WHERE LOWER(nombre) = LOWER(:nombre) AND LOWER(marca) = LOWER(:marca) AND estatus = 1";
            $stmt = $conex->prepare($sql);
            $stmt->execute(['nombre' => $nombre, 'marca' => $marca]);
            $resultado = $stmt->fetchColumn() > 0;
            $conex = null;
            return $resultado;
        } catch (PDOException $e) {
            if ($conex) {
                $conex = null;
            }
            throw $e;
        }
    }

    private function ejecutarRegistro($datos) {
        $conex = $this->getConex1();
        try {
            $conex->beginTransaction();
            
            $sql = "INSERT INTO productos(nombre, descripcion, marca, cantidad_mayor, precio_mayor, precio_detal, 
                    stock_disponible, stock_maximo, stock_minimo, imagen, id_categoria, estatus)
                    VALUES (:nombre, :descripcion, :marca, :cantidad_mayor, :precio_mayor, :precio_detal, 
                    0, :stock_maximo, :stock_minimo, :imagen, :id_categoria, 1)";
            
            $stmt = $conex->prepare($sql);
            $resultado = $stmt->execute($datos);
            
            if ($resultado) {
                $conex->commit();
                $conex = null;
                return ['respuesta' => 1, 'accion' => 'incluir', 'mensaje' => 'Producto registrado exitosamente'];
            }
            
            $conex->rollBack();
            $conex = null;
            return ['respuesta' => 0, 'accion' => 'incluir', 'mensaje' => 'Error al registrar producto'];
            
        } catch (PDOException $e) {
            if ($conex) {
                $conex->rollBack();
                $conex = null;
            }
            throw $e;
        }
    }
    
    private function ejecutarActualizacion($datos) {
        $conex = $this->getConex1();
        try {
            $conex->beginTransaction();
            
            $sql = "UPDATE productos SET 
                    nombre = :nombre,
                    descripcion = :descripcion,
                    marca = :marca,
                    cantidad_mayor = :cantidad_mayor,
                    precio_mayor = :precio_mayor,
                    precio_detal = :precio_detal,
                    stock_maximo = :stock_maximo,
                    stock_minimo = :stock_minimo,
                    imagen = :imagen,
                    id_categoria = :id_categoria
                    WHERE id_producto = :id_producto";
            
            $stmt = $conex->prepare($sql);
            $resultado = $stmt->execute($datos);
            
            if ($resultado) {
                $conex->commit();
                $conex = null;
                return ['respuesta' => 1, 'accion' => 'actualizar', 'mensaje' => 'Producto actualizado exitosamente'];
            }
            
            $conex->rollBack();
            $conex = null;
            return ['respuesta' => 0, 'accion' => 'actualizar', 'mensaje' => 'Error al actualizar producto'];
            
        } catch (PDOException $e) {
            if ($conex) {
                $conex->rollBack();
                $conex = null;
            }
            throw $e;
        }
    }
    
    private function ejecutarEliminacion($datos) {
        $conex = $this->getConex1();
        try {
            $conex->beginTransaction();
            
            // Verificar stock antes de eliminar
            $sql = "SELECT stock_disponible FROM productos WHERE id_producto = :id_producto";
            $stmt = $conex->prepare($sql);
            $stmt->execute(['id_producto' => $datos['id_producto']]);
            $stock = $stmt->fetchColumn();
    
            if ($stock > 0) {
                $conex->rollBack();
                $conex = null;
                return ['respuesta' => 0, 'accion' => 'eliminar', 'mensaje' => 'No se puede eliminar un producto con stock disponible'];
            }
    
            $sql = "UPDATE productos SET estatus = 0 WHERE id_producto = :id_producto";
            $stmt = $conex->prepare($sql);
            $resultado = $stmt->execute($datos);
            
            if ($resultado) {
                $conex->commit();
                $conex = null;
                return ['respuesta' => 1, 'accion' => 'eliminar', 'mensaje' => 'Producto eliminado exitosamente'];
            }
            
            $conex->rollBack();
            $conex = null;
            return ['respuesta' => 0, 'accion' => 'eliminar', 'mensaje' => 'Error al eliminar producto'];
            
        } catch (PDOException $e) {
            if ($conex) {
                $conex->rollBack();
                $conex = null;
            }
            throw $e;
        }
    }
    
    private function ejecutarCambioEstatus($datos) {
        $conex = $this->getConex1();
        try {
            $conex->beginTransaction();
            
            $nuevo_estatus = ($datos['estatus_actual'] == 2) ? 1 : 2;
            
            $sql = "UPDATE productos SET estatus = :nuevo_estatus WHERE id_producto = :id_producto";
            $stmt = $conex->prepare($sql);
            $resultado = $stmt->execute([
                'nuevo_estatus' => $nuevo_estatus,
                'id_producto' => $datos['id_producto']
            ]);
            
            if ($resultado) {
                $conex->commit();
                $conex = null;
                return ['respuesta' => 1, 'accion' => 'cambiarEstatus', 'nuevo_estatus' => $nuevo_estatus, 'mensaje' => 'Estatus cambiado exitosamente'];
            }
            
            $conex->rollBack();
            $conex = null;
            return ['respuesta' => 0, 'accion' => 'cambiarEstatus', 'mensaje' => 'Error al cambiar estatus'];
            
        } catch (PDOException $e) {
            if ($conex) {
                $conex->rollBack();
                $conex = null;
            }
            throw $e;
        }
    }
    

    public function consultar() {
        $conex = $this->getConex1();
        try {
            $sql = "SELECT p.*, c.nombre AS nombre_categoria 
                    FROM productos p 
                    INNER JOIN categoria c ON p.id_categoria = c.id_categoria 
                    WHERE p.estatus IN (1,2)";
            
            $stmt = $conex->prepare($sql);
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $conex = null;
            return $resultado;
        } catch (PDOException $e) {
            if ($conex) {
                $conex = null;
            }
            throw $e;
        }
    }

    public function obtenerCategoria() {
        return $this->objcategoria->consultar();
    }

    private function imgToBase64($imgPath) {
        $fullPath = __DIR__ . '/../' . $imgPath;
        if (file_exists($fullPath)) {
            $imgData = file_get_contents($fullPath);
            return 'data:image/png;base64,' . base64_encode($imgData);
        }
        return '';
    }

    public function generarPDF() {
        $productos = $this->consultar();
        $fechaHoraActual = date('d/m/Y h:i A');
        $graficoBase64 = $this->imgToBase64('assets/img/grafica_reportes/grafico_productos.png');

        $html = '
        <html>
        <head>
            <title>Productos PDF</title>
            <style>
                body { font-family: Arial, sans-serif; font-size: 12px; }
                h1 { font-size: 24px; font-weight: bold; text-align: center; margin-bottom: 20px; }
                p { text-align: left; font-size: 12px; }
                h2 { font-size: 20px; font-weight: bold; margin-top: 20px; text-align: center; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                th, td { border: 1px solid #000; padding: 8px; text-align: center; }
                th { background-color: rgb(243, 108, 164); color: #000; font-size: 14px; }
                td { font-size: 12px; }
            </style>
        </head>
        <body>
            <h1>LISTADO DE PRODUCTOS</h1>
            <p><strong>Fecha y Hora de Expedición: </strong>' . $fechaHoraActual . '</p>';

        if (!empty($graficoBase64)) {
            $html .= '<h2 style="text-align:center;">Top 10 productos con más stock</h2>
                      <div style="text-align: center;"><img src="' . $graficoBase64 . '" width="600"></div><br>';
        }

        $html .= '<table>
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Descripcion</th>
                            <th>Marca</th>
                            <th>Precio</th>
                            <th>Precio al mayor</th>
                            <th>Stock</th>
                        </tr>
                    </thead>
                    <tbody>';

        foreach ($productos as $p) {
            $html .= '<tr>
                        <td>' . htmlspecialchars($p['nombre']) . '</td>
                        <td>' . htmlspecialchars($p['descripcion']) . '</td>
                        <td>' . htmlspecialchars($p['marca']) . '</td>
                        <td>' . htmlspecialchars($p['precio_detal']) . '</td>
                        <td>' . htmlspecialchars($p['precio_mayor']) . '</td>
                        <td>' . htmlspecialchars($p['stock_disponible']) . '</td>
                      </tr>';
        }

        $html .= '</tbody></table></body></html>';

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("Reporte_Productos.pdf", array("Attachment" => false));
    }
}

?>