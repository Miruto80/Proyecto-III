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


public function registrarBitacora($id_persona, $accion, $descripcion) {
    $consulta = "INSERT INTO bitacora (accion, fecha_hora, descripcion, id_persona) 
                 VALUES (:accion, NOW(), :descripcion, :id_persona)";
    
    $strExec = $this->conex->prepare($consulta);
    $strExec->bindParam(':accion', $accion);
    $strExec->bindParam(':descripcion', $descripcion);
    $strExec->bindParam(':id_persona', $id_persona);
    
    return $strExec->execute(); // Devuelve true si la inserción fue exitosa
    }


   // Método privado para convertir imagen a base64 (si la usas localmente)
    private function imgToBase64($imgPath) {
        if (file_exists($imgPath)) {
            $imgData = file_get_contents($imgPath);
            $base64 = base64_encode($imgData);
            return 'data:image/png;base64,' . $base64;
        } else {
            return '';
        }
    }

    public function generarPDF($graficoBase64 = '') {
        $proveedores = $this->consultar();
        $fechaHoraActual = date('d/m/Y h:i A');

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
                <tbody>';
        // Agrega la imagen de la gráfica si existe
        if (!empty($graficoBase64)) {
            $html .= '<div style="text-align:center;"><img src="' . $graficoBase64 . '" width="400" /></div><br>';
        }

        $html .= '<table><thead><tr>
                    <th>Nombre</th>
                    <th>Tipo Documento</th>
                    <th>N° Documento</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Dirección</th>
                  </tr></thead><tbody>';

        foreach ($proveedores as $p) {
            $html .= '<tr>
                        <td>' . htmlspecialchars($p['nombre']) . '</td>
                        <td>' . htmlspecialchars($p['tipo_documento']) . '</td>
                        <td>' . htmlspecialchars($p['numero_documento']) . '</td>
                        <td>' . htmlspecialchars($p['correo']) . '</td>
                        <td>' . htmlspecialchars($p['telefono']) . '</td>
                        <td>' . htmlspecialchars($p['direccion']) . '</td>
                      </tr>';
        }

        $html .= '</tbody></table></body></html>';

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Enviar PDF al navegador
        header("Content-type: application/pdf");
        echo $dompdf->output();
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