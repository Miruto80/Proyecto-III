<?php
require_once 'conexion.php';
class categoria extends Conexion {
    private $conex1;
    private $nombre;
    private $id_categoria;
    public function __construct() {
        parent::__construct(); // Llama al constructor de la clase padre

        // Obtener las conexiones de la clase padre
        $this->conex1 = $this->getConex1();
        $this->conex2 = $this->getConex2();
    
         // Verifica si las conexiones son exitosas
        if (!$this->conex1) {
            die('Error al conectar con la primera base de datos');
        }

        if (!$this->conex2) {
            die('Error al conectar con la segunda base de datos');
        }
    }
    public function registrar() {
        $registro = "INSERT INTO categoria(nombre, estatus) VALUES (:nombre, 1)";
        $strExec = $this->conex1->prepare($registro);
        $strExec->bindParam(':nombre', $this->nombre);
        $resul = $strExec->execute();
        return $resul ? ['respuesta' => 1, 'accion' => 'incluir'] : ['respuesta' => 0, 'accion' => 'incluir'];
    }
    public function modificar() {
        $registro = "UPDATE categoria SET nombre = :nombre WHERE id_categoria = :id_categoria";
        $strExec = $this->conex1->prepare($registro);
        $strExec->bindParam(':nombre', $this->nombre);
        $strExec->bindParam(':id_categoria', $this->id_categoria);
        $resul = $strExec->execute();
        return $resul ? ['respuesta' => 1, 'accion' => 'actualizar'] : ['respuesta' => 0, 'accion' => 'actualizar'];
    }
    public function eliminar() {
        $registro = "DELETE FROM categoria WHERE id_categoria = :id_categoria";
        $strExec = $this->conex1->prepare($registro);
        $strExec->bindParam(':id_categoria', $this->id_categoria);
        $resul = $strExec->execute();
        return $resul ? ['respuesta' => 1, 'accion' => 'eliminar'] : ['respuesta' => 0, 'accion' => 'eliminar'];
    }
    public function consultar() {
        $registro = "SELECT * FROM categoria";
        $consulta = $this->conex1->prepare($registro);
        $resul = $consulta->execute();
        return $resul ? $consulta->fetchAll(PDO::FETCH_ASSOC) : [];
    }

    public function registrarBitacora($id_persona, $accion, $descripcion) {
    $consulta = "INSERT INTO bitacora (accion, fecha_hora, descripcion, id_persona) 
                 VALUES (:accion, NOW(), :descripcion, :id_persona)";
    
    $strExec = $this->conex1->prepare($consulta);
    $strExec->bindParam(':accion', $accion);
    $strExec->bindParam(':descripcion', $descripcion);
    $strExec->bindParam(':id_persona', $id_persona);
    
    return $strExec->execute(); // Devuelve true si la inserción fue exitosa
    }

    public function set_Nombre($nombre) {
        $this->nombre = $nombre;
    }
    public function set_Id_categoria($id_categoria) {
        $this->id_categoria = $id_categoria;
    }
}
?>