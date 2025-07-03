<?php
class VerCarrito {

    public function procesarCarrito($jsonDatos){
        $datos = json_decode($jsonDatos, true);
        $operacion = $datos ['operacion']?? '';
        $datosProcesar = $datos ['datos']?? [];

        try{
            switch($operacion){
                case 'actualizar':
                    return $this->actualizarCantidad($datosProcesar['id'], $datosProcesar['cantidad']);
                
                case 'eliminar':
                    return $this->eliminarProducto($datosProcesar['id']);
                
                case 'obtener':
                    return ['respuesta'=> 1, 'carrito'=> $this->obtenerCarrito()];
                   
                default:
                     return ['respuesta' => 0, 'mensaje' => 'Operación no válida'];  }

            } catch (Exception $e) {
                return ['respuesta' => 0, 'accion' => $operacion,'mensaje' => 'Error interno: ' . $e->getMessage()];
            }
    }
    

    private function obtenerCarrito() {
        return $_SESSION['carrito'] ?? [];
    }

    private function guardarCarrito($carrito) {
        $_SESSION['carrito'] = $carrito;
    }

    public function eliminarProducto($id) {
        $id = (string)$id;
        $carrito = $this->obtenerCarrito();

        if (isset($carrito[$id])) {
            unset($carrito[$id]);
            $this->guardarCarrito($carrito);
            return ['respuesta' => 1, 'accion' => 'eliminar', 'mensaje' => 'Producto eliminado'];
        }

        return ['respuesta' => 0, 'accion' => 'eliminar', 'mensaje' => 'Producto no encontrado'];
    }
    private function actualizarCantidad($id, $cantidad) {
        $carrito = $this->obtenerCarrito();
        if (isset($carrito[$id])) {
            $stockDisponible = $carrito[$id]['stockDisponible'] ?? 0;
            if ($cantidad >= 1 && $cantidad <= $stockDisponible) {
                $carrito[$id]['cantidad'] = $cantidad;
                $this->guardarCarrito($carrito);
                return ['respuesta' => 1, 'accion' => 'actualizar', 'mensaje' => 'Cantidad actualizada'];
            }
            return ['respuesta' => 0, 'accion' => 'actualizar', 'mensaje' => 'Cantidad inválida'];
        }
        return ['respuesta' => 0, 'accion' => 'actualizar', 'mensaje' => 'Producto no encontrado'];
    }

}
?>
