<?php
require_once('assets/dompdf/vendor/autoload.php'); //archivo para cargar las funciones de la 
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


private function imgToBase64($imgPath) {
    $fullPath = __DIR__ . '/../' . $imgPath; // Ahora busca en la carpeta correcta

    if (file_exists($fullPath)) {
        $imgData = file_get_contents($fullPath);
        return 'data:image/png;base64,' . base64_encode($imgData);
    }
    return ''; // Si la imagen no existe, devuelve cadena vacía
}



    public function generarPDF() {

    $proveedores = $this->consultar();
    $fechaHoraActual = date('d/m/Y h:i A');

    // Ruta de la imagen en la carpeta img
    $graficoBase64 = $this->imgToBase64('assets/img/grafico_proveedores.png');

    $html = '
<html>
<head>
    <title>Proveedores PDF</title>
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
    <h1>LISTADO DE PROVEEDORES</h1>
    <p><strong>Fecha y Hora de Expedición: </strong>' . $fechaHoraActual . '</p>';

    // Agregar la imagen del gráfico si existe
   if (!empty($graficoBase64)) {
    $html .= '<h2 style="text-align:center;">Top 5 Proveedores con Más Compras</h2>
              <div style="text-align: center;"><img src="' . $graficoBase64 . '" width="600"></div><br>';
}


    $html .= '<table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Tipo Documento</th>
                        <th>N° Documento</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th>Dirección</th>
                    </tr>
                </thead>
                <tbody>';

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

    // Generar el PDF con DOMPDF
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream("Reporte_Proveedores.pdf", array("Attachment" => false));
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
    $registro = "UPDATE proveedor SET estatus = 0 WHERE id_proveedor = :id_proveedor";
    $strExec = $this->conex->prepare($registro);
    $strExec->bindParam(':id_proveedor', $this->id_proveedor);
    $resul = $strExec->execute();
    return $resul ? ['respuesta' => 1, 'accion' => 'eliminar'] : ['respuesta' => 0, 'accion' => 'eliminar'];
}

    
    public function consultar() {
    $registro = "SELECT * FROM proveedor WHERE estatus = 1";
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

