<?php
require_once('dompdf/vendor/autoload.php'); //archivo para cargar las funciones de la 
//libreria DOMPDF
// lo siguiente es hacer rerencia al espacio de trabajo
use Dompdf\Dompdf;
use Dompdf\Options;
require_once 'conexion.php';
class proveedor extends Conexion {
    private $conex;
    private $id_proveedor;
    private $numero_documento;
    private $tipo_documento;
    private $nombre;
    private $correo;
    private $telefono;
    private $direccion;
    private $estatus;
    
    function __construct() {
        $this->conex = new Conexion();
        $this->conex = $this->conex->Conex();
    }






 // Método privado que convierte una imagen a base64
private function imgToBase64($imgPath)
{
    if (file_exists($imgPath)) {
        $imgData = file_get_contents($imgPath);
        $base64 = base64_encode($imgData);
        return 'data:image/png;base64,' . $base64;
    } else {
        return '';
    }
}

public function generarPDF()
{
    try {
        $conex = $this->conex;

        // Si no se ha seteado $this->nombre o está vacío, trae todos
        if (empty($this->nombre)) {
            $resultado = $conex->prepare("SELECT nombre FROM proveedor");
            $resultado->execute();
        } else {
            $resultado = $conex->prepare("SELECT nombre FROM proveedor WHERE nombre LIKE :nombre");
            $resultado->bindValue(':nombre', '%' . $this->nombre . '%');
            $resultado->execute();
        }

        $proveedores = $resultado->fetchAll(PDO::FETCH_ASSOC);

        // ...el resto de tu código para el PDF sin cambios
        $fechaHoraActual = date('Y-m-d H:i:s');
        $imageBase64 = $this->imgToBase64('img/logo.png');

        $html = '
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; font-size: 10px; }
                table { width: 100%; border-collapse: collapse; table-layout: fixed; }
                th, td { border: 1px solid #000; padding: 4px; text-align: center; word-wrap: break-word; }
                th { background-color: rgb(243, 108, 164); color: #000; }
                td { background-color: #FFF; }
                h1 { text-align: center; font-size: 16px; }
            </style>
        </head>
        <body>
            <h1>LISTA DE PROVEEDORES</h1>
            <center><img src="' . $imageBase64 . '" style="margin: 10px auto;" width="100" /></center>
            <p><strong>Fecha y Hora de Expedición: </strong>' . $fechaHoraActual . '</p>
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                    </tr>
                </thead>
                <tbody>';

        if (!empty($proveedores)) {
            foreach ($proveedores as $prov) {
                $html .= '<tr><td>' . htmlspecialchars($prov['nombre']) . '</td></tr>';
            }
        } else {
            $html .= '<tr><td colspan="1">No se encontraron registros.</td></tr>';
        }

        $html .= '
                </tbody>
            </table>
        </body>
        </html>';

        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $pdf = new \Dompdf\Dompdf($options);
        $pdf->setPaper("A4", "landscape");
        $pdf->loadHtml($html);
        $pdf->render();
        $pdf->stream('LISTA_DE_PROVEEDORES.pdf', ["Attachment" => false]);

    } catch (Exception $e) {
        echo "Error al generar el PDF: " . $e->getMessage();
    }
}






    
    public function registrar() {
        $registro = "INSERT INTO proveedor(numero_documento, tipo_documento, nombre, correo, telefono, direccion, estatus) 
                    VALUES (:numero_documento, :tipo_documento, :nombre, :correo, :telefono, :direccion, 1)";
        $strExec = $this->conex->prepare($registro);
        $strExec->bindParam(':numero_documento', $this->numero_documento);
        $strExec->bindParam(':tipo_documento', $this->tipo_documento);
        $strExec->bindParam(':nombre', $this->nombre);
        $strExec->bindParam(':correo', $this->correo);
        $strExec->bindParam(':telefono', $this->telefono);
        $strExec->bindParam(':direccion', $this->direccion);
        $resul = $strExec->execute();
        return $resul ? ['respuesta' => 1, 'accion' => 'incluir'] : ['respuesta' => 0, 'accion' => 'incluir'];
    }
    
    public function modificar() {
        $registro = "UPDATE proveedor SET numero_documento = :numero_documento, 
                    tipo_documento = :tipo_documento, nombre = :nombre, correo = :correo, 
                    telefono = :telefono, direccion = :direccion 
                    WHERE id_proveedor = :id_proveedor";
        $strExec = $this->conex->prepare($registro);
        $strExec->bindParam(':numero_documento', $this->numero_documento);
        $strExec->bindParam(':tipo_documento', $this->tipo_documento);
        $strExec->bindParam(':nombre', $this->nombre);
        $strExec->bindParam(':correo', $this->correo);
        $strExec->bindParam(':telefono', $this->telefono);
        $strExec->bindParam(':direccion', $this->direccion);
        $strExec->bindParam(':id_proveedor', $this->id_proveedor);
        $resul = $strExec->execute();
        return $resul ? ['respuesta' => 1, 'accion' => 'actualizar'] : ['respuesta' => 0, 'accion' => 'actualizar'];
    }
    
    public function eliminar() {
        $registro = "DELETE FROM proveedor WHERE id_proveedor = :id_proveedor";
        $strExec = $this->conex->prepare($registro);
        $strExec->bindParam(':id_proveedor', $this->id_proveedor);
        $resul = $strExec->execute();
        return $resul ? ['respuesta' => 1, 'accion' => 'eliminar'] : ['respuesta' => 0, 'accion' => 'eliminar'];
    }
    
    public function consultar() {
        $registro = "SELECT * FROM proveedor";
        $consulta = $this->conex->prepare($registro);
        $resul = $consulta->execute();
        return $resul ? $consulta->fetchAll(PDO::FETCH_ASSOC) : [];
    }
    
    public function consultarPorId() {
        $registro = "SELECT * FROM proveedor WHERE id_proveedor = :id_proveedor";
        $consulta = $this->conex->prepare($registro);
        $consulta->bindParam(':id_proveedor', $this->id_proveedor);
        $resul = $consulta->execute();
        return $resul ? $consulta->fetch(PDO::FETCH_ASSOC) : [];
    }
    
    // Setters
    public function set_Id_proveedor($id_proveedor) {
        $this->id_proveedor = $id_proveedor;
    }
    
    public function set_Numero_documento($numero_documento) {
        $this->numero_documento = $numero_documento;
    }
    
    public function set_Tipo_documento($tipo_documento) {
        $this->tipo_documento = $tipo_documento;
    }
    
    public function set_Nombre($nombre) {
        $this->nombre = $nombre;
    }
    
    public function set_Correo($correo) {
        $this->correo = $correo;
    }
    
    public function set_Telefono($telefono) {
        $this->telefono = $telefono;
    }
    
    public function set_Direccion($direccion) {
        $this->direccion = $direccion;
    }
}
?>