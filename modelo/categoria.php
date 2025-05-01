<?php

    require_once 'conexion.php';

    class categoria extends Conexion{
        
        private $conex;
        private $nombre;
        private $id_categoria;
        private $estatus;


        function __construct(){

            $this->conex = new Conexion();
            $this->conex = $this->conex->Conex();
        }

        public function registrar(){
            $registro ="INSERT INTO categoria(nombre,estatus)
            VALUES (:nombre,1)";

            $strExec = $this->conex->prepare($registro);
            $strExec->bindParam(':nombre',$this->nombre);

             $resul=$strExec->execute();
              if ($resul) {
                  $res['respuesta'] = 1;
                   $res['accion'] = 'incluir';
            } else {
                 $res['respuesta'] = 0;
                 $res['accion'] = 'incluir';
              }

        return $res;
        } // fin registrar


  

        public function consultar(){

            $registro ="SELECT * FROM categoria";
            $consulta = $this->conex->prepare($registro);
            $resul = $consulta->execute();

            $datos=$consulta->fetchAll(PDO::FETCH_ASSOC);
                if ($resul){
                    return $datos;
                } else{
                    return $res = 0;
                }
        
        } //fin consultar

        

    public function get_Nombre(){
        return $this->nombre;
    }

    public function set_Nombre($nombre){
        return $this->nombre=$nombre;
    }

    public function get_Id_categoria(){
        return $this->id_categoria;
    }

    public function set_Id_categoria($id_categoria){
        return $this->id_categoria=$id_categoria;
    }

    public function get_Estatus(){
        return $this->estatus;
    }

    public function set_Estatus($estatus){
        return $this->estatus=$estatus;
    }

    }


?>