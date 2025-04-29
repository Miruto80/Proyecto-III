<?php

require_once (__DIR__.'/../config/config.php');

	class Conexion extends PDO{
	private $conex ;

	public function __construct(){
	
		$conexstring ="mysql:host="._DB_HOST_.";dbname="._DB_NAME_.";charset=utf8";
	try {
		$this->conex = new PDO($conexstring, _DB_USER_, _DB_PASS_);
		$this->conex->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch (PDOException $e) {
		die ("Conexión Fallida".$e->getMessage());
	}
}
	public function Conex(){
	return $this->conex;
	}
}

?>