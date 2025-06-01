<?php

require_once 'conexion.php';

class Datos extends Conexion{

    private $conex1;
    private $conex2;
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

    public function registrarBitacora($id_persona, $accion, $descripcion) {
    $consulta = "INSERT INTO bitacora (accion, fecha_hora, descripcion, id_persona) 
                 VALUES (:accion, NOW(), :descripcion, :id_persona)";
    
    $strExec = $this->conex2->prepare($consulta);
    $strExec->bindParam(':accion', $accion);
    $strExec->bindParam(':descripcion', $descripcion);
    $strExec->bindParam(':id_persona', $id_persona);
    
    return $strExec->execute(); // Devuelve true si la inserción fue exitosa
    }
    
    public function actualizar(){
        $registro = "UPDATE usuario SET nombre = :nombre, apellido = :apellido, cedula = :cedula, telefono = :telefono, correo = :correo WHERE id_persona = :id_persona";

        $strExec = $this->conex2->prepare($registro);
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
        $registro = "UPDATE usuario SET clave = :clave WHERE id_persona = :id_persona";

        $strExec = $this->conex2->prepare($registro);
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


    
    public function eliminar(){
        try {
            $registro = "UPDATE usuario SET estatus = 0 WHERE id_persona = :id_persona";
            $strExec = $this->conex2->prepare($registro);
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
        $consulta = "SELECT cedula FROM usuario WHERE cedula = :cedula";
        $strExec = $this->conex2->prepare($consulta);
        $strExec->bindParam(':cedula', $this->cedula);
        $strExec->execute();
        return $strExec->rowCount() > 0;
    }


     
    public function existeCorreo() {
        $consulta = "SELECT correo FROM usuario WHERE correo = :correo";
        $strExec = $this->conex2->prepare($consulta);
        $strExec->bindParam(':correo', $this->correo);
        $strExec->execute();
        return $strExec->rowCount() > 0;
    }

    public function obtenerClave($id_persona) {
        $consulta = "SELECT clave FROM usuario WHERE id_persona = :id_persona"; 
        $strExec = $this->conex2->prepare($consulta);
        $strExec->bindParam(':id_persona', $id_persona); 
        $strExec->execute();
    
        $fila = $strExec->fetch(PDO::FETCH_ASSOC);
        if ($fila && isset($fila['clave'])) {
            return $this->decryptClave($fila['clave']); // Desencripta la clave antes de retornarla
        }
        return null; // Retorna null si no se encuentra
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
