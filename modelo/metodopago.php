<?php
require_once 'conexion.php';
class MetodoPago extends Conexion {
    private $conex1;
    private $conex2;
    private $id_metodopago;
    private $nombre;
    private $descripcion;

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
        $registro = "INSERT INTO metodo_pago(nombre, descripcion, estatus) VALUES (:nombre, :descripcion, 1)";
        $strExec = $this->conex1->prepare($registro);
        $strExec->bindParam(':nombre', $this->nombre);
        $strExec->bindParam(':descripcion', $this->descripcion);
        $resul = $strExec->execute();
        return $resul ? ['respuesta' => 1, 'accion' => 'incluir'] : ['respuesta' => 0, 'accion' => 'incluir'];
    }

    public function modificar() {
        $registro = "UPDATE metodo_pago SET nombre = :nombre, descripcion = :descripcion WHERE id_metodopago = :id_metodopago";
        $strExec = $this->conex1->prepare($registro);
        $strExec->bindParam(':nombre', $this->nombre);
        $strExec->bindParam(':descripcion', $this->descripcion);
        $strExec->bindParam(':id_metodopago', $this->id_metodopago);
        $resul = $strExec->execute();
        return $resul ? ['respuesta' => 1, 'accion' => 'actualizar'] : ['respuesta' => 0, 'accion' => 'actualizar'];
    }


    public function eliminar() {
        $registro = "UPDATE metodo_pago SET estatus = 0 WHERE id_metodopago = :id_metodopago";
        $strExec = $this->conex1->prepare($registro);
        $strExec->bindParam(':id_metodopago', $this->id_metodopago);
        $resul = $strExec->execute();
        return $resul ? ['respuesta' => 1, 'accion' => 'eliminar'] : ['respuesta' => 0, 'accion' => 'eliminar'];
    }

    public function consultar() {
        $registro = "SELECT * FROM metodo_pago WHERE estatus = 1";
        $consulta = $this->conex1->prepare($registro);
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
