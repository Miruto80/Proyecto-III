<?php
require_once 'conexion.php';
class proveedor extends Conexion {
    private $conex;
    private $nombre;
    private $id_proveedor;
    function __construct() {
        $this->conex = new Conexion();
        $this->conex = $this->conex->Conex();
    }
    public function registrar() {
        $registro = "INSERT INTO proveedor(nombre, estatus) VALUES (:nombre, 1)";
        $strExec = $this->conex->prepare($registro);
        $strExec->bindParam(':nombre', $this->nombre);
        $resul = $strExec->execute();
        return $resul ? ['respuesta' => 1, 'accion' => 'incluir'] : ['respuesta' => 0, 'accion' => 'incluir'];
    }
    public function modificar() {
        $registro = "UPDATE proveedor SET nombre = :nombre WHERE id_proveedor = :id_proveedor";
        $strExec = $this->conex->prepare($registro);
        $strExec->bindParam(':nombre', $this->nombre);
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
    public function set_Nombre($nombre) {
        $this->nombre = $nombre;
    }
    public function set_Id_proveedor($id_proveedor) {
        $this->id_proveedor = $id_proveedor;
    }
}
?>