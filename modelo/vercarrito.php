<?php
class Carrito {

    public static function obtenerCarrito() {
        return $_SESSION['carrito'] ?? [];
    }

    public static function guardarCarrito($carrito) {
        $_SESSION['carrito'] = $carrito;
    }

    public static function eliminarProducto($id) {
        $carrito = self::obtenerCarrito();
        if (isset($carrito[$id])) {
            unset($carrito[$id]);
            self::guardarCarrito($carrito);
            return true;
        }
        return false;
    }

    public static function actualizarCantidad($id, $cantidad) {
        $carrito = self::obtenerCarrito();
        if (isset($carrito[$id])) {
            $stockDisponible = $carrito[$id]['stockDisponible'];
            if ($cantidad >= 1 && $cantidad <= $stockDisponible) {
                $carrito[$id]['cantidad'] = $cantidad;
                self::guardarCarrito($carrito);
                return true;
            }
        }
        return false;
    }
}
?>
