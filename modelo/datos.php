<?php
class datos {
    // Propiedades privadas para la conexión a la base de datos
	private $ip = "localhost"; // Dirección IP del servidor de la base de datos
    private $bd = "inventario"; // Nombre de la base de datos
    private $usuario = "root"; // Usuario de la base de datos
    private $contrasena = ""; // Contraseña del usuario
    // Método para establecer la conexión a la base de datos
    function conecta() {
        // Crear una nueva instancia de PDO para la conexión
        $pdo = new PDO("mysql:host=".$this->ip.";dbname=".$this->bd."",$this->usuario,$this->contrasena);
        // Configurar el conjunto de caracteres a UTF-8
        $pdo->exec("set names utf8");
        // Retornar el objeto PDO para su uso
        return $pdo;
    }
}
?>