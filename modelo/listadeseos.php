<?php
require_once('modelo/conexion.php'); 

class ListaDeseo extends Conexion {
    private $conex;

    public function __construct() {
        
        $this->conex = new Conexion();
        $this->conex = $this->conex->conex();

        
        if (!$this->conex) {
            die('Error al conectar con la base de datos');
        }
    }

    // Métodos setters y getters
    public function set_Id_lista($id_lista) {
        $this->id_lista = $id_lista;
    }

    public function set_Id_persona($id_persona) {
        $this->id_persona = $id_persona;
    }

    public function set_Id_producto($id_producto) {
        $this->id_producto = $id_producto;
    }

    public function set_Nombre($nombre) {
        $this->nombre = $nombre;
    }

    public function set_Detalle($detalle) {
        $this->detalle = $detalle;
    }

    // Métodos getters
    public function get_Id_lista() {
        return $this->id_lista;
    }

    public function get_Id_persona() {
        return $this->id_persona;
    }

    public function get_Id_producto() {
        return $this->id_producto;
    }

    public function get_Nombre() {
        return $this->nombre;
    }

    public function get_Detalle() {
        return $this->detalle;
    }

    // Método para consultar todas las listas de deseos
    public function consultar() {
        // Consulta para obtener todas las listas de deseos
        $sql = "SELECT * FROM lista_deseo";
        
        // Preparar y ejecutar la consulta utilizando PDO
        $consulta = $this->conex->prepare($sql);
        $consulta->execute();
        
        // Retornar los resultados como un array asociativo
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para añadir un artículo a un pedido
    public function añadirAPedido() {
        // Consulta para insertar el producto en la tabla de pedidos
        $sql = "INSERT INTO pedido (id_persona, id_producto) VALUES (:id_persona, :id_producto)";
        
        // Preparar la consulta
        $consulta = $this->conex->prepare($sql);

        // Bind de los parámetros
        $consulta->bindParam(':id_persona', $this->id_persona);
        $consulta->bindParam(':id_producto', $this->id_producto);

        // Ejecutar la consulta y devolver el resultado
        return $consulta->execute();
    }

    // Método para eliminar un artículo de la lista de deseos
    public function eliminar() {
        // Consulta para eliminar el artículo de la lista de deseos
        $sql = "DELETE FROM lista_deseo WHERE id_lista = :id_lista";

        // Preparar la consulta
        $consulta = $this->conex->prepare($sql);

        // Bind del parámetro
        $consulta->bindParam(':id_lista', $this->id_lista);

        // Ejecutar la consulta y devolver el resultado
        return $consulta->execute();
    }
}
?>
