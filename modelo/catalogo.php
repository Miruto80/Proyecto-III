<?php
require_once('modelo/conexion.php');
require_once('modelo/categoria.php');
require_once('modelo/producto.php');

class Catalogo extends Conexion {
    private $objcategoria;
    private $objproducto;

    public function __construct() {
        parent::__construct();
        $this->objcategoria = new categoria();
        $this->objproducto = new producto();
    }

    public function obtenerProductosMasVendidos() {
        return $this->objproducto->MasVendidos();
    }

    public function obtenerProductosActivos() {
        return $this->objproducto->ProductosActivos();
    }

    public function obtenerPorCategoria($categoriaId) {
        $conex = $this->getConex1();
        try {
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
            ";
            $stmt = $conex->prepare($sql);
            $stmt->bindParam(':categoriaId', $categoriaId, PDO::PARAM_INT);
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $conex = null;
            return $resultado;
        } catch (PDOException $e) {
            if ($conex) {
                $conex = null;
            }
            throw $e;
        }
    }

    public function obtenerCategorias() {
        return $this->objcategoria->consultar();
    }

    public function buscarProductos($termino) {
        $conex = $this->getConex1();
        try {
            $sql = "
                SELECT *  
                FROM productos  
                WHERE estatus = 1  
                  AND (nombre LIKE :busqueda OR marca LIKE :busqueda)
            ";
            $stmt = $conex->prepare($sql);
            $busqueda = '%' . $termino . '%';
            $stmt->bindParam(':busqueda', $busqueda, PDO::PARAM_STR);
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $conex = null;
            return $resultado;
        } catch (PDOException $e) {
            if ($conex) {
                $conex = null;
            }
            throw $e;
        }
    }
}
?>