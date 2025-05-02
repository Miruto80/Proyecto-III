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

    function __construct(){ // Metodo para BD
        $this->conex = new Conexion();
        $this->conex = $this->conex->Conex();
    } 

    public function verificarUsuario() {
        $consulta = "SELECT p.*, ru.nombre AS nombre_usuario
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
}