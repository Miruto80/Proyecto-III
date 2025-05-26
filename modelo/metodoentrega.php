<?php 

require_once 'conexion.php';

class metodoentrega extends Conexion {
    private $conex;
    private $id_entrega;
    private $nombre;
    private $descripcion;

    public function __construct() {
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
        $registro = "INSERT INTO metodo_entrega(nombre, descripcion, estatus) VALUES (:nombre, :descripcion, 1)";
        $strExec = $this->conex->prepare($registro);
        $strExec->bindParam(':nombre', $this->nombre);
        $strExec->bindParam(':descripcion', $this->descripcion);
        $resul = $strExec->execute();
        return $resul ? ['respuesta' => 1, 'accion' => 'incluir'] : ['respuesta' => 0, 'accion' => 'incluir'];
    }

    public function modificar() {
        $registro = "UPDATE metodo_entrega SET nombre = :nombre, descripcion = :descripcion WHERE id_entrega = :id_entrega";
        $strExec = $this->conex->prepare($registro);
        $strExec->bindParam(':nombre', $this->nombre);
        $strExec->bindParam(':descripcion', $this->descripcion);
        $strExec->bindParam(':id_entrega', $this->id_entrega);
        $resul = $strExec->execute();
        return $resul ? ['respuesta' => 1, 'accion' => 'actualizar'] : ['respuesta' => 0, 'accion' => 'actualizar'];
    }

    public function eliminar() {
        $registro = "DELETE FROM metodo_entrega WHERE id_entrega = :id_entrega";
        $strExec = $this->conex->prepare($registro);
        $strExec->bindParam(':id_entrega', $this->id_entrega);
        $resul = $strExec->execute();
        return $resul ? ['respuesta' => 1, 'accion' => 'eliminar'] : ['respuesta' => 0, 'accion' => 'eliminar'];
    }

    public function consultar() {
        $registro = "SELECT * FROM metodo_entrega";
        $consulta = $this->conex->prepare($registro);
        $resul = $consulta->execute();
        return $resul ? $consulta->fetchAll(PDO::FETCH_ASSOC) : [];
    }

    // Setters
    public function set_Id_entrega($id) {
        $this->id_entrega = $id;
    }

    public function set_Nombre($nombre) {
        $this->nombre = $nombre;
    }

    public function set_Descripcion($descripcion) {
        $this->descripcion = $descripcion;
    }
}




?>