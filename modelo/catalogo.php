<?php
require_once('modelo/conexion.php');
class Catalogo extends Conexion {
    private $conex;

    public function __construct() {
        $this->conex = new Conexion();
        $this->conex = $this->conex->conex();
    
        // Verifica si la conexión es exitosa
        if (!$this->conex) {
            die('Error al conectar con la base de datos');
        }
    }
    

    public function obtenerProductosActivos() {
        $sql = "SELECT * FROM productos WHERE estatus = 1";  // Filtra por productos activos (estatus = 1)
        $consulta = $this->conex->prepare($sql);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }
    

    public function obtenerPorCategoria($categoriaId) {
        $sql = "
            SELECT 
                productos.*, 
                categoria.nombre AS nombre_categoria 
            FROM 
                productos 
            INNER JOIN 
                categoria ON productos.id_categoria = categoria.id_categoria
            WHERE 
                productos.estatus = 1 AND productos.id_categoria = :categoriaId
        ";  // Filtra por estatus y categoría
        $consulta = $this->conex->prepare($sql);
        $consulta->bindParam(':categoriaId', $categoriaId);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    public function obtenerCategorias() {
      $sql = "SELECT id_categoria, nombre FROM categoria WHERE estatus = 1"; // Solo lo necesario
      $consulta = $this->conex->prepare($sql);
      $consulta->execute();
      return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }
}



?>