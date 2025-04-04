<?php
require_once('modelo/datos.php');

// Clase principal que extiende de la clase datos
class principal extends datos {
    // Método para obtener conteos de diferentes tablas
    public function obtenerConteos() {
        // Conectar a la base de datos
        $co = $this->conecta();
        // Establecer el modo de error para excepciones
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // Inicializar un array para almacenar los conteos
        $conteos = array();

        // Definir las tablas de las que se obtendrán los conteos
        $tablas = ['categorias', 'ubicaciones', 'proveedores', 'productos', 'clientes', 'usuarios', 'apartados'];

        // Iterar sobre cada tabla para obtener el conteo
        foreach ($tablas as $tabla) {
            try {
                // Ejecutar la consulta para contar registros en la tabla actual
                $resultado = $co->query("SELECT COUNT(*) as total FROM $tabla");
                // Obtener el resultado de la consulta
                $fila = $resultado->fetch(PDO::FETCH_ASSOC);
                // Almacenar el conteo en el array usando el nombre de la tabla como clave
                $conteos[$tabla] = $fila['total'];
            } catch(Exception $e) {
                // Si ocurre un error, establecer el conteo en 0
                $conteos[$tabla] = 0;
            }
        }
        // Retornar el array con los conteos
        return $conteos;
    }
}
?>