<?php

require_once (__DIR__.'/../config/config.php');

class Conexion {
    private $conex1;
    private $conex2;

    public function __construct() {
        try {
            // Primera conexión
            $this->conex1 = new PDO("mysql:host="._DB_HOST_.";dbname="._DB_NAME_1_.";charset=utf8", _DB_USER_, _DB_PASS_);
            $this->conex1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Segunda conexión
            $this->conex2 = new PDO("mysql:host="._DB_HOST_.";dbname="._DB_NAME_2_.";charset=utf8", _DB_USER_, _DB_PASS_);
            $this->conex2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {
            die ("Conexión Fallida: ".$e->getMessage());
        }
    }

    public function getConex1() {
        return $this->conex1;
    }

    public function getConex2() {
        return $this->conex2;
    }
}
?>
