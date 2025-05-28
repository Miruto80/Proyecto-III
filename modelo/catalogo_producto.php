<?php
require_once('modelo/conexion.php');
require_once('modelo/categoria.php');
class Catalogo extends Conexion {
    private $conex1;
    private $conex2;

    private $objcategoria;

    public function __construct() {
        parent::__construct(); // Llama al constructor de la clase padre

        // Obtener las conexiones de la clase padre
        $this->conex1 = $this->getConex1();
        $this->conex2 = $this->getConex2();
        $this->objcategoria = new categoria();

    
         // Verifica si las conexiones son exitosas
        if (!$this->conex1) {
            die('Error al conectar con la primera base de datos');
        }

        if (!$this->conex2) {
            die('Error al conectar con la segunda base de datos');
        }
    }
    

    public function obtenerProductosActivos() {
        $sql = "SELECT * FROM productos WHERE estatus = 1";
        $consulta = $this->conex1->prepare($sql);
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
        $consulta = $this->conex1->prepare($sql);
        $consulta->bindParam(':categoriaId', $categoriaId);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    public function obtenerCategorias() {
        return $this->objcategoria->consultar();
    }
   
    public function buscarProductos($termino) {
        $sql = "
            SELECT * 
            FROM productos 
            WHERE estatus = 1 
              AND (nombre LIKE :busqueda OR marca LIKE :busqueda)
        ";
        $consulta = $this->conex1->prepare($sql);
        $busqueda = '%' . $termino . '%';
        $consulta->bindParam(':busqueda', $busqueda, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }
}





?>