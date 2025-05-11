<?php
require_once 'conexion.php';
class MetodoPago extends Conexion {
    private $conex;
    private $id_metodopago;
    private $nombre;
    private $descripcion;

    function __construct() {
        $this->conex = new Conexion();
        $this->conex = $this->conex->Conex();
    }

    public function registrar() {
        $registro = "INSERT INTO metodo_pago(nombre, descripcion, estatus) VALUES (:nombre, :descripcion, 1)";
        $strExec = $this->conex->prepare($registro);
        $strExec->bindParam(':nombre', $this->nombre);
        $strExec->bindParam(':descripcion', $this->descripcion);
        $resul = $strExec->execute();
        return $resul ? ['respuesta' => 1, 'accion' => 'incluir'] : ['respuesta' => 0, 'accion' => 'incluir'];
    }

    public function modificar() {
        $registro = "UPDATE metodo_pago SET nombre = :nombre, descripcion = :descripcion WHERE id_metodopago = :id_metodopago";
        $strExec = $this->conex->prepare($registro);
        $strExec->bindParam(':nombre', $this->nombre);
        $strExec->bindParam(':descripcion', $this->descripcion);
        $strExec->bindParam(':id_metodopago', $this->id_metodopago);
        $resul = $strExec->execute();
        return $resul ? ['respuesta' => 1, 'accion' => 'actualizar'] : ['respuesta' => 0, 'accion' => 'actualizar'];
    }

    public function eliminar() {
        $registro = "DELETE FROM metodo_pago WHERE id_metodopago = :id_metodopago";
        $strExec = $this->conex->prepare($registro);
        $strExec->bindParam(':id_metodopago', $this->id_metodopago);
        $resul = $strExec->execute();
        return $resul ? ['respuesta' => 1, 'accion' => 'eliminar'] : ['respuesta' => 0, 'accion' => 'eliminar'];
    }

    public function consultar() {
        $registro = "SELECT * FROM metodo_pago";
        $consulta = $this->conex->prepare($registro);
        $resul = $consulta->execute();
        return $resul ? $consulta->fetchAll(PDO::FETCH_ASSOC) : [];
    }

    public function set_Id_metodopago($id) {
        $this->id_metodopago = $id;
    }

    public function set_Nombre($nombre) {
        $this->nombre = $nombre;
    }

    public function set_Descripcion($descripcion) {
        $this->descripcion = $descripcion;
    }
}
?>
