<?php
require_once 'conexion.php';
class tipousuario extends Conexion {
    private $conex1;
    private $conex2;
    private $id_tipo;
    private $nombre;
    private $nivel;
    private $estatus;

    function __construct() {
        parent::__construct(); // Llama al constructor de la clase padre

        // Obtener las conexiones de la clase padre
        $this->conex1 = $this->getConex1();
        $this->conex2 = $this->getConex2();
    }

    public function registrarBitacora($id_persona, $accion, $descripcion) {
    $consulta = "INSERT INTO bitacora (accion, fecha_hora, descripcion, id_persona) 
                 VALUES (:accion, NOW(), :descripcion, :id_persona)";
    
    $strExec = $this->conex2->prepare($consulta);
    $strExec->bindParam(':accion', $accion);
    $strExec->bindParam(':descripcion', $descripcion);
    $strExec->bindParam(':id_persona', $id_persona);
    
    return $strExec->execute(); // Devuelve true si la inserción fue exitosa
    }

    public function registrar() {
        $registro = "INSERT INTO rol_usuario(nombre, nivel, estatus) VALUES (:nombre, :nivel, :estatus)";
        $strExec = $this->conex2->prepare($registro);
        $strExec->bindParam(':nombre', $this->nombre);
        $strExec->bindParam(':nivel', $this->nivel);
        $strExec->bindParam(':estatus', $this->estatus);
        $resul = $strExec->execute();
        return $resul ? ['respuesta' => 1, 'accion' => 'incluir'] : ['respuesta' => 0, 'accion' => 'incluir'];
    }

    public function modificar() {
        $registro = "UPDATE rol_usuario SET nombre = :nombre, nivel = :nivel, estatus = :estatus WHERE id_tipo = :id_tipo";
        $strExec = $this->conex2->prepare($registro);
        $strExec->bindParam(':nombre', $this->nombre);
        $strExec->bindParam(':nivel', $this->nivel);
        $strExec->bindParam(':estatus', $this->estatus);
        $strExec->bindParam(':id_tipo', $this->id_tipo);
        $resul = $strExec->execute();
        return $resul ? ['respuesta' => 1, 'accion' => 'actualizar'] : ['respuesta' => 0, 'accion' => 'actualizar'];
    }

    public function eliminar() {
        $registro = "DELETE FROM rol_usuario WHERE id_tipo = :id_tipo";
        $strExec = $this->conex2->prepare($registro);
        $strExec->bindParam(':id_tipo', $this->id_tipo);
        $resul = $strExec->execute();
        return $resul ? ['respuesta' => 1, 'accion' => 'eliminar'] : ['respuesta' => 0, 'accion' => 'eliminar'];
    }

    public function consultar() {
        $registro = "SELECT * FROM rol_usuario";
        $consulta = $this->conex2->prepare($registro);
        $resul = $consulta->execute();
        return $resul ? $consulta->fetchAll(PDO::FETCH_ASSOC) : [];
    }

    // Setters
    public function set_Id_tipo($id_tipo) {
        $this->id_tipo = $id_tipo;
    }

    public function set_Nombre($nombre) {
        $this->nombre = $nombre;
    }

    public function set_Nivel($nivel) {
        $this->nivel = $nivel;
    }

    public function set_Estatus($estatus) {
        $this->estatus = $estatus;
    }
}
?>
