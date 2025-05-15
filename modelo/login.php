<?php

require_once 'conexion.php';

class Login extends Conexion {

    private $conex;
    private $id_usuario;
    private $cedula;
    private $clave;
    private $nombres;
    private $apellidos;
    private $id_rol;
    private $telefono;
    private $correo; 

    function __construct(){ // Metodo para BD
        $this->conex = new Conexion();
        $this->conex = $this->conex->Conex();
    } 

    public function verificarUsuario() {
        $consulta = "SELECT p.*, ru.nombre AS nombre_usuario, ru.nivel
                     FROM personas p
                     INNER JOIN rol_usuario ru ON p.id_tipo = ru.id_tipo

                     WHERE p.cedula = :cedula 
                     AND p.clave = :clave";
                     
        $strExec = $this->conex->prepare($consulta);
        $strExec->bindParam(':cedula', $this->cedula);
        $strExec->bindParam(':clave', $this->clave);
        $strExec->execute();
        $resultado = $strExec->fetchObject();
        if ($resultado) {
            if ($resultado->estatus != 1) {
                $resultado->noactiva = true; // para indicar que la cuenta está suspendida o inactiva
            }
        }
        return $resultado;
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

        $registro = "INSERT INTO personas(cedula, nombre, apellido, correo, telefono, clave, estatus, id_tipo)
            VALUES(:cedula,:nombre, :apellido, :correo, :telefono,:clave, 1,2)";

        $strExec = $this->conex->prepare($registro);
        $strExec->bindParam(':cedula', $this->cedula);
        $strExec->bindParam(':nombre', $this->nombre);
        $strExec->bindParam(':apellido', $this->apellido);
        $strExec->bindParam(':correo', $this->correo);
        $strExec->bindParam(':telefono', $this->telefono);
        $strExec->bindParam(':clave', $this->clave);

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

   
    public function get_IdUsuario() {
        return $this->id_usuario;
    }

    public function set_IdUsuario($id_usuario) {
        $this->id_usuario = $id_usuario;
    }

    public function get_Cedula() {
        return $this->cedula;
    }

    public function set_Cedula($cedula) {
        $this->cedula = $cedula;
    }

    public function get_Clave() {
        return $this->clave;
    }

    public function set_Clave($clave) {
        $this->clave = $clave;
         // $this->clave = password_hash($clave, PASSWORD_DEFAULT);  encriptar clave
    }

    public function get_Nombre() {
        return $this->nombre;
    }

    public function set_Nombre($nombre) {
        $this->nombre = $nombre;
    }

    public function get_Apellido() {
        return $this->apellido;
    }

    public function set_Apellido($apellido) {
        $this->apellido = $apellido;
    }

    public function get_TipoUsuario() {
        return $this->id_rol;
    }

    public function set_Id_rol($id_rol) {
        $this->id_rol = $id_rol;
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
   
}