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
    private $encryptionKey = "MotorLoveMakeup"; // Usa una clave segura
    private $cipherMethod = "AES-256-CBC";

    function __construct(){ // Metodo para BD
        $this->conex = new Conexion();
        $this->conex = $this->conex->Conex();
    } 

    private function encryptClave($clave) {
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($this->cipherMethod));
     // Se genera un IV aleatorio
    $encrypted = openssl_encrypt($clave, $this->cipherMethod, $this->encryptionKey, 0, $iv);
     //Se encripta la clave con AES-256-CBC
    return base64_encode($iv . $encrypted); // Guarda IV junto con el cifrado
    }

    private function decryptClave($claveEncriptada) {
    $data = base64_decode($claveEncriptada);
    //Recuperamos el IV y los datos cifrados en binario.
    $ivLength = openssl_cipher_iv_length($this->cipherMethod);
    //Obtener la longitud del IV, Para saber cuántos bytes tomar.
    $iv = substr($data, 0, $ivLength);
    //Extraer el IV → Separamos el IV del texto cifrado.
    $encryptedData = substr($data, $ivLength);
    //Extraer los datos cifrados, Ahora tenemos solo el texto cifrado.
    $claveDesencriptada = openssl_decrypt($encryptedData, $this->cipherMethod, $this->encryptionKey, 0, $iv);
    // Desencriptar con OpenSSL, Convertimos el texto cifrado en la clave original.
    return $claveDesencriptada;
    }

    
    public function verificarUsuario() {
    $consulta = "SELECT p.*, ru.nombre AS nombre_usuario, ru.nivel, p.clave
                 FROM personas p
                 INNER JOIN rol_usuario ru ON p.id_tipo = ru.id_tipo
                 WHERE p.cedula = :cedula";
                 
    $strExec = $this->conex->prepare($consulta);
    $strExec->bindParam(':cedula', $this->cedula);
    $strExec->execute();
    $resultado = $strExec->fetchObject();

    if ($resultado) {
        // Desencriptar la clave almacenada
        $claveDesencriptada = $this->decryptClave($resultado->clave);

        // Comparar con la clave ingresada
        if ($claveDesencriptada === $this->clave) {
            if (!in_array($resultado->estatus, [1, 2, 3])) {
                $resultado->noactiva = true;
            }
            return $resultado;
        }
    }

    return null; // Retorna null si las credenciales no coinciden
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
    
    // Encriptar la clave antes de almacenarla
    $claveEncriptada = $this->encryptClave($this->clave);
    $strExec->bindParam(':clave', $claveEncriptada);

    $resul = $strExec->execute();
    if ($resul) {
        $res['respuesta'] = 1;
        $res['accion'] = 'incluir';
    } else {
        $res['respuesta'] = 0;
        $res['accion'] = 'incluir';
    }

    return $res;
}

     public function existeCedula() {
        $consulta = "SELECT cedula FROM personas WHERE cedula = :cedula";
        $strExec = $this->conex->prepare($consulta);
        $strExec->bindParam(':cedula', $this->cedula);
        $strExec->execute();
        return $strExec->rowCount() > 0;
    }

    public function obtenerPersonaPorCedula() {
    $consulta = "SELECT * FROM personas WHERE cedula = :cedula";
    $strExec = $this->conex->prepare($consulta);
    $strExec->bindParam(':cedula', $this->cedula);
    $strExec->execute();
    
    if ($strExec->rowCount() > 0) {
        return $strExec->fetchObject();
    }

    return null; // Retorna null si no se encuentra la cédula
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