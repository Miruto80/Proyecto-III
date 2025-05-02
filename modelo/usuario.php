<?php

require_once 'conexion.php';

class Usuario extends Conexion
{

    private $conex;
    private $id_usuario;
    private $nombre;
    private $apellido;
    private $cedula;
    private $telefono;
    private $correo;
    private $id_rol;
    private $clave;
    private $estatus;

    function __construct() {
        $this->conex = new Conexion();
        $this->conex = $this->conex->Conex();
    }

  

    public function registrar() {

        $registro = "INSERT INTO personas(cedula, nombre, apellido, correo, telefono, clave, estatus, id_tipo)
            VALUES(:cedula,:nombre, :apellido, :correo, :telefono,:clave, 1,:id_rol)";

        $strExec = $this->conex->prepare($registro);
        $strExec->bindParam(':cedula', $this->cedula);
        $strExec->bindParam(':nombre', $this->nombre);
        $strExec->bindParam(':apellido', $this->apellido);
        $strExec->bindParam(':correo', $this->correo);
        $strExec->bindParam(':telefono', $this->telefono);
        $strExec->bindParam(':clave', $this->clave);
        $strExec->bindParam(':id_rol', $this->id_rol);

        $resul = $strExec->execute();
        if ($resul) {
            $res['respuesta'] = 1;
            $res['accion'] = 'incluir';
        } else {
            $res['respuesta'] = 0;
            $res['accion'] = 'incluir';
        }

        return $res;
    } //fin registrar


     public function consultar(){

        $registro="SELECT p.*,ru.id_tipo, ru.nombre AS nombre_tipo
        FROM personas p 
        INNER JOIN  rol_usuario ru ON p.id_tipo = ru.id_tipo";
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
            $registro = "DELETE FROM personas WHERE id_persona = :id_usuario";
            $strExec = $this->conex->prepare($registro);
            $strExec->bindParam(':id_usuario', $this->id_usuario);
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
   
    public function obtenerRol()
    {
        $query = "SELECT * FROM rol_usuario WHERE id_tipo >= 1";
        $consulta = $this->conex->prepare($query);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }


    public function get_Id_Usuario()
    {
        return $this->id_usuario;
    }

    public function set_Id_Usuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;
    }

    public function get_Nombre()
    {
        return $this->nombres;
    }

    public function set_Nombre($nombre)
    {
        return $this->nombre = ucfirst(strtolower($nombre));
    }

    public function get_Apellidos()
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

    public function get_Id_rol()
    {
        return $this->tipo_usuario;
    }
    public function set_Id_rol($id_rol)
    {
        $this->id_rol = $id_rol;
    }

    public function get_Clave()
    {
        return $this->clave;
    }
    public function set_Clave($clave)
    {
        $this->clave = $clave;
    }

    public function get_Estatus()
    {
        return $this->estatus;
    }

    public function set_Estatus($estatus)
    {
        $this->estatus = $estatus;
    }
}
