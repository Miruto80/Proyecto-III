<?php
require_once 'conexion.php';
class categoria extends Conexion {
    private $conex;
    private $nombre;
    private $id_categoria;
    function __construct() {
        $this->conex = new Conexion();
        $this->conex = $this->conex->Conex();
    }
    public function registrar() {
        $registro = "INSERT INTO categoria(nombre, estatus) VALUES (:nombre, 1)";
        $strExec = $this->conex->prepare($registro);
        $strExec->bindParam(':nombre', $this->nombre);
        $resul = $strExec->execute();
        return $resul ? ['respuesta' => 1, 'accion' => 'incluir'] : ['respuesta' => 0, 'accion' => 'incluir'];
    }
    public function modificar() {
        $registro = "UPDATE categoria SET nombre = :nombre WHERE id_categoria = :id_categoria";
        $strExec = $this->conex->prepare($registro);
        $strExec->bindParam(':nombre', $this->nombre);
        $strExec->bindParam(':id_categoria', $this->id_categoria);
        $resul = $strExec->execute();
        return $resul ? ['respuesta' => 1, 'accion' => 'actualizar'] : ['respuesta' => 0, 'accion' => 'actualizar'];
    }
    public function eliminar() {
        $registro = "DELETE FROM categoria WHERE id_categoria = :id_categoria";
        $strExec = $this->conex->prepare($registro);
        $strExec->bindParam(':id_categoria', $this->id_categoria);
        $resul = $strExec->execute();
        return $resul ? ['respuesta' => 1, 'accion' => 'eliminar'] : ['respuesta' => 0, 'accion' => 'eliminar'];
    }
    public function consultar() {
        $registro = "SELECT * FROM categoria";
        $consulta = $this->conex->prepare($registro);
        $resul = $consulta->execute();
        return $resul ? $consulta->fetchAll(PDO::FETCH_ASSOC) : [];
    }
    public function set_Nombre($nombre) {
        $this->nombre = $nombre;
    }
    public function set_Id_categoria($id_categoria) {
        $this->id_categoria = $id_categoria;
    }
}
?>