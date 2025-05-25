<?php

require_once 'conexion.php';

class Clave extends Conexion{

    private $conex;
    private $id_persona;
    private $nombre;
    private $apellido;
    private $cedula;
    private $telefono;
    private $correo;
    private $clave;
    private $estatus;
    private $encryptionKey = "MotorLoveMakeup"; 
    private $cipherMethod = "AES-256-CBC";
    
    function __construct() {
        $this->conex = new Conexion();
        $this->conex = $this->conex->Conex();
    }

    private function encryptClave($clave) {
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($this->cipherMethod)); 
        $encrypted = openssl_encrypt($clave, $this->cipherMethod, $this->encryptionKey, 0, $iv);
        return base64_encode($iv . $encrypted);
    }

    private function decryptClave($claveEncriptada) {
        $data = base64_decode($claveEncriptada);
        $ivLength = openssl_cipher_iv_length($this->cipherMethod);
        $iv = substr($data, 0, $ivLength);
        $encryptedData = substr($data, $ivLength);    
        $claveDesencriptada = openssl_decrypt($encryptedData, $this->cipherMethod, $this->encryptionKey, 0, $iv);
        return $claveDesencriptada;
    }


     public function actualizarClave(){
        $registro = "UPDATE personas SET clave = :clave WHERE id_persona = :id_persona";

        $strExec = $this->conex->prepare($registro);
        $strExec->bindParam(':id_persona', $this->id_persona);
        // Encriptar la clave antes de almacenarla
        $claveEncriptada = $this->encryptClave($this->clave);
        $strExec->bindParam(':clave', $claveEncriptada);

        $resul = $strExec->execute();
        if ($resul) {
            $res=array('respuesta'=>1,'accion'=>'clave');
        } else {
            $res=array('respuesta'=>0,'accion'=>'clave');
        }
        return $res;
    } // fin actulizar


     
   

   


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
