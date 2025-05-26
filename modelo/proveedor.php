<?php
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