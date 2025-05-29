<?php

require_once 'conexion.php';

class Usuario extends Conexion
{

    private $conex1;
    private $conex2;
    private $id_usuario;
    private $nombre;
    private $apellido;
    private $cedula;
    private $telefono;
    private $correo;
    private $id_rol;
    private $clave;
    private $estatus;
    private $encryptionKey = "MotorLoveMakeup"; 
    private $cipherMethod = "AES-256-CBC";
    
    function __construct() {
        parent::__construct(); // Llama al constructor de la clase padre

        // Obtener las conexiones de la clase padre
        $this->conex1 = $this->getConex1();
        $this->conex2 = $this->getConex2();
   }

    private function encryptClave($clave) {
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($this->cipherMethod)); 
        $encrypted = openssl_encrypt($clave, $this->cipherMethod, $this->encryptionKey, 0, $iv);
        return base64_encode($iv . $encrypted);
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

        $registro = "INSERT INTO usuario(cedula, nombre, apellido, correo, telefono, clave, estatus, id_rol)
            VALUES(:cedula,:nombre, :apellido, :correo, :telefono,:clave, 1,:id_rol)";

        $strExec = $this->conex2->prepare($registro);
        $strExec->bindParam(':cedula', $this->cedula);
        $strExec->bindParam(':nombre', $this->nombre);
        $strExec->bindParam(':apellido', $this->apellido);
        $strExec->bindParam(':correo', $this->correo);
        $strExec->bindParam(':telefono', $this->telefono);
        // Encriptar la clave antes de almacenarla
        $claveEncriptada = $this->encryptClave($this->clave);
        $strExec->bindParam(':clave', $claveEncriptada);
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

        $registro="SELECT p.*, ru.id_rol, ru.nombre AS nombre_tipo, ru.nivel
        FROM usuario p 
        INNER JOIN rol_usuario ru ON p.id_rol = ru.id_rol
        WHERE ru.nivel IN (2, 3)";
        $consulta = $this->conex2->prepare($registro);
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
            $registro = "DELETE FROM usuario WHERE id_persona = :id_usuario";
            $strExec = $this->conex2->prepare($registro);
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
        $query = "SELECT * FROM rol_usuario WHERE id_rol >= 1";
        $consulta = $this->conex2->prepare($query);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

     public function actualizar(){
        $registro = "UPDATE usuario SET cedula = :cedula, correo = :correo, estatus = :estatus, id_rol = :id_rol,  WHERE id_persona = :id_usuario";

        $strExec = $this->conex2->prepare($registro);
        $strExec->bindParam(':id_usuario', $this->id_usuario);
        $strExec->bindParam(':cedula', $this->cedula);
        $strExec->bindParam(':correo', $this->correo);
        $strExec->bindParam(':id_rol', $this->id_rol);
        $strExec->bindParam(':estatus', $this->estatus);

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
        return $this->nombre;
    }

    public function set_Nombre($nombre)
    {
        $this->nombre = ucfirst(strtolower($nombre));
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
        return $this->id_rol;
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
