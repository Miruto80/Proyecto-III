<?php
require_once('modelo/conexion.php');

class ListaDeseo extends Conexion {
    private $conex1;
    private $conex2;

    public function __construct() {
        parent::__construct(); // Hereda constructor y conexiones

        $this->conex1 = $this->getConex1();
        $this->conex2 = $this->getConex2();

        if (!$this->conex1) {
            die('Error al conectar con la primera base de datos');
        }
        if (!$this->conex2) {
            die('Error al conectar con la segunda base de datos');
        }
    }

    public function obtenerListaDeseo($id_persona) {
        $sql = "
            SELECT ld.id_lista, ld.id_persona, ld.id_producto, 
                   p.nombre, p.marca, p.descripcion, p.imagen, 
                   p.precio_detal, p.precio_mayor, 
                   p.cantidad_mayor, p.stock_disponible
            FROM lista_deseo ld
            JOIN productos p ON ld.id_producto = p.id_producto
            WHERE ld.id_persona = :id_persona
        ";
        $consulta = $this->conex1->prepare($sql);
        $consulta->bindParam(':id_persona', $id_persona, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public function eliminarProductoLista($id_lista) {
        $sql = "DELETE FROM lista_deseo WHERE id_lista = :id_lista";
        $consulta = $this->conex1->prepare($sql);
        $consulta->bindParam(':id_lista', $id_lista, PDO::PARAM_INT);
        return $consulta->execute();
    }

    public function vaciarListaDeseo($id_persona) {
        $sql = "DELETE FROM lista_deseo WHERE id_persona = :id_persona";
        $consulta = $this->conex1->prepare($sql);
        $consulta->bindParam(':id_persona', $id_persona, PDO::PARAM_INT);
        return $consulta->execute();
    }

    public function estaEnLista($id_persona, $id_producto) {
        $sql = "SELECT 1 FROM lista_deseo WHERE id_persona = :id_persona AND id_producto = :id_producto";
        $consulta = $this->conex1->prepare($sql);
        $consulta->bindParam(':id_persona', $id_persona, PDO::PARAM_INT);
        $consulta->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->fetch() ? true : false;
    }

    public function agregarProductoLista($id_persona, $id_producto) {
        $sql = "INSERT INTO lista_deseo (id_persona, id_producto) VALUES (:id_persona, :id_producto)";
        $consulta = $this->conex1->prepare($sql);
        $consulta->bindParam(':id_persona', $id_persona, PDO::PARAM_INT);
        $consulta->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
        return $consulta->execute();
    }
}
?>
