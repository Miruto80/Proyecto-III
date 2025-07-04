<?php
require_once('modelo/conexion.php');

class ListaDeseo extends Conexion {

    public function procesarListaDeseo($json) {
        $datos = json_decode($json, true);
        $operacion = $datos['operacion'] ?? null;
        $params = $datos['datos'] ?? [];

        switch ($operacion) {
            case 'agregar':
                return $this->procesarAgregar($params);
            case 'eliminar':
                return $this->procesarEliminar($params);
            case 'vaciar':
                return $this->procesarVaciar($params);
            default:
                return ['status' => 'error', 'message' => 'Operación no válida'];
        }
    }

    private function procesarAgregar($params) {
        $id_persona = $params['id_persona'] ?? null;
        $id_producto = $params['id_producto'] ?? null;

        if (!$id_persona || !$id_producto) {
            return ['status' => 'error', 'message' => 'Datos incompletos'];
        }

        if ($this->estaEnLista($id_persona, $id_producto)) {
            return ['status' => 'exists', 'message' => 'El producto ya está en la lista'];
        }

        if ($this->agregarProductoLista($id_persona, $id_producto)) {
            return ['status' => 'success'];
        }

        return ['status' => 'error', 'message' => 'No se pudo agregar el producto'];
    }

    private function procesarEliminar($params) {
        $id_lista = $params['id_lista'] ?? null;

        if (!$id_lista) {
            return ['status' => 'error', 'message' => 'ID de lista no válido'];
        }

        if ($this->eliminarProductoLista($id_lista)) {
            return ['status' => 'success'];
        }

        return ['status' => 'error', 'message' => 'No se pudo eliminar el producto'];
    }

    private function procesarVaciar($params) {
        $id_persona = $params['id_persona'] ?? null;

        if (!$id_persona) {
            return ['status' => 'error', 'message' => 'ID de persona no válido'];
        }

        if ($this->vaciarListaDeseo($id_persona)) {
            return ['status' => 'success'];
        }

        return ['status' => 'error', 'message' => 'No se pudo vaciar la lista'];
    }

    public function obtenerListaDeseo($id_persona) {
        $conex = $this->getConex1();
        try {
            $sql = "
                SELECT ld.id_lista, ld.id_persona, ld.id_producto, 
                       p.nombre, p.marca, p.descripcion, p.imagen, 
                       p.precio_detal, p.precio_mayor, 
                       p.cantidad_mayor, p.stock_disponible
                FROM lista_deseo ld
                JOIN productos p ON ld.id_producto = p.id_producto
                WHERE ld.id_persona = :id_persona
            ";
            $stmt = $conex->prepare($sql);
            $stmt->bindParam(':id_persona', $id_persona, PDO::PARAM_INT);
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

    private function eliminarProductoLista($id_lista) {
        $conex = $this->getConex1();
        try {
            $sql = "DELETE FROM lista_deseo WHERE id_lista = :id_lista";
            $stmt = $conex->prepare($sql);
            $stmt->bindParam(':id_lista', $id_lista, PDO::PARAM_INT);
            $resultado = $stmt->execute();
            $conex = null;
            return $resultado;
        } catch (PDOException $e) {
            if ($conex) {
                $conex = null;
            }
            throw $e;
        }
    }

    private function vaciarListaDeseo($id_persona) {
        $conex = $this->getConex1();
        try {
            $sql = "DELETE FROM lista_deseo WHERE id_persona = :id_persona";
            $stmt = $conex->prepare($sql);
            $stmt->bindParam(':id_persona', $id_persona, PDO::PARAM_INT);
            $resultado = $stmt->execute();
            $conex = null;
            return $resultado;
        } catch (PDOException $e) {
            if ($conex) {
                $conex = null;
            }
            throw $e;
        }
    }

    private function estaEnLista($id_persona, $id_producto) {
        $conex = $this->getConex1();
        try {
            $sql = "SELECT 1 FROM lista_deseo WHERE id_persona = :id_persona AND id_producto = :id_producto";
            $stmt = $conex->prepare($sql);
            $stmt->bindParam(':id_persona', $id_persona, PDO::PARAM_INT);
            $stmt->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
            $stmt->execute();
            $resultado = $stmt->fetch() ? true : false;
            $conex = null;
            return $resultado;
        } catch (PDOException $e) {
            if ($conex) {
                $conex = null;
            }
            throw $e;
        }
    }
 
    private function agregarProductoLista($id_persona, $id_producto) {
        $conex = $this->getConex1();
        try {
            $sql = "INSERT INTO lista_deseo (id_persona, id_producto) VALUES (:id_persona, :id_producto)";
            $stmt = $conex->prepare($sql);
            $stmt->bindParam(':id_persona', $id_persona, PDO::PARAM_INT);
            $stmt->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
            $resultado = $stmt->execute();
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
