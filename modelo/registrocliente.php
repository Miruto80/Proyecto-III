<?php
require_once(__DIR__ . '/conexion.php');


class RegistroCliente extends Conexion {
    private $conex;
    private $cedula;
    private $nombre;
    private $apellido;
    private $correo;
    private $telefono;
    private $clave;

    public function __construct() {
        $this->conex = new Conexion();
        $this->conex = $this->conex->conex();
    }

    // Setters
    public function setDatos($cedula, $nombre, $apellido, $correo, $telefono, $clave) {
        $this->cedula = $cedula;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->correo = $correo;
        $this->telefono = $telefono;
        $this->clave = password_hash($clave, PASSWORD_DEFAULT); // encriptar clave
    }

    // Método para registrar
    public function registrarCliente() {
        $sql = "INSERT INTO personas (cedula, nombre, apellido, correo, telefono, clave, id_tipo, estatus) 
                VALUES (:cedula, :nombre, :apellido, :correo, :telefono, :clave, 1, 1)";

        $stmt = $this->conex->prepare($sql);
        $stmt->bindParam(':cedula', $this->cedula);
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':apellido', $this->apellido);
        $stmt->bindParam(':correo', $this->correo);
        $stmt->bindParam(':telefono', $this->telefono);
        $stmt->bindParam(':clave', $this->clave);

        $resultado = $stmt->execute();

        if ($resultado) {
            return ['respuesta' => 1, 'accion' => 'incluir'];
        } else {
            return ['respuesta' => 0, 'accion' => 'incluir'];
        }
    }
}
