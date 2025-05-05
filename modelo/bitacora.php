<?php

require_once 'conexion.php';

class Bitacora extends Conexion {

    private $conex;
    private $id_bitacora;


    function __construct(){ // Metodo para BD
        $this->conex = new Conexion();
        $this->conex = $this->conex->Conex();
    } 

   
   

public function consultar(){

        $registro="SELECT b.*, p.nombre, p.apellido, ru.nombre AS nombre_usuario
                     FROM bitacora b
                     INNER JOIN personas p ON b.id_persona = p.id_persona
                     INNER JOIN rol_usuario ru ON p.id_tipo = ru.id_tipo";
        $consulta = $this->conex->prepare($registro);
        $resul = $consulta->execute();

        $datos=$consulta->fetchAll(PDO::FETCH_ASSOC);
            if ($resul){
                return $datos;
            } else{
                return $res = 0;
            }
    
    } //fin consultar


      public function eliminar(){
        try {
            $registro = "DELETE FROM bitacora WHERE id_bitacora = :id_bitacora";
            $strExec = $this->conex->prepare($registro);
            $strExec->bindParam(':id_bitacora', $this->id_bitacora);
            $result = $strExec->execute();
                if ($result){
                    $res=array('respuesta'=>1,'accion'=>'eliminar');
                } else{
                    $res=array('respuesta'=>0,'accion'=>'eliminar');
                }

                return $res;
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }


    public function get_Idbitacora() {
        return $this->id_bitacora;
    }

    public function set_Idbitacora($id_bitacora) {
        $this->id_bitacora = $id_bitacora;
    }

}