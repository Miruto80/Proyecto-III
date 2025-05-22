<?php

require_once 'conexion.php';

class Datos extends Conexion{

    private $conex;
    private $id_persona;
    private $nombre;
    private $apellido;
    private $cedula;
    private $telefono;
    private $correo;
    private $clave;
    private $estatus;

    function __construct() {
        $this->conex = new Conexion();
        $this->conex = $this->conex->Conex();
    }


    public function actualizar(){
        $registro = "UPDATE personas SET nombre = :nombre, apellido = :apellido, cedula = :cedula, telefono = :telefono, correo = :correo WHERE id_persona = :id_persona";

        $strExec = $this->conex->prepare($registro);
        $strExec->bindParam(':id_persona', $this->id_persona);
        $strExec->bindParam(':nombre', $this->nombre);
        $strExec->bindParam(':apellido', $this->apellido);
        $strExec->bindParam(':cedula', $this->cedula);
        $strExec->bindParam(':telefono', $this->telefono);
        $strExec->bindParam(':correo', $this->correo);

        $resul = $strExec->execute();
        if ($resul) {
            $res=array('respuesta'=>1,'accion'=>'actualizar');
        } else {
            $res=array('respuesta'=>0,'accion'=>'actualizar');
        }
        return $res;
    } // fin actulizar

     public function actualizarClave(){
        $registro = "UPDATE personas SET clave = :clave WHERE id_persona = :id_persona";

        $strExec = $this->conex->prepare($registro);
        $strExec->bindParam(':id_persona', $this->id_persona);
        $strExec->bindParam(':clave', $this->clave);

        $resul = $strExec->execute();
        if ($resul) {
            $res=array('respuesta'=>1,'accion'=>'clave');
        } else {
            $res=array('respuesta'=>0,'accion'=>'clave');
        }
        return $res;
    } // fin actulizar


    
    public function eliminar(){
        try {
            $registro = "UPDATE personas SET estatus = 0 WHERE id_persona = :id_persona";
            $strExec = $this->conex->prepare($registro);
            $strExec->bindParam(':id_persona', $this->id_persona);
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

     public function existeCedula() {
        $consulta = "SELECT cedula FROM personas WHERE cedula = :cedula";
        $strExec = $this->conex->prepare($consulta);
        $strExec->bindParam(':cedula', $this->cedula);
        $strExec->execute();
        return $strExec->rowCount() > 0;
    }


     
    public function existeCorreo() {
        $consulta = "SELECT correo FROM personas WHERE correo = :correo";
        $strExec = $this->conex->prepare($consulta);
        $strExec->bindParam(':correo', $this->correo);
        $strExec->execute();
        return $strExec->rowCount() > 0;
    }

  public function obtenerClave($id_persona) {
    $consulta = "SELECT clave FROM personas WHERE id_persona = :id_persona"; // Ajusta según la columna real
    $strExec = $this->conex->prepare($consulta);
    $strExec->bindParam(':id_persona', $id_persona); // Asegura que sea un entero
    $strExec->execute();
    
    $fila = $strExec->fetch(PDO::FETCH_ASSOC);
    return $fila ? $fila['clave'] : null;
}



    public function get_Id_persona()
    {
        return $this->id_persona;
    }

    public function set_Id_persona($id_persona)
    {
        $this->id_persona = $id_persona;
    }

    public function get_Nombre()
    {
        return $this->nombre;
    }

    public function set_Nombre($nombre)
    {
        return $this->nombre = ucfirst(strtolower($nombre));
    }

    public function get_Apellido()
    {
        return $this->apellido;
    }
    public function set_Apellido($apellido)
    {
        $this->apellido = ucfirst(strtolower($apellido));
    }

    public function get_Cedula()
    {
        return $this->cedula;
    }
    public function set_Cedula($cedula)
    {
        $this->cedula = $cedula;
    }

    public function get_Telefono()
    {
        return $this->telefono;
    }
    public function set_Telefono($telefono)
    {
        $this->telefono = $telefono;
    }

    public function get_Correo()
    {
        return $this->correo;
    }
    public function set_Correo($correo)
    {
        $this->correo = ucfirst(strtolower($correo));
    }

  

    public function get_Clave()
    {
        return $this->clave;
    }
    public function set_Clave($clave)
    {
        $this->clave = $clave;
    }

  
}
