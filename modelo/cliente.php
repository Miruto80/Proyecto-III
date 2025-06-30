<?php

require_once 'conexion.php';

class Cliente extends Conexion
{

    private $conex1;
    private $conex2;
    private $id_persona;
    private $nombre;
    private $apellido;
    private $cedula;
    private $telefono;
    private $correo;
    private $id_rol;
    private $clave;
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
    

    public function consultar(){
        $registro="SELECT * FROM cliente WHERE estatus >=1"; // Filtra solo el nivel 1
    
        $consulta = $this->conex1->prepare($registro);
        $resul = $consulta->execute();
    
        $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        if ($resul){
            return $datos;
        } else {
            return $res = 0;
        }
    } //fin consultar

  
    public function actualizar(){
        $registro = "UPDATE cliente SET cedula = :cedula, correo = :correo WHERE id_persona = :id_persona";

        $strExec = $this->conex1->prepare($registro);
        $strExec->bindParam(':id_persona', $this->id_persona);
        $strExec->bindParam(':cedula', $this->cedula);
        $strExec->bindParam(':correo', $this->correo);

        $resul = $strExec->execute();
        if ($resul) {
            $res=array('respuesta'=>1,'accion'=>'actualizar');
        } else {
            $res=array('respuesta'=>0,'accion'=>'actualizar');
        }
        return $res;
    }


         public function existeCedula() {
            // Buscar en conex1
            $consulta = "SELECT cedula FROM cliente WHERE cedula = :cedula";
            $strExec = $this->conex1->prepare($consulta);
            $strExec->bindParam(':cedula', $this->cedula);
            $strExec->execute();

            // Si no hay resultados, buscar en conex2
            if ($strExec->rowCount() == 0) {
                $consulta = "SELECT cedula FROM usuario WHERE cedula = :cedula";
                $strExec = $this->conex2->prepare($consulta);
                $strExec->bindParam(':cedula', $this->cedula);
                $strExec->execute();
            }

            return $strExec->rowCount() > 0;
        }

    public function existeCorreo() {
        //conex1
        $consulta = "SELECT correo FROM cliente WHERE correo = :correo";
        $strExec = $this->conex1->prepare($consulta);
        $strExec->bindParam(':correo', $this->correo);
        $strExec->execute();

        //buscar en conex2
        if ($strExec->rowCount() == 0) {
            $consulta = "SELECT correo FROM usuario WHERE correo = :correo";
            $strExec = $this->conex2->prepare($consulta);
            $strExec->bindParam(':correo', $this->correo);
            $strExec->execute();
        }

    return $strExec->rowCount() > 0;
}
    
    
    public function get_Id_Persona()
    {
        return $this->id_persona;
    }

    public function set_Id_Persona($id_persona)
    {
        $this->id_persona = $id_persona;
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
        $this->correo = strtolower($correo);
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
